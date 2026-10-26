{{-- Toont een melding na een redirect, bijv. in de controller:
     return redirect()->route('events.show', $event)->with('success', 'Event created!');
     session('success') leest die "flash"-melding één keer uit. --}}
@if (session('success'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" x-transition
         class="mx-auto mt-6 max-w-7xl px-6">
        <div class="flex items-center justify-between gap-4 rounded-xl border border-green-800/60 bg-green-950/40 px-5 py-3 text-sm text-green-300">
            <span>{{ session('success') }}</span>
            <button type="button" @click="show = false" class="text-green-400 hover:text-green-200" aria-label="Close">✕</button>
        </div>
    </div>
@endif

@if (session('error'))
    <div class="mx-auto mt-6 max-w-7xl px-6">
        <div class="rounded-xl border border-red-900/60 bg-red-950/40 px-5 py-3 text-sm text-red-300">
            {{ session('error') }}
        </div>
    </div>
@endif
