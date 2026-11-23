<div>
  {{-- ===== Hero (rounded banner, like the reference) ===== --}}
  <section class="mx-auto max-w-7xl px-4 pt-6">
    <div class="hero-bg grid-bg relative overflow-hidden rounded-3xl px-6 pb-10 pt-12 text-center text-ink md:px-12">
      <svg class="pointer-events-none absolute -start-4 top-6 hidden h-32 w-48 text-gold/60 md:block" viewBox="0 0 200 120" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><path d="M5 60C40 20 90 20 150 45l35-8-12 28c-30 25-80 35-120 5z"/><circle cx="120" cy="52" r="10"/><path d="M30 95c10-10 25-15 40-10"/></svg>
      <svg class="pointer-events-none absolute -end-4 top-6 hidden h-32 w-44 text-gold/60 md:block" viewBox="0 0 200 120" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><path d="M60 15l75-8 20 70-75 10z"/><path d="M70 25l55-6 12 48-55 7z"/><path d="M10 100c50-5 120-20 190-45"/></svg>

      <span class="inline-block rounded-full bg-white px-4 py-1.5 text-xs font-bold text-ink">{{ __('More than :n courses', ['n' => max($stats['courses'], 1)]) }}</span>
      <h1 class="mx-auto mt-5 max-w-3xl text-4xl font-bold leading-tight md:text-5xl">{{ __('Start your journey to build your') }} <span class="text-ink">{{ __('tech skills') }}</span></h1>
      <p class="mx-auto mt-4 max-w-2xl text-steel">{{ __('Project-based courses in web, backend, security, cloud and AI, taught in Arabic.') }}</p>

      <form action="{{ route('courses.index') }}" class="mx-auto mt-7 flex max-w-xl overflow-hidden rounded-xl bg-white p-1.5">
        <input type="search" name="q" placeholder="{{ __('Search courses or instructors') }}" aria-label="{{ __('Search') }}" class="min-w-0 flex-1 bg-transparent px-4 text-ink placeholder:text-steel focus:outline-none">
        <button class="btn btn-gold !px-6">{{ __('Search') }}</button>
      </form>

      <div class="mx-auto mt-10 grid max-w-5xl gap-5 text-start md:grid-cols-3">
        @foreach($categories->take(3) as $cat)
          <a wire:key="hero-{{ $cat->id }}" href="{{ route('courses.index', ['cat' => $cat->slug]) }}" class="group relative flex h-44 flex-col justify-between overflow-hidden rounded-2xl border border-silver bg-white shadow-card p-5 transition hover:border-gold">
            <i class="mdi {{ $cat->icon ?: 'mdi-code-tags' }} text-4xl text-gold" aria-hidden="true"></i>
            <span class="font-bold">{{ $cat->tr('name') }}</span>
            <span class="absolute bottom-4 end-4 grid h-10 w-10 place-items-center rounded-full bg-mist text-ink transition group-hover:bg-gold rtl:-scale-x-100" aria-hidden="true">↗</span>
          </a>
        @endforeach
      </div>
    </div>
  </section>

  {{-- ===== Discover skills (tabs + horizontal slider) ===== --}}
  <section class="mx-auto max-w-7xl px-4 py-14" x-data="{ tab: 'all', go(d) { $refs.row.scrollBy({ left: d * (document.dir === 'rtl' ? -1 : 1) * 320, behavior: 'smooth' }) } }">
    <h2 class="text-2xl font-bold md:text-3xl">{{ __('Discover skills courses') }}</h2>
    <div class="mt-6 flex gap-6 overflow-x-auto border-b border-silver no-scrollbar">
      <button type="button" x-on:click="tab = 'all'" class="shrink-0 border-b-2 pb-3 text-sm font-semibold" :class="tab === 'all' ? 'border-gold text-ink' : 'border-transparent text-steel'">{{ __('All') }}</button>
      @foreach($categories as $cat)
        <button type="button" wire:key="tab-{{ $cat->id }}" x-on:click="tab = '{{ $cat->slug }}'" class="shrink-0 border-b-2 pb-3 text-sm font-semibold" :class="tab === '{{ $cat->slug }}' ? 'border-gold text-ink' : 'border-transparent text-steel'">{{ $cat->tr('name') }}</button>
      @endforeach
    </div>

    <div class="relative mt-8">
      <button type="button" x-on:click="go(-1)" class="absolute -start-3 top-1/2 z-10 hidden h-11 w-11 -translate-y-1/2 place-items-center rounded-full border border-silver bg-white shadow-card md:grid" aria-label="{{ __('Previous') }}"><span class="rtl:-scale-x-100">←</span></button>
      <button type="button" x-on:click="go(1)" class="absolute -end-3 top-1/2 z-10 hidden h-11 w-11 -translate-y-1/2 place-items-center rounded-full border border-silver bg-white shadow-card md:grid" aria-label="{{ __('Next') }}"><span class="rtl:-scale-x-100">→</span></button>
      <div x-ref="row" class="no-scrollbar flex snap-x gap-5 overflow-x-auto px-1 pb-6 pt-1">
        @foreach($discover as $c)
          <x-course-card :c="$c" wire:key="disc-{{ $c->id }}" class="w-72 shrink-0 snap-start"
              x-show="tab === 'all' || tab === '{{ $c->category?->slug }}'" />
        @endforeach
      </div>
    </div>
    <div class="mt-4 text-center"><a href="{{ route('courses.index') }}" class="btn btn-gold !px-10">{{ __('More') }}</a></div>
  </section>

  {{-- ===== Best sellers: one big card + four small ===== --}}
  @if($best->isNotEmpty())
  <section class="bg-white py-14">
    <div class="mx-auto max-w-7xl px-4">
      <div class="eyebrow">{{ __('Most enrolled') }}</div>
      <h2 class="mt-1 text-2xl font-bold md:text-3xl">{{ __('Best sellers') }}</h2>
      <div class="mt-8 grid gap-6 lg:grid-cols-5">
        <x-course-mini :c="$best->first()" :big="true" wire:key="big-{{ $best->first()->id }}" class="lg:col-span-3" />
        <div class="grid gap-6 sm:grid-cols-2 lg:col-span-2">
          @foreach($best->slice(1) as $c)<x-course-mini :c="$c" wire:key="bs-{{ $c->id }}" />@endforeach
          @foreach($latest->take(max(0, 3 - $best->slice(1)->count())) as $c)<x-course-mini :c="$c" wire:key="bl-{{ $c->id }}" />@endforeach
        </div>
      </div>
    </div>
  </section>
  @endif

  <x-course-row :eyebrow="__('Hand-picked')" :title="__('Featured courses')" :courses="$featured" />

  {{-- ===== Offer band ===== --}}
  <section class="mx-auto max-w-7xl px-4">
    <div class="flex flex-col items-start justify-between gap-4 rounded-3xl bg-gradient-to-r from-gold-light via-gold to-gold-dark px-8 py-8 text-ink md:flex-row md:items-center">
      <div><div class="text-sm font-semibold">{{ __('Limited offer') }}</div><div class="mt-1 text-3xl font-bold">{{ __('40% off selected courses') }}</div></div>
      <div class="flex items-center gap-3">
        <span dir="ltr" class="rounded-xl border-2 border-dashed border-ink px-4 py-2 text-lg font-bold">MOUSA40</span>
        <a href="{{ route('courses.index') }}" class="btn btn-dark">{{ __('Shop now') }}</a>
      </div>
    </div>
  </section>

  {{-- ===== Community preview ===== --}}
  <section class="mx-auto mt-16 max-w-7xl px-4">
    <div class="hero-bg grid-bg rounded-3xl px-6 py-14 text-ink md:px-12">
      <div class="flex flex-wrap items-end justify-between gap-4">
        <div><div class="eyebrow !text-ink">{{ __('Community') }}</div><h2 class="mt-1 text-2xl font-bold md:text-3xl">{{ __('Learn together, not alone') }}</h2>
          <p class="mt-2 max-w-xl text-steel">{{ __('Share what you build, ask questions and get answers from other learners.') }}</p></div>
        <a href="{{ route('community') }}" class="btn btn-gold">{{ __('Join the conversation') }}</a>
      </div>
      <div class="mt-8 grid gap-4 md:grid-cols-3">
        @forelse($posts as $p)
          <a wire:key="hp-{{ $p->id }}" href="{{ route('community') }}" class="block rounded-2xl border border-silver bg-white p-5 transition hover:border-gold">
            <div class="flex items-center gap-3"><x-avatar :user="$p->user" /><div><div class="text-sm font-semibold">{{ $p->user->name }}</div><div class="text-xs text-steel">{{ $p->created_at->diffForHumans() }}</div></div></div>
            <p class="mt-4 text-sm leading-6 text-steel">{{ \Illuminate\Support\Str::limit($p->body, 140) }}</p>
            <div class="mt-4 flex gap-4 text-xs text-steel"><span>♥ {{ $p->likes_count }}</span><span>💬 {{ $p->comments_count }}</span></div>
          </a>
        @empty
          <p class="col-span-full rounded-2xl border border-silver p-8 text-center text-steel">{{ __('No posts yet. Be the first to share something.') }}</p>
        @endforelse
      </div>
    </div>
  </section>

  {{-- ===== Stats + journey ===== --}}
  <section class="mx-auto max-w-7xl px-4 py-16">
    <div class="grid grid-cols-2 gap-4 md:grid-cols-4">
      @foreach([['courses', 'Courses'], ['students', 'Learners'], ['members', 'Members'], ['posts', 'Community posts']] as [$k, $label])
        <div class="card p-6 text-center"><div class="text-3xl font-bold text-ink">{{ number_format($stats[$k]) }}</div><div class="mt-1 text-xs text-steel">{{ __($label) }}</div></div>
      @endforeach
    </div>
    <div class="mt-14"><div class="eyebrow">{{ __('How it works') }}</div><h2 class="mt-1 text-2xl font-bold md:text-3xl">{{ __('Your learning journey') }}</h2></div>
    <div class="mt-8 grid gap-5 md:grid-cols-4">
      @foreach([['Choose a course', 'Browse paths and preview free lessons.'], ['Enroll securely', 'Pay with Vodafone Cash and we confirm it fast.'], ['Learn and track', 'Your progress is saved on every lesson.'], ['Share and grow', 'Post your projects and learn with the community.']] as $i => [$h, $d])
        <div class="card p-6"><div class="text-3xl font-bold text-ink" dir="ltr">{{ $i + 1 }}</div><h3 class="mt-3 font-bold">{{ __($h) }}</h3><p class="mt-2 text-sm text-steel">{{ __($d) }}</p></div>
      @endforeach
    </div>
  </section>

  {{-- ===== FAQ ===== --}}
  <section class="mx-auto max-w-3xl px-4 pb-16" x-data="{ open: null }">
    <h2 class="mb-6 text-2xl font-bold md:text-3xl">{{ __('Frequently asked questions') }}</h2>
    @foreach([['How do I enroll in a course?', 'Add it to your cart, check out, and the course unlocks once your payment is confirmed.'], ['Can I rewatch lessons?', 'Yes. Once you own a course you can rewatch any lesson, any time.'], ['Which payment methods are available?', 'Vodafone Cash with a receipt upload, with card payments coming soon.'], ['Can I study from my phone?', 'Yes. The platform works on phones, tablets and computers.']] as $i => [$q, $a])
      <div class="mb-3 rounded-2xl bg-white px-5 shadow-card">
        <button type="button" class="flex w-full items-center justify-between py-4 text-start font-semibold" x-on:click="open = open === {{ $i }} ? null : {{ $i }}">
          {{ __($q) }}<span x-text="open === {{ $i }} ? '−' : '+'" class="text-xl text-ink"></span>
        </button>
        <p x-cloak x-show="open === {{ $i }}" class="pb-4 text-sm text-steel">{{ __($a) }}</p>
      </div>
    @endforeach
  </section>

  {{-- ===== Final CTA ===== --}}
  @guest
  <section class="mx-auto max-w-7xl px-4">
    <div class="hero-bg grid-bg rounded-3xl px-6 py-16 text-center text-ink">
      <h2 class="mx-auto max-w-2xl text-3xl font-bold leading-snug md:text-4xl">{{ __('Sign up now and start learning with real projects!') }}</h2>
      <p class="mx-auto mt-3 max-w-xl text-steel">{{ __('Create your free account to preview lessons and join the community.') }}</p>
      <a href="{{ route('register') }}" class="btn btn-gold mt-7 !px-10">{{ __('Create your account now') }}</a>
    </div>
  </section>
  @endguest
</div>
