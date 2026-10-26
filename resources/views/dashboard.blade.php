<x-app-layout>
    @php
        // TIJDELIJKE VOORBEELDDATA — straks in een DashboardController (of closure):
        //   $myEvents     = auth()->user()->events()->withCount('participants')->latest('starts_at')->get();      // 1-op-veel
        //   $joinedEvents = auth()->user()->joinedEvents()->orderBy('starts_at')->get();                           // veel-op-veel
        $myEvents = [
            ['id' => 1, 'title' => 'Friday Night Ranked', 'city' => 'Rotterdam', 'starts_at' => '2026-11-06 19:30', 'participants_count' => 8, 'is_cancelled' => false],
            ['id' => 6, 'title' => 'Fighting Game Friday', 'city' => 'Delft', 'starts_at' => '2026-12-04 19:00', 'participants_count' => 10, 'is_cancelled' => false],
        ];
        $joinedEvents = [
            ['id' => 2, 'title' => 'Board Game Social', 'city' => 'Rotterdam', 'starts_at' => '2026-11-13 20:00', 'is_cancelled' => false],
            ['id' => 5, 'title' => 'Retro LAN Weekend', 'city' => 'Rotterdam', 'starts_at' => '2026-11-28 12:00', 'is_cancelled' => true],
        ];
    @endphp

    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.22em] text-violet-300">Dashboard</p>
                <h2 class="mt-1 text-2xl font-bold tracking-tight text-white">Welcome back, {{ Auth::user()->name }}</h2>
            </div>
            <a href="{{ route('events.create') }}"
               class="inline-flex items-center justify-center rounded-lg bg-violet-600 px-5 py-2.5 text-xs font-bold uppercase tracking-wide text-white shadow-lg shadow-violet-950/30 transition hover:bg-violet-500">
                + Organize event
            </a>
        </div>
    </x-slot>

    <div class="mx-auto grid max-w-7xl gap-8 px-6 py-10 lg:grid-cols-2">
        {{-- Events die ik organiseer (alleen hier zie je Edit/Delete: jij bent de maker) --}}
        <section class="rounded-2xl border border-zinc-800 bg-zinc-900 p-6">
            <h3 class="text-base font-semibold text-white">Events I organize</h3>

            <ul class="mt-4 divide-y divide-zinc-800">
                @forelse ($myEvents as $event)
                    <li class="flex items-center justify-between gap-4 py-4">
                        <div class="min-w-0">
                            <a href="{{ route('events.show', $event['id']) }}" class="truncate font-medium text-white hover:text-violet-300">{{ $event['title'] }}</a>
                            <p class="text-sm text-zinc-400">
                                {{ \Illuminate\Support\Carbon::parse($event['starts_at'])->format('j M · H:i') }} · {{ $event['city'] }} · {{ $event['participants_count'] }} players
                            </p>
                        </div>
                        <div class="flex shrink-0 items-center gap-2">
                            <a href="{{ route('events.edit', $event['id']) }}"
                               class="rounded-lg border border-zinc-700 px-3 py-1.5 text-xs font-semibold text-zinc-300 transition hover:border-zinc-500 hover:text-white">Edit</a>

                            <form method="POST" action="/events/{{ $event['id'] }}" onsubmit="return confirm('Delete this event?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="rounded-lg border border-red-900/60 px-3 py-1.5 text-xs font-semibold text-red-300 transition hover:bg-red-950/40">Delete</button>
                            </form>
                        </div>
                    </li>
                @empty
                    <li class="py-6 text-sm text-zinc-400">You haven't organized any events yet.</li>
                @endforelse
            </ul>
        </section>

        {{-- Events waar ik aan deelneem (via tussentabel event_user) --}}
        <section class="rounded-2xl border border-zinc-800 bg-zinc-900 p-6">
            <h3 class="text-base font-semibold text-white">Events I'm joining</h3>

            <ul class="mt-4 divide-y divide-zinc-800">
                @forelse ($joinedEvents as $event)
                    <li class="flex items-center justify-between gap-4 py-4">
                        <div class="min-w-0">
                            <a href="{{ route('events.show', $event['id']) }}" class="truncate font-medium text-white hover:text-violet-300 {{ $event['is_cancelled'] ? 'line-through opacity-60' : '' }}">{{ $event['title'] }}</a>
                            <p class="text-sm text-zinc-400">
                                {{ \Illuminate\Support\Carbon::parse($event['starts_at'])->format('j M · H:i') }} · {{ $event['city'] }}
                            </p>
                        </div>
                        @if ($event['is_cancelled'])
                            <span class="shrink-0 rounded-full bg-red-600/15 px-2.5 py-1 text-xs font-semibold text-red-300">Cancelled</span>
                        @endif
                    </li>
                @empty
                    <li class="py-6 text-sm text-zinc-400">
                        You're not joining any events yet. <a href="{{ route('events.index') }}" class="font-semibold text-violet-400 hover:text-violet-300">Browse events</a>
                    </li>
                @endforelse
            </ul>
        </section>
    </div>
</x-app-layout>
