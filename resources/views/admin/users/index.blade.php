<x-app-layout>
    {{-- Data komt uit Admin\UserController@index: $users (met events_count via withCount) --}}

    <x-slot name="header">
        <p class="text-xs font-semibold uppercase tracking-[0.22em] text-violet-300">Admin</p>
        <h2 class="mt-1 text-2xl font-bold tracking-tight text-white">Manage users</h2>
    </x-slot>

    <div class="mx-auto max-w-7xl px-6 py-10">
        @include('admin.partials.nav')

        <div class="mt-6 overflow-x-auto rounded-2xl border border-zinc-800 bg-zinc-900">
            <table class="w-full min-w-[640px] text-left text-sm">
                <thead class="border-b border-zinc-800 text-xs uppercase tracking-wide text-zinc-500">
                    <tr>
                        <th class="px-6 py-4 font-semibold">User</th>
                        <th class="px-6 py-4 font-semibold">Role</th>
                        <th class="px-6 py-4 font-semibold">Events</th>
                        <th class="px-6 py-4 font-semibold">Joined</th>
                        <th class="px-6 py-4 text-right font-semibold">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-800">
                    @foreach ($users as $user)
                        <tr class="{{ $user->is_active ? '' : 'opacity-60' }}">
                            <td class="px-6 py-4">
                                <p class="font-semibold text-white">{{ $user->name }}</p>
                                <p class="text-zinc-400">{{ $user->email }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <span class="rounded-full px-2.5 py-1 text-xs font-semibold {{ $user->isAdmin() ? 'bg-violet-600/15 text-violet-300' : 'bg-zinc-800 text-zinc-300' }}">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-zinc-300">{{ $user->events_count }}</td>
                            <td class="px-6 py-4 text-zinc-400">{{ $user->created_at->format('j M Y') }}</td>
                            <td class="px-6 py-4 text-right">
                                {{-- STATUS-TOGGLE (verplichte eis):
                                     - knop in een lijst            ✔
                                     - MOET via POST                ✔ method="POST" + @csrf
                                     - MOET naar aparte controller-actie → Admin\UserController@toggleActive --}}
                                @if ($user->id !== auth()->id()) {{-- je kunt jezelf niet deactiveren --}}
                                    <form method="POST" action="{{ route('admin.users.toggle-active', $user) }}" class="inline">
                                        @csrf
                                        <button type="submit"
                                                class="inline-flex min-w-28 items-center justify-center gap-2 rounded-lg border px-4 py-2 text-xs font-bold uppercase tracking-wide transition {{ $user->is_active ? 'border-green-800/60 bg-green-950/40 text-green-300 hover:bg-green-900/40' : 'border-zinc-700 bg-zinc-950 text-zinc-400 hover:text-white' }}">
                                            <span class="size-2 rounded-full {{ $user->is_active ? 'bg-green-400' : 'bg-zinc-600' }}"></span>
                                            {{ $user->is_active ? 'Active' : 'Inactive' }}
                                        </button>
                                    </form>
                                @else
                                    <span class="text-xs text-zinc-500">You</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
