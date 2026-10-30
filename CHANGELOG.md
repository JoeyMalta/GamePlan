# Changelog

All notable changes to **Gameplan** are documented in this file.
Format based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/).

## [Unreleased]

### Added
- Admin events list now has **Edit** and **Delete** buttons per row next to the status toggle (like the example in the assignment).
- Admins can edit and delete any event from the event detail page (moderator role); organizers can still only manage their own events.
- Custom **403** and **404** error pages in the Gameplan style (shown automatically by Laravel, e.g. after `abort(403)`).
- "Current photos" block on the edit page with a *Remove* checkbox per photo (`remove_images[]`).

### Changed
- Static images (logo + hero) moved to `resources/images` and loaded with `Vite::asset()`, following the course instructions on static images.
- Event images now use the uploaded path from storage (`asset('storage/' . $path)`) with the hero image as fallback.
- Kept the `event_images` table so an event can have multiple location photos (decided against a single `image` column: more useful, and no ERD change needed).

### Removed
- **Laravel Boost** (`composer remove laravel/boost --dev`) and the files it generated for AI coding tools (`AGENTS.md`, `CLAUDE.md`, `boost.json`, `.mcp.json`, `.ai/`, `.agents/`, `.claude/`, `.junie/`). Boost isn't needed for the app itself; removed after feedback on the research report.
- Unused files: old `public/favicon.svg` and `public/images/` (moved to `resources/images`), Breeze's `layouts/navigation.blade.php` (replaced by the Gameplan navbar), empty `state/` folder and the `Claude outputs` work folder.

### Security
- Ran `composer audit` and found **CVE-2026-45070** in `symfony/mime` (email header injection, medium severity — OWASP *Injection*). Updated only that package with `composer update symfony/mime --with-dependencies`; `composer audit` now reports no advisories.

## 2026-10-29

### Added
- Every successful login is stored as a row in `logins` (`$request->user()->logins()->create()` in `AuthenticatedSessionController@store`). This is the basis for the *deeper validation* rule: a user may only create events after logging in on 3 different days, counted with a separate Eloquent query (no counter column).
- Connected `database.sqlite` to the PhpStorm database window to verify the data.

## 2026-10-28

### Added
- `logins` table (`user_id` foreign key with `cascadeOnDelete`, `created_at` = moment of login).
- `Login` model with `user()` relationship (belongsTo) and `logins()` on `User` (hasMany).
- Set `APP_TIMEZONE=Europe/Amsterdam` so logins are counted on the correct (Dutch) day.

## 2026-10-27

### Added
- `event_user` pivot table for joining events (many-to-many), with `cascadeOnDelete` on both foreign keys and a unique `(event_id, user_id)` pair so a user can't join twice.
- `participants()` on `Event` and `joinedEvents()` on `User` (belongsToMany with `withTimestamps()`).
- `events()` relationships (hasMany) on `User` and `Category`.

## 2026-10-26

### Added
- **ERD** for Gameplan (`docs/gameplan-erd.dbml` + PNG): users, categories, events, event_images, event_user (pivot) and logins, with 1-to-many and many-to-many relations.
- Laravel **Breeze** (Blade) for login, registration and profile; fixed the setup for Tailwind 4 and added Alpine.js.
- Front-end views in the Gameplan style (dark + violet): event overview with search & filters, event detail, create/edit form (shared partial), dashboard, admin pages for users and events, flash messages. Views use temporary sample data and `Route::view()` routes until the controllers are built. *(HTML/CSS styling made with help of AI, see README.)*
- Migration that adds `role`, `is_active` and soft deletes to `users`.
- `categories` table + `Category` model (`name` is unique).
- `events` table with foreign keys to `users` and `categories` (`restrictOnDelete`, so no event loses its link), `decimal` price (0 = free), `is_cancelled` and soft deletes.
- `Event` model with fillable fields, casts and `user()` / `category()` relationships.

### Changed
- Renamed "meetups" to "events" on the homepage so it matches the ERD.

### Security
- `role`, `is_active`, `user_id` and `is_cancelled` are deliberately **not** in `Fillable` to prevent mass assignment.

## 2026-10-24

### Changed
- Updated package lock metadata.

## 2026-10-18

### Added
- Project setup: new Laravel project, homepage with layout, navbar partial and hero component.
