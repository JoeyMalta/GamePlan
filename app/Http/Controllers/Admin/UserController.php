<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * Lijst van alle gebruikers voor de admin.
     */
    public function index(): View
    {
        $users = User::withCount('events')->orderBy('name')->get();

        return view('admin.users.index', compact('users'));
    }

    /**
     * STATUS-TOGGLE: account (de)activeren.
     * Verplichte eis: knop in een lijst, via POST, naar een APARTE actie in de controller.
     */
    public function toggleActive(User $user): RedirectResponse
    {
        // Een admin kan zichzelf niet buitensluiten
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot deactivate your own account.');
        }

        $user->is_active = ! $user->is_active;   // true wordt false, false wordt true
        $user->save();

        $status = $user->is_active ? 'activated' : 'deactivated';

        return back()->with('success', "{$user->name} has been {$status}.");
    }
}
