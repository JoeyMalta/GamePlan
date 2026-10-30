import Alpine from 'alpinejs';

// Laat Vite ook de statische afbeeldingen in resources/images verwerken,
// zodat Vite::asset('resources/images/...') werkt na `npm run build`.
import.meta.glob(['../images/**']);

window.Alpine = Alpine;

Alpine.start();
