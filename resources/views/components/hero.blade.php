@props([
    'eyebrow' => 'Play. Meet. Connect.',
    'title' => 'Your next game starts here.',
    'description' => 'Gameplan helps players discover local gaming events, find new teammates, and turn a shared game into a real connection.',
    'primaryLabel' => 'Plan Now',
    'primaryHref' => '/events/create',
    'secondaryLabel' => 'Learn More',
    'secondaryHref' => '/events',

    // Eerstvolgende events (Event-models), meegegeven vanuit HomeController via home.blade.php
    'events' => [],
])

<section
    {{ $attributes->merge([
        'class' => 'relative isolate flex flex-1 overflow-hidden bg-zinc-950 text-white',
    ]) }}
>
    <img
        src="{{ Vite::asset('resources/images/gameplan-hero.png') }}"
        alt=""
        class="absolute inset-0 -z-30 h-full w-full scale-[1.02] object-cover object-[62%_center]"
        fetchpriority="high"
    >

    <div class="absolute inset-0 -z-20 bg-zinc-950/25"></div>
    <div class="absolute inset-0 -z-10 bg-linear-to-r from-zinc-950 via-zinc-950/65 to-zinc-950/10"></div>
    <div class="absolute inset-x-0 bottom-0 -z-10 h-2/3 bg-linear-to-t from-zinc-950 via-zinc-950/45 to-transparent"></div>

    <div class="mx-auto flex min-h-full w-full max-w-7xl flex-col justify-between gap-12 px-6 pb-6 pt-14 sm:px-8 sm:pb-8 sm:pt-20 lg:px-12 lg:pt-24">
        <div class="flex max-w-2xl flex-col gap-6 text-shadow-lg">
            <p class="text-sm font-semibold uppercase tracking-[0.24em] text-violet-300">
                {{ $eyebrow }}
            </p>

            <div class="flex flex-col gap-5">
                <h1 class="text-5xl font-bold uppercase leading-[0.92] tracking-[-0.045em] text-balance sm:text-6xl lg:text-7xl">
                    {{ $title }}
                </h1>

                <p class="max-w-xl text-base leading-7 text-zinc-200 sm:text-lg sm:leading-8">
                    {{ $description }}
                </p>
            </div>

            <div class="flex flex-col gap-3 pt-2 sm:flex-row">
                <a
                    href="{{ $primaryHref }}"
                    class="inline-flex items-center justify-center rounded-lg bg-violet-600 px-7 py-3.5 text-sm font-bold uppercase tracking-wide text-white shadow-lg shadow-violet-950/30 transition hover:-translate-y-0.5 hover:bg-violet-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-violet-400"
                >
                    {{ $primaryLabel }}
                </a>

                <a
                    href="{{ $secondaryHref }}"
                    class="inline-flex items-center justify-center rounded-lg border border-white/25 bg-zinc-950/30 px-7 py-3.5 text-sm font-bold uppercase tracking-wide text-white backdrop-blur-sm transition hover:-translate-y-0.5 hover:border-white/40 hover:bg-white/10 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white"
                >
                    {{ $secondaryLabel }}
                </a>
            </div>
        </div>

        <div class="mx-auto flex w-full max-w-5xl flex-col gap-3">
            <div class="flex items-end justify-between gap-4">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.22em] text-violet-300">
                        Ready when you are
                    </p>
                    <h2 class="text-xl font-bold text-white sm:text-2xl">Upcoming events</h2>
                </div>

                <a href="{{ $secondaryHref }}" class="hidden text-sm font-semibold text-zinc-300 transition hover:text-white sm:block">
                    View all events →
                </a>
            </div>

            <div class="grid gap-3 md:grid-cols-3">
                @forelse ($events as $event)
                    {{-- Elke kaart linkt naar de detailpagina van DAT event (route model binding) --}}
                    <a
                        href="{{ route('events.show', $event) }}"
                        class="group flex min-w-0 items-center gap-4 rounded-2xl border border-white/15 bg-zinc-950/55 p-4 shadow-xl shadow-black/25 backdrop-blur-xl transition hover:-translate-y-1 hover:border-violet-400/50 hover:bg-zinc-900/70"
                    >
                        <div class="flex size-14 shrink-0 flex-col items-center justify-center rounded-xl bg-violet-600 text-center text-white shadow-lg shadow-violet-950/30">
                            <span class="text-[0.65rem] font-bold uppercase leading-none tracking-wide">
                                {{ $event->starts_at->format('j M') }}
                            </span>
                            <span class="pt-1 text-xs font-semibold text-violet-100">
                                {{ $event->starts_at->format('H:i') }}
                            </span>
                        </div>

                        <div class="min-w-0 flex-1">
                            <span class="text-[0.65rem] font-semibold uppercase tracking-[0.16em] text-violet-300">
                                {{ $event->category->name }}
                            </span>
                            <h3 class="truncate text-base font-semibold text-white group-hover:text-violet-200">
                                {{ $event->title }}
                            </h3>
                            <p class="truncate text-xs text-zinc-400">
                                {{ $event->city }}
                                · {{ $event->participants_count }} {{ Str::plural('player', $event->participants_count) }}
                                · {{ $event->price == 0 ? 'Free' : '€' . number_format($event->price, 2, ',', '.') }}
                            </p>
                        </div>

                        <span class="text-lg text-zinc-500 transition group-hover:translate-x-0.5 group-hover:text-white" aria-hidden="true">
                            →
                        </span>
                    </a>
                @empty
                    <p class="rounded-2xl border border-white/15 bg-zinc-950/55 p-4 text-sm text-zinc-300 backdrop-blur-xl md:col-span-3">
                        No upcoming events yet. Be the first to <a href="{{ route('events.create') }}" class="font-semibold text-violet-300 hover:text-violet-200">organize one</a>!
                    </p>
                @endforelse
            </div>
        </div>
    </div>
</section>
