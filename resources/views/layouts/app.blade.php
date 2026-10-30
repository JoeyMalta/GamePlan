<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Gameplan') }}</title>
        <link rel="icon" href="{{ Vite::asset('resources/images/logo.svg') }}" type="image/svg+xml">

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="flex min-h-svh flex-col bg-zinc-950 font-sans text-zinc-100 antialiased">
        @include('partials.navbar')

        @isset($header)
            <header class="border-b border-zinc-800 bg-zinc-900/60">
                <div class="mx-auto max-w-7xl px-6 py-6">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <x-flash />

        <main class="flex-1">
            {{ $slot }}
        </main>
    </body>
</html>
