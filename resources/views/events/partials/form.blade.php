{{-- Gedeelde formuliervelden voor create én edit (partial).
     $event is null bij aanmaken, en gevuld bij bewerken.
     old('veld', ...) zet na een validatiefout de ingevulde waarde terug. --}}
@php
    // $categories komt uit de controller (id => naam). $event is een Event-model of null.
    // datetime-local verwacht het formaat 2026-11-06T19:30
    $startsAtValue = old('starts_at', $event?->starts_at?->format('Y-m-d\TH:i'));
@endphp

<div class="space-y-6">
    {{-- Titel --}}
    <div>
        <x-input-label for="title" value="Title" />
        <x-text-input id="title" name="title" type="text" :value="old('title', $event?->title)" required maxlength="255" placeholder="Friday Night Ranked" />
        <x-input-error :messages="$errors->get('title')" class="mt-2" />
    </div>

    {{-- Beschrijving --}}
    <div>
        <x-input-label for="description" value="Description" />
        <textarea id="description" name="description" rows="5" required
                  class="block w-full rounded-lg border border-zinc-700 bg-zinc-950 px-3.5 py-2.5 text-sm text-white focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500/40 placeholder-zinc-500"
                  placeholder="What are you playing, who is it for, what should people bring?">{{ old('description', $event?->description) }}</textarea>
        <x-input-error :messages="$errors->get('description')" class="mt-2" />
    </div>

    <div class="grid gap-6 sm:grid-cols-2">
        {{-- Categorie (category_id → foreign key naar categories) --}}
        <div>
            <x-input-label for="category_id" value="Category" />
            <select id="category_id" name="category_id" required class="block w-full rounded-lg border border-zinc-700 bg-zinc-950 px-3.5 py-2.5 text-sm text-white focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500/40">
                <option value="">Choose a category</option>
                @foreach ($categories as $id => $name)
                    <option value="{{ $id }}" @selected(old('category_id', $event?->category_id) == $id)>{{ $name }}</option>
                @endforeach
            </select>
            <x-input-error :messages="$errors->get('category_id')" class="mt-2" />
        </div>

        {{-- Datum + tijd in één veld → kolom starts_at (datetime) --}}
        <div>
            <x-input-label for="starts_at" value="Date & time" />
            <x-text-input id="starts_at" name="starts_at" type="datetime-local" :value="$startsAtValue" required class="[color-scheme:dark]" />
            <x-input-error :messages="$errors->get('starts_at')" class="mt-2" />
        </div>
    </div>

    <div class="grid gap-6 sm:grid-cols-2">
        {{-- Stad (hierop filteren we) --}}
        <div>
            <x-input-label for="city" value="City" />
            <x-text-input id="city" name="city" type="text" :value="old('city', $event?->city)" required placeholder="Rotterdam" />
            <x-input-error :messages="$errors->get('city')" class="mt-2" />
        </div>

        {{-- Adres --}}
        <div>
            <x-input-label for="address" value="Address" />
            <x-text-input id="address" name="address" type="text" :value="old('address', $event?->address)" required placeholder="Wijnhaven 107" />
            <x-input-error :messages="$errors->get('address')" class="mt-2" />
        </div>
    </div>

    {{-- Prijs: 0 = gratis --}}
    <div class="sm:w-1/2">
        <x-input-label for="price" value="Price (€)" />
        <x-text-input id="price" name="price" type="number" min="0" step="0.01" :value="old('price', $event?->price ?? 0)" required />
        <p class="mt-2 text-xs text-zinc-500">Enter 0 for a free event.</p>
        <x-input-error :messages="$errors->get('price')" class="mt-2" />
    </div>

    {{-- Afbeeldingen → tabel event_images. name="images[]" + multiple = meerdere bestanden als array --}}
    <div>
        <x-input-label for="images" value="Location photos" />
        <input id="images" name="images[]" type="file" accept="image/*" multiple
               class="block w-full cursor-pointer rounded-lg border border-dashed border-zinc-700 bg-zinc-950 p-3 text-sm text-zinc-400 file:mr-4 file:cursor-pointer file:rounded-md file:border-0 file:bg-violet-600 file:px-4 file:py-2 file:text-xs file:font-bold file:uppercase file:tracking-wide file:text-white hover:file:bg-violet-500">
        <p class="mt-2 text-xs text-zinc-500">JPG or PNG, max 2 MB per photo.</p>
        <x-input-error :messages="$errors->get('images')" class="mt-2" />
        {{-- images.* geeft per foto een eigen lijst (images.0, images.1, ...) → flatten() maakt er één lijst teksten van --}}
        <x-input-error :messages="collect($errors->get('images.*'))->flatten()->all()" class="mt-2" />
    </div>
</div>
