<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Event;
use Illuminate\Http\Request;
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
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
