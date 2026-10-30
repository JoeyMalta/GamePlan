<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Event;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class EventController extends Controller
{
    /**
     * Overzicht van alle events, met zoeken (vrije tekst) en filteren (dropdowns).
     */
    public function index(Request $request): View
    {
        // Begin met alle events, en laad de relaties meteen mee (voorkomt N+1 queries)
        $query = Event::with(['category', 'images'])->withCount('participants');

        // ZOEKEN: vrije tekst in meerdere kolommen (titel OF beschrijving)
        if ($request->filled('q')) {
            $search = $request->input('q');

            // Gegroepeerd in een closure: WHERE (title LIKE ... OR description LIKE ...)
            $query->where(function ($subQuery) use ($search) {
                $subQuery->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // FILTEREN: elke ingevulde dropdown voegt een voorwaarde toe (AND)
        if ($request->filled('city')) {
            $query->where('city', $request->input('city'));
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->input('category'));
        }

        if ($request->input('price') === 'free') {
            $query->where('price', 0);
        } elseif ($request->input('price') === 'paid') {
            $query->where('price', '>', 0);
        }

        $events = $query->orderBy('starts_at')->get();

        // Data voor de dropdowns
        $categories = Category::orderBy('name')->get();
        $cities = Event::distinct()->orderBy('city')->pluck('city');

        return view('events.index', compact('events', 'categories', 'cities'));
    }

    /**
     * Formulier om een event aan te maken (of de "vergrendeld"-melding).
     */
    public function create(Request $request): View
    {
        $user = $request->user();

        return view('events.create', [
            'canCreate' => $user->canCreateEvents(),
            'loginDays' => $user->loginDays(),
            'requiredDays' => User::REQUIRED_LOGIN_DAYS,
            'categories' => Category::orderBy('name')->pluck('name', 'id'),
        ]);
    }

    /**
     * Nieuw event opslaan.
     */
    public function store(Request $request): RedirectResponse
    {
        // 1. DIEPERE VALIDATIE: ook hier controleren, niet alleen het formulier verbergen.
        //    Anders kan iemand direct een POST naar /events sturen en de regel omzeilen.
        if (! $request->user()->canCreateEvents()) {
            abort(403, 'You can create events after logging in on '.User::REQUIRED_LOGIN_DAYS.' different days.');
        }

        // 2. VALIDATIE (server-side): verplichte velden + controle op inhoud.
        $validated = $request->validate($this->rules());

        // 3. Opslaan via de relatie: user_id wordt automatisch de ingelogde gebruiker
        $event = $request->user()->events()->create(
            collect($validated)->except('images')->all()
        );

        // 4. Foto's opslaan (aanpak van Antwan: storePublicly + pad in de database)
        $this->storeImages($request, $event);

        return redirect()
            ->route('events.show', $event)
            ->with('success', 'Your event has been created!');
    }

    /**
     * Detailpagina van één event.
     * Route model binding: Laravel zoekt het event op basis van {event} in de URL (of geeft een 404).
     */
    public function show(Event $event): View
    {
        // Relaties in één keer meeladen (eager loading)
        $event->load(['category', 'user', 'images', 'participants']);

        // Bepalen wat de bezoeker mag zien (knoppen in de view) — dit is GEEN beveiliging,
        // de echte controle zit in edit(), update() en destroy().
        $user = auth()->user();
        $isOwner = $user !== null && $user->id === $event->user_id;
        $isAdmin = $user !== null && $user->isAdmin();
        $isParticipant = $user !== null && $event->participants->contains($user);

        return view('events.show', compact('event', 'isOwner', 'isAdmin', 'isParticipant'));
    }

    /**
     * Formulier om een event te bewerken — alleen voor de maker of een admin.
     */
    public function edit(Event $event): View
    {
        $this->authorizeOwnerOrAdmin($event);

        $event->load('images');

        return view('events.edit', [
            'event' => $event,
            'categories' => Category::orderBy('name')->pluck('name', 'id'),
        ]);
    }

    /**
     * Wijzigingen opslaan — alleen voor de maker of een admin.
     */
    public function update(Request $request, Event $event): RedirectResponse
    {
        // Ook hier controleren: iemand kan zelf een PUT-verzoek sturen zonder het formulier
        $this->authorizeOwnerOrAdmin($event);

        $validated = $request->validate($this->rules());

        $event->update(collect($validated)->except('images')->all());

        // Aangevinkte foto's verwijderen (bestand + rij in de database).
        // Via $event->images() zodat je alleen foto's van DIT event kunt verwijderen.
        $imagesToRemove = $event->images()->whereIn('id', $request->input('remove_images', []))->get();
        foreach ($imagesToRemove as $image) {
            Storage::disk('public')->delete($image->path);
            $image->delete();
        }

        $this->storeImages($request, $event);

        return redirect()
            ->route('events.show', $event)
            ->with('success', 'Your event has been updated.');
    }

    /**
     * Event verwijderen (soft delete) — alleen voor de maker of een admin.
     */
    public function destroy(Event $event): RedirectResponse
    {
        $this->authorizeOwnerOrAdmin($event);

        // Soft delete: vult deleted_at, de rij en alle koppelingen blijven bestaan
        $event->delete();

        return redirect()
            ->route('events.index')
            ->with('success', 'The event has been deleted.');
    }

    /**
     * AUTORISATIE: alleen de maker van het event of een admin mag verder, anders 403.
     * (OWASP A01: Broken Access Control — ook bij deeplinken naar /events/{id}/edit)
     */
    private function authorizeOwnerOrAdmin(Event $event): void
    {
        $user = auth()->user();

        if ($user->id !== $event->user_id && ! $user->isAdmin()) {
            abort(403);
        }
    }

    /**
     * Validatieregels voor aanmaken én bewerken (op één plek, DRY).
     *
     * @return array<string, array<int, string>>
     */
    private function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:5000'],
            'category_id' => ['required', 'exists:categories,id'],
            'starts_at' => ['required', 'date', 'after:now'],
            'city' => ['required', 'string', 'max:100'],
            'address' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0', 'max:9999.99'],
            'images' => ['nullable', 'array', 'max:5'],
            'images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ];
    }

    /**
     * Geüploade foto's opslaan in storage/app/public/events en het pad in event_images zetten.
     */
    private function storeImages(Request $request, Event $event): void
    {
        foreach ($request->file('images', []) as $image) {
            $path = $image->storePublicly('events', 'public');
            $event->images()->create(['path' => $path]);
        }
    }
}
