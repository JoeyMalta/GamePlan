<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Homepage met de 3 eerstvolgende events.
     */
    public function index(): View
    {
        $upcomingEvents = Event::with('category')
            ->withCount('participants')
            ->where('is_cancelled', false)      // afgezegde events niet tonen
            ->where('starts_at', '>', now())    // alleen events in de toekomst
            ->orderBy('starts_at')              // vroegste eerst
            ->take(3)                           // maximaal 3 (SQL: LIMIT 3)
            ->get();

        return view('home', compact('upcomingEvents'));
    }
}
