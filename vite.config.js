import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/filament.css',
                'resources/sass/app.scss',
                'resources/js/app.js',
                'resources/sass/main-site.scss',
                'resources/js/main-site.js',
                'resources/sass/guest.scss',
            ],
            refresh: true,
        }),
    ],
});
