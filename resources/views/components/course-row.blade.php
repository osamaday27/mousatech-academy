@props(['title', 'courses'])
@if($courses->isNotEmpty())
<section class="mx-auto max-w-7xl px-4 py-12">
  <div class="mb-6 flex items-end justify-between border-b border-silver pb-3">
    <h2 class="text-2xl font-bold">{{ $title }}</h2>
    <a href="{{ route('courses.index') }}" class="text-sm font-semibold text-steel hover:text-ink">{{ __('View all') }}</a>
  </div>
  <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
    @foreach($courses as $c)
      <x-course-card :c="$c" wire:key="{{ \Illuminate\Support\Str::slug($title) }}-{{ $c->id }}" />
    @endforeach
  </div>
</section>
@endif
