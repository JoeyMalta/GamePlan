<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Event;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Vult de database met demo-data voor Gameplan.
     */
    public function run(): void
    {
        // 1. Categorieën (voor de filter-dropdown)
        $categories = [];
        foreach (['Competitive', 'Social', 'Casual', 'Tabletop', 'LAN party'] as $name) {
            $categories[$name] = Category::create(['name' => $name]);
        }

        // 2. Demo-accounts (wachtwoord van allemaal: "password").
        //    Bewust zonder de UserFactory: die gebruikt Faker, en Faker staat in require-dev.
        //    In productie (composer install --no-dev) bestaat Faker niet, dan zou de seeder crashen.
        //    forceCreate omdat 'role' bewust niet in Fillable staat; het wachtwoord wordt gehasht door de cast 'hashed'.
        $admin = $this->createUser('Admin', 'admin@gameplan.test', 'admin');
        $sam = $this->createUser('Sam de Vries', 'sam@gameplan.test');
        $lisa = $this->createUser('Lisa Bakker', 'lisa@gameplan.test');

        // 3. Login-geschiedenis (voor de regel: 3 verschillende inlogdagen)
        //    Sam: 3 verschillende dagen → mag events maken
        foreach ([3, 2, 1] as $daysAgo) {
            $sam->logins()->forceCreate([
                'created_at' => now()->subDays($daysAgo),
                'updated_at' => now()->subDays($daysAgo),
            ]);
        }
        //    Lisa: 2 logins op dezelfde dag → telt als 1 dag → geblokkeerd
        $lisa->logins()->create();
        $lisa->logins()->create();

        // 4. Events (via de relatie: user_id wordt automatisch ingevuld)
        $ranked = $sam->events()->create([
            'title' => 'Friday Night Ranked',
            'description' => "Bring your A-game! We play ranked matches in teams of 5.\nNew players are welcome.",
            'category_id' => $categories['Competitive']->id,
            'city' => 'Rotterdam',
            'address' => 'Wijnhaven 107',
            'starts_at' => now()->addDays(7)->setTime(19, 30),
            'price' => 0,
        ]);

        $boardGames = $sam->events()->create([
            'title' => 'Board Game Social',
            'description' => 'A relaxed evening with classic and modern board games. Snacks included.',
            'category_id' => $categories['Social']->id,
            'city' => 'Rotterdam',
            'address' => 'Witte de Withstraat 20',
            'starts_at' => now()->addDays(14)->setTime(20, 0),
            'price' => 5,
        ]);

        $coop = $admin->events()->create([
            'title' => 'Indie Co-op Evening',
            'description' => 'Discover small indie games you can play together on the couch.',
            'category_id' => $categories['Casual']->id,
            'city' => 'Delft',
            'address' => 'Markt 1',
            'starts_at' => now()->addDays(21)->setTime(19, 0),
            'price' => 0,
        ]);

        $catan = $admin->events()->create([
            'title' => 'Catan Tournament',
            'description' => 'Settle, trade and build. Winner gets a free drink!',
            'category_id' => $categories['Tabletop']->id,
            'city' => 'Schiedam',
            'address' => 'Broersveld 50',
            'starts_at' => now()->addDays(24)->setTime(14, 0),
            'price' => 7.50,
        ]);

        // Een afgezegd event (is_cancelled staat bewust niet in Fillable → apart instellen)
        $lan = $sam->events()->create([
            'title' => 'Retro LAN Weekend',
            'description' => 'Bring your own PC. Classic shooters and strategy games all weekend.',
            'category_id' => $categories['LAN party']->id,
            'city' => 'Rotterdam',
            'address' => 'Lloydstraat 9',
            'starts_at' => now()->addDays(30)->setTime(12, 0),
            'price' => 15,
        ]);
        $lan->is_cancelled = true;
        $lan->save();

        // 5. Foto's per event (bestanden staan in database/seeders/images)
        $this->attachImage($ranked, 'FridayNightRanked.png');
        $this->attachImage($boardGames, 'BoardGameSocial.png');
        $this->attachImage($coop, 'IndieCo-opEvent.png');
        $this->attachImage($catan, 'CatanTournament.png');
        $this->attachImage($lan, 'RetroLan.png');

        // 6. Deelnemers (veel-op-veel via event_user)
        $ranked->participants()->attach([$lisa->id, $admin->id]);
        $boardGames->participants()->attach([$lisa->id]);
        $coop->participants()->attach([$sam->id, $lisa->id]);
    }

    /**
     * Maakt een demo-account aan met wachtwoord "password".
     */
    private function createUser(string $name, string $email, string $role = 'user'): User
    {
        return User::forceCreate([
            'name' => $name,
            'email' => $email,
            'password' => 'password',
            'role' => $role,
            'email_verified_at' => now(),
        ]);
    }

    /**
     * Kopieert een foto naar storage/app/public/events en slaat het pad op in event_images.
     * Precies wat er bij een echte upload gebeurt (EventController::storeImages), maar dan vanuit een map.
     * Ontbreekt het bestand? Dan slaan we het over en toont de site de standaardafbeelding.
     */
    private function attachImage(Event $event, string $fileName): void
    {
        $source = database_path('seeders/images/' . $fileName);

        if (! file_exists($source)) {
            return;
        }

        // putFile geeft het bestand een unieke naam en geeft het pad terug, bijv. "events/abc123.jpg"
        $path = Storage::disk('public')->putFile('events', new File($source));

        $event->images()->create(['path' => $path]);
    }
}
