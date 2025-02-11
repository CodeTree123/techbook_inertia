import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import react from '@vitejs/plugin-react';
import { resolve } from 'path';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/js/app.jsx',
                'resources/js/Pages/user/Home/Home.jsx',
                'resources/js/Pages/user/allWorkOrder/AllWorkOrder.jsx',
                'resources/js/Pages/user/workOrder/WoView.jsx',
                'resources/js/Pages/user/createWorkOrder/CreateWorkOrder.jsx',
                'resources/js/Pages/user/auth/Login.jsx',
                'resources/js/Pages/user/changePassword/ChangePassword.jsx',
                'resources/js/Pages/user/forgotPassword/ForgotPassword.jsx',
                'resources/js/Pages/user/profile/EditProfile.jsx',
                'resources/js/Pages/user/resetPassword/ResetPassword.jsx',
                'resources/js/Pages/user/verifyEmail/VerifyEmail.jsx',
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
