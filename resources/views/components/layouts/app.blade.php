<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ isset($title) ? $title.' | ' : '' }}{{ config('app.name') }}</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  @livewireStyles
</head>
<body class="min-h-screen bg-white font-sans text-ink antialiased"
      x-data="{ msg: '', menu: false }"
      x-on:toast.window="msg = $event.detail.message; setTimeout(() => msg = '', 2500)">

  <div class="bg-ink px-4 py-2 text-center text-xs text-gold">{{ __('Use code MOUSA40 for 40% off selected courses') }}</div>

  <header class="sticky top-0 z-40 border-b border-silver bg-white">
    <div class="mx-auto flex h-16 max-w-7xl items-center justify-between gap-4 px-4">
      <a href="{{ route('home') }}" class="flex items-center gap-2 text-lg font-bold">
        <span class="inline-block h-4 w-4 bg-gold"></span>MousaTech Academy
      </a>

      <nav class="hidden items-center gap-6 text-sm font-medium md:flex">
        <a href="{{ route('home') }}" class="hover:text-gold-dark">{{ __('Home') }}</a>
        <a href="{{ route('courses.index') }}" class="hover:text-gold-dark">{{ __('Courses') }}</a>
        <a href="https://wa.me/{{ config('academy.whatsapp') }}" target="_blank" rel="noopener" class="hover:text-gold-dark">{{ __('Contact us') }}</a>
      </nav>

      <div class="flex items-center gap-3 text-sm">
        <a href="{{ route('lang', app()->getLocale() === 'ar' ? 'en' : 'ar') }}" class="font-semibold">{{ app()->getLocale() === 'ar' ? 'EN' : 'عربي' }}</a>
        <livewire:cart-badge />
        @auth
          @can('dashboard.view')
            <a href="{{ route('admin.dashboard') }}" class="hidden font-semibold text-gold-dark md:inline">{{ __('Dashboard') }}</a>
          @endcan
          <a href="{{ route('my-courses') }}" class="hidden md:inline">{{ __('My courses') }}</a>
          <form method="POST" action="{{ route('logout') }}" class="hidden md:block">@csrf<button class="text-steel hover:text-ink">{{ __('Log out') }}</button></form>
        @else
          <a href="{{ route('login') }}" class="hidden md:inline">{{ __('Log in') }}</a>
          <a href="{{ route('register') }}" class="btn btn-gold hidden md:inline-flex">{{ __('Sign up') }}</a>
        @endauth
        <button type="button" class="md:hidden" x-on:click="menu = !menu" aria-label="{{ __('Menu') }}">
          <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 6h16M4 12h16M4 18h16"/></svg>
        </button>
      </div>
    </div>
    <nav x-cloak x-show="menu" class="flex flex-col gap-3 border-t border-silver px-4 py-4 text-sm md:hidden">
      <a href="{{ route('home') }}">{{ __('Home') }}</a>
      <a href="{{ route('courses.index') }}">{{ __('Courses') }}</a>
      @auth
        @can('dashboard.view')<a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a>@endcan
        <a href="{{ route('my-courses') }}">{{ __('My courses') }}</a>
        <a href="{{ route('orders.index') }}">{{ __('My orders') }}</a>
        <form method="POST" action="{{ route('logout') }}">@csrf<button>{{ __('Log out') }}</button></form>
      @else
        <a href="{{ route('login') }}">{{ __('Log in') }}</a>
        <a href="{{ route('register') }}">{{ __('Sign up') }}</a>
      @endauth
    </nav>
  </header>

  <main>{{ $slot }}</main>

  <footer class="mt-20 bg-ink text-silver">
    <div class="mx-auto flex max-w-7xl flex-col gap-6 px-4 py-10 text-sm md:flex-row md:justify-between">
      <div>
        <div class="font-bold text-white">MousaTech Academy</div>
        <p class="mt-2 max-w-sm text-steel">{{ __('Programming and technology courses, built around real projects.') }}</p>
      </div>
      <div class="space-y-1">
        <a class="block hover:text-gold" href="https://wa.me/{{ config('academy.whatsapp') }}" target="_blank" rel="noopener">WhatsApp: +{{ config('academy.whatsapp') }}</a>
        <a class="block hover:text-gold" href="mailto:{{ config('academy.email') }}">{{ config('academy.email') }}</a>
      </div>
    </div>
  </footer>

  <div x-cloak x-show="msg" x-text="msg" class="fixed bottom-4 start-4 z-50 bg-ink px-4 py-3 text-sm text-white shadow-lg"></div>
  @livewireScripts
</body>
</html>
