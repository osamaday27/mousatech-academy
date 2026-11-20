<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="theme-color" content="#080808">
  <title>{{ isset($title) ? $title.' | ' : '' }}{{ config('app.name') }}</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  @livewireStyles
</head>
<body class="min-h-screen bg-white pb-16 font-sans text-ink antialiased md:pb-0"
      x-data="{ msg: '', menu: false }"
      x-on:toast.window="msg = $event.detail.message; setTimeout(() => msg = '', 2500)">

  <div class="bg-gold px-4 py-1.5 text-center text-xs font-semibold text-ink">{{ __('Use code MOUSA40 for 40% off selected courses') }}</div>

  <header class="sticky top-0 z-40 border-b border-steel/40 bg-ink text-white">
    <div class="mx-auto flex h-16 max-w-7xl items-center gap-4 px-4">
      <a href="{{ route('home') }}" class="flex items-center gap-2 text-lg font-bold">
        <span class="inline-block h-4 w-4 bg-gold"></span>MousaTech <span class="hidden text-gold sm:inline">Academy</span>
      </a>

      <nav class="ms-6 hidden items-center gap-6 text-sm font-medium md:flex">
        @foreach([['home', 'Home'], ['courses.index', 'Courses'], ['community', 'Community']] as [$r, $label])
          <a href="{{ route($r) }}" class="{{ request()->routeIs($r) ? 'text-gold' : 'text-silver hover:text-white' }}">{{ __($label) }}</a>
        @endforeach
        <a href="https://wa.me/{{ config('academy.whatsapp') }}" target="_blank" rel="noopener" class="text-silver hover:text-white">{{ __('Contact us') }}</a>
      </nav>

      <form action="{{ route('courses.index') }}" class="ms-auto hidden lg:block">
        <input type="search" name="q" placeholder="{{ __('Search courses or instructors') }}" aria-label="{{ __('Search') }}"
               class="w-64 border border-steel bg-transparent px-3 py-2 text-sm text-white placeholder:text-steel focus:border-gold focus:outline-none">
      </form>

      <div class="ms-auto flex items-center gap-4 text-sm lg:ms-0">
        <a href="{{ route('lang', app()->getLocale() === 'ar' ? 'en' : 'ar') }}" class="font-semibold text-silver hover:text-gold">{{ app()->getLocale() === 'ar' ? 'EN' : 'عربي' }}</a>
        <livewire:cart-badge />
        @auth
          <div class="relative" x-data="{ o: false }" x-on:click.outside="o = false">
            <button type="button" x-on:click="o = !o" class="flex items-center" aria-label="{{ __('Account') }}"><x-avatar :user="auth()->user()" size="h-9 w-9" /></button>
            <div x-cloak x-show="o" class="absolute end-0 mt-3 w-52 border border-steel bg-ink py-1 text-sm shadow-lg">
              <a class="block px-4 py-2 hover:bg-steel/30" href="{{ route('profile', auth()->user()) }}">{{ __('My profile') }}</a>
              <a class="block px-4 py-2 hover:bg-steel/30" href="{{ route('my-courses') }}">{{ __('My courses') }}</a>
              <a class="block px-4 py-2 hover:bg-steel/30" href="{{ route('orders.index') }}">{{ __('My orders') }}</a>
              @can('dashboard.view')<a class="block px-4 py-2 text-gold hover:bg-steel/30" href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a>@endcan
              <form method="POST" action="{{ route('logout') }}">@csrf<button class="block w-full px-4 py-2 text-start text-silver hover:bg-steel/30">{{ __('Log out') }}</button></form>
            </div>
          </div>
        @else
          <a href="{{ route('login') }}" class="hidden text-silver hover:text-white md:inline">{{ __('Log in') }}</a>
          <a href="{{ route('register') }}" class="btn btn-gold !py-2">{{ __('Sign up') }}</a>
        @endauth
      </div>
    </div>
  </header>

  <main>{{ $slot }}</main>

  <footer class="mt-20 bg-ink text-silver">
    <div class="mx-auto grid max-w-7xl gap-10 px-4 py-12 text-sm md:grid-cols-4">
      <div class="md:col-span-2">
        <div class="flex items-center gap-2 font-bold text-white"><span class="inline-block h-3 w-3 bg-gold"></span>MousaTech Academy</div>
        <p class="mt-3 max-w-sm text-steel">{{ __('Programming and technology courses, built around real projects.') }}</p>
      </div>
      <div class="space-y-2">
        <div class="font-semibold text-white">{{ __('Explore') }}</div>
        <a class="block hover:text-gold" href="{{ route('courses.index') }}">{{ __('Courses') }}</a>
        <a class="block hover:text-gold" href="{{ route('community') }}">{{ __('Community') }}</a>
        <a class="block hover:text-gold" href="{{ route('cart') }}">{{ __('Cart') }}</a>
      </div>
      <div class="space-y-2">
        <div class="font-semibold text-white">{{ __('Contact us') }}</div>
        <a class="block hover:text-gold" href="https://wa.me/{{ config('academy.whatsapp') }}" target="_blank" rel="noopener" dir="ltr">WhatsApp: +{{ config('academy.whatsapp') }}</a>
        <a class="block hover:text-gold" href="mailto:{{ config('academy.email') }}">{{ config('academy.email') }}</a>
      </div>
    </div>
    <div class="border-t border-steel/40 py-4 text-center text-xs text-steel">© {{ date('Y') }} MousaTech Academy</div>
  </footer>

  {{-- App-style bottom navigation on phones --}}
  <nav class="fixed inset-x-0 bottom-0 z-40 grid grid-cols-4 border-t border-steel/40 bg-ink text-[11px] text-silver md:hidden">
    @php($tabs = [['home', 'Home', 'M3 11l9-8 9 8v9a1 1 0 0 1-1 1h-5v-6H9v6H4a1 1 0 0 1-1-1z'], ['courses.index', 'Courses', 'M4 5h16v14H4zM9 5v14'], ['community', 'Community', 'M4 5h16v11H9l-5 4z']])
    @foreach($tabs as [$r, $label, $d])
      <a href="{{ route($r) }}" class="flex flex-col items-center gap-1 py-2 {{ request()->routeIs($r) ? 'text-gold' : '' }}">
        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path d="{{ $d }}"/></svg>{{ __($label) }}
      </a>
    @endforeach
    <a href="{{ auth()->check() ? route('profile', auth()->user()) : route('login') }}" class="flex flex-col items-center gap-1 py-2 {{ request()->routeIs('profile', 'login') ? 'text-gold' : '' }}">
      <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><circle cx="12" cy="8" r="4"/><path d="M4 21c0-4 4-6 8-6s8 2 8 6"/></svg>{{ auth()->check() ? __('Profile') : __('Log in') }}
    </a>
  </nav>

  <div x-cloak x-show="msg" x-text="msg" class="fixed bottom-20 start-4 z-50 bg-gold px-4 py-3 text-sm font-semibold text-ink shadow-lg md:bottom-4"></div>
  @livewireScripts
</body>
</html>
