<div class="mx-auto max-w-5xl px-4 py-10">
  <a href="{{ route('admin.courses') }}" class="text-sm underline">{{ __('Courses') }}</a>
  <h1 class="mt-2 text-3xl font-bold">{{ $course->tr('title') }}</h1>

  <div class="mt-8 grid gap-6 md:grid-cols-2">
    <form wire:submit="addSection" class="space-y-3 border border-silver p-5">
      <h2 class="font-semibold">{{ __('Add section') }}</h2>
      <div><input wire:model="s_ar" placeholder="{{ __('Title (Arabic)') }}" class="field">@error('s_ar')<span class="err">{{ $message }}</span>@enderror</div>
      <div><input wire:model="s_en" placeholder="{{ __('Title (English)') }}" class="field" dir="ltr">@error('s_en')<span class="err">{{ $message }}</span>@enderror</div>
      <button class="btn btn-dark">{{ __('Add section') }}</button>
    </form>

    <form wire:submit="addLesson" class="space-y-3 border border-silver p-5">
      <h2 class="font-semibold">{{ __('Add lesson') }}</h2>
      <div><select wire:model="l_section" class="field"><option value="">{{ __('Choose a section') }}</option>@foreach($sections as $s)<option value="{{ $s->id }}">{{ $s->tr('title') }}</option>@endforeach</select>@error('l_section')<span class="err">{{ $message }}</span>@enderror</div>
      <div><input wire:model="l_ar" placeholder="{{ __('Title (Arabic)') }}" class="field">@error('l_ar')<span class="err">{{ $message }}</span>@enderror</div>
      <div><input wire:model="l_en" placeholder="{{ __('Title (English)') }}" class="field" dir="ltr">@error('l_en')<span class="err">{{ $message }}</span>@enderror</div>
      <div><input wire:model="l_video" placeholder="{{ __('Video embed URL (https://...)') }}" class="field" dir="ltr">@error('l_video')<span class="err">{{ $message }}</span>@enderror</div>
      <div class="flex items-center gap-4"><input type="number" min="0" wire:model="l_min" class="field w-28" dir="ltr"><label class="flex items-center gap-2 text-sm"><input type="checkbox" wire:model="l_preview"> {{ __('Free preview') }}</label></div>
      <button class="btn btn-dark">{{ __('Add lesson') }}</button>
    </form>
  </div>

  <div class="mt-8 border border-silver">
    @forelse($sections as $s)
      <div wire:key="cs-{{ $s->id }}" class="border-b border-silver last:border-b-0">
        <div class="flex items-center justify-between bg-ink px-4 py-3 text-white"><span class="font-semibold">{{ $s->tr('title') }}</span><button type="button" wire:click="delSection({{ $s->id }})" wire:confirm="{{ __('Delete this section and its lessons?') }}" class="text-xs text-gold underline">{{ __('Delete') }}</button></div>
        @foreach($s->lessons as $l)
          <div wire:key="cl-{{ $l->id }}" class="flex items-center justify-between border-t border-silver px-4 py-2 text-sm">
            <span>{{ $l->tr('title') }} @if($l->is_preview)<span class="ms-2 bg-gold px-1.5 text-xs font-bold">{{ __('Free preview') }}</span>@endif</span>
            <button type="button" wire:click="delLesson({{ $l->id }})" wire:confirm="{{ __('Delete this lesson?') }}" class="text-xs text-red-600 underline">{{ __('Delete') }}</button>
          </div>
        @endforeach
      </div>
    @empty
      <p class="p-8 text-center text-steel">{{ __('No sections yet. Add the first one above.') }}</p>
    @endforelse
  </div>
</div>
