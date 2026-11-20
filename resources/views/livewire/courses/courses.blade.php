<div>
  <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
    <div>
      <h4 class="mb-1"><i class="mdi mdi-school-outline text-gold"></i> {{ __('admin.courses.title') }}</h4>
      <p class="text-muted mb-0">{{ __('admin.courses.subtitle') }}</p>
    </div>
    @can('courses.create')<button type="button" class="btn btn-gold" wire:click="create"><i class="mdi mdi-plus"></i> {{ __('admin.add_new') }}</button>@endcan
  </div>

  @if($showForm)
    <form wire:submit="save" class="card mb-4">
      <div class="card-header bg-white fw-bold py-3"><i class="mdi mdi-pencil-outline text-gold"></i> {{ $editId ? __('admin.edit') : __('admin.add_new') }}</div>
      <div class="card-body">
        <div class="alert alert-warning d-flex gap-2"><i class="mdi mdi-alert-outline fs-5"></i><div>{{ __('admin.courses.warning') }}</div></div>
        <div class="row">
          <div class="col-md-6">
            <x-admin.field name="title.ar" :label="__('admin.fields.title_ar')" :hint="__('admin.hints.title_ar')" icon="mdi-translate" :preview="$title['ar']" :required="true">
              <input type="text" wire:model.live.debounce.300ms="title.ar" class="form-control @error('title.ar') is-invalid @enderror" dir="rtl">
            </x-admin.field>
          </div>
          <div class="col-md-6">
            <x-admin.field name="title.en" :label="__('admin.fields.title_en')" :hint="__('admin.hints.title_en')" icon="mdi-alphabetical" :preview="$title['en']" :required="true">
              <input type="text" wire:model.live.debounce.300ms="title.en" class="form-control @error('title.en') is-invalid @enderror" dir="ltr">
            </x-admin.field>
          </div>
          <div class="col-md-6">
            <x-admin.field name="description.ar" :label="__('admin.fields.description_ar')" :hint="__('admin.hints.course_description')" icon="mdi-text-box-outline" :preview="\Illuminate\Support\Str::limit($description['ar'], 140)" :required="true">
              <textarea rows="4" wire:model.live.debounce.400ms="description.ar" class="form-control @error('description.ar') is-invalid @enderror" dir="rtl"></textarea>
            </x-admin.field>
          </div>
          <div class="col-md-6">
            <x-admin.field name="description.en" :label="__('admin.fields.description_en')" :hint="__('admin.hints.course_description')" icon="mdi-text-box-outline" :preview="\Illuminate\Support\Str::limit($description['en'], 140)" :required="true">
              <textarea rows="4" wire:model.live.debounce.400ms="description.en" class="form-control @error('description.en') is-invalid @enderror" dir="ltr"></textarea>
            </x-admin.field>
          </div>
          <div class="col-md-6">
            <x-admin.field name="slug" :label="__('admin.fields.slug')" :hint="__('admin.hints.slug')" icon="mdi-link-variant" :preview="$slug ? url('/courses/'.$slug) : ''" :required="true">
              <input type="text" wire:model.live.debounce.300ms="slug" class="form-control @error('slug') is-invalid @enderror" dir="ltr">
            </x-admin.field>
          </div>
          <div class="col-md-6">
            <x-admin.field name="category_id" :label="__('admin.fields.category')" :hint="__('admin.hints.category')" icon="mdi-shape-outline" :preview="optional($categories->firstWhere('id', (int) $category_id))->tr('name')" :required="true">
              <select wire:model.live="category_id" class="form-select @error('category_id') is-invalid @enderror"><option value="">{{ __('admin.choose') }}</option>@foreach($categories as $cat)<option value="{{ $cat->id }}">{{ $cat->tr('name') }}</option>@endforeach</select>
            </x-admin.field>
          </div>
          <div class="col-md-6">
            <x-admin.field name="instructor_name" :label="__('admin.fields.instructor')" :hint="__('admin.hints.instructor')" icon="mdi-account-tie-outline" :preview="$instructor_name" :required="true">
              <input type="text" wire:model.live.debounce.300ms="instructor_name" class="form-control @error('instructor_name') is-invalid @enderror">
            </x-admin.field>
          </div>
          <div class="col-md-6">
            <x-admin.field name="level" :label="__('admin.fields.level')" :hint="__('admin.hints.level')" icon="mdi-stairs" :preview="__(ucfirst($level))" :required="true">
              <select wire:model.live="level" class="form-select @error('level') is-invalid @enderror"><option value="beginner">{{ __('Beginner') }}</option><option value="intermediate">{{ __('Intermediate') }}</option><option value="advanced">{{ __('Advanced') }}</option></select>
            </x-admin.field>
          </div>
          <div class="col-md-4">
            <x-admin.field name="price" :label="__('admin.fields.price')" :hint="__('admin.hints.price')" icon="mdi-cash" :preview="is_numeric($price) ? \App\Support\Money::fmt((int) round($price * 100)) : ''" :required="true">
              <input type="number" step="0.01" min="0" wire:model.live.debounce.300ms="price" class="form-control @error('price') is-invalid @enderror" dir="ltr">
            </x-admin.field>
          </div>
          <div class="col-md-4">
            <x-admin.field name="sale_price" :label="__('admin.fields.sale_price')" :hint="__('admin.hints.sale_price')" icon="mdi-tag-outline" :preview="is_numeric($sale_price) ? \App\Support\Money::fmt((int) round($sale_price * 100)) : ''">
              <input type="number" step="0.01" min="0" wire:model.live.debounce.300ms="sale_price" class="form-control @error('sale_price') is-invalid @enderror" dir="ltr">
            </x-admin.field>
          </div>
          <div class="col-md-4">
            <x-admin.field name="duration_minutes" :label="__('admin.fields.duration')" :hint="__('admin.hints.duration')" icon="mdi-clock-outline" :preview="is_numeric($duration_minutes) ? intdiv((int) $duration_minutes, 60).__('h').' '.((int) $duration_minutes % 60).__('m') : ''" :required="true">
              <input type="number" min="0" wire:model.live.debounce.300ms="duration_minutes" class="form-control @error('duration_minutes') is-invalid @enderror" dir="ltr">
            </x-admin.field>
          </div>
          <div class="col-md-6">
            <x-admin.field name="image" :label="__('admin.fields.image')" :hint="__('admin.hints.image')" icon="mdi-image-outline" :preview="$image ? $image->getClientOriginalName() : ($currentImage ? basename($currentImage) : '')" previewicon="mdi-file-image-outline">
              <input type="file" wire:model="image" accept="image/png,image/jpeg,image/webp" class="form-control @error('image') is-invalid @enderror">
              <div wire:loading wire:target="image" class="small text-muted mt-1"><i class="mdi mdi-loading mdi-spin"></i> {{ __('admin.uploading') }}</div>
              @if($image)<img src="{{ $image->temporaryUrl() }}" alt="" class="img-thumbnail mt-2" style="max-height:120px">@elseif($currentImage)<img src="{{ $currentImage }}" alt="" class="img-thumbnail mt-2" style="max-height:120px">@endif
            </x-admin.field>
          </div>
          <div class="col-md-6">
            <x-admin.field name="hover_image" :label="__('admin.fields.hover_image')" :hint="__('admin.hints.hover_image')" icon="mdi-image-multiple-outline" :preview="$hover_image ? $hover_image->getClientOriginalName() : ($currentHover ? basename($currentHover) : '')" previewicon="mdi-file-image-outline">
              <input type="file" wire:model="hover_image" accept="image/png,image/jpeg,image/webp" class="form-control @error('hover_image') is-invalid @enderror">
              <div wire:loading wire:target="hover_image" class="small text-muted mt-1"><i class="mdi mdi-loading mdi-spin"></i> {{ __('admin.uploading') }}</div>
              @if($hover_image)<img src="{{ $hover_image->temporaryUrl() }}" alt="" class="img-thumbnail mt-2" style="max-height:120px">@elseif($currentHover)<img src="{{ $currentHover }}" alt="" class="img-thumbnail mt-2" style="max-height:120px">@endif
            </x-admin.field>
          </div>
          <div class="col-md-6">
            <x-admin.field name="course_status" :label="__('admin.fields.status')" :hint="__('admin.hints.publish')" icon="mdi-publish" :preview="$course_status === 'published' ? __('admin.published') : __('admin.draft')" :previewicon="$course_status === 'published' ? 'mdi-check-circle-outline' : 'mdi-file-clock-outline'" :required="true">
              <select wire:model.live="course_status" class="form-select @error('course_status') is-invalid @enderror"><option value="draft">{{ __('admin.draft') }}</option><option value="published">{{ __('admin.published') }}</option></select>
            </x-admin.field>
          </div>
          <div class="col-md-6">
            <x-admin.field name="is_featured" :label="__('admin.fields.featured')" :hint="__('admin.hints.featured')" icon="mdi-star-outline" :preview="$is_featured ? __('admin.yes') : __('admin.no')" :previewicon="$is_featured ? 'mdi-star' : 'mdi-star-off-outline'">
              <div class="form-check form-switch"><input class="form-check-input" type="checkbox" role="switch" wire:model.live="is_featured" id="c-feat"><label class="form-check-label" for="c-feat">{{ $is_featured ? __('admin.yes') : __('admin.no') }}</label></div>
            </x-admin.field>
          </div>
        </div>
      </div>
      <div class="card-footer bg-white d-flex gap-2 py-3">
        <button class="btn btn-gold" wire:loading.attr="disabled" wire:target="save,image,hover_image"><i class="mdi mdi-content-save-outline"></i> {{ __('admin.save') }}</button>
        <button type="button" class="btn btn-outline-secondary" wire:click="cancel">{{ __('admin.cancel') }}</button>
      </div>
    </form>
  @endif

  <div class="card">
    <div class="card-body">
      <div class="row g-2 mb-3">
        <div class="col-md-4"><div class="input-group"><span class="input-group-text bg-white"><i class="mdi mdi-magnify"></i></span><input type="search" class="form-control" wire:model.live.debounce.300ms="search" placeholder="{{ __('admin.search_placeholder') }}"></div></div>
        <div class="col-md-3"><select class="form-select" wire:model.live="categoryFilter"><option value="">{{ __('admin.all_categories') }}</option>@foreach($categories as $cat)<option value="{{ $cat->id }}">{{ $cat->tr('name') }}</option>@endforeach</select></div>
        <div class="col-md-2"><select class="form-select" wire:model.live="status"><option value="">{{ __('admin.all_statuses') }}</option><option value="published">{{ __('admin.published') }}</option><option value="draft">{{ __('admin.draft') }}</option></select></div>
        <div class="col-md-3"><select class="form-select" wire:model.live="trashed"><option value="">{{ __('admin.not_deleted') }}</option><option value="only">{{ __('admin.deleted_only') }}</option></select></div>
      </div>

      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
          <thead><tr><th>#</th><th>{{ __('admin.fields.course') }}</th><th>{{ __('admin.fields.category') }}</th><th>{{ __('admin.fields.price') }}</th><th>{{ __('admin.students') }}</th><th>{{ __('admin.fields.status') }}</th><th>{{ __('admin.actions') }}</th></tr></thead>
          <tbody>
            @forelse($rows as $r)
              <tr wire:key="crs-{{ $r->id }}">
                <td>{{ $r->id }}</td>
                <td><div class="d-flex align-items-center gap-2"><img src="{{ $r->imageUrl() }}" alt="" width="64" height="40" class="rounded object-fit-cover"><div><div class="fw-semibold">{{ $r->tr('title') }}</div><small class="text-muted">{{ $r->instructor_name }} @if($r->is_featured)<i class="mdi mdi-star text-warning"></i>@endif</small></div></div></td>
                <td>{{ $r->category?->tr('name') }}</td>
                <td>{{ \App\Support\Money::fmt($r->currentPrice()) }} @if($r->hasDiscount())<small class="text-muted text-decoration-line-through">{{ \App\Support\Money::fmt($r->price) }}</small>@endif</td>
                <td>{{ $r->enrollments_count }}</td>
                <td>@if($r->trashed())<span class="badge text-bg-secondary">{{ __('admin.deleted') }}</span>@else<span class="badge {{ $r->status === 'published' ? 'text-bg-success' : 'text-bg-secondary' }}">{{ $r->status === 'published' ? __('admin.published') : __('admin.draft') }}</span>@endif</td>
                <td class="text-nowrap">
                  @if($r->trashed())
                    @can('courses.update')<button type="button" class="btn btn-sm btn-outline-success" wire:click="restore({{ $r->id }})"><i class="mdi mdi-restore"></i> {{ __('admin.restore') }}</button>@endcan
                  @else
                    @can('courses.update')
                      <button type="button" class="btn btn-sm btn-outline-dark" wire:click="edit({{ $r->id }})" title="{{ __('admin.edit') }}"><i class="mdi mdi-pencil-outline"></i></button>
                      <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.curriculum', $r) }}" title="{{ __('admin.curriculum.title') }}"><i class="mdi mdi-format-list-bulleted"></i></a>
                      <button type="button" class="btn btn-sm btn-outline-warning" wire:click="toggleStatus({{ $r->id }})" title="{{ __('admin.toggle_status') }}"><i class="mdi mdi-swap-horizontal"></i></button>
                      <button type="button" class="btn btn-sm btn-outline-secondary" wire:click="toggleFeatured({{ $r->id }})" title="{{ __('admin.fields.featured') }}"><i class="mdi mdi-star-outline"></i></button>
                    @endcan
                    @can('courses.delete')<button type="button" class="btn btn-sm btn-outline-danger" onclick="confirmDelete({{ $r->id }})" title="{{ __('admin.delete') }}"><i class="mdi mdi-trash-can-outline"></i></button>@endcan
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
