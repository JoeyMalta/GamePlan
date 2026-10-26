<x-app-layout>
    @php
        // TIJDELIJKE VOORBEELDDATA — straks via route model binding: public function show(Event $event)
        // met $event->load('category', 'user', 'images', 'participants').
        $event = [
            'id' => 1,
            'title' => 'Friday Night Ranked',
            'category' => 'Competitive',
            'description' => "Bring your A-game! We play ranked matches in teams of 5.\nNew players are welcome, we'll balance the teams.\n\nSnacks and drinks available at the bar.",
            'city' => 'Rotterdam',
            'address' => 'Wijnhaven 107',
            'starts_at' => '2026-11-06 19:30',
            'price' => 0,
            'is_cancelled' => false,
            'organizer' => 'JoeyMalta',
            'images' => ['images/gameplan-hero.png', 'images/gameplan-hero.png', 'images/gameplan-hero.png'],
            'participants' => ['Sam', 'Lisa', 'Mo', 'Noah', 'Eva', 'Daan', 'Yara', 'Finn'],
        ];

        // Straks: $isOwner = auth()->id() === $event->user_id;
        //         $isParticipant = $event->participants->contains(auth()->id());
        //         $isAdmin = auth()->user()?->role === 'admin';
        $isOwner = true;
        $isParticipant = false;
        $isAdmin = true;

        $startsAt = \Illuminate\Support\Carbon::parse($event['starts_at']);
    @endphp

    <div class="mx-auto max-w-7xl px-6 py-10">
        <a href="{{ route('events.index') }}" class="text-sm font-medium text-zinc-400 transition hover:text-white">← All events</a>

        @if ($event['is_cancelled'])
            <div class="mt-6 rounded-xl border border-red-900/60 bg-red-950/40 px-5 py-4 text-sm text-red-300">
                <span class="font-semibold">This event has been cancelled</span> by an administrator.
            </div>
        @endif

        <div class="mt-6 grid gap-8 lg:grid-cols-3">
            {{-- Linkerkolom: foto's + info --}}
            <div class="lg:col-span-2">
                <div class="overflow-hidden rounded-2xl border border-zinc-800 bg-zinc-900">
                    <img src="{{ asset($event['images'][0]) }}" alt="" class="aspect-[16/9] w-full object-cover">
                </div>

                @if (count($event['images']) > 1)
                    <div class="mt-3 grid grid-cols-4 gap-3">
                        @foreach (array_slice($event['images'], 1) as $image)
                            <img src="{{ asset($image) }}" alt="" class="aspect-[4/3] w-full rounded-xl border border-zinc-800 object-cover">
                        @endforeach
                    </div>
                @endif

                <div class="mt-8">
                    <span class="rounded-full bg-violet-600/15 px-3 py-1 text-xs font-semibold text-violet-300">{{ $event['category'] }}</span>
                    <h1 class="mt-4 text-3xl font-bold tracking-tight text-white sm:text-4xl">{{ $event['title'] }}</h1>
                    <p class="mt-2 text-sm text-zinc-400">Organized by <span class="font-semibold text-zinc-200">{{ $event['organizer'] }}</span></p>

                    {{-- nl2br(e(...)): e() escapet HTML (tegen XSS), nl2br zet enters om naar <br> --}}
                    <div class="mt-6 text-base leading-7 text-zinc-300">{!! nl2br(e($event['description'])) !!}</div>
                </div>
            </div>

            {{-- Rechterkolom: details + acties --}}
            <aside class="space-y-6">
                <div class="rounded-2xl border border-zinc-800 bg-zinc-900 p-6">
                    <dl class="space-y-4 text-sm">
                        <div>
                            <dt class="text-xs font-semibold uppercase tracking-wide text-zinc-500">When</dt>
                            <dd class="mt-1 font-medium text-white">{{ $startsAt->format('l j F Y') }} · {{ $startsAt->format('H:i') }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-semibold uppercase tracking-wide text-zinc-500">Where</dt>
                            <dd class="mt-1 font-medium text-white">{{ $event['address'] }}, {{ $event['city'] }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-semibold uppercase tracking-wide text-zinc-500">Price</dt>
                            <dd class="mt-1 font-medium {{ $event['price'] == 0 ? 'text-green-400' : 'text-white' }}">
                                {{ $event['price'] == 0 ? 'Free' : '€' . number_format($event['price'], 2, ',', '.') }}
                            </dd>
                        </div>
                    </dl>

                    <div class="mt-6 border-t border-zinc-800 pt-6">
                        @auth
                            @unless ($event['is_cancelled'])
                                {{-- Deelnemen/afmelden = rij toevoegen/verwijderen in event_user --}}
                                @if ($isParticipant)
                                    <form method="POST" action="/events/{{ $event['id'] }}/leave">
                                        @csrf
                                        @method('DELETE')
                                        <x-secondary-button type="submit" class="w-full">Leave event</x-secondary-button>
                                    </form>
                                @else
                                    <form method="POST" action="/events/{{ $event['id'] }}/join">
                                        @csrf
                                        <x-primary-button class="w-full">Join event</x-primary-button>
                                    </form>
                                @endif
                            @endunless
                        @else
                            <a href="{{ route('login') }}" class="inline-flex w-full items-center justify-center rounded-lg bg-violet-600 px-5 py-2.5 text-xs font-bold uppercase tracking-wide text-white transition hover:bg-violet-500">
                                Log in to join
                            </a>
                        @endauth
                    </div>
                </div>

                {{-- Deelnemers --}}
                <div class="rounded-2xl border border-zinc-800 bg-zinc-900 p-6">
                    <h3 class="text-sm font-semibold text-white">
                        Players <span class="text-zinc-500">({{ count($event['participants']) }})</span>
                    </h3>
                    <div class="mt-4 flex flex-wrap gap-2">
                        @foreach ($event['participants'] as $name)
                            <span class="inline-flex items-center gap-2 rounded-full border border-zinc-800 bg-zinc-950 py-1 pl-1 pr-3 text-xs text-zinc-300">
                                <span class="flex size-6 items-center justify-center rounded-full bg-violet-600 text-[0.65rem] font-bold text-white">{{ strtoupper(substr($name, 0, 1)) }}</span>
                                {{ $name }}
                            </span>
                        @endforeach
                    </div>
                </div>

                {{-- Alleen zichtbaar voor de maker (Edit/Delete) en de admin (afzeggen) --}}
                @auth
                    @if ($isOwner || $isAdmin)
                        <div class="rounded-2xl border border-zinc-800 bg-zinc-900 p-6">
                            <h3 class="text-sm font-semibold text-white">Manage</h3>
                            <div class="mt-4 flex flex-col gap-3">
                                @if ($isOwner)
                                    <a href="{{ route('events.edit', $event['id']) }}"
                                       class="inline-flex w-full items-center justify-center rounded-lg border border-zinc-700 px-5 py-2.5 text-xs font-bold uppercase tracking-wide text-zinc-200 transition hover:border-zinc-500 hover:text-white">
                                        Edit event
                                    </a>

                                    {{-- D van CRUD: DELETE naar EventController@destroy (soft delete) --}}
                                    <form method="POST" action="/events/{{ $event['id'] }}" onsubmit="return confirm('Delete this event?')">
                                        @csrf
                                        @method('DELETE')
                                        <x-danger-button class="w-full">Delete event</x-danger-button>
                                    </form>
                                @endif

                                @if ($isAdmin)
                                    {{-- Zelfde toggle-actie als in de adminlijst --}}
                                    <form method="POST" action="/admin/events/{{ $event['id'] }}/toggle-cancelled">
                                        @csrf
                                        <x-secondary-button type="submit" class="w-full">
                                            {{ $event['is_cancelled'] ? 'Reinstate event' : 'Cancel event (admin)' }}
                                        </x-secondary-button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @endif
                @endauth
            </aside>
        </div>
    </div>
</x-app-layout>
