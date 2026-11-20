@props(['c', 'big' => false])
<article {{ $attributes->merge(['class' => 'group flex flex-col overflow-hidden rounded-2xl bg-white shadow-card transition hover:-translate-y-1 hover:shadow-xl']) }}>
  <a href="{{ route('courses.show', $c) }}" class="relative block overflow-hidden bg-ink {{ $big ? 'aspect-[16/10]' : 'aspect-video' }}">
    <img src="{{ $c->imageUrl() }}" alt="{{ $c->tr('title') }}" loading="lazy" class="h-full w-full object-cover transition duration-500 group-hover:scale-105">
    <span class="absolute top-3 start-3 grid h-9 w-9 place-items-center rounded-full bg-ink/70 text-gold" aria-hidden="true"><svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg></span>
  </a>
  <div class="flex flex-1 flex-col p-5">
    <span class="w-fit rounded-md bg-mist px-2.5 py-1 text-xs text-steel">{{ $c->category?->tr('name') }}</span>
    <h3 class="mt-3 font-bold leading-snug {{ $big ? 'text-xl' : '' }}"><a href="{{ route('courses.show', $c) }}" class="hover:text-gold-dark">{{ $c->tr('title') }}</a></h3>
    <div class="mt-auto flex items-center justify-between pt-4">
      <span class="font-bold text-gold-dark">{{ \App\Support\Money::fmt($c->currentPrice()) }}</span>
      <button type="button" wire:click="add({{ $c->id }})" class="rounded-lg bg-ink px-3 py-1.5 text-xs font-semibold text-white hover:bg-navy-3">{{ __('Add to cart') }}</button>
    </div>
  </div>
</article>
