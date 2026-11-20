<div>
  <x-page-header :title="__('Community')" :sub="__('Share what you build, ask questions and get answers from other learners.')" />

  <div class="mx-auto max-w-2xl px-4 py-10">
    @auth
      <form wire:submit="publish" class="card p-4">
        <div class="flex gap-3">
          <x-avatar :user="auth()->user()" />
          <textarea wire:model="body" rows="3" maxlength="2000" placeholder="{{ __('What are you working on?') }}" class="field resize-none"></textarea>
        </div>
        @error('body')<span class="err">{{ $message }}</span>@enderror
        @if($image)<img src="{{ $image->temporaryUrl() }}" alt="" class="mt-3 max-h-48 w-full object-cover">@endif
        @error('image')<span class="err">{{ $message }}</span>@enderror
        <div class="mt-3 flex items-center justify-between">
          <label class="cursor-pointer text-sm font-medium text-steel hover:text-ink">
            <input type="file" wire:model="image" accept="image/png,image/jpeg,image/webp" class="hidden">＋ {{ __('Add image') }}
          </label>
          <button class="btn btn-gold" wire:loading.attr="disabled" wire:target="publish,image">{{ __('Post') }}</button>
        </div>
      </form>
    @else
      <div class="card p-6 text-center"><p class="text-steel">{{ __('Log in to share a post, comment and like.') }}</p>
        <a href="{{ route('login') }}" class="btn btn-gold mt-4">{{ __('Log in') }}</a></div>
    @endauth

    <div class="mt-8 flex border-b border-silver text-sm font-semibold">
      @foreach(['latest' => 'Latest', 'popular' => 'Top'] as $k => $label)
        <button type="button" wire:click="$set('sort', '{{ $k }}')" class="px-4 py-3 {{ $sort === $k ? 'border-b-2 border-gold text-ink' : 'text-steel' }}">{{ __($label) }}</button>
      @endforeach
    </div>

    <div class="mt-6 space-y-5">
      @forelse($ids as $id)
        <livewire:post-card :post-id="$id" :key="'post-'.$id" />
      @empty
        <p class="card p-10 text-center text-steel">{{ __('No posts yet. Be the first to share something.') }}</p>
      @endforelse
    </div>

    @if($hasMore)<button type="button" wire:click="more" wire:loading.attr="disabled" class="btn btn-line mt-8 w-full">{{ __('Load more') }}</button>@endif
  </div>
</div>
