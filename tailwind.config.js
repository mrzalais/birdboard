const colors = require('tailwindcss/colors')

module.exports = {
  purge: [],
  darkMode: false, // or 'media' or 'class'
  theme: {
    extend: {
        colors: {
            'light-blue': '#22D3EE',
        }
    },
  },
  variants: {
    extend: {},
  },
  plugins: [],
}
