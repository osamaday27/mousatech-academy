<div>
  <section class="grid-bg bg-ink text-white">
    <div class="mx-auto max-w-7xl px-4 py-12">
      <div class="text-sm text-gold">{{ $course->category?->tr('name') }}</div>
      <h1 class="mt-2 max-w-3xl text-3xl font-bold md:text-4xl">{{ $course->tr('title') }}</h1>
      <div class="mt-4 flex flex-wrap gap-x-6 gap-y-1 text-sm text-silver">
        <span>{{ __('Instructor') }}: {{ $course->instructor_name }}</span>
        <span>{{ __(ucfirst($course->level)) }}</span>
        <span>{{ $course->durationLabel() }}</span>
        <span>{{ $students }} {{ __('students') }}</span>
      </div>
    </div>
  </section>

  <div class="mx-auto grid max-w-7xl gap-10 px-4 py-10 lg:grid-cols-3">
    <div class="lg:col-span-2">
      <img src="{{ $course->imageUrl() }}" alt="{{ $course->tr('title') }}" class="aspect-video w-full object-cover">
      <h2 class="mt-8 text-xl font-bold">{{ __('About this course') }}</h2>
      <p class="mt-3 whitespace-pre-line leading-7 text-steel">{{ $course->tr('description') }}</p>

      <h2 class="mt-10 text-xl font-bold">{{ __('Curriculum') }}</h2>
      <div class="mt-4 border border-silver">
        @forelse($sections as $s)
          <div wire:key="s-{{ $s->id }}" class="border-b border-silver last:border-b-0">
            <div class="bg-ink px-4 py-3 font-semibold text-white">{{ $s->tr('title') }}</div>
            @foreach($s->lessons as $l)
              <div wire:key="l-{{ $l->id }}" class="flex items-center justify-between border-t border-silver px-4 py-3 text-sm">
                <span>{{ $l->tr('title') }}</span>
                @if($owned)
                  <a href="{{ route('learn', [$course, $l->id]) }}" class="font-semibold text-gold-dark">{{ __('Watch') }}</a>
                @elseif($l->is_preview)
                  <a href="{{ route('learn', [$course, $l->id]) }}" class="font-semibold text-gold-dark">{{ __('Free preview') }}</a>
                @else
                  <span class="text-steel">{{ __('Locked') }}</span>
                @endif
              </div>
            @endforeach
          </div>
        @empty
          <p class="p-6 text-steel">{{ __('The curriculum is being prepared.') }}</p>
        @endforelse
      </div>
    </div>

    <aside class="h-fit border border-silver p-6 shadow-sm lg:sticky lg:top-24">
      @if($owned)
        <p class="mb-4 font-semibold">{{ __('You own this course') }}</p>
        <a href="{{ route('learn', $course) }}" class="btn btn-gold w-full">{{ __('Continue learning') }}</a>
      @else
        <div class="flex items-baseline gap-3">
          <span class="text-3xl font-bold text-gold-dark">{{ \App\Support\Money::fmt($course->currentPrice()) }}</span>
          @if($course->hasDiscount())
            <span class="text-steel line-through">{{ \App\Support\Money::fmt($course->price) }}</span>
            <span class="bg-ink px-1.5 py-0.5 text-xs font-bold text-gold">-{{ $course->discountPercent() }}%</span>
          @endif
        </div>
        <button type="button" wire:click="buyNow" class="btn btn-gold mt-5 w-full">{{ __('Buy now') }}</button>
        <button type="button" wire:click="add({{ $course->id }})" class="btn btn-line mt-3 w-full">{{ __('Add to cart') }}</button>
      @endif
    </aside>
  </div>

  <x-course-row :title="__('Related courses')" :courses="$related" />
</div>
