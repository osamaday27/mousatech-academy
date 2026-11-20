<div>
  {{-- Hero --}}
  <section class="grid-bg bg-ink text-white">
    <div class="mx-auto grid max-w-7xl items-center gap-12 px-4 py-16 md:grid-cols-5 md:py-24">
      <div class="md:col-span-3">
        <div class="eyebrow !text-gold">{{ __('Programming & technology academy') }}</div>
        <h1 class="mt-4 text-4xl font-bold leading-tight md:text-6xl">{{ __('Learn to build software that ships') }}</h1>
        <p class="mt-5 max-w-xl text-lg text-silver">{{ __('Project-based courses in web, backend, security, cloud and AI, taught in Arabic.') }}</p>

        <form action="{{ route('courses.index') }}" class="mt-8 flex max-w-xl">
          <input type="search" name="q" placeholder="{{ __('Search courses or instructors') }}" aria-label="{{ __('Search') }}"
                 class="min-w-0 flex-1 border border-steel bg-black/40 px-4 py-3 text-white placeholder:text-steel focus:border-gold focus:outline-none">
          <button class="btn btn-gold !px-6">{{ __('Search') }}</button>
        </form>
        <div class="mt-4 flex flex-wrap gap-2 text-xs">
          @foreach($categories->take(4) as $cat)
            <a href="{{ route('courses.index', ['cat' => $cat->slug]) }}" class="border border-steel px-3 py-1.5 text-silver transition hover:border-gold hover:text-gold">{{ $cat->tr('name') }}</a>
          @endforeach
        </div>
      </div>

      <div class="md:col-span-2">
        <pre dir="ltr" class="overflow-x-auto border border-steel bg-black/60 p-5 text-left text-sm leading-7 text-silver"><code><span class="text-steel">// your next 90 days</span>
$student-&gt;enroll($course);
$student-&gt;build($project);
$student-&gt;share($community);
<span class="text-gold">$student-&gt;ship();</span></code></pre>
        @guest<a href="{{ route('register') }}" class="btn btn-gold mt-4 w-full">{{ __('Create free account') }}</a>@endguest
      </div>
    </div>
  </section>

  {{-- Stats --}}
  <section class="border-b border-silver bg-white">
    <div class="mx-auto grid max-w-7xl grid-cols-2 divide-x divide-silver px-4 rtl:divide-x-reverse md:grid-cols-4">
      @foreach([['courses', 'Courses'], ['students', 'Learners'], ['members', 'Members'], ['posts', 'Community posts']] as [$k, $label])
        <div class="py-6 text-center"><div class="text-3xl font-bold text-gold-dark">{{ number_format($stats[$k]) }}</div><div class="mt-1 text-xs text-steel">{{ __($label) }}</div></div>
      @endforeach
    </div>
  </section>

  {{-- Categories --}}
  <section class="mx-auto max-w-7xl px-4 py-14">
    <div class="eyebrow">{{ __('Learning paths') }}</div>
    <h2 class="mt-1 text-2xl font-bold md:text-3xl">{{ __('Categories') }}</h2>
    <div class="mt-8 grid gap-px border border-silver bg-silver sm:grid-cols-2 lg:grid-cols-3">
      @foreach($categories as $i => $cat)
        <a wire:key="cat-{{ $cat->id }}" href="{{ route('courses.index', ['cat' => $cat->slug]) }}" class="group flex items-center gap-4 bg-white p-6 transition hover:bg-ink">
          <span class="text-2xl font-bold text-silver transition group-hover:text-gold" dir="ltr">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
          <span class="flex-1"><span class="block font-semibold group-hover:text-white">{{ $cat->tr('name') }}</span><span class="text-xs text-steel">{{ $cat->courses_count }} {{ __('courses') }}</span></span>
          <span class="text-gold opacity-0 transition group-hover:opacity-100 rtl:rotate-180" aria-hidden="true">→</span>
        </a>
      @endforeach
    </div>
  </section>

  <x-course-row :eyebrow="__('Hand-picked')" :title="__('Featured courses')" :courses="$featured" />
  <x-course-row :eyebrow="__('Most enrolled')" :title="__('Best sellers')" :courses="$best" />

  {{-- Offer band --}}
  <section class="bg-gold text-ink">
    <div class="mx-auto flex max-w-7xl flex-col items-start justify-between gap-4 px-4 py-10 md:flex-row md:items-center">
      <div><div class="text-sm font-semibold">{{ __('Limited offer') }}</div><div class="mt-1 text-3xl font-bold">{{ __('40% off selected courses') }}</div></div>
      <div class="flex items-center gap-3">
        <span dir="ltr" class="border-2 border-dashed border-ink px-4 py-2 text-lg font-bold">MOUSA40</span>
        <a href="{{ route('courses.index') }}" class="btn btn-dark">{{ __('Shop now') }}</a>
      </div>
    </div>
  </section>

  <x-course-row :eyebrow="__('Just added')" :title="__('Latest courses')" :courses="$latest" />

  {{-- Community preview --}}
  <section class="grid-bg bg-ink py-16 text-white">
    <div class="mx-auto max-w-7xl px-4">
      <div class="flex flex-wrap items-end justify-between gap-4">
        <div><div class="eyebrow !text-gold">{{ __('Community') }}</div><h2 class="mt-1 text-2xl font-bold md:text-3xl">{{ __('Learn together, not alone') }}</h2>
          <p class="mt-2 max-w-xl text-silver">{{ __('Share what you build, ask questions and get answers from other learners.') }}</p></div>
        <a href="{{ route('community') }}" class="btn btn-gold">{{ __('Join the conversation') }}</a>
      </div>
      <div class="mt-8 grid gap-4 md:grid-cols-3">
        @forelse($posts as $p)
          <a wire:key="hp-{{ $p->id }}" href="{{ route('community') }}" class="block border border-steel bg-black/40 p-5 transition hover:border-gold">
            <div class="flex items-center gap-3"><x-avatar :user="$p->user" /><div><div class="text-sm font-semibold">{{ $p->user->name }}</div><div class="text-xs text-steel">{{ $p->created_at->diffForHumans() }}</div></div></div>
            <p class="mt-4 text-sm leading-6 text-silver">{{ \Illuminate\Support\Str::limit($p->body, 140) }}</p>
            <div class="mt-4 flex gap-4 text-xs text-steel"><span>♥ {{ $p->likes_count }}</span><span>💬 {{ $p->comments_count }}</span></div>
          </a>
        @empty
          <p class="col-span-full border border-steel p-8 text-center text-silver">{{ __('No posts yet. Be the first to share something.') }}</p>
        @endforelse
      </div>
    </div>
  </section>

  {{-- Journey --}}
  <section class="mx-auto max-w-7xl px-4 py-16">
    <div class="eyebrow">{{ __('How it works') }}</div>
    <h2 class="mt-1 text-2xl font-bold md:text-3xl">{{ __('Your learning journey') }}</h2>
    <div class="mt-8 grid gap-px border border-silver bg-silver md:grid-cols-4">
      @foreach([['Choose a course', 'Browse paths and preview free lessons.'], ['Enroll securely', 'Pay with Vodafone Cash and we confirm it fast.'], ['Learn and track', 'Your progress is saved on every lesson.'], ['Share and grow', 'Post your projects and learn with the community.']] as $i => [$h, $d])
        <div class="bg-white p-6"><div class="text-3xl font-bold text-gold-dark" dir="ltr">{{ $i + 1 }}</div><h3 class="mt-3 font-semibold">{{ __($h) }}</h3><p class="mt-2 text-sm text-steel">{{ __($d) }}</p></div>
      @endforeach
    </div>
  </section>

  {{-- FAQ --}}
  <section class="mx-auto max-w-3xl px-4 pb-16" x-data="{ open: null }">
    <h2 class="mb-6 text-2xl font-bold md:text-3xl">{{ __('Frequently asked questions') }}</h2>
    @foreach([['How do I enroll in a course?', 'Add it to your cart, check out, and the course unlocks once your payment is confirmed.'], ['Can I rewatch lessons?', 'Yes. Once you own a course you can rewatch any lesson, any time.'], ['Which payment methods are available?', 'Vodafone Cash with a receipt upload, with card payments coming soon.'], ['Can I study from my phone?', 'Yes. The platform works on phones, tablets and computers.']] as $i => [$q, $a])
      <div class="border-b border-silver">
        <button type="button" class="flex w-full items-center justify-between py-4 text-start font-medium" x-on:click="open = open === {{ $i }} ? null : {{ $i }}">
          {{ __($q) }}<span x-text="open === {{ $i }} ? '−' : '+'" class="text-xl text-gold-dark"></span>
        </button>
        <p x-cloak x-show="open === {{ $i }}" class="pb-4 text-sm text-steel">{{ __($a) }}</p>
      </div>
    @endforeach
  </section>
</div>
