<div class="mx-auto max-w-7xl px-4 py-10">
  <div class="flex items-center justify-between"><h1 class="text-3xl font-bold">{{ __('My courses') }}</h1><a href="{{ route('orders.index') }}" class="text-sm underline">{{ __('My orders') }}</a></div>
  <div class="mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
    @forelse($courses as $c)
      @php($pct = $c->lessons_count ? (int) round(($done[$c->id] ?? 0) / $c->lessons_count * 100) : 0)
      <a wire:key="mc-{{ $c->id }}" href="{{ route('learn', $c) }}" class="border border-silver transition hover:shadow-md">
        <img src="{{ $c->imageUrl() }}" alt="" class="aspect-video w-full object-cover">
        <div class="p-4">
          <h3 class="font-semibold">{{ $c->tr('title') }}</h3>
          <div class="mt-3 h-1.5 bg-silver"><div class="h-1.5 bg-gold" style="width: {{ $pct }}%"></div></div>
          <div class="mt-1 text-xs text-steel">{{ $pct }}% {{ __('complete') }}</div>
        </div>
      </a>
    @empty
      <p class="col-span-full border border-silver p-10 text-center text-steel">{{ __('You have not enrolled in any course yet.') }} <a class="underline" href="{{ route('courses.index') }}">{{ __('Browse courses') }}</a></p>
    @endforelse
  </div>
</div>
