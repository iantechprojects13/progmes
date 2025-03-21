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
                '100': '25rem',
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
                '20': '20rem',
                '24': '24rem',
                '32': '32rem',
                '48': '48rem',
                '64': '64rem',
                '72': '72rem',
                '80': '80rem',
                '96': '96rem',
            },
            minWidth: {
                '6': '6rem',
                '8': '8rem',
                '10': '10rem',
                '12': '12rem',
                '16': '16rem',
                '18': '18rem',
                '20': '20rem',
                '24': '24rem',
                '32': '32rem',
                '48': '48rem',
                '64': '64rem',
                '72': '72rem',
                '80': '80rem',
                '96': '96rem',
            },
            minHeight: {
                '6': '6rem',
                '8': '8rem',
                '10': '10rem',
                '12': '12rem',
                '16': '16rem',
                '18': '18rem',
                '20': '20rem',
                '24': '24rem',
                '32': '32rem',
                '48': '48rem',
                '64': '64rem',
                '72': '72rem',
                '80': '80rem',
                '96': '24rem',
            },
            spacing: {
                '58': '14.5rem',
                '60': '15rem',
                '62': '15.5rem',
                '66': '16.5rem',
                '68': '17rem',
                '70': '17.5rem',
            },
            fontFamily: {
                sans: ['sans-serif'], // Change 'Poppins' to your preferred font
              },
        },
    },

    plugins: [forms],
};
