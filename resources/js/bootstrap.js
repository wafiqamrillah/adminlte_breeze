/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/**
 * We'll load the Livewire ESM module.
 */
import { Alpine, Livewire } from '../../vendor/livewire/livewire/dist/livewire.esm.js';
window.Livewire = Livewire;
window.Alpine = Alpine;

window.Livewire.start();

/**
 * We'll load the jQuery library because we need it for AdminLTE layout.
 * Then, set Alpine listener manipulation to jQuery.
 */
import $ from 'jquery';
window.$ = window.jQuery = $;
document.addEventListener('alpine:init', () => {
    Alpine.setListenerManipulators(
        (target, eventName, handler, options, modifiers) => { jQuery(target).on(eventName, handler); },
        (target, eventName, handler, options) => { jQuery(target).off(eventName, handler); }
    )
});

/**
 * We'll load the AdminLTE layout.
 */
import 'admin-lte';

/**
 * We'll load the animate.css library.
 */
import 'animate.css';

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

// import Echo from 'laravel-echo';

// import Pusher from 'pusher-js';
// window.Pusher = Pusher;

// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: import.meta.env.VITE_PUSHER_APP_KEY,
//     cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER ?? 'mt1',
//     wsHost: import.meta.env.VITE_PUSHER_HOST ? import.meta.env.VITE_PUSHER_HOST : `ws-${import.meta.env.VITE_PUSHER_APP_CLUSTER}.pusher.com`,
//     wsPort: import.meta.env.VITE_PUSHER_PORT ?? 80,
//     wssPort: import.meta.env.VITE_PUSHER_PORT ?? 443,
//     forceTLS: (import.meta.env.VITE_PUSHER_SCHEME ?? 'https') === 'https',
//     enabledTransports: ['ws', 'wss'],
// });