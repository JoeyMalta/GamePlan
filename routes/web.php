<?php

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

// Alleen voor ingelogde gebruikers (middleware 'auth')
Route::middleware('auth')->group(function () {
    Route::get('/events/create', [EventController::class, 'create'])->name('events.create');
    Route::post('/events', [EventController::class, 'store'])->name('events.store');
});

Route::get('/events/{event}', [EventController::class, 'show'])->name('events.show');

// TIJDELIJK: wordt in de volgende stap EventController@edit
Route::view('/events/{event}/edit', 'events.edit')
    ->middleware('auth')
    ->name('events.edit');

/*
| Admin — TIJDELIJK. Straks met eigen 'admin'-middleware en controllers.
*/
Route::middleware('auth')->prefix('admin')->group(function () {
    Route::view('/users', 'admin.users.index')->name('admin.users.index');
    Route::view('/events', 'admin.events.index')->name('admin.events.index');
});

require __DIR__.'/auth.php';
