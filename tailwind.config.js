const defaultTheme = require('tailwindcss/defaultTheme');
const colors = require('tailwindcss/colors');

module.exports = {
    purge: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],
    darkMode: false, // or 'media' or 'class'
    theme: {
        extend: {
            textColor: {
                default: 'var(--text-default-color)'
            },
            backgroundColor: {
                page: 'var(--page-background-color)',
                card: 'var(--card-background-color)',
                button: 'var(--button-background-color)',
                header: 'var(--header-background-color)',
            },
            colors: {
                'gray-background': '#F5F6F9',
                'blue': '#47CDFF',
                'light-blue': '#8AE2FE',
            }
        },
    },
    variants: {
        extend: {},
    },
    plugins: [],
}
