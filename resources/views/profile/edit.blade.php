<x-app-layout>
    <x-slot name="header">
        <p class="text-xs font-semibold uppercase tracking-[0.22em] text-violet-300">Account</p>
        <h2 class="mt-1 text-2xl font-bold tracking-tight text-white">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <!-- Zelfde container (max-w-7xl px-6) als de header-balk, zodat alles op één lijn uitlijnt -->
    <div class="mx-auto max-w-7xl space-y-6 px-6 py-10">
        <div class="rounded-2xl border border-zinc-800 bg-zinc-900 p-6 sm:p-8">
            <div class="max-w-xl">
                @include('profile.partials.update-profile-information-form')
            </div>
        </div>

        <div class="rounded-2xl border border-zinc-800 bg-zinc-900 p-6 sm:p-8">
            <div class="max-w-xl">
                @include('profile.partials.update-password-form')
            </div>
        </div>

        <div class="rounded-2xl border border-red-900/50 bg-zinc-900 p-6 sm:p-8">
            <div class="max-w-xl">
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>
</x-app-layout>
