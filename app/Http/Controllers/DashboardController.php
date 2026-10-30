<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Persoonlijk overzicht: events die ik organiseer en events waar ik aan deelneem.
     */
    public function index(Request $request): View
    {
        $user = $request->user();

        // 1-op-veel: users.id -> events.user_id  (relatie events() in User)
        $myEvents = $user->events()
            ->withCount('participants')
            ->orderBy('starts_at')
            ->get();

        // veel-op-veel: via de tussentabel event_user  (relatie joinedEvents() in User)
        $joinedEvents = $user->joinedEvents()
            ->orderBy('starts_at')
            ->get();

        return view('dashboard', compact('myEvents', 'joinedEvents'));
    }
}
