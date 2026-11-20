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
    extend: {
      colors: {
        // Palette taken from the MousaTech "M" logo: deep navy + metallic gold
        ink: '#0E1B2E',
        navy: { DEFAULT: '#0E1B2E', 2: '#16263E', 3: '#1F3556' },
        steel: '#7B879E',
        silver: '#D9DEE8',
        mist: '#F4F6FA',
        gold: { DEFAULT: '#D4AF37', light: '#F5D5A2', dark: '#A8841F' },
      },
      fontFamily: { sans: ['Cairo', 'Inter', 'ui-sans-serif', 'system-ui', 'sans-serif'] },
      boxShadow: { card: '0 6px 24px rgba(14,27,46,.08)' },
    },
  },
  plugins: [],
};
