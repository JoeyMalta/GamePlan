# Gameplan

> **TODO (Joey): schrijf hier in je eigen woorden 3–5 zinnen over Gameplan.**
> Bijvoorbeeld: wat is het, voor wie is het, en waarom heb je dit onderwerp gekozen.

Schoolproject voor CMTPRG02-5 *Web Application Frameworks* (Hogeschool Rotterdam), gebouwd met Laravel.

## Techniek

- Laravel 13 (PHP 8.3 of hoger)
- Laravel Breeze (Blade) voor inloggen, registreren en profiel
- Tailwind CSS 4 en Alpine.js, gebouwd met Vite
- SQLite als database (lokaal)

## Installatie (lokaal)

```bash
git clone https://github.com/JoeyMalta/GamePlan.git
cd GamePlan

composer install
cp .env.example .env
php artisan key:generate

php artisan migrate:fresh --seed   # maakt de database en vult hem met demo-data
php artisan storage:link           # maakt geüploade foto's bereikbaar via /storage

npm install
npm run build
```

Daarna draait de site via Laravel Herd (bijvoorbeeld `https://gameplan.test`) of met `php artisan serve`.

## Demo-accounts

Alle accounts hebben het wachtwoord `password`.

| Rol | E-mail | Bijzonderheid |
|---|---|---|
| Admin | `admin@gameplan.test` | toegang tot `/admin` |
| Gebruiker | `sam@gameplan.test` | heeft op 3 verschillende dagen ingelogd → mag events aanmaken |
| Gebruiker | `lisa@gameplan.test` | heeft op 1 dag ingelogd → mag nog geen events aanmaken |

## Functionaliteit en waar het in de code staat

| Eis | Waar |
|---|---|
| Inloggen / registreren | Laravel Breeze (`routes/auth.php`) |
| Alleen voor ingelogde gebruikers | `middleware('auth')` in `routes/web.php` |
| Rollen (admin / gebruiker) | kolom `users.role`, `User::isAdmin()`, middleware `EnsureUserIsAdmin` |
| Alleen de maker (of admin) mag een event wijzigen of verwijderen | `EventController::authorizeOwnerOrAdmin()` |
| Eigen gegevens aanpassen | `/profile` (Breeze) |
| Validatie | `EventController::rules()` |
| Zoeken en filteren | `EventController::index()` |
| Status-toggles via POST | `Admin\UserController::toggleActive()`, `Admin\EventController::toggleCancelled()` |
| Diepere validatie: pas na 3 verschillende inlogdagen een event aanmaken | `User::loginDays()` en `User::canCreateEvents()` |
| Gedeactiveerde gebruikers blokkeren | `LoginRequest::authenticate()` en middleware `EnsureUserIsActive` |
| Deelnemen aan events (veel-op-veel) | `EventController::join()` / `leave()`, tussentabel `event_user` |

## Database

Het ERD staat in `docs/gameplan-erd.png` (bron: `docs/gameplan-erd.dbml`, te openen op dbdiagram.io).

## Changelog

Zie [CHANGELOG.md](CHANGELOG.md).

## Bronnen

- Foto's van de demo-events: [Unsplash](https://unsplash.com/license)
- [Laravel-documentatie](https://laravel.com/docs) en het lesmateriaal van CMTPRG02-5

## AI-gebruik

> **TODO (Joey): beschrijf hier zelf, eerlijk en concreet, waarvoor je AI hebt gebruikt.**
> De cursushandleiding vraagt hierover transparantie. Noem bijvoorbeeld welke onderdelen je met hulp van AI hebt gemaakt, wat je zelf hebt geschreven, en hoe je hebt gecontroleerd dat je alles begrijpt.
