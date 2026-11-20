<div class="mx-auto max-w-7xl px-4 py-10">
  <div class="flex items-center justify-between">
    <h1 class="text-3xl font-bold">{{ __('Courses') }}</h1>
    @can('courses.create')<button type="button" wire:click="create" class="btn btn-gold">{{ __('New course') }}</button>@endcan
  </div>
  @error('delete')<div class="mt-3 border border-red-300 bg-red-50 p-3 text-sm text-red-700">{{ $message }}</div>@enderror

  @if($showForm)
    <form wire:submit="save" class="mt-6 grid gap-4 border border-silver p-5 md:grid-cols-2">
      <div><label class="label">{{ __('Title (Arabic)') }}</label><input wire:model="title_ar" class="field">@error('title_ar')<span class="err">{{ $message }}</span>@enderror</div>
      <div><label class="label">{{ __('Title (English)') }}</label><input wire:model="title_en" class="field" dir="ltr">@error('title_en')<span class="err">{{ $message }}</span>@enderror</div>
      <div><label class="label">Slug</label><input wire:model="slug" class="field" dir="ltr">@error('slug')<span class="err">{{ $message }}</span>@enderror</div>
      <div><label class="label">{{ __('Category') }}</label>
        <select wire:model="category_id" class="field"><option value="">—</option>@foreach($categories as $c)<option value="{{ $c->id }}">{{ $c->tr('name') }}</option>@endforeach</select>@error('category_id')<span class="err">{{ $message }}</span>@enderror</div>
      <div><label class="label">{{ __('Description (Arabic)') }}</label><textarea wire:model="description_ar" rows="4" class="field"></textarea>@error('description_ar')<span class="err">{{ $message }}</span>@enderror</div>
      <div><label class="label">{{ __('Description (English)') }}</label><textarea wire:model="description_en" rows="4" class="field" dir="ltr"></textarea>@error('description_en')<span class="err">{{ $message }}</span>@enderror</div>
      <div><label class="label">{{ __('Instructor') }}</label><input wire:model="instructor_name" class="field">@error('instructor_name')<span class="err">{{ $message }}</span>@enderror</div>
      <div><label class="label">{{ __('Level') }}</label><select wire:model="level" class="field"><option value="beginner">{{ __('Beginner') }}</option><option value="intermediate">{{ __('Intermediate') }}</option><option value="advanced">{{ __('Advanced') }}</option></select></div>
      <div><label class="label">{{ __('Price (EGP)') }}</label><input type="number" step="0.01" min="0" wire:model="price" class="field" dir="ltr">@error('price')<span class="err">{{ $message }}</span>@enderror</div>
      <div><label class="label">{{ __('Sale price (EGP)') }}</label><input type="number" step="0.01" min="0" wire:model="sale_price" class="field" dir="ltr">@error('sale_price')<span class="err">{{ $message }}</span>@enderror</div>
      <div><label class="label">{{ __('Duration (minutes)') }}</label><input type="number" min="0" wire:model="duration_minutes" class="field" dir="ltr">@error('duration_minutes')<span class="err">{{ $message }}</span>@enderror</div>
      <div><label class="label">{{ __('Status') }}</label><select wire:model="status" class="field"><option value="draft">{{ __('Draft') }}</option><option value="published">{{ __('Published') }}</option></select></div>
      <div><label class="label">{{ __('Main image') }}</label><input type="file" wire:model="image" accept="image/*" class="field">@error('image')<span class="err">{{ $message }}</span>@enderror</div>
      <div><label class="label">{{ __('Hover image') }}</label><input type="file" wire:model="hover_image" accept="image/*" class="field">@error('hover_image')<span class="err">{{ $message }}</span>@enderror</div>
      <label class="flex items-center gap-2 text-sm"><input type="checkbox" wire:model="is_featured"> {{ __('Featured') }}</label>
      <div class="flex gap-3 md:col-span-2">
        <button class="btn btn-gold" wire:loading.attr="disabled">{{ __('Save') }}</button>
        <button type="button" wire:click="$set('showForm', false)" class="btn btn-line">{{ __('Cancel') }}</button>
      </div>
    </form>
  @endif

  <div class="mt-6 overflow-x-auto border border-silver">
    <table class="w-full min-w-[640px] text-sm">
      <thead class="bg-ink text-white"><tr>@foreach(['Course', 'Price', 'Status', ''] as $h)<th class="p-3 text-start font-medium">{{ __($h) }}</th>@endforeach</tr></thead>
      <tbody class="divide-y divide-silver">
        @foreach($list as $c)
          <tr wire:key="ac-{{ $c->id }}">
            <td class="p-3 font-semibold">{{ $c->tr('title') }}<div class="text-xs font-normal text-steel">{{ $c->category?->tr('name') }}</div></td>
            <td class="p-3">{{ \App\Support\Money::fmt($c->currentPrice()) }}</td>
            <td class="p-3">{{ __(ucfirst($c->status)) }}</td>
            <td class="space-x-3 p-3 text-end rtl:space-x-reverse">
              <button type="button" wire:click="edit({{ $c->id }})" class="underline">{{ __('Edit') }}</button>
              <a href="{{ route('admin.curriculum', $c) }}" class="underline">{{ __('Curriculum') }}</a>
              @can('courses.delete')<button type="button" wire:click="delete({{ $c->id }})" wire:confirm="{{ __('Delete this course?') }}" class="text-red-600 underline">{{ __('Delete') }}</button>@endcan
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
  <div class="mt-6">{{ $list->links() }}</div>
</div>
