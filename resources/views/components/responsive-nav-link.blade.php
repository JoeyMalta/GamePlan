@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full border-l-4 border-violet-500 bg-violet-500/10 py-2 ps-3 pe-4 text-start text-base font-medium text-white focus:outline-none transition'
            : 'block w-full border-l-4 border-transparent py-2 ps-3 pe-4 text-start text-base font-medium text-zinc-400 hover:border-zinc-600 hover:bg-zinc-800 hover:text-white focus:outline-none transition';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
