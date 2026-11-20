<div>
  <div class="mb-4">
    <a href="{{ route('admin.courses') }}" class="text-decoration-none small"><i class="mdi mdi-arrow-right-bold-outline"></i> {{ __('admin.nav.courses') }}</a>
    <h4 class="mt-1"><i class="mdi mdi-format-list-bulleted text-gold"></i> {{ __('admin.curriculum.title') }}: {{ $course->tr('title') }}</h4>
    <p class="text-muted mb-0">{{ __('admin.curriculum.subtitle') }}</p>
  </div>

  <div class="row g-4">
    <div class="col-lg-5">
      <form wire:submit="addSection" class="card mb-4">
        <div class="card-header bg-white fw-bold py-3"><i class="mdi mdi-folder-plus-outline text-gold"></i> {{ __('admin.curriculum.add_section') }}</div>
        <div class="card-body">
          <x-admin.field name="section.ar" :label="__('admin.fields.section_ar')" :hint="__('admin.hints.section')" icon="mdi-translate" :preview="$section['ar']" :required="true">
            <input type="text" wire:model.live.debounce.300ms="section.ar" class="form-control @error('section.ar') is-invalid @enderror" dir="rtl">
          </x-admin.field>
          <x-admin.field name="section.en" :label="__('admin.fields.section_en')" :hint="__('admin.hints.section')" icon="mdi-alphabetical" :preview="$section['en']" :required="true">
            <input type="text" wire:model.live.debounce.300ms="section.en" class="form-control @error('section.en') is-invalid @enderror" dir="ltr">
          </x-admin.field>
          <button class="btn btn-gold" wire:loading.attr="disabled"><i class="mdi mdi-plus"></i> {{ __('admin.save') }}</button>
        </div>
      </form>

      <form wire:submit="addLesson" class="card">
        <div class="card-header bg-white fw-bold py-3"><i class="mdi mdi-play-circle-outline text-gold"></i> {{ __('admin.curriculum.add_lesson') }}</div>
        <div class="card-body">
          <div class="alert alert-warning d-flex gap-2"><i class="mdi mdi-alert-outline fs-5"></i><div>{{ __('admin.curriculum.warning') }}</div></div>
          <x-admin.field name="lesson_section" :label="__('admin.fields.section')" :hint="__('admin.hints.lesson_section')" icon="mdi-folder-outline" :preview="optional($sections->firstWhere('id', (int) $lesson_section))->tr('title')" :required="true">
            <select wire:model.live="lesson_section" class="form-select @error('lesson_section') is-invalid @enderror"><option value="">{{ __('admin.choose') }}</option>@foreach($sections as $s)<option value="{{ $s->id }}">{{ $s->tr('title') }}</option>@endforeach</select>
          </x-admin.field>
          <x-admin.field name="lesson.ar" :label="__('admin.fields.lesson_ar')" :hint="__('admin.hints.lesson')" icon="mdi-translate" :preview="$lesson['ar']" :required="true">
            <input type="text" wire:model.live.debounce.300ms="lesson.ar" class="form-control @error('lesson.ar') is-invalid @enderror" dir="rtl">
          </x-admin.field>
          <x-admin.field name="lesson.en" :label="__('admin.fields.lesson_en')" :hint="__('admin.hints.lesson')" icon="mdi-alphabetical" :preview="$lesson['en']" :required="true">
            <input type="text" wire:model.live.debounce.300ms="lesson.en" class="form-control @error('lesson.en') is-invalid @enderror" dir="ltr">
          </x-admin.field>
          <x-admin.field name="video_url" :label="__('admin.fields.video_url')" :hint="__('admin.hints.video_url')" icon="mdi-video-outline" :preview="$video_url">
            <input type="url" wire:model.live.debounce.400ms="video_url" class="form-control @error('video_url') is-invalid @enderror" dir="ltr" placeholder="https://">
          </x-admin.field>
          <x-admin.field name="lesson_minutes" :label="__('admin.fields.duration')" :hint="__('admin.hints.lesson_minutes')" icon="mdi-clock-outline" :preview="$lesson_minutes" :required="true">
            <input type="number" min="0" wire:model.live.debounce.300ms="lesson_minutes" class="form-control @error('lesson_minutes') is-invalid @enderror" dir="ltr">
          </x-admin.field>
          <x-admin.field name="is_preview" :label="__('admin.fields.free_preview')" :hint="__('admin.hints.free_preview')" icon="mdi-eye-outline" :preview="$is_preview ? __('admin.yes') : __('admin.no')">
            <div class="form-check form-switch"><input class="form-check-input" type="checkbox" role="switch" wire:model.live="is_preview" id="l-prev"><label class="form-check-label" for="l-prev">{{ $is_preview ? __('admin.yes') : __('admin.no') }}</label></div>
          </x-admin.field>
          <button class="btn btn-gold" wire:loading.attr="disabled"><i class="mdi mdi-plus"></i> {{ __('admin.save') }}</button>
        </div>
      </form>
    </div>

    <div class="col-lg-7">
      @forelse($sections as $s)
        <div class="card mb-3" wire:key="sec-{{ $s->id }}">
          <div class="card-header d-flex justify-content-between align-items-center text-white" style="background:var(--navy)">
            <span class="fw-bold"><i class="mdi mdi-folder-outline text-warning"></i> {{ $s->tr('title') }}</span>
            <button type="button" class="btn btn-sm btn-outline-light" onclick="confirmDelete({{ $s->id }}, 'deleteSectionConfirmed')"><i class="mdi mdi-trash-can-outline"></i></button>
          </div>
          <ul class="list-group list-group-flush">
            @forelse($s->lessons as $l)
              <li class="list-group-item d-flex justify-content-between align-items-center" wire:key="les-{{ $l->id }}">
                <span><i class="mdi mdi-play-circle-outline text-gold"></i> {{ $l->tr('title') }} @if($l->is_preview)<span class="badge text-bg-warning">{{ __('admin.fields.free_preview') }}</span>@endif @unless($l->video_url)<span class="badge text-bg-light border">{{ __('admin.no_video') }}</span>@endunless</span>
                <button type="button" class="btn btn-sm btn-outline-danger" onclick="confirmDelete({{ $l->id }}, 'deleteLessonConfirmed')"><i class="mdi mdi-trash-can-outline"></i></button>
              </li>
            @empty
              <li class="list-group-item text-muted small">{{ __('admin.curriculum.no_lessons') }}</li>
            @endforelse
          </ul>
        </div>
      @empty
        <div class="card"><div class="card-body text-center text-muted py-5"><i class="mdi mdi-inbox-outline fs-1 d-block"></i>{{ __('admin.curriculum.empty') }}</div></div>
      @endforelse
    </div>
  </div>
</div>
