<x-app-layout>
    <x-slot name="header">
        <p class="text-xs font-semibold uppercase tracking-[0.22em] text-violet-300">Dashboard</p>
        <h2 class="mt-1 text-2xl font-bold tracking-tight text-white">
            Welcome back, {{ Auth::user()->name }}
        </h2>
    </x-slot>

    <div class="mx-auto max-w-7xl px-6 py-10">
        <div class="grid gap-4 md:grid-cols-3">
            <a href="/events" class="group rounded-2xl border border-zinc-800 bg-zinc-900 p-6 transition hover:-translate-y-0.5 hover:border-violet-500/50">
                <p class="text-sm font-semibold text-white group-hover:text-violet-200">Browse events</p>
                <p class="mt-2 text-sm leading-6 text-zinc-400">Find game nights near you and sign up.</p>
            </a>

            <a href="/events/create" class="group rounded-2xl border border-zinc-800 bg-zinc-900 p-6 transition hover:-translate-y-0.5 hover:border-violet-500/50">
                <p class="text-sm font-semibold text-white group-hover:text-violet-200">Organize an event</p>
                <p class="mt-2 text-sm leading-6 text-zinc-400">Host your own meetup for other players.</p>
            </a>

            <a href="{{ route('profile.edit') }}" class="group rounded-2xl border border-zinc-800 bg-zinc-900 p-6 transition hover:-translate-y-0.5 hover:border-violet-500/50">
                <p class="text-sm font-semibold text-white group-hover:text-violet-200">Your profile</p>
                <p class="mt-2 text-sm leading-6 text-zinc-400">Update your name, email and password.</p>
            </a>
        </div>
    </div>
</x-app-layout>
