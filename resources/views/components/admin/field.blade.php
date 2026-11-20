@props(['label', 'name', 'hint' => null, 'icon' => 'mdi-form-textbox', 'preview' => null, 'previewicon' => null, 'required' => false])
<div class="mb-4">
  <label class="form-label fw-semibold"><i class="mdi {{ $icon }} text-gold"></i> {{ $label }} @if($required)<span class="text-danger">*</span>@endif</label>
  {{ $slot }}
  @error($name)<div class="text-danger small mt-1"><i class="mdi mdi-alert-circle-outline"></i> {{ $message }}</div>@enderror
  @if($hint)<div class="form-text"><i class="mdi mdi-information-outline"></i> {{ $hint }}</div>@endif
  <div class="field-preview mt-2">
    <i class="mdi {{ $previewicon ?: 'mdi-eye-outline' }}"></i>
    @if(filled($preview))<span>{{ $preview }}</span>@else<span class="text-muted">{{ __('admin.preview_empty') }}</span>@endif
  </div>
</div>
