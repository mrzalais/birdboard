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
