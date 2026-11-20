<div class="mx-auto grid max-w-7xl gap-0 px-4 py-6 lg:grid-cols-3 lg:gap-8">
  <div class="lg:col-span-2">
    <a href="{{ route('courses.show', $course) }}" class="text-sm text-steel underline">{{ $course->tr('title') }}</a>
    @if($cur)
      <h1 class="mt-2 text-2xl font-bold">{{ $cur->tr('title') }}</h1>
      <div class="mt-4 aspect-video w-full bg-ink">
        @if($cur->video_url)
          <iframe src="{{ $cur->video_url }}" class="h-full w-full" allow="fullscreen; picture-in-picture" allowfullscreen referrerpolicy="strict-origin" title="{{ $cur->tr('title') }}"></iframe>
        @else
          <div class="grid h-full place-items-center text-sm text-silver">{{ __('No video has been added to this lesson yet.') }}</div>
        @endif
      </div>
      @if($owned)
        <button type="button" wire:click="toggle({{ $cur->id }})" class="btn {{ in_array($cur->id, $done) ? 'btn-line' : 'btn-gold' }} mt-4">
          {{ in_array($cur->id, $done) ? __('Mark as not completed') : __('Mark as completed') }}
        </button>
      @endif
    @else
      <p class="mt-6 border border-silver p-10 text-center text-steel">{{ __('The curriculum is being prepared.') }}</p>
    @endif
  </div>

  <aside class="mt-6 h-fit border border-silver lg:mt-8">
    <div class="border-b border-silver p-4">
      <div class="text-sm font-semibold">{{ __('Your progress') }}: {{ $percent }}%</div>
      <div class="mt-2 h-1.5 bg-silver"><div class="h-1.5 bg-gold" style="width: {{ $percent }}%"></div></div>
    </div>
    @foreach($sections as $s)
      <div wire:key="ps-{{ $s->id }}">
        <div class="bg-ink px-4 py-2 text-sm font-semibold text-white">{{ $s->tr('title') }}</div>
        @foreach($s->lessons as $l)
          @php($can = $owned || $l->is_preview)
          <button type="button" wire:key="pl-{{ $l->id }}" @if($can) wire:click="select({{ $l->id }})" @else disabled @endif
                  class="flex w-full items-center justify-between border-t border-silver px-4 py-3 text-start text-sm {{ $cur && $cur->id === $l->id ? 'bg-gold/20 font-semibold' : '' }} {{ $can ? 'hover:bg-silver/20' : 'text-steel' }}">
            <span>{{ $l->tr('title') }}</span>
            <span class="text-xs">{{ in_array($l->id, $done) ? '✓' : ($can ? '' : __('Locked')) }}</span>
          </button>
        @endforeach
      </div>
    @endforeach
  </aside>
</div>
