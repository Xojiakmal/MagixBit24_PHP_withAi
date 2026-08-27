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
            },
            colors: {
                dark: {
                    bg: 'rgba(0, 0, 0, 0.4)',
                    surface: 'rgba(255, 255, 255, 0.05)',
                    border: 'rgba(255, 255, 255, 0.1)',
                    text: '#E2E2E8',
                    muted: '#8A8A93'
                },
                accent: {
                    DEFAULT: '#9B72FF', // The purple glow
                    hover: '#B596FF'
                }
            }
        },
    },

    plugins: [forms],
};
