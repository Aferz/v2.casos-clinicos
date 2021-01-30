const defaultTheme = require('tailwindcss/defaultTheme');
const colors = require('tailwindcss/colors')

module.exports = {
  purge: [
    './storage/framework/views/*.php',
    './resources/views/**/*.blade.php'
  ],

  theme: {
    extend: {
      colors: {
        primary: colors.indigo,
        error: colors.red,
        success: colors.green,
        warning: colors.yellow,
      },

      fontFamily: {
        sans: ['Inter var', ...defaultTheme.fontFamily.sans],
      },

      gridTemplateColumns: {
        'clinical-case': '1fr 24rem'
      }
    },
  },

  variants: {
    extend: {
      textColor: ['group-focus'],
      opacity: ['disabled'],
      cursor: ['disabled']
    },
  },

  plugins: [
    require('@tailwindcss/forms'),
    require('@tailwindcss/line-clamp')
  ],
};
