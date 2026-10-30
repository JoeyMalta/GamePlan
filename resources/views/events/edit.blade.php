<x-app-layout>
    @php
        // TIJDELIJK: straks krijg je $event via route model binding: public function edit(Event $event)
        // En de controller controleert eerst of de ingelogde gebruiker de maker is (access control, OWASP A01).
        $event = [
            'id' => 1, 'title' => 'Friday Night Ranked', 'category_id' => 1,
            'description' => "Bring your A-game! We play ranked matches in teams of 5.\nSnacks and drinks available.",
            'city' => 'Rotterdam', 'address' => 'Wijnhaven 107', 'starts_at' => '2026-11-06T19:30', 'price' => 0,
            // Straks: $event->images (rijen uit event_images met id + path)
            'images' => [],
        ];
    @endphp

    <x-slot name="header">
        <p class="text-xs font-semibold uppercase tracking-[0.22em] text-violet-300">Edit event</p>
        <h2 class="mt-1 text-2xl font-bold tracking-tight text-white">{{ $event['title'] }}</h2>
    </x-slot>

    <div class="mx-auto max-w-7xl px-6 py-10">
        <form method="POST" action="/events/{{ $event['id'] }}" enctype="multipart/form-data"
              class="rounded-2xl border border-zinc-800 bg-zinc-900 p-6 sm:p-8">
            @csrf
            @method('PUT') {{-- HTML kent alleen GET/POST; @method zet PUT erin voor de update-route --}}

            <div class="max-w-3xl">
                {{-- Bestaande foto's: aanvinken = verwijderen bij opslaan (name="remove_images[]") --}}
                @if (count($event['images']))
                    <div class="mb-8">
                        <p class="mb-3 text-sm font-medium text-zinc-300">Current photos</p>
                        <div class="grid grid-cols-2 gap-3 sm:grid-cols-4">
                            @foreach ($event['images'] as $image)
                                <label class="group relative block cursor-pointer overflow-hidden rounded-xl border border-zinc-800">
                                    <img src="{{ asset('storage/' . $image['path']) }}" alt="" class="aspect-[4/3] w-full object-cover">
                                    <span class="absolute inset-x-0 bottom-0 flex items-center gap-2 bg-zinc-950/80 px-3 py-2 text-xs text-zinc-300">
                                        <input type="checkbox" name="remove_images[]" value="{{ $image['id'] }}"
                                               class="size-4 rounded border-zinc-600 bg-zinc-950 text-red-600 focus:ring-red-500 focus:ring-offset-zinc-900">
                                        Remove
                                    </span>
                                </label>
                            @endforeach
                        </div>
                        <p class="mt-2 text-xs text-zinc-500">New photos you upload below are added to these.</p>
                    </div>
                @endif

                @include('events.partials.form', ['event' => $event])

                <div class="mt-8 flex items-center gap-3 border-t border-zinc-800 pt-6">
                    <x-primary-button>Save changes</x-primary-button>
                    <a href="{{ route('events.show', $event['id']) }}" class="px-3 py-2 text-sm font-medium text-zinc-400 transition hover:text-white">Cancel</a>
                </div>
            </div>
        </form>
    </div>
</x-app-layout>
