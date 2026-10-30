<x-app-layout>
    {{-- Data komt uit EventController@create:
         $canCreate (bool), $loginDays (int, uit de Eloquent-query), $requiredDays (3) en $categories --}}

    <x-slot name="header">
        <p class="text-xs font-semibold uppercase tracking-[0.22em] text-violet-300">Organize</p>
        <h2 class="mt-1 text-2xl font-bold tracking-tight text-white">Create an event</h2>
    </x-slot>

    <div class="mx-auto max-w-7xl px-6 py-10">
        @if ($canCreate)
            {{-- enctype="multipart/form-data" is VERPLICHT als je bestanden (afbeeldingen) wilt uploaden --}}
            <form method="POST" action="{{ route('events.store') }}" enctype="multipart/form-data"
                  class="rounded-2xl border border-zinc-800 bg-zinc-900 p-6 sm:p-8">
                @csrf {{-- beschermt tegen CSRF-aanvallen (OWASP) --}}

                <div class="max-w-3xl">
                    @include('events.partials.form', ['event' => null, 'categories' => $categories])

                    <div class="mt-8 flex items-center gap-3 border-t border-zinc-800 pt-6">
                        <x-primary-button>Publish event</x-primary-button>
                        <a href="{{ route('events.index') }}" class="px-3 py-2 text-sm font-medium text-zinc-400 transition hover:text-white">Cancel</a>
                    </div>
                </div>
            </form>
        @else
            {{-- Diepere validatie: pas na 3 verschillende inlogdagen mag je een event aanmaken --}}
            <div class="rounded-2xl border border-zinc-800 bg-zinc-900 p-8 text-center sm:p-12">
                <div class="mx-auto flex size-14 items-center justify-center rounded-full bg-violet-600/15 text-2xl">🔒</div>
                <h3 class="mt-5 text-lg font-semibold text-white">Almost there!</h3>
                <p class="mx-auto mt-2 max-w-md text-sm leading-6 text-zinc-400">
                    To keep Gameplan safe, you can organize events after logging in on
                    <span class="font-semibold text-white">{{ $requiredDays }} different days</span>.
                </p>

                <div class="mx-auto mt-6 max-w-xs">
                    <div class="flex justify-between text-xs font-medium text-zinc-400">
                        <span>Progress</span>
                        <span>{{ min($loginDays, $requiredDays) }} / {{ $requiredDays }} days</span>
                    </div>
                    <div class="mt-2 h-2 overflow-hidden rounded-full bg-zinc-800">
                        <div class="h-full rounded-full bg-violet-600" style="width: {{ min(100, $loginDays / $requiredDays * 100) }}%"></div>
                    </div>
                </div>

                <a href="{{ route('events.index') }}" class="mt-8 inline-flex rounded-lg border border-zinc-700 px-5 py-2.5 text-xs font-bold uppercase tracking-wide text-zinc-300 transition hover:border-zinc-500 hover:text-white">
                    Browse events instead
                </a>
            </div>
        @endif
    </div>
</x-app-layout>
