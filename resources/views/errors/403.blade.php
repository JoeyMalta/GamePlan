{{-- Laravel toont deze pagina automatisch bij een 403, bijv. na abort(403) in een controller --}}
@extends('layouts.main')

@section('title', '403 · Gameplan')

@section('content')
    <section class="flex flex-1 items-center justify-center bg-zinc-950 px-6 py-24 text-center">
        <div class="max-w-md">
            <p class="text-sm font-semibold uppercase tracking-[0.24em] text-violet-300">Error 403</p>
            <h1 class="mt-3 text-4xl font-bold tracking-tight text-white">No access</h1>
            <p class="mt-4 text-sm leading-6 text-zinc-400">{{-- Bericht uit abort(403, '...') tonen; zonder bericht een algemene tekst --}}
                {{ $exception->getMessage() ?: "You don't have permission to view or change this page." }}</p>
            <div class="mt-8 flex justify-center gap-3">
                <a href="{{ url('/') }}" class="rounded-lg bg-violet-600 px-5 py-2.5 text-xs font-bold uppercase tracking-wide text-white transition hover:bg-violet-500">Home</a>
                <a href="/events" class="rounded-lg border border-zinc-700 px-5 py-2.5 text-xs font-bold uppercase tracking-wide text-zinc-300 transition hover:border-zinc-500 hover:text-white">All events</a>
            </div>
        </div>
    </section>
@endsection
