<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

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

        // 2. Demo-accounts (wachtwoord van allemaal: "password", komt uit de UserFactory)
        $admin = User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@gameplan.test',
            'role' => 'admin',
        ]);

        $sam = User::factory()->create([
            'name' => 'Sam de Vries',
            'email' => 'sam@gameplan.test',
        ]);

        $lisa = User::factory()->create([
            'name' => 'Lisa Bakker',
            'email' => 'lisa@gameplan.test',
        ]);

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

        // 5. Deelnemers (veel-op-veel via event_user)
        $ranked->participants()->attach([$lisa->id, $admin->id]);
        $boardGames->participants()->attach([$lisa->id]);
        $coop->participants()->attach([$sam->id, $lisa->id]);
    }
}
