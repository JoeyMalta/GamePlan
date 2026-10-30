<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * Aantal verschillende dagen dat een gebruiker moet hebben ingelogd om events te mogen maken.
     */
    public const REQUIRED_LOGIN_DAYS = 3;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }

    public function joinedEvents(): BelongsToMany
    {
        return $this->belongsToMany(Event::class)->withTimestamps();
    }

    public function logins(): HasMany
    {
        return $this->hasMany(Login::class);
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * DIEPERE VALIDATIE: telt op hoeveel VERSCHILLENDE dagen deze gebruiker heeft ingelogd.
     * Losse Eloquent-query op de tabel logins — geen teller-kolom in de database.
     * SQL: SELECT COUNT(DISTINCT DATE(created_at)) FROM logins WHERE user_id = ?
     */
    public function loginDays(): int
    {
        return (int) $this->logins()
            ->selectRaw('COUNT(DISTINCT DATE(created_at)) as days')
            ->value('days');
    }

    /**
     * Mag deze gebruiker events aanmaken? Admins altijd, anderen na 3 verschillende inlogdagen.
     */
    public function canCreateEvents(): bool
    {
        return $this->isAdmin() || $this->loginDays() >= self::REQUIRED_LOGIN_DAYS;
    }
}
