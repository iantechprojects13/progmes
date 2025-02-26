import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],

    theme: { 
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            height: {
                'custom-full': '425px',
                '96': '24rem',
            },
            zIndex: {
                '60': 60,
                '70': 70,
                '80': 80,
                '90': 90,
                '100': 100,
            },
            maxWidth: {
                '6': '6rem',
                '8': '8rem',
                '10': '10rem',
                '12': '12rem',
                '16': '16rem',
                '18': '18rem',
            },
            minWidth: {
                '6': '6rem',
                '8': '8rem',
                '10': '10rem',
                '12': '12rem',
                '16': '16rem',
                '18': '18rem',
            },
            minHeight: {
                '6': '6rem',
                '8': '8rem',
                '10': '10rem',
                '12': '12rem',
                '16': '16rem',
                '18': '18rem',
                '96': '24rem',
            }
        },
    },

    plugins: [forms],
};
