<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="theme-color" content="#0E1B2E">
  <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
  <title>{{ isset($title) ? $title.' | ' : '' }}{{ config('app.name') }}</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font@7.4.47/css/materialdesignicons.min.css">
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  @livewireStyles
</head>
<body class="min-h-screen bg-mist pb-16 font-sans text-ink antialiased md:pb-0"
      x-data="{ msg: '', menu: false }"
      x-on:toast.window="msg = $event.detail.message; setTimeout(() => msg = '', 2500)">

  <div class="bg-ink px-4 py-2 text-center text-xs font-semibold text-gold-light">{{ __('Use code MOUSA40 for 40% off selected courses') }}</div>

  <header class="sticky top-0 z-40 border-b border-white/10 bg-ink text-white">
    <div class="mx-auto flex h-[72px] max-w-7xl items-center gap-4 px-4">
      <a href="{{ route('home') }}" class="flex items-center gap-2 text-lg font-bold">
        <img src="{{ asset('images/logo.png') }}" alt="MousaTech" class="h-11 w-11 object-contain">
        <span class="hidden leading-tight sm:block">MousaTech<span class="block text-xs font-medium text-gold">Academy</span></span>
      </a>

      @php($navcats = \App\Models\Category::where('is_active', true)->orderBy('sort_order')->get())
      <div class="relative hidden md:block" x-data="{ o: false }" x-on:click.outside="o = false">
        <button type="button" x-on:click="o = !o" class="flex items-center gap-2 rounded-xl border border-gold/60 px-4 py-2 text-sm font-semibold text-gold-light transition hover:bg-gold hover:text-ink">
          {{ __('Browse courses') }}
          <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M6 9l6 6 6-6"/></svg>
        </button>
        <div x-cloak x-show="o" class="absolute start-0 mt-3 w-64 overflow-hidden rounded-2xl border border-white/10 bg-navy-2 py-2 text-sm shadow-card">
          @foreach($navcats as $nc)<a class="block px-4 py-2.5 hover:bg-white/10" href="{{ route('courses.index', ['cat' => $nc->slug]) }}">{{ $nc->tr('name') }}</a>@endforeach
          <a class="block border-t border-white/10 px-4 py-2.5 font-semibold text-gold hover:bg-white/10" href="{{ route('courses.index') }}">{{ __('All courses') }}</a>
        </div>
      </div>

      <form action="{{ route('courses.index') }}" class="hidden flex-1 lg:block">
        <div class="relative mx-auto max-w-xl">
          <input type="search" name="q" placeholder="{{ __('Search courses or instructors') }}" aria-label="{{ __('Search') }}"
                 class="w-full rounded-xl border border-white/20 bg-white/5 py-2.5 pe-4 ps-11 text-sm text-white placeholder:text-steel focus:border-gold focus:outline-none">
          <svg class="pointer-events-none absolute start-3 top-1/2 h-5 w-5 -translate-y-1/2 text-gold" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="7"/><path d="M20 20l-4-4"/></svg>
        </div>
      </form>

      <nav class="ms-auto hidden items-center gap-5 text-sm font-medium xl:flex">
        @foreach([['home', 'Home'], ['community', 'Community']] as [$r, $label])
          <a href="{{ route($r) }}" class="{{ request()->routeIs($r) ? 'text-gold' : 'text-silver hover:text-white' }}">{{ __($label) }}</a>
        @endforeach
      </nav>

      <div class="ms-auto flex items-center gap-4 text-sm xl:ms-0">
        <a href="{{ route('lang', app()->getLocale() === 'ar' ? 'en' : 'ar') }}" class="font-semibold text-silver hover:text-gold">{{ app()->getLocale() === 'ar' ? 'EN' : 'عربي' }}</a>
        <livewire:cart-badge />
        @auth
          <div class="relative" x-data="{ o: false }" x-on:click.outside="o = false">
            <button type="button" x-on:click="o = !o" class="flex items-center overflow-hidden rounded-full ring-2 ring-gold/70" aria-label="{{ __('Account') }}"><x-avatar :user="auth()->user()" size="h-9 w-9" /></button>
            <div x-cloak x-show="o" class="absolute end-0 mt-3 w-52 overflow-hidden rounded-2xl border border-white/10 bg-navy-2 py-1 text-sm shadow-card">
              <a class="block px-4 py-2 hover:bg-white/10" href="{{ route('profile', auth()->user()) }}">{{ __('My profile') }}</a>
              <a class="block px-4 py-2 hover:bg-white/10" href="{{ route('my-courses') }}">{{ __('My courses') }}</a>
              <a class="block px-4 py-2 hover:bg-white/10" href="{{ route('orders.index') }}">{{ __('My orders') }}</a>
              @can('dashboard.view')<a class="block px-4 py-2 text-gold hover:bg-white/10" href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a>@endcan
              <form method="POST" action="{{ route('logout') }}">@csrf<button class="block w-full px-4 py-2 text-start text-silver hover:bg-white/10">{{ __('Log out') }}</button></form>
            </div>
          </div>
        @else
          <a href="{{ route('login') }}" class="btn btn-dark hidden !py-2 md:inline-flex">{{ __('Log in') }}</a>
          <a href="{{ route('register') }}" class="btn btn-gold !py-2">{{ __('Sign up') }}</a>
        @endauth
      </div>
    </div>
  </header>

  <main>{{ $slot }}</main>

  <footer class="mt-20 bg-ink text-silver">
    <div class="mx-auto max-w-7xl px-4 py-12">
      <div class="flex flex-col items-center gap-6 text-center text-sm">
        <img src="{{ asset('images/logo.png') }}" alt="MousaTech" class="h-16 w-16 object-contain">
        <p class="max-w-md text-steel">{{ __('Programming and technology courses, built around real projects.') }}</p>
        <div class="flex flex-wrap justify-center gap-x-8 gap-y-2 underline-offset-4">
          <a class="hover:text-gold hover:underline" href="{{ route('courses.index') }}">{{ __('Courses') }}</a>
          <a class="hover:text-gold hover:underline" href="{{ route('community') }}">{{ __('Community') }}</a>
          <a class="hover:text-gold hover:underline" href="https://wa.me/{{ config('academy.whatsapp') }}" target="_blank" rel="noopener">{{ __('Contact us') }}</a>
          <a class="hover:text-gold hover:underline" href="mailto:{{ config('academy.email') }}">{{ config('academy.email') }}</a>
        </div>
      </div>
    </div>
    <div class="border-t border-white/10 py-4 text-center text-xs text-steel">© {{ date('Y') }} MousaTech Academy</div>
  </footer>

  {{-- App-style bottom navigation on phones --}}
  <nav class="fixed inset-x-0 bottom-0 z-40 grid grid-cols-4 border-t border-white/10 bg-ink text-[11px] text-silver md:hidden">
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

  <div x-cloak x-show="msg" x-text="msg" class="fixed bottom-20 start-4 z-50 rounded-xl bg-gold px-4 py-3 text-sm font-semibold text-ink shadow-lg md:bottom-4"></div>
  @livewireScripts
</body>
</html>
