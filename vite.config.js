import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    server: {
        // Ensure Vite serves on IPv4 localhost so the browser loads modules from 127.0.0.1
        host: '127.0.0.1',
        // If you need to expose Vite to other devices on the network, consider setting this to true
        // host: true,
        hmr: {
            host: '127.0.0.1',
        },
    },
});
