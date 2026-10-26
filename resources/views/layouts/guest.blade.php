<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Gameplan') }}</title>
        <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="flex min-h-svh flex-col bg-zinc-950 font-sans text-zinc-100 antialiased">
        @include('partials.navbar')

        <main class="relative isolate flex flex-1 items-center justify-center px-4 py-16 sm:px-6">
            <!-- subtle glow -->
            <div class="pointer-events-none absolute inset-0 -z-10 bg-[radial-gradient(ellipse_at_top,rgba(124,58,237,0.18),transparent_60%)]"></div>

            <div class="w-full max-w-md rounded-2xl border border-zinc-800 bg-zinc-900/80 p-8 shadow-2xl shadow-black/40 backdrop-blur sm:p-10">
                {{ $slot }}
            </div>
        </main>
    </body>
</html>
