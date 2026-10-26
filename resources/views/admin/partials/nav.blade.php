{{-- Tabs voor het admin-gedeelte. request()->is() kijkt naar de huidige URL. --}}
<nav class="flex gap-2 border-b border-zinc-800">
    @foreach (['admin/users' => 'Users', 'admin/events' => 'Events'] as $url => $label)
        <a href="/{{ $url }}"
           class="-mb-px border-b-2 px-4 py-3 text-sm font-semibold transition {{ request()->is($url) ? 'border-violet-500 text-white' : 'border-transparent text-zinc-400 hover:text-white' }}">
            {{ $label }}
        </a>
    @endforeach
</nav>
