import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import fs from 'fs';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/js/app.js',
                'resources/css/app.css'
            ],
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
    ],
    server: {
        host: '0.0.0.0',
        port: 5174,
        https: process.env.VITE_DEV_SERVER_KEY && process.env.VITE_DEV_SERVER_CERT ? {
            key: fs.readFileSync(process.env.VITE_DEV_SERVER_KEY),
            cert: fs.readFileSync(process.env.VITE_DEV_SERVER_CERT)
        } : false,
        hmr: {
            host: 'localhost',
            port: 5174
        },
        cors: {
            origin: [
                //'https://f8284b5c3f72.ngrok-free.app',
                'http://127.0.0.1:8000',
                'http://localhost:8000',
                /^https:\/\/.*\.ngrok-free\.app$/,
                /^https:\/\/.*\.ngrok\.io$/
            ],
            credentials: true
        }
    },
});
