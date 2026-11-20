/** @type {import('tailwindcss').Config} */
export default {
  content: [
    './resources/**/*.blade.php',
    './resources/**/*.js',
    './app/Livewire/**/*.php',
    './vendor/livewire/livewire/src/Features/SupportPagination/views/*.blade.php',
    './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
  ],
  theme: {
    // Design rule: no border radius anywhere.
    borderRadius: { none: '0', sm: '0', DEFAULT: '0', md: '0', lg: '0', xl: '0', '2xl': '0', '3xl': '0', full: '0' },
    extend: {
      colors: {
        ink: '#080808',
        steel: '#777777',
        silver: '#D4D4D4',
        gold: { DEFAULT: '#D4AF37', dark: '#B8962E' },
      },
      fontFamily: { sans: ['Inter', 'Cairo', 'ui-sans-serif', 'system-ui', 'sans-serif'] },
    },
  },
  plugins: [],
};
