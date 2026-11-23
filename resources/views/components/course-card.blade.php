@props(['c'])
<article {{ $attributes->merge(['class' => 'group flex flex-col overflow-hidden rounded-2xl bg-white shadow-card transition hover:-translate-y-1 hover:shadow-xl']) }}>
  <a href="{{ route('courses.show', $c) }}" class="relative block aspect-video overflow-hidden bg-mist">
    <img src="{{ $c->imageUrl() }}" alt="{{ $c->tr('title') }}" loading="lazy"
         class="absolute inset-0 h-full w-full object-cover transition duration-500 group-hover:scale-105 group-hover:opacity-0">
    <img src="{{ $c->hoverUrl() }}" alt="" loading="lazy" aria-hidden="true"
         class="absolute inset-0 h-full w-full object-cover opacity-0 transition duration-500 group-hover:scale-105 group-hover:opacity-100">
    @if($c->is_featured)
      <span class="absolute top-3 start-3 rounded-lg bg-gold px-2.5 py-1 text-xs font-bold text-ink">{{ __('Featured') }}</span>
    @elseif($c->created_at && $c->created_at->gt(now()->subDays(14)))
      <span class="absolute top-3 start-3 rounded-lg bg-white px-2.5 py-1 text-xs font-bold text-ink">{{ __('New') }}</span>
    @endif
  </a>
  <div class="flex flex-1 flex-col p-5">
    <span class="w-fit rounded-md bg-mist px-2.5 py-1 text-xs text-steel">{{ $c->category?->tr('name') }}</span>
    <h3 class="mt-3 font-bold leading-snug"><a href="{{ route('courses.show', $c) }}" class="hover:text-ink">{{ $c->tr('title') }}</a></h3>
    <div class="mt-1 text-sm text-steel">{{ $c->instructor_name }}</div>
    <div class="mt-3 flex flex-wrap gap-x-3 text-xs text-steel">
      <span>{{ __(ucfirst($c->level)) }}</span><span>{{ $c->durationLabel() }}</span>
      @isset($c->enrollments_count)<span>{{ $c->enrollments_count }} {{ __('students') }}</span>@endisset
    </div>
    <div class="mt-auto flex items-baseline gap-2 pt-4">
      <span class="text-lg font-bold text-ink">{{ \App\Support\Money::fmt($c->currentPrice()) }}</span>
      @if($c->hasDiscount())
        <span class="text-sm text-steel line-through">{{ \App\Support\Money::fmt($c->price) }}</span>
        <span class="rounded-md bg-gold px-1.5 py-0.5 text-xs font-bold text-ink">-{{ $c->discountPercent() }}%</span>
      @endif
    </div>
    <button type="button" wire:click="add({{ $c->id }})" wire:loading.attr="disabled" class="btn btn-dark mt-4 w-full">{{ __('Add to cart') }}</button>
  </div>
</article>
