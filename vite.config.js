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
                'resources/js/participantes.js',
                'resources/css/inscripciones.css',
                'resources/js/inscripciones.js',
                'resources/css/inventario.css',
                'resources/js/inventario.js',
                'resources/css/donaciones.css',
                'resources/js/donaciones.js',
                'resources/css/servicios.css',
                'resources/js/servicios.js',
                'resources/css/ventas.css',
                'resources/js/ventas.js'
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