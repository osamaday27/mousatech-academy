<div>
  <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
    <div>
      <h4 class="mb-1"><i class="mdi mdi-ticket-percent-outline text-gold"></i> {{ __('admin.coupons.title') }}</h4>
      <p class="text-muted mb-0">{{ __('admin.coupons.subtitle') }}</p>
    </div>
    @can('coupons.create')<button type="button" class="btn btn-gold" wire:click="create"><i class="mdi mdi-plus"></i> {{ __('admin.add_new') }}</button>@endcan
  </div>

  @if($showForm)
    <form wire:submit="save" class="card mb-4">
      <div class="card-header bg-white fw-bold py-3"><i class="mdi mdi-pencil-outline text-gold"></i> {{ $editId ? __('admin.edit') : __('admin.add_new') }}</div>
      <div class="card-body">
        <div class="alert alert-warning d-flex gap-2"><i class="mdi mdi-alert-outline fs-5"></i><div>{{ __('admin.coupons.warning') }}</div></div>
        <div class="row">
          <div class="col-md-6">
            <x-admin.field name="code" :label="__('admin.fields.code')" :hint="__('admin.hints.code')" icon="mdi-ticket-outline" :preview="strtoupper($code)" :required="true">
              <input type="text" wire:model.live.debounce.300ms="code" class="form-control text-uppercase @error('code') is-invalid @enderror" dir="ltr">
            </x-admin.field>
          </div>
          <div class="col-md-6">
            <x-admin.field name="type" :label="__('admin.fields.type')" :hint="__('admin.hints.type')" icon="mdi-percent-outline" :preview="$type === 'percent' ? __('admin.percent') : __('admin.fixed')" :required="true">
              <select wire:model.live="type" class="form-select @error('type') is-invalid @enderror"><option value="percent">{{ __('admin.percent') }}</option><option value="fixed">{{ __('admin.fixed') }}</option></select>
            </x-admin.field>
          </div>
          <div class="col-md-4">
            <x-admin.field name="value" :label="__('admin.fields.value')" :hint="$type === 'percent' ? __('admin.hints.value_percent') : __('admin.hints.value_fixed')" icon="mdi-numeric" :preview="is_numeric($value) ? ($type === 'percent' ? (int) $value.'%' : \App\Support\Money::fmt((int) round($value * 100))) : ''" :required="true">
              <input type="number" step="{{ $type === 'percent' ? '1' : '0.01' }}" min="0" wire:model.live.debounce.300ms="value" class="form-control @error('value') is-invalid @enderror" dir="ltr">
            </x-admin.field>
          </div>
          <div class="col-md-4">
            <x-admin.field name="min_total" :label="__('admin.fields.min_total')" :hint="__('admin.hints.min_total')" icon="mdi-cash-check" :preview="is_numeric($min_total) ? \App\Support\Money::fmt((int) round($min_total * 100)) : ''" :required="true">
              <input type="number" step="0.01" min="0" wire:model.live.debounce.300ms="min_total" class="form-control @error('min_total') is-invalid @enderror" dir="ltr">
            </x-admin.field>
          </div>
          <div class="col-md-4">
            <x-admin.field name="per_user_limit" :label="__('admin.fields.per_user')" :hint="__('admin.hints.per_user')" icon="mdi-account-check-outline" :preview="$per_user_limit" :required="true">
              <input type="number" min="1" wire:model.live.debounce.300ms="per_user_limit" class="form-control @error('per_user_limit') is-invalid @enderror" dir="ltr">
            </x-admin.field>
          </div>
          <div class="col-md-4">
            <x-admin.field name="max_uses" :label="__('admin.fields.max_uses')" :hint="__('admin.hints.max_uses')" icon="mdi-counter" :preview="filled($max_uses) ? $max_uses : __('admin.unlimited')">
              <input type="number" min="1" wire:model.live.debounce.300ms="max_uses" class="form-control @error('max_uses') is-invalid @enderror" dir="ltr">
            </x-admin.field>
          </div>
          <div class="col-md-4">
            <x-admin.field name="starts_at" :label="__('admin.fields.starts_at')" :hint="__('admin.hints.starts_at')" icon="mdi-calendar-start" :preview="$starts_at ? str_replace('T', ' ', $starts_at) : ''">
              <input type="datetime-local" wire:model.live="starts_at" class="form-control @error('starts_at') is-invalid @enderror" dir="ltr">
            </x-admin.field>
          </div>
          <div class="col-md-4">
            <x-admin.field name="expires_at" :label="__('admin.fields.expires_at')" :hint="__('admin.hints.expires_at')" icon="mdi-calendar-end" :preview="$expires_at ? str_replace('T', ' ', $expires_at) : ''">
              <input type="datetime-local" wire:model.live="expires_at" class="form-control @error('expires_at') is-invalid @enderror" dir="ltr">
            </x-admin.field>
          </div>
          <div class="col-12">
            <x-admin.field name="is_active" :label="__('admin.fields.status')" :hint="__('admin.hints.status')" icon="mdi-toggle-switch-outline" :preview="$is_active ? __('admin.active') : __('admin.inactive')" :previewicon="$is_active ? 'mdi-check-circle-outline' : 'mdi-close-circle-outline'">
              <div class="form-check form-switch"><input class="form-check-input" type="checkbox" role="switch" wire:model.live="is_active" id="cp-active"><label class="form-check-label" for="cp-active">{{ $is_active ? __('admin.active') : __('admin.inactive') }}</label></div>
            </x-admin.field>
          </div>
        </div>
      </div>
      <div class="card-footer bg-white d-flex gap-2 py-3">
        <button class="btn btn-gold" wire:loading.attr="disabled"><i class="mdi mdi-content-save-outline"></i> {{ __('admin.save') }}</button>
        <button type="button" class="btn btn-outline-secondary" wire:click="cancel">{{ __('admin.cancel') }}</button>
      </div>
    </form>
  @endif

  <div class="card">
    <div class="card-body">
      <div class="row g-2 mb-3">
        <div class="col-md-5"><div class="input-group"><span class="input-group-text bg-white"><i class="mdi mdi-magnify"></i></span><input type="search" class="form-control" wire:model.live.debounce.300ms="search" placeholder="{{ __('admin.search_code') }}"></div></div>
        <div class="col-md-3"><select class="form-select" wire:model.live="status"><option value="">{{ __('admin.all_statuses') }}</option><option value="active">{{ __('admin.active') }}</option><option value="inactive">{{ __('admin.inactive') }}</option></select></div>
        <div class="col-md-3"><select class="form-select" wire:model.live="trashed"><option value="">{{ __('admin.not_deleted') }}</option><option value="only">{{ __('admin.deleted_only') }}</option></select></div>
      </div>
      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
          <thead><tr><th>#</th><th>{{ __('admin.fields.code') }}</th><th>{{ __('admin.fields.value') }}</th><th>{{ __('admin.used') }}</th><th>{{ __('admin.fields.expires_at') }}</th><th>{{ __('admin.fields.status') }}</th><th>{{ __('admin.actions') }}</th></tr></thead>
          <tbody>
            @forelse($rows as $r)
              <tr wire:key="cp-{{ $r->id }}">
                <td>{{ $r->id }}</td>
                <td dir="ltr" class="fw-bold">{{ $r->code }}</td>
                <td>{{ $r->type === 'percent' ? $r->value.'%' : \App\Support\Money::fmt($r->value) }}</td>
                <td>{{ $r->used_count }} / {{ $r->max_uses ?? '∞' }}</td>
                <td class="small text-muted">{{ $r->expires_at?->format('Y-m-d H:i') ?? '—' }}</td>
                <td>@if($r->trashed())<span class="badge text-bg-secondary">{{ __('admin.deleted') }}</span>@else<span class="badge {{ $r->is_active ? 'text-bg-success' : 'text-bg-secondary' }}">{{ $r->is_active ? __('admin.active') : __('admin.inactive') }}</span>@endif</td>
                <td class="text-nowrap">
                  @if($r->trashed())
                    @can('coupons.update')<button type="button" class="btn btn-sm btn-outline-success" wire:click="restore({{ $r->id }})"><i class="mdi mdi-restore"></i> {{ __('admin.restore') }}</button>@endcan
                  @else
                    @can('coupons.update')
                      <button type="button" class="btn btn-sm btn-outline-dark" wire:click="edit({{ $r->id }})" title="{{ __('admin.edit') }}"><i class="mdi mdi-pencil-outline"></i></button>
                      <button type="button" class="btn btn-sm btn-outline-warning" wire:click="toggleStatus({{ $r->id }})" title="{{ __('admin.toggle_status') }}"><i class="mdi mdi-swap-horizontal"></i></button>
                    @endcan
                    @can('coupons.delete')<button type="button" class="btn btn-sm btn-outline-danger" onclick="confirmDelete({{ $r->id }})" title="{{ __('admin.delete') }}"><i class="mdi mdi-trash-can-outline"></i></button>@endcan
                  @endif
                </td>
              </tr>
            @empty
              <tr><td colspan="7" class="text-center text-muted py-5"><i class="mdi mdi-inbox-outline fs-1 d-block"></i>{{ __('admin.no_data') }}</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
      <x-admin.pager :rows="$rows" />
    </div>
  </div>
</div>
