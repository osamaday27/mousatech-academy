@props(['title', 'courses', 'eyebrow' => null])
@if($courses->isNotEmpty())
<section class="mx-auto max-w-7xl px-4 py-12">
  <div class="mb-8 flex items-end justify-between gap-4">
    <div>
      @if($eyebrow)<div class="eyebrow">{{ $eyebrow }}</div>@endif
      <h2 class="mt-1 text-2xl font-bold md:text-3xl">{{ $title }}</h2>
    </div>
    <a href="{{ route('courses.index') }}" class="shrink-0 rounded-xl border border-silver px-4 py-2 text-sm font-semibold transition hover:border-gold hover:bg-gold">{{ __('View all') }}</a>
  </div>
  <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
    @foreach($courses as $c)
      <x-course-card :c="$c" wire:key="{{ \Illuminate\Support\Str::slug($title) }}-{{ $c->id }}" />
    @endforeach
  </div>
</section>
@endif
