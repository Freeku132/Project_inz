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
                page: 'var(--page-background-color)',
                page2: 'var(--page2-background-color)',
                page3: 'var(--page3-background-color)',
                container: 'var(--container-background-color)',

            },
            colors: {
                default: 'var(--text-default-color)',
                default2: 'var(--text-default2-color)',
                'bg-semi-75': 'rgba(0, 0, 0, 0.75)',
            },
            borderColor:{
                default: 'var(--border-default-color)',
                focus: 'var(--border-focus-color)',
                container: 'var(--border-container-color)'
            },
            backgroundImage: {
                default: 'var(--page-background-image)'
            }
        },
    },


    plugins: [require('@tailwindcss/forms')],
};
