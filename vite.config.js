// import { defineConfig } from 'vite';
// import laravel from 'laravel-vite-plugin';
// import react from '@vitejs/plugin-react';

// export default defineConfig({
//     plugins: [
//         laravel({
//             input: 'resources/js/app.jsx',
//             refresh: true,
//         }),
//         react(),
//     ],
//     // resolve: {
//     //     alias: {
//     //         '@': resolve(__dirname, 'resources/js'),
//     //     },
//     // },
// });
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import react from '@vitejs/plugin-react';
import { resolve } from 'path'; // You need this for alias resolution

export default defineConfig({
  plugins: [
    laravel({
      input: [
        'resources/js/app.jsx', // Main entry file
        'resources/js/Pages/user/workOrder/AllWorkOrder.jsx',
        'resources/js/Pages/user/workOrder/WoView.jsx', // Explicit inclusion
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
});