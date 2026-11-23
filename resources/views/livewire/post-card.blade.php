<article class="card">
  <div class="flex items-start justify-between gap-3 p-4">
    <a href="{{ route('profile', $p->user) }}" class="flex items-center gap-3">
      <x-avatar :user="$p->user" />
      <span><span class="block text-sm font-semibold hover:text-ink">{{ $p->user->name }}</span>
        <span class="block text-xs text-steel">{{ $p->created_at->diffForHumans() }}</span></span>
    </a>
    @if($manage)
      <div class="flex gap-3 text-xs text-steel">
        @if($owner)<button type="button" wire:click="startEdit" class="underline hover:text-ink">{{ __('Edit') }}</button>@endif
        <button type="button" wire:click="deletePost" wire:confirm="{{ __('Delete this post?') }}" class="underline hover:text-red-600">{{ __('Delete') }}</button>
      </div>
    @endif
  </div>

  <div class="px-4 pb-4">
    @if($editing)
      <form wire:submit="saveEdit">
        <textarea wire:model="editBody" rows="3" class="field resize-none"></textarea>
        @error('editBody')<span class="err">{{ $message }}</span>@enderror
        <div class="mt-2 flex gap-2"><button class="btn btn-gold !py-1.5">{{ __('Save') }}</button><button type="button" wire:click="$set('editing', false)" class="btn btn-line !py-1.5">{{ __('Cancel') }}</button></div>
      </form>
    @else
      <p class="whitespace-pre-line break-words leading-7">{{ $p->body }}</p>
    @endif
  </div>

  @if($p->image)<img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($p->image) }}" alt="" loading="lazy" class="max-h-[28rem] w-full object-cover">@endif

  <div class="flex items-center gap-6 border-t border-silver px-4 py-3 text-sm">
    <button type="button" wire:click="like" class="flex items-center gap-1.5 font-semibold {{ $liked ? 'text-ink' : 'text-steel hover:text-ink' }}" aria-pressed="{{ $liked ? 'true' : 'false' }}">
      <span aria-hidden="true">{{ $liked ? '♥' : '♡' }}</span>{{ __('Like') }} <span class="text-xs">{{ $p->likes_count }}</span>
    </button>
    <button type="button" wire:click="toggle" class="flex items-center gap-1.5 font-semibold text-steel hover:text-ink">
      <span aria-hidden="true">💬</span>{{ __('Comments') }} <span class="text-xs">{{ $p->comments_count }}</span>
    </button>
  </div>

  @if($open)
    <div class="border-t border-silver bg-silver/10 p-4">
      <div class="space-y-4">
        @foreach($comments as $c)
          <div wire:key="cm-{{ $c->id }}" class="flex gap-3">
            <a href="{{ route('profile', $c->user) }}"><x-avatar :user="$c->user" size="h-8 w-8 text-sm" /></a>
            <div class="min-w-0 flex-1 border border-silver bg-white px-3 py-2">
              <div class="flex items-center justify-between gap-2">
                <a href="{{ route('profile', $c->user) }}" class="text-sm font-semibold hover:text-ink">{{ $c->user->name }}</a>
                <span class="flex items-center gap-3 text-xs text-steel">{{ $c->created_at->diffForHumans() }}
                  @if(auth()->check() && ($c->user_id === auth()->id() || auth()->user()->can('posts.moderate')))
                    <button type="button" wire:click="deleteComment({{ $c->id }})" wire:confirm="{{ __('Delete this comment?') }}" class="underline hover:text-red-600">{{ __('Delete') }}</button>
                  @endif
                </span>
              </div>
              <p class="mt-1 whitespace-pre-line break-words text-sm">{{ $c->body }}</p>
            </div>
          </div>
        @endforeach
        @if($comments->isEmpty())<p class="text-center text-sm text-steel">{{ __('No comments yet.') }}</p>@endif
      </div>

      @auth
        <form wire:submit="addComment" class="mt-4 flex gap-2">
          <input type="text" wire:model="comment" maxlength="1000" placeholder="{{ __('Write a comment...') }}" class="field">
          <button class="btn btn-dark" wire:loading.attr="disabled">{{ __('Send') }}</button>
        </form>
        @error('comment')<span class="err">{{ $message }}</span>@enderror
      @else
        <p class="mt-4 text-center text-sm"><a href="{{ route('login') }}" class="font-semibold underline">{{ __('Log in') }}</a> {{ __('to comment') }}</p>
      @endauth
    </div>
  @endif
</article>
