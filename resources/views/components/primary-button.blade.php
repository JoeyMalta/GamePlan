<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center rounded-lg bg-violet-600 px-5 py-2.5 text-xs font-bold uppercase tracking-wide text-white shadow-lg shadow-violet-950/30 transition hover:bg-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-400 focus:ring-offset-2 focus:ring-offset-zinc-900 disabled:opacity-50']) }}>
    {{ $slot }}
</button>
