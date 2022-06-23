/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./index.html",
    "./src/**/*.{vue,js,ts,jsx,tsx}"
  ],
  theme: {
    container: {
      center: true,
      padding: {
        DEFAULT: '4rem',
        sm: '1rem',
        lg: '6rem',
        xl: '8rem',
        '2xl': '10rem'
      }
    },
    extend: {
    },
  },
  plugins: [],
}
