<nav x-data="{ open: false }" class="shrink-0 border-b border-zinc-800 bg-zinc-950">
    <div class="mx-auto flex max-w-7xl items-center justify-between px-6 py-4">

        <!-- Logo -->
        <a href="/" class="flex items-center gap-2.5 text-2xl font-bold tracking-tight text-white">
            <img src="{{ asset('favicon.svg') }}" alt="" class="size-8">
            <span>Game<span class="text-violet-500">plan</span></span>
        </a>

        <!-- Desktop navigation -->
        <div class="hidden items-center gap-8 md:flex">
            <a href="/" class="text-sm font-medium transition {{ request()->is('/') ? 'text-white' : 'text-zinc-300 hover:text-white' }}">
                Home
            </a>
            <a href="/events" class="text-sm font-medium transition {{ request()->is('events') ? 'text-white' : 'text-zinc-300 hover:text-white' }}">
                Events
            </a>
            <a href="/events/create" class="text-sm font-medium transition {{ request()->is('events/create') ? 'text-white' : 'text-zinc-300 hover:text-white' }}">
                Organize
            </a>

            {{-- Alleen zichtbaar voor admins. role-kolom komt uit de users-migration --}}
            @if (auth()->user()?->role === 'admin')
                <a href="/admin/users" class="text-sm font-medium transition {{ request()->is('admin/*') ? 'text-violet-300' : 'text-violet-400 hover:text-violet-300' }}">
                    Admin
                </a>
            @endif
        </div>

        <!-- Account (desktop) -->
        <div class="hidden items-center gap-3 md:flex">
            @auth
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button type="button" class="inline-flex items-center gap-2 rounded-lg px-3 py-2 text-sm font-medium text-zinc-300 transition hover:bg-zinc-800 hover:text-white">
                            {{ Auth::user()->name }}
                            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('dashboard')">My events</x-dropdown-link>
                        <x-dropdown-link :href="route('profile.edit')">Profile</x-dropdown-link>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                onclick="event.preventDefault(); this.closest('form').submit();">
                                Log out
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            @else
                <a href="{{ route('login') }}" class="px-4 py-2 text-sm font-medium text-zinc-300 transition hover:text-white">
                    Log in
                </a>
                <a href="{{ route('register') }}" class="rounded-lg bg-violet-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-violet-500">
                    Sign up
                </a>
            @endauth
        </div>

        <!-- Mobile button -->
        <button
            type="button"
            @click="open = ! open"
            class="rounded-lg p-2 text-zinc-300 hover:bg-zinc-800 hover:text-white md:hidden"
            aria-label="Open navigation"
        >
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path x-show="! open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                <path x-show="open" style="display: none;" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    <!-- Mobile navigation -->
    <div x-show="open" style="display: none;" class="border-t border-zinc-800 px-6 py-4 md:hidden">
        <div class="flex flex-col gap-4">
            <a href="/" class="text-sm font-medium text-zinc-300">Home</a>
            <a href="/events" class="text-sm font-medium text-zinc-300">Events</a>
            <a href="/events/create" class="text-sm font-medium text-zinc-300">Organize</a>
            @if (auth()->user()?->role === 'admin')
                <a href="/admin/users" class="text-sm font-medium text-violet-400">Admin</a>
            @endif

            <div class="mt-2 border-t border-zinc-800 pt-4">
                @auth
                    <p class="pb-2 text-sm font-semibold text-white">{{ Auth::user()->name }}</p>
                    <a href="{{ route('dashboard') }}" class="block py-2 text-sm font-medium text-zinc-300">My events</a>
                    <a href="{{ route('profile.edit') }}" class="block py-2 text-sm font-medium text-zinc-300">Profile</a>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="block py-2 text-sm font-medium text-zinc-300">Log out</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="block py-2 text-sm font-medium text-zinc-300">Log in</a>
                    <a href="{{ route('register') }}" class="mt-2 block rounded-lg bg-violet-600 px-4 py-2 text-center text-sm font-semibold text-white">Sign up</a>
                @endauth
            </div>
        </div>
    </div>
</nav>
