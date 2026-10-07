<nav class="border-b border-zinc-800 bg-zinc-950">
    <div class="mx-auto flex max-w-7xl items-center justify-between px-6 py-4">

        <!-- Logo -->
        <a
            href="/"
            class="text-2xl font-bold tracking-tight text-white"
        >
            Game<span class="text-violet-500">plan</span>
        </a>

        <!-- Desktop navigation -->
        <div class="hidden items-center gap-8 md:flex">

            <a
                href="/"
                class="text-sm font-medium text-zinc-300 transition hover:text-white"
            >
                Home
            </a>

            <a
                href="/meetups"
                class="text-sm font-medium text-zinc-300 transition hover:text-white"
            >
                Meetups
            </a>

            <a
                href="/meetups/create"
                class="text-sm font-medium text-zinc-300 transition hover:text-white"
            >
                Organize
            </a>

        </div>

        <!-- Account -->
        <div class="hidden items-center gap-3 md:flex">

            <a
                href="/login"
                class="px-4 py-2 text-sm font-medium text-zinc-300 transition hover:text-white"
            >
                Log in
            </a>

            <a
                href="/register"
                class="rounded-lg bg-violet-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-violet-500"
            >
                Sign up
            </a>

        </div>

        <!-- Mobile button -->
        <button
            id="mobile-menu-button"
            type="button"
            class="rounded-lg p-2 text-zinc-300 hover:bg-zinc-800 hover:text-white md:hidden"
            aria-label="Open navigation"
        >
            <svg
                class="h-6 w-6"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M4 6h16M4 12h16M4 18h16"
                />
            </svg>
        </button>

    </div>

    <!-- Mobile navigation -->
    <div
        id="mobile-menu"
        class="hidden border-t border-zinc-800 px-6 py-4 md:hidden"
    >
        <div class="flex flex-col gap-4">

            <a href="/" class="text-sm font-medium text-zinc-300">
                Home
            </a>

            <a href="/meetups" class="text-sm font-medium text-zinc-300">
                Meetups
            </a>

            <a href="/meetups/create" class="text-sm font-medium text-zinc-300">
                Organize
            </a>

            <div class="mt-2 border-t border-zinc-800 pt-4">

                <a
                    href="/login"
                    class="block py-2 text-sm font-medium text-zinc-300"
                >
                    Log in
                </a>

                <a
                    href="/register"
                    class="mt-2 block rounded-lg bg-violet-600 px-4 py-2 text-center text-sm font-semibold text-white"
                >
                    Sign up
                </a>

            </div>

        </div>
    </div>
</nav>
