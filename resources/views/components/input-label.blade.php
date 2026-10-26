@props(['value'])

<label {{ $attributes->merge(['class' => 'mb-1.5 block text-sm font-medium text-zinc-300']) }}>
    {{ $value ?? $slot }}
</label>
