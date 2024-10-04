import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    server: {
        hmr: {
            clientPort: 5173,
            host: "172.29.0.6",
            protocol: "ws",
        },
        port: 5173,
        host: true,
        watch: {
            usePolling: true
        }
    },
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
            ],
            refresh: true,
        }),
        vue(),
    ],
});
