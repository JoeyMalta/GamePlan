{{-- Herbruikbare kaart voor één event. Gebruik: <x-event-card :event="$event" /> --}}
@props(['event'])

@php
    $startsAt = \Illuminate\Support\Carbon::parse($event['starts_at']);
@endphp

<a href="{{ route('events.show', $event['id']) }}"
   class="group flex flex-col overflow-hidden rounded-2xl border border-zinc-800 bg-zinc-900 transition hover:-translate-y-1 hover:border-violet-500/50">

    <div class="relative aspect-[16/9] overflow-hidden bg-zinc-800">
        <img src="{{ asset($event['image']) }}" alt="" class="h-full w-full object-cover transition duration-300 group-hover:scale-105">

        <span class="absolute left-3 top-3 rounded-full bg-zinc-950/80 px-3 py-1 text-xs font-semibold text-violet-300 backdrop-blur">
            {{ $event['category'] }}
        </span>

        @if ($event['is_cancelled'])
            <span class="absolute right-3 top-3 rounded-full bg-red-600 px-3 py-1 text-xs font-bold uppercase tracking-wide text-white">
                Cancelled
            </span>
        @endif
    </div>

    <div class="flex flex-1 gap-4 p-5">
        <div class="flex size-14 shrink-0 flex-col items-center justify-center rounded-xl bg-violet-600 text-center text-white">
            <span class="text-[0.65rem] font-bold uppercase leading-none tracking-wide">{{ $startsAt->format('j M') }}</span>
            <span class="pt-1 text-xs font-semibold text-violet-100">{{ $startsAt->format('H:i') }}</span>
        </div>

        <div class="min-w-0 flex-1">
            <h3 class="truncate text-base font-semibold text-white group-hover:text-violet-200 {{ $event['is_cancelled'] ? 'line-through opacity-60' : '' }}">
                {{ $event['title'] }}
            </h3>
            <p class="mt-1 truncate text-sm text-zinc-400">{{ $event['city'] }}</p>

            <div class="mt-3 flex items-center gap-3 text-xs font-medium text-zinc-400">
                <span>{{ $event['participants_count'] }} players</span>
                <span class="text-zinc-600">•</span>
                <span class="{{ $event['price'] == 0 ? 'text-green-400' : 'text-zinc-300' }}">
                    {{ $event['price'] == 0 ? 'Free' : '€' . number_format($event['price'], 2, ',', '.') }}
                </span>
            </div>
        </div>
    </div>
</a>
