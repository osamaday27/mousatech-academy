@props(['title', 'sub' => null])
<section class="grid-bg bg-ink text-white">
  <div class="mx-auto max-w-7xl px-4 py-10 md:py-14">
    <h1 class="text-3xl font-bold md:text-4xl">{{ $title }}</h1>
    @if($sub)<p class="mt-2 max-w-2xl text-silver">{{ $sub }}</p>@endif
  </div>
</section>
