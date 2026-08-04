import {
    defineConfig
} from 'vite';
import laravel from 'laravel-vite-plugin';
import { bunny } from 'laravel-vite-plugin/fonts';
import tailwindcss from "@tailwindcss/vite";

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/js/site.js',
                'resources/js/passkeys.js',
            ],
            refresh: true,
            // `latin-ext` carries the Lithuanian ą ę ė į ų ū č š ž glyphs.
            fonts: [
                bunny('Instrument Sans', {
                    weights: [400, 500, 600],
                    subsets: ['latin', 'latin-ext'],
                }),
                bunny('Alfa Slab One', {
                    weights: [400],
                    subsets: ['latin', 'latin-ext'],
                }),
                bunny('Archivo', {
                    weights: [400, 500, 600, 700],
                    subsets: ['latin', 'latin-ext'],
                }),
                bunny('Barlow Condensed', {
                    weights: [500, 600, 700],
                    subsets: ['latin', 'latin-ext'],
                }),
            ],
        }),
        tailwindcss(),
    ],
    server: {
        cors: true,
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
