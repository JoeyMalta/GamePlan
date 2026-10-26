<x-guest-layout>
    <div class="mb-8 text-center">
        <h1 class="text-2xl font-bold tracking-tight text-white">Welcome back</h1>
        <p class="mt-2 text-sm text-zinc-400">Log in to find your next game night.</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-6" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="you@example.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <div class="mb-1.5 flex items-center justify-between">
                <x-input-label for="password" :value="__('Password')" class="mb-0" />
                @if (Route::has('password.request'))
                    <a class="text-xs font-medium text-violet-400 transition hover:text-violet-300" href="{{ route('password.request') }}">
                        {{ __('Forgot password?') }}
                    </a>
                @endif
            </div>
            <x-text-input id="password" type="password" name="password" required autocomplete="current-password" placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <label for="remember_me" class="flex items-center gap-2.5">
            <input id="remember_me" type="checkbox" name="remember" class="size-4 rounded border-zinc-600 bg-zinc-950 text-violet-600 focus:ring-violet-500 focus:ring-offset-zinc-900">
            <span class="text-sm text-zinc-400">{{ __('Remember me') }}</span>
        </label>

        <x-primary-button class="w-full">
            {{ __('Log in') }}
        </x-primary-button>
    </form>

    <p class="mt-8 text-center text-sm text-zinc-400">
        New to Gameplan?
        <a href="{{ route('register') }}" class="font-semibold text-violet-400 transition hover:text-violet-300">Create an account</a>
    </p>
</x-guest-layout>
