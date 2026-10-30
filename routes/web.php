<?php

use App\Http\Controllers\Admin\EventController as AdminEventController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| Events
|--------------------------------------------------------------------------
| LET OP de volgorde: /events/create moet VÓÓR /events/{event} staan,
| anders denkt Laravel dat "create" het {event} is.
*/
Route::get('/events', [EventController::class, 'index'])->name('events.index');

// Alleen voor ingelogde gebruikers (middleware 'auth').
// Wie wat mag (maker/admin) wordt daarna in de controller gecontroleerd.
Route::middleware('auth')->group(function () {
    Route::get('/events/create', [EventController::class, 'create'])->name('events.create');
    Route::post('/events', [EventController::class, 'store'])->name('events.store');
    Route::get('/events/{event}/edit', [EventController::class, 'edit'])->name('events.edit');
    Route::put('/events/{event}', [EventController::class, 'update'])->name('events.update');
    Route::delete('/events/{event}', [EventController::class, 'destroy'])->name('events.destroy');
});

Route::get('/events/{event}', [EventController::class, 'show'])->name('events.show');

/*
|--------------------------------------------------------------------------
| Admin — alleen voor ingelogde admins (middleware 'auth' + 'admin')
|--------------------------------------------------------------------------
| prefix('admin')  → alle URL's beginnen met /admin
| name('admin.')   → alle routenamen beginnen met admin.
*/
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    Route::post('/users/{user}/toggle-active', [AdminUserController::class, 'toggleActive'])->name('users.toggle-active');

    Route::get('/events', [AdminEventController::class, 'index'])->name('events.index');
    Route::post('/events/{event}/toggle-cancelled', [AdminEventController::class, 'toggleCancelled'])->name('events.toggle-cancelled');
});

require __DIR__.'/auth.php';
