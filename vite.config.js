import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import react from '@vitejs/plugin-react';
import { resolve } from 'path';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/js/app.jsx',
                'resources/js/Pages/user/workOrder/AllWorkOrder.jsx',
                'resources/js/Pages/user/workOrder/WoView.jsx',
            ],
            refresh: true,
        }),
        react(),
    ],
    resolve: {
        alias: {
            '@': resolve(__dirname, 'resources/js'),
        },
    },
    server: {
        host: '0.0.0.0', // For network access
        port: 5173,      // Default Vite dev server port
    },
});
