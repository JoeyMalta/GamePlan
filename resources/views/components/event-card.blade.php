{{-- Herbruikbare kaart voor één event (een Event-model). Gebruik: <x-event-card :event="$event" /> --}}
@props(['event'])

@php
    // Eerste geüploade foto. Storage::disk('public')->url() maakt de URL: lokaal /storage/..., in productie de bucket-URL.
    // anders een standaardafbeelding uit resources/images.
    $image = $event->images->first();
    $imageUrl = $image ? Storage::disk('public')->url($image->path) : Vite::asset('resources/images/gameplan-hero.png');
@endphp

<a href="{{ route('events.show', $event) }}"
   class="group flex flex-col overflow-hidden rounded-2xl border border-zinc-800 bg-zinc-900 transition hover:-translate-y-1 hover:border-violet-500/50">

    <div class="relative aspect-[16/9] overflow-hidden bg-zinc-800">
        <img src="{{ $imageUrl }}" alt="" class="h-full w-full object-cover transition duration-300 group-hover:scale-105">

        <span class="absolute left-3 top-3 rounded-full bg-zinc-950/80 px-3 py-1 text-xs font-semibold text-violet-300 backdrop-blur">
            {{ $event->category->name }}
        </span>

        @if ($event->is_cancelled)
            <span class="absolute right-3 top-3 rounded-full bg-red-600 px-3 py-1 text-xs font-bold uppercase tracking-wide text-white">
                Cancelled
            </span>
        @endif
    </div>

    <div class="flex flex-1 gap-4 p-5">
        {{-- starts_at is een Carbon-datum dankzij de cast 'datetime' in het Event-model --}}
        <div class="flex size-14 shrink-0 flex-col items-center justify-center rounded-xl bg-violet-600 text-center text-white">
            <span class="text-[0.65rem] font-bold uppercase leading-none tracking-wide">{{ $event->starts_at->format('j M') }}</span>
            <span class="pt-1 text-xs font-semibold text-violet-100">{{ $event->starts_at->format('H:i') }}</span>
        </div>

        <div class="min-w-0 flex-1">
            <h3 class="truncate text-base font-semibold text-white group-hover:text-violet-200 {{ $event->is_cancelled ? 'line-through opacity-60' : '' }}">
                {{ $event->title }}
            </h3>
            <p class="mt-1 truncate text-sm text-zinc-400">{{ $event->city }}</p>

            <div class="mt-3 flex items-center gap-3 text-xs font-medium text-zinc-400">
                {{-- participants_count komt van withCount('participants') in de controller --}}
                <span>{{ $event->participants_count }} {{ Str::plural('player', $event->participants_count) }}</span>
                <span class="text-zinc-600">•</span>
                <span class="{{ $event->price == 0 ? 'text-green-400' : 'text-zinc-300' }}">
                    {{ $event->price == 0 ? 'Free' : '€' . number_format($event->price, 2, ',', '.') }}
                </span>
            </div>
        </div>
    </div>
</a>
