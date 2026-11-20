<div>
  <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
    <div>
      <h4 class="mb-1"><i class="mdi mdi-shape-outline text-gold"></i> {{ __('admin.categories.title') }}</h4>
      <p class="text-muted mb-0">{{ __('admin.categories.subtitle') }}</p>
    </div>
    @can('categories.create')<button type="button" class="btn btn-gold" wire:click="create"><i class="mdi mdi-plus"></i> {{ __('admin.add_new') }}</button>@endcan
  </div>

  @if($showForm)
    <form wire:submit="save" class="card mb-4">
      <div class="card-header bg-white fw-bold py-3"><i class="mdi mdi-pencil-outline text-gold"></i> {{ $editId ? __('admin.edit') : __('admin.add_new') }}</div>
      <div class="card-body">
        <div class="alert alert-warning d-flex gap-2"><i class="mdi mdi-alert-outline fs-5"></i><div>{{ __('admin.categories.warning') }}</div></div>
        <div class="row">
          <div class="col-md-6">
            <x-admin.field name="name.ar" :label="__('admin.fields.name_ar')" :hint="__('admin.hints.name_ar')" icon="mdi-translate" :preview="$name['ar']" :required="true">
              <input type="text" wire:model.live.debounce.300ms="name.ar" class="form-control @error('name.ar') is-invalid @enderror" dir="rtl">
            </x-admin.field>
          </div>
          <div class="col-md-6">
            <x-admin.field name="name.en" :label="__('admin.fields.name_en')" :hint="__('admin.hints.name_en')" icon="mdi-alphabetical" :preview="$name['en']" :required="true">
              <input type="text" wire:model.live.debounce.300ms="name.en" class="form-control @error('name.en') is-invalid @enderror" dir="ltr">
            </x-admin.field>
          </div>
          <div class="col-md-6">
            <x-admin.field name="description.ar" :label="__('admin.fields.description_ar')" :hint="__('admin.hints.description_ar')" icon="mdi-text-box-outline" :preview="$description['ar']">
              <textarea rows="3" wire:model.live.debounce.300ms="description.ar" class="form-control @error('description.ar') is-invalid @enderror" dir="rtl"></textarea>
            </x-admin.field>
          </div>
          <div class="col-md-6">
            <x-admin.field name="description.en" :label="__('admin.fields.description_en')" :hint="__('admin.hints.description_en')" icon="mdi-text-box-outline" :preview="$description['en']">
              <textarea rows="3" wire:model.live.debounce.300ms="description.en" class="form-control @error('description.en') is-invalid @enderror" dir="ltr"></textarea>
            </x-admin.field>
          </div>
          <div class="col-md-6">
            <x-admin.field name="slug" :label="__('admin.fields.slug')" :hint="__('admin.hints.slug')" icon="mdi-link-variant" :preview="$slug" :required="true">
              <input type="text" wire:model.live.debounce.300ms="slug" class="form-control @error('slug') is-invalid @enderror" dir="ltr">
            </x-admin.field>
          </div>
          <div class="col-md-6">
            <x-admin.field name="icon" :label="__('admin.fields.icon')" :hint="__('admin.hints.icon')" icon="mdi-emoticon-outline" :preview="$icon" :previewicon="$icon" :required="true">
              <input type="text" wire:model.live.debounce.300ms="icon" class="form-control @error('icon') is-invalid @enderror" dir="ltr" placeholder="mdi-code-tags">
            </x-admin.field>
          </div>
          <div class="col-md-6">
            <x-admin.field name="sort_order" :label="__('admin.fields.sort_order')" :hint="__('admin.hints.sort_order')" icon="mdi-sort-numeric-ascending" :preview="$sort_order" :required="true">
              <input type="number" min="0" wire:model.live.debounce.300ms="sort_order" class="form-control @error('sort_order') is-invalid @enderror" dir="ltr">
            </x-admin.field>
          </div>
          <div class="col-md-6">
            <x-admin.field name="is_active" :label="__('admin.fields.status')" :hint="__('admin.hints.status')" icon="mdi-toggle-switch-outline" :preview="$is_active ? __('admin.active') : __('admin.inactive')" :previewicon="$is_active ? 'mdi-check-circle-outline' : 'mdi-close-circle-outline'">
              <div class="form-check form-switch"><input class="form-check-input" type="checkbox" role="switch" wire:model.live="is_active" id="cat-active"><label class="form-check-label" for="cat-active">{{ $is_active ? __('admin.active') : __('admin.inactive') }}</label></div>
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
        <div class="col-md-5"><div class="input-group"><span class="input-group-text bg-white"><i class="mdi mdi-magnify"></i></span><input type="search" class="form-control" wire:model.live.debounce.300ms="search" placeholder="{{ __('admin.search_placeholder') }}"></div></div>
        <div class="col-md-3"><select class="form-select" wire:model.live="status"><option value="">{{ __('admin.all_statuses') }}</option><option value="active">{{ __('admin.active') }}</option><option value="inactive">{{ __('admin.inactive') }}</option></select></div>
        <div class="col-md-3"><select class="form-select" wire:model.live="trashed"><option value="">{{ __('admin.not_deleted') }}</option><option value="only">{{ __('admin.deleted_only') }}</option></select></div>
      </div>

      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
          <thead><tr><th>#</th><th>{{ __('admin.fields.name') }}</th><th>{{ __('admin.fields.slug') }}</th><th>{{ __('admin.courses_count') }}</th><th>{{ __('admin.fields.sort_order') }}</th><th>{{ __('admin.fields.status') }}</th><th>{{ __('admin.actions') }}</th></tr></thead>
          <tbody>
            @forelse($rows as $r)
              <tr wire:key="cat-{{ $r->id }}">
                <td>{{ $r->id }}</td>
                <td><i class="mdi {{ $r->icon }} text-gold fs-5"></i> <span class="fw-semibold">{{ $r->tr('name') }}</span></td>
                <td dir="ltr" class="text-muted">{{ $r->slug }}</td>
                <td><span class="badge text-bg-light">{{ $r->courses_count }}</span></td>
                <td>{{ $r->sort_order }}</td>
                <td>@if($r->trashed())<span class="badge text-bg-secondary">{{ __('admin.deleted') }}</span>@else<span class="badge {{ $r->is_active ? 'text-bg-success' : 'text-bg-secondary' }}">{{ $r->is_active ? __('admin.active') : __('admin.inactive') }}</span>@endif</td>
                <td class="text-nowrap">
                  @if($r->trashed())
                    @can('categories.update')<button type="button" class="btn btn-sm btn-outline-success" wire:click="restore({{ $r->id }})"><i class="mdi mdi-restore"></i> {{ __('admin.restore') }}</button>@endcan
                  @else
                    @can('categories.update')
                      <button type="button" class="btn btn-sm btn-outline-dark" wire:click="edit({{ $r->id }})" title="{{ __('admin.edit') }}"><i class="mdi mdi-pencil-outline"></i></button>
                      <button type="button" class="btn btn-sm btn-outline-warning" wire:click="toggleStatus({{ $r->id }})" title="{{ __('admin.toggle_status') }}"><i class="mdi mdi-swap-horizontal"></i></button>
                    @endcan
                    @can('categories.delete')<button type="button" class="btn btn-sm btn-outline-danger" onclick="confirmDelete({{ $r->id }})" title="{{ __('admin.delete') }}"><i class="mdi mdi-trash-can-outline"></i></button>@endcan
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
