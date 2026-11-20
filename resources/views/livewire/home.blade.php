<div>
  <section class="grid-bg bg-ink text-white">
    <div class="mx-auto grid max-w-7xl items-center gap-10 px-4 py-16 md:grid-cols-2 md:py-24">
      <div>
        <h1 class="text-4xl font-bold leading-tight md:text-5xl">{{ __('Learn to build software that ships') }}</h1>
        <p class="mt-5 max-w-lg text-silver">{{ __('Project-based courses in web, backend, security, cloud and AI, taught in Arabic.') }}</p>
        <div class="mt-8 flex flex-wrap gap-3">
          <a href="{{ route('courses.index') }}" class="btn btn-gold">{{ __('Browse courses') }}</a>
          @guest<a href="{{ route('register') }}" class="btn border border-silver text-white hover:bg-white hover:text-ink">{{ __('Create free account') }}</a>@endguest
        </div>
      </div>
      <pre dir="ltr" class="overflow-x-auto border border-steel bg-black/60 p-5 text-left text-sm leading-7 text-silver"><code><span class="text-steel">// your next 90 days</span>
$student-&gt;enroll($course);
$student-&gt;build($project);
<span class="text-gold">$student-&gt;ship();</span></code></pre>
    </div>
  </section>

  <section class="mx-auto max-w-7xl px-4 py-12">
    <h2 class="mb-6 border-b border-silver pb-3 text-2xl font-bold">{{ __('Categories') }}</h2>
    <div class="grid gap-px border border-silver bg-silver sm:grid-cols-2 lg:grid-cols-3">
      @foreach($categories as $cat)
        <a wire:key="cat-{{ $cat->id }}" href="{{ route('courses.index', ['cat' => $cat->slug]) }}" class="flex items-center justify-between bg-white p-5 transition hover:bg-ink hover:text-gold">
          <span class="font-semibold">{{ $cat->tr('name') }}</span>
          <span class="text-sm text-steel">{{ $cat->courses_count }} {{ __('courses') }}</span>
        </a>
      @endforeach
    </div>
  </section>

  <x-course-row :title="__('Featured courses')" :courses="$featured" />
  <x-course-row :title="__('Best sellers')" :courses="$best" />
  <x-course-row :title="__('Latest courses')" :courses="$latest" />

  <section class="bg-ink py-14 text-white">
    <div class="mx-auto grid max-w-7xl gap-8 px-4 md:grid-cols-3">
      @foreach([['Real projects', 'Every course ends with something you can put in your portfolio.'], ['Learn at your pace', 'Pick up where you stopped. Your progress is saved on every lesson.'], ['Free previews', 'Watch preview lessons before you decide to buy.']] as [$h, $p])
        <div class="border-s-2 border-gold ps-4"><h3 class="font-semibold">{{ __($h) }}</h3><p class="mt-2 text-sm text-silver">{{ __($p) }}</p></div>
      @endforeach
    </div>
  </section>

  <section class="mx-auto max-w-3xl px-4 py-14" x-data="{ open: null }">
    <h2 class="mb-6 text-2xl font-bold">{{ __('Frequently asked questions') }}</h2>
    @foreach([['How do I enroll in a course?', 'Add it to your cart, check out, and the course unlocks once your payment is confirmed.'], ['Can I rewatch lessons?', 'Yes. Once you own a course you can rewatch any lesson, any time.'], ['Which payment methods are available?', 'Vodafone Cash with a receipt upload, with card payments coming soon.'], ['Can I study from my phone?', 'Yes. The platform works on phones, tablets and computers.']] as $i => [$q, $a])
      <div class="border-b border-silver">
        <button type="button" class="flex w-full items-center justify-between py-4 text-start font-medium" x-on:click="open = open === {{ $i }} ? null : {{ $i }}">
          {{ __($q) }}<span x-text="open === {{ $i }} ? '−' : '+'" class="text-gold-dark"></span>
        </button>
        <p x-cloak x-show="open === {{ $i }}" class="pb-4 text-sm text-steel">{{ __($a) }}</p>
      </div>
    @endforeach
  </section>
</div>
