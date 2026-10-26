<x-app-layout>
    @php
        // TIJDELIJKE VOORBEELDDATA — straks komt dit uit de EventController:
        //   $events     = Event::with('category')->withCount('participants')->...->get();
        //   $categories = Category::orderBy('name')->get();
        //   $cities     = Event::distinct()->orderBy('city')->pluck('city');
        // De keys volgen de kolommen uit de ERD.
        $categories = [1 => 'Competitive', 2 => 'Social', 3 => 'Casual', 4 => 'Tabletop', 5 => 'LAN party'];
        $cities = ['Delft', 'Rotterdam', 'Schiedam'];

        $events = [
            ['id' => 1, 'title' => 'Friday Night Ranked', 'category' => 'Competitive', 'city' => 'Rotterdam', 'starts_at' => '2026-11-06 19:30', 'price' => 0, 'participants_count' => 8, 'is_cancelled' => false, 'image' => 'images/gameplan-hero.png'],
            ['id' => 2, 'title' => 'Board Game Social', 'category' => 'Social', 'city' => 'Rotterdam', 'starts_at' => '2026-11-13 20:00', 'price' => 5, 'participants_count' => 14, 'is_cancelled' => false, 'image' => 'images/gameplan-hero.png'],
            ['id' => 3, 'title' => 'Indie Co-op Evening', 'category' => 'Casual', 'city' => 'Delft', 'starts_at' => '2026-11-20 19:00', 'price' => 0, 'participants_count' => 6, 'is_cancelled' => false, 'image' => 'images/gameplan-hero.png'],
            ['id' => 4, 'title' => 'Catan Tournament', 'category' => 'Tabletop', 'city' => 'Schiedam', 'starts_at' => '2026-11-22 14:00', 'price' => 7.5, 'participants_count' => 16, 'is_cancelled' => false, 'image' => 'images/gameplan-hero.png'],
            ['id' => 5, 'title' => 'Retro LAN Weekend', 'category' => 'LAN party', 'city' => 'Rotterdam', 'starts_at' => '2026-11-28 12:00', 'price' => 15, 'participants_count' => 24, 'is_cancelled' => true, 'image' => 'images/gameplan-hero.png'],
            ['id' => 6, 'title' => 'Fighting Game Friday', 'category' => 'Competitive', 'city' => 'Delft', 'starts_at' => '2026-12-04 19:00', 'price' => 0, 'participants_count' => 10, 'is_cancelled' => false, 'image' => 'images/gameplan-hero.png'],
        ];
    @endphp

    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.22em] text-violet-300">Discover</p>
                <h2 class="mt-1 text-2xl font-bold tracking-tight text-white">All events</h2>
            </div>

            <a href="{{ route('events.create') }}"
               class="inline-flex items-center justify-center rounded-lg bg-violet-600 px-5 py-2.5 text-xs font-bold uppercase tracking-wide text-white shadow-lg shadow-violet-950/30 transition hover:bg-violet-500">
                + Organize event
            </a>
        </div>
    </x-slot>

    <div class="mx-auto max-w-7xl px-6 py-10">

        {{-- ZOEKEN & FILTEREN
             method="GET": de filters komen in de URL te staan (?q=...&city=...).
             Zo kun je een gefilterde lijst delen of bookmarken, en de controller leest ze uit met $request->input('city'). --}}
        <form method="GET" action="{{ route('events.index') }}"
              class="rounded-2xl border border-zinc-800 bg-zinc-900 p-5">
            <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-12">
                <div class="lg:col-span-4">
                    <x-input-label for="q" value="Search" />
                    <x-text-input id="q" name="q" type="search" :value="request('q')" placeholder="Title or description..." />
                </div>

                <div class="lg:col-span-2">
                    <x-input-label for="city" value="City" />
                    <select id="city" name="city"
                            class="block w-full rounded-lg border border-zinc-700 bg-zinc-950 px-3.5 py-2.5 text-sm text-white focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500/40">
                        <option value="">All cities</option>
                        @foreach ($cities as $city)
                            <option value="{{ $city }}" @selected(request('city') === $city)>{{ $city }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="lg:col-span-2">
                    <x-input-label for="category" value="Category" />
                    <select id="category" name="category"
                            class="block w-full rounded-lg border border-zinc-700 bg-zinc-950 px-3.5 py-2.5 text-sm text-white focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500/40">
                        <option value="">All categories</option>
                        @foreach ($categories as $id => $name)
                            <option value="{{ $id }}" @selected(request('category') == $id)>{{ $name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="lg:col-span-2">
                    <x-input-label for="price" value="Price" />
                    <select id="price" name="price"
                            class="block w-full rounded-lg border border-zinc-700 bg-zinc-950 px-3.5 py-2.5 text-sm text-white focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500/40">
                        <option value="">Any price</option>
                        <option value="free" @selected(request('price') === 'free')>Free</option>
                        <option value="paid" @selected(request('price') === 'paid')>Paid</option>
                    </select>
                </div>

                <div class="flex items-end gap-2 lg:col-span-2">
                    <x-primary-button class="flex-1">Filter</x-primary-button>
                    <a href="{{ route('events.index') }}"
                       class="inline-flex items-center justify-center rounded-lg border border-zinc-700 px-4 py-2.5 text-xs font-bold uppercase tracking-wide text-zinc-300 transition hover:border-zinc-500 hover:text-white">
                        Reset
                    </a>
                </div>
            </div>
        </form>

        <p class="mt-8 text-sm text-zinc-400">
            <span class="font-semibold text-white">{{ count($events) }}</span> events found
        </p>

        <div class="mt-4 grid gap-6 md:grid-cols-2 lg:grid-cols-3">
            @forelse ($events as $event)
                <x-event-card :event="$event" />
            @empty
                <div class="col-span-full rounded-2xl border border-dashed border-zinc-700 p-12 text-center">
                    <p class="text-base font-semibold text-white">No events found</p>
                    <p class="mt-2 text-sm text-zinc-400">Try another filter, or organize one yourself.</p>
                </div>
            @endforelse
        </div>
    </div>
</x-app-layout>
