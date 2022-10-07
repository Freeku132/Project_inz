const defaultTheme = require('tailwindcss/defaultTheme');

/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Nunito', ...defaultTheme.fontFamily.sans],
            },
            backgroundColor: {
                page: 'var(--page-background-color)'
            },
            colors: {
                default: 'var(--text-default-color)'
            },
            borderColor:{
                default: 'var(--border-default-color)'
            },
            backgroundImage: {
                default: 'var(--page-background-image)'
            }
        },
    },


    plugins: [require('@tailwindcss/forms')],
};
