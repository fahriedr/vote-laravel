import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    build: {
        manifest: true,
        outDir: 'public/build', // <--- hasil build masuk ke public/build
        assetsDir: '',          // <--- biar tidak bikin nested folder aneh
    },
    base: '', // penting untuk shared hosting
});
