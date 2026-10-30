<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventController;

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
| Events — TIJDELIJK (alleen de views tonen)
|--------------------------------------------------------------------------
| Route::view() koppelt een URL direct aan een view, zonder controller.
| Zodra de backend er is, vervangen we dit door een EventController:
|   Route::resource('events', EventController::class);
|
| LET OP de volgorde: /events/create moet VÓÓR /events/{event} staan,
| anders denkt Laravel dat "create" het {event} is.
*/
Route::get('/events', [EventController::class, 'index'])->name('events.index');

Route::view('/events/create', 'events.create')
    ->middleware('auth')
    ->name('events.create');

Route::view('/events/{event}', 'events.show')->name('events.show');

Route::view('/events/{event}/edit', 'events.edit')
    ->middleware('auth')
    ->name('events.edit');

/*
| Admin — TIJDELIJK. Straks:
|   Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
|       Route::get('/users', [Admin\UserController::class, 'index'])->name('users.index');
|       Route::post('/users/{user}/toggle-active', [Admin\UserController::class, 'toggleActive'])->name('users.toggle');
|       ...
|   });
| De 'admin'-middleware maken we zelf: die laat alleen gebruikers met role = admin door.
*/
Route::middleware('auth')->prefix('admin')->group(function () {
    Route::view('/users', 'admin.users.index')->name('admin.users.index');
    Route::view('/events', 'admin.events.index')->name('admin.events.index');
});

require __DIR__.'/auth.php';
