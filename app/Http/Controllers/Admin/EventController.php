<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class EventController extends Controller
{
    /**
     * Lijst van alle events voor de admin.
     */
    public function index(): View
    {
        $events = Event::with(['user', 'category'])
            ->withCount('participants')
            ->orderBy('starts_at')
            ->get();

        return view('admin.events.index', compact('events'));
    }

    /**
     * STATUS-TOGGLE: event afzeggen of weer inplannen.
     * Verplichte eis: knop in een lijst, via POST, naar een APARTE actie in de controller.
     */
    public function toggleCancelled(Event $event): RedirectResponse
    {
        // is_cancelled staat bewust NIET in Fillable, dus we zetten hem hier expliciet
        $event->is_cancelled = ! $event->is_cancelled;
        $event->save();

        $status = $event->is_cancelled ? 'cancelled' : 'reinstated';

        return back()->with('success', "\"{$event->title}\" has been {$status}.");
    }
}
