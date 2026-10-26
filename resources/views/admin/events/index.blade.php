<x-app-layout>
    @php
        // TIJDELIJKE VOORBEELDDATA — straks in Admin\EventController@index:
        //   $events = Event::with('user', 'category')->withCount('participants')->orderBy('starts_at')->get();
        $events = [
            ['id' => 1, 'title' => 'Friday Night Ranked', 'organizer' => 'JoeyMalta', 'category' => 'Competitive', 'city' => 'Rotterdam', 'starts_at' => '2026-11-06 19:30', 'participants_count' => 8, 'is_cancelled' => false],
            ['id' => 2, 'title' => 'Board Game Social', 'organizer' => 'Sam de Vries', 'category' => 'Social', 'city' => 'Rotterdam', 'starts_at' => '2026-11-13 20:00', 'participants_count' => 14, 'is_cancelled' => false],
            ['id' => 5, 'title' => 'Retro LAN Weekend', 'organizer' => 'Mo Haddou', 'category' => 'LAN party', 'city' => 'Rotterdam', 'starts_at' => '2026-11-28 12:00', 'participants_count' => 24, 'is_cancelled' => true],
        ];
    @endphp

    <x-slot name="header">
        <p class="text-xs font-semibold uppercase tracking-[0.22em] text-violet-300">Admin</p>
        <h2 class="mt-1 text-2xl font-bold tracking-tight text-white">Manage events</h2>
    </x-slot>

    <div class="mx-auto max-w-7xl px-6 py-10">
        @include('admin.partials.nav')

        <div class="mt-6 overflow-x-auto rounded-2xl border border-zinc-800 bg-zinc-900">
            <table class="w-full min-w-[720px] text-left text-sm">
                <thead class="border-b border-zinc-800 text-xs uppercase tracking-wide text-zinc-500">
                    <tr>
                        <th class="px-6 py-4 font-semibold">Event</th>
                        <th class="px-6 py-4 font-semibold">Organizer</th>
                        <th class="px-6 py-4 font-semibold">Date</th>
                        <th class="px-6 py-4 font-semibold">Players</th>
                        <th class="px-6 py-4 text-right font-semibold">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-800">
                    @foreach ($events as $event)
                        <tr>
                            <td class="px-6 py-4">
                                <a href="{{ route('events.show', $event['id']) }}" class="font-semibold text-white hover:text-violet-300 {{ $event['is_cancelled'] ? 'line-through opacity-60' : '' }}">
                                    {{ $event['title'] }}
                                </a>
                                <p class="text-zinc-400">{{ $event['category'] }} · {{ $event['city'] }}</p>
                            </td>
                            <td class="px-6 py-4 text-zinc-300">{{ $event['organizer'] }}</td>
                            <td class="px-6 py-4 text-zinc-400">{{ \Illuminate\Support\Carbon::parse($event['starts_at'])->format('j M Y · H:i') }}</td>
                            <td class="px-6 py-4 text-zinc-300">{{ $event['participants_count'] }}</td>
                            <td class="px-6 py-4 text-right">
                                {{-- STATUS-TOGGLE: POST naar Admin\EventController@toggleCancelled --}}
                                <form method="POST" action="/admin/events/{{ $event['id'] }}/toggle-cancelled" class="inline">
                                    @csrf
                                    <button type="submit"
                                            class="inline-flex min-w-32 items-center justify-center gap-2 rounded-lg border px-4 py-2 text-xs font-bold uppercase tracking-wide transition {{ $event['is_cancelled'] ? 'border-red-900/60 bg-red-950/40 text-red-300 hover:bg-red-900/40' : 'border-green-800/60 bg-green-950/40 text-green-300 hover:bg-green-900/40' }}">
                                        <span class="size-2 rounded-full {{ $event['is_cancelled'] ? 'bg-red-400' : 'bg-green-400' }}"></span>
                                        {{ $event['is_cancelled'] ? 'Cancelled' : 'Scheduled' }}
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <p class="mt-4 text-xs text-zinc-500">Click a status to switch it. Cancelled events stay visible with a "Cancelled" label.</p>
    </div>
</x-app-layout>
