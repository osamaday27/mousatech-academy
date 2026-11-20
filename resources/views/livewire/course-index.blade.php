<div class="mx-auto max-w-7xl px-4 py-10">
  <h1 class="text-3xl font-bold">{{ __('All courses') }}</h1>

  <div class="mt-6 grid gap-3 md:grid-cols-4">
    <input type="search" wire:model.live.debounce.300ms="q" placeholder="{{ __('Search courses or instructors') }}" class="field md:col-span-1">
    <select wire:model.live="cat" class="field">
      <option value="">{{ __('All categories') }}</option>
      @foreach($categories as $c)<option value="{{ $c->slug }}">{{ $c->tr('name') }}</option>@endforeach
    </select>
    <select wire:model.live="level" class="field">
      <option value="">{{ __('All levels') }}</option>
      <option value="beginner">{{ __('Beginner') }}</option>
      <option value="intermediate">{{ __('Intermediate') }}</option>
      <option value="advanced">{{ __('Advanced') }}</option>
    </select>
    <select wire:model.live="sort" class="field">
      <option value="latest">{{ __('Newest') }}</option>
      <option value="popular">{{ __('Most popular') }}</option>
      <option value="price_asc">{{ __('Price: low to high') }}</option>
      <option value="price_desc">{{ __('Price: high to low') }}</option>
    </select>
  </div>

  <div class="mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
    @forelse($courses as $c)
      <x-course-card :c="$c" wire:key="c-{{ $c->id }}" />
    @empty
      <p class="col-span-full border border-silver p-10 text-center text-steel">{{ __('No courses match your filters. Try clearing the search.') }}</p>
    @endforelse
  </div>

  <div class="mt-8">{{ $courses->links() }}</div>
</div>
