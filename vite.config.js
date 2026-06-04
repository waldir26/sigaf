import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/login.css',
                'resources/css/dashboard.css',
                'resources/css/dark-mode.css',
                'resources/js/dashboard.js',
                'resources/js/app.js',
                'resources/css/programas.css',
                'resources/js/programas.js',
                'resources/css/escuelas.css',
                'resources/js/escuelas.js',
                'resources/css/participantes.css',
                'resources/js/participantes.js'
            ],
            refresh: true,
        }),
        tailwindcss(),
    ],
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});