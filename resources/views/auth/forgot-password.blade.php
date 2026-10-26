<x-guest-layout>
    <div class="mb-8 text-center">
        <h1 class="text-2xl font-bold tracking-tight text-white">Forgot your password?</h1>
        <p class="mt-2 text-sm leading-6 text-zinc-400">
            {{ __('No problem. Enter your email address and we will send you a link to choose a new one.') }}
        </p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-6" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" type="email" name="email" :value="old('email')" required autofocus placeholder="you@example.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <x-primary-button class="w-full">
            {{ __('Email Password Reset Link') }}
        </x-primary-button>
    </form>

    <p class="mt-8 text-center text-sm text-zinc-400">
        <a href="{{ route('login') }}" class="font-semibold text-violet-400 transition hover:text-violet-300">Back to log in</a>
    </p>
</x-guest-layout>
