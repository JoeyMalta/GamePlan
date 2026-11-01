# Gameplan

Gameplan is een website waar gepassioneerde game developers in Nederland events op kunnen zetten om mensen met
dezelfde mindset te ontmoeten. Dit doen ze door leuke events te organiseren waar mensen lekker kunnen socializen,
zonder de druk van industrie-events. Gebruikers kunnen zich aanmelden voor een opkomend event. Zo is het voor de
organisatoren duidelijk met hoeveel mensen ze rekening moeten houden.

**Live:** https://gameplan-production-jnboci.laravel.cloud

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

## Productie (OTAP)

De live-versie draait op Laravel Cloud. Verschillen met lokaal staan in de `.env`
(die niet in git staat):

- `APP_ENV=production` en `APP_DEBUG=false` (geen foutdetails voor bezoekers)
- eigen database: MySQL
- foto-uploads op S3-objectopslag in plaats van de lokale `public`-disk

Bij een deployment draait: `composer install --no-dev`, `npm run build`
en `php artisan migrate --force`.

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

Bij dit project heb ik Claude (Anthropic) gebruikt, via de Claude-app (Cowork).
Ik heb het op twee manieren ingezet:

### 1. Front-end (HTML / CSS / Tailwind)
De views en de styling in GamePlan-stijl (donker + violet) zijn grotendeels door
Claude gegenereerd. Dit was met Antwan afgestemd. Het gaat om de
Blade-views in `resources/views` (layout, navbar, hero, event-cards, formulieren,
admin-pagina's, dashboard, 403/404-pagina's). Ik heb de structuur en de inhoud
bepaald (welke pagina's, welke velden, MeetUp als voorbeeld) en de views daarna
aangepast en gekoppeld aan echte data.

### 2. Back-end (Laravel): Claude als mentor
Voor de back-end heb ik Claude gebruikt als uitleg- en reviewhulp:
- uitleg van Laravel-concepten (migrations, Eloquent-relaties, route model binding,
  middleware, validatie, `attach`/`detach`)
- het ERD-ontwerp samen doorlopen (1-op-veel en veel-op-veel, pivot `event_user`)
- feedback op mijn code en hulp bij foutmeldingen die kwamen door gebroken Herd installaties of NPM bugs
- bijv. de EventController, de admin-controllers en de middleware heeft
  Claude code voorgesteld omdat laracast niet mijn specifieke situatie had. 
  Die heb ik doorgenomen, laten uitleggen en waar nodig aangepast.
  De migrations, models, en relaties heb ik zelf geschreven.
- De CHANGELOG en de opzet van deze README zijn met hulp van Claude geschreven.
- Ik heb claude ook netjes comments laten maken voor mijn code zodat alles leesbaar en duidelijk bleef voor de komende dagen.

### Hoe ik heb gecontroleerd dat ik het begrijp
   - elke stap laten uitleggen voordat ik committe
   - alles handmatig getest met de drie demo-accounts (403 bij deeplinks, deactiveren, de 3-dagenregel)
   - Screenshots van leermomenten (eigen code, fouten en uitleg) staan in de map `ai_Logs`. De volledige chatlogs heb ik bewaard en kan ik op verzoek laten zien.

### Wat ik zelf heb bepaald
- De keuze voor het onderwerp (GamePlan, gebaseerd op MeetUp) en de features op de site.
- De diepere validatie: eerst inloggen op 3 verschillende dagen, geteld met een
  losse Eloquent-query op de `logins`-tabel
- meerdere foto's via `event_images` om de site wat complexer te maken
- deployment naar Laravel Cloud voor extra punt.
- soft deletes, `restrictOnDelete` gekozen na uitleg (en de tip van Antwan om events niet zomaar te verwijderen)
