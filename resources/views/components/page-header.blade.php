@props(['title', 'sub' => null])
<div class="mx-auto max-w-7xl px-4 pt-6">
  <section class="hero-bg grid-bg rounded-3xl text-white">
    <div class="px-6 py-10 md:px-12 md:py-14">
      <h1 class="text-3xl font-bold md:text-4xl">{{ $title }}</h1>
      @if($sub)<p class="mt-2 max-w-2xl text-silver">{{ $sub }}</p>@endif
    </div>
  </section>
</div>
