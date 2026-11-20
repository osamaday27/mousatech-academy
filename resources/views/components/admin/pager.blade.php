@props(['rows'])
<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mt-3">
  <div class="text-muted small"><i class="mdi mdi-format-list-numbered"></i> {{ __('admin.showing', ['from' => $rows->firstItem() ?? 0, 'to' => $rows->lastItem() ?? 0, 'total' => $rows->total()]) }}</div>
  <div class="d-flex align-items-center gap-3">
    <select wire:model.live="perPage" class="form-select form-select-sm w-auto" aria-label="{{ __('admin.per_page') }}">
      @foreach([10, 25, 50, 100] as $n)<option value="{{ $n }}">{{ $n }} / {{ __('admin.page') }}</option>@endforeach
    </select>
    {{ $rows->links('livewire::bootstrap') }}
  </div>
</div>
