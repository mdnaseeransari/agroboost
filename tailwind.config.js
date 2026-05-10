import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
                poppins: ['Poppins', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                agro: {
                    green: '#2D5016',
                    gold: '#D4A574',
                },
                status: {
                    success: '#10B981',
                    warning: '#F59E0B',
                    danger: '#EF4444',
                    info: '#3B82F6',
                }
            },
        },
    },

    plugins: [forms],
};
