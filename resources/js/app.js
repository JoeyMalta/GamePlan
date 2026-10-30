import Alpine from 'alpinejs';

// Laat Vite de statische afbeeldingen in resources/images meebouwen,
// zodat Vite::asset('resources/images/...') ze in public/build/manifest.json vindt.
// eager + ?url: Vite importeert ze direct als URL. We bewaren het resultaat op window,
// anders ziet Vite de regel als "ongebruikt" en gooit hij hem weg bij npm run build.
window.gameplanImages = import.meta.glob('../images/**', { eager: true, query: '?url', import: 'default' });

window.Alpine = Alpine;

Alpine.start();
