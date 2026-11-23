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
    // very light radius everywhere
    borderRadius: { none: '0', sm: '2px', DEFAULT: '4px', md: '4px', lg: '4px', xl: '6px', '2xl': '6px', '3xl': '8px', full: '6px' },
    extend: {
      colors: {
        ink: '#111111',                 // text: black
        steel: '#6B7280',               // text: gray
        silver: '#E1E3E8',              // borders
        mist: '#F5F5F6',                // light gray surfaces
        gold: { DEFAULT: '#D4AF37', light: '#F5D5A2', dark: '#A8841F' }, // accents only (buttons, lines, icons)
      },
      fontFamily: { sans: ['Cairo', 'Inter', 'ui-sans-serif', 'system-ui', 'sans-serif'] },
      boxShadow: { card: '0 2px 12px rgba(17,17,17,.07)' },
    },
  },
  plugins: [],
};
