<div>
  <section class="grid-bg bg-ink text-white">
    <div class="mx-auto flex max-w-4xl flex-col gap-6 px-4 py-12 sm:flex-row sm:items-center">
      <x-avatar :user="$user" size="h-24 w-24 text-4xl" />
      <div class="min-w-0 flex-1">
        <div class="flex flex-wrap items-center gap-3">
          <h1 class="text-3xl font-bold">{{ $user->name }}</h1>
          @foreach($roles as $r)<span class="bg-gold px-2 py-0.5 text-xs font-bold text-ink">{{ __($r) }}</span>@endforeach
        </div>
        @if($user->bio)<p class="mt-2 max-w-xl whitespace-pre-line text-silver">{{ $user->bio }}</p>@endif
        <p class="mt-2 text-xs text-steel">{{ __('Member since') }} {{ $user->created_at->translatedFormat('F Y') }}</p>
      </div>
      @if($owner && ! $editing)<button type="button" wire:click="edit" class="btn border border-silver text-white hover:bg-white hover:text-ink">{{ __('Edit profile') }}</button>@endif
    </div>
  </section>

  <div class="border-b border-silver">
    <div class="mx-auto grid max-w-4xl grid-cols-3 divide-x divide-silver rtl:divide-x-reverse">
      @foreach([[$postsCount, 'Posts'], [$likesReceived, 'Likes received'], [$coursesCount, 'Courses']] as [$n, $label])
        <div class="py-5 text-center"><div class="text-2xl font-bold text-gold-dark">{{ $n }}</div><div class="text-xs text-steel">{{ __($label) }}</div></div>
      @endforeach
    </div>
  </div>

  <div class="mx-auto max-w-2xl px-4 py-10">
    @if($owner && $editing)
      <form wire:submit="save" class="card mb-8 space-y-4 p-5">
        <div><label class="label" for="pn">{{ __('Full name') }}</label><input id="pn" wire:model="name" class="field">@error('name')<span class="err">{{ $message }}</span>@enderror</div>
        <div><label class="label" for="pb">{{ __('Bio') }}</label><textarea id="pb" wire:model="bio" rows="3" maxlength="500" class="field"></textarea>@error('bio')<span class="err">{{ $message }}</span>@enderror</div>
        <div><label class="label" for="pa">{{ __('Profile photo') }}</label><input id="pa" type="file" wire:model="avatar" accept="image/png,image/jpeg,image/webp" class="field">@error('avatar')<span class="err">{{ $message }}</span>@enderror</div>
        <div class="flex gap-3"><button class="btn btn-gold" wire:loading.attr="disabled" wire:target="save,avatar">{{ __('Save') }}</button><button type="button" wire:click="$set('editing', false)" class="btn btn-line">{{ __('Cancel') }}</button></div>
      </form>
    @endif

    <h2 class="mb-5 text-xl font-bold">{{ __('Posts') }}</h2>
    <div class="space-y-5">
      @forelse($ids as $id)
        <livewire:post-card :post-id="$id" :key="'pp-'.$id" />
      @empty
        <p class="card p-10 text-center text-steel">{{ __('No posts yet.') }}</p>
      @endforelse
    </div>
    @if($postsCount > $perPage)<button type="button" wire:click="more" class="btn btn-line mt-8 w-full">{{ __('Load more') }}</button>@endif
  </div>
</div>
