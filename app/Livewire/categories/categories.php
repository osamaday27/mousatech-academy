<?php

namespace App\Livewire\categories;

use App\Livewire\Concerns\NeedsPermission;
use App\Livewire\crud\crud;
use App\Models\Category;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.admin')]
class categories extends Component
{
    use crud, NeedsPermission;

    public string $status = '';
    public array $name = ['ar' => '', 'en' => ''];
    public array $description = ['ar' => '', 'en' => ''];
    public string $slug = '';
    public string $icon = 'mdi-code-tags';
    public $sort_order = 0;
    public bool $is_active = true;

    protected function modelclass(): string
    {
        return Category::class;
    }

    protected function perm(): string
    {
        return 'categories';
    }

    public function updatingStatus(): void
    {
        $this->resetPage();
    }

    protected function rules(): array
    {
        return [
            'name.ar' => 'required|string|max:120',
            'name.en' => 'required|string|max:120',
            'description.ar' => 'nullable|string|max:500',
            'description.en' => 'nullable|string|max:500',
            'slug' => ['required', 'alpha_dash:ascii', 'max:150', Rule::unique('categories', 'slug')->ignore($this->editId)],
            'icon' => ['required', 'string', 'max:60', 'regex:/^mdi-[a-z0-9-]+$/'],
            'sort_order' => 'required|integer|min:0|max:9999',
            'is_active' => 'boolean',
        ];
    }

    protected function validationAttributes(): array
    {
        return [
            'name.ar' => __('admin.fields.name_ar'),
            'name.en' => __('admin.fields.name_en'),
            'description.ar' => __('admin.fields.description_ar'),
            'description.en' => __('admin.fields.description_en'),
            'slug' => __('admin.fields.slug'),
            'icon' => __('admin.fields.icon'),
            'sort_order' => __('admin.fields.sort_order'),
            'is_active' => __('admin.fields.status'),
        ];
    }

    protected function resetForm(): void
    {
        $this->editId = null;
        $this->name = ['ar' => '', 'en' => ''];
        $this->description = ['ar' => '', 'en' => ''];
        $this->slug = '';
        $this->icon = 'mdi-code-tags';
        $this->sort_order = 0;
        $this->is_active = true;
        $this->resetValidation();
    }

    public function create(): void
    {
        $this->need('categories.create');
        $this->resetForm();
        $this->showForm = true;
    }

    public function edit(int $id): void
    {
        $this->need('categories.update');
        $c = Category::findOrFail($id);
        $this->resetForm();
        $this->editId = $c->id;
        $this->name = ['ar' => $c->getTranslation('name', 'ar', false), 'en' => $c->getTranslation('name', 'en', false)];
        $this->description = ['ar' => (string) $c->getTranslation('description', 'ar', false), 'en' => (string) $c->getTranslation('description', 'en', false)];
        $this->slug = $c->slug;
        $this->icon = $c->icon ?: 'mdi-code-tags';
        $this->sort_order = $c->sort_order;
        $this->is_active = $c->is_active;
        $this->showForm = true;
    }

    public function save(): void
    {
        $this->need($this->editId ? 'categories.update' : 'categories.create');
        $d = $this->validate();
        $c = $this->editId ? Category::findOrFail($this->editId) : new Category();
        $c->fill([
            'name' => $d['name'],
            'description' => array_filter($d['description'] ?? []),
            'slug' => $d['slug'],
            'icon' => $d['icon'],
            'sort_order' => (int) $d['sort_order'],
            'is_active' => (bool) $d['is_active'],
        ])->save();

        $this->showForm = false;
        $this->resetForm();
        $this->toast(__('admin.saved_ok'));
    }

    public function toggleStatus(int $id): void
    {
        $this->need('categories.update');
        $c = Category::findOrFail($id);
        $c->update(['is_active' => ! $c->is_active]);
        $this->toast(__('admin.status_changed'));
    }

    public function render()
    {
        $s = $this->search;
        $rows = Category::query()->withCount('courses')
            ->when($this->trashed === 'only', fn ($q) => $q->onlyTrashed())
            ->when($s !== '', fn ($q) => $q->where(fn ($w) => $w
                ->where('name->ar', 'like', "%{$s}%")
                ->orWhere('name->en', 'like', "%{$s}%")
                ->orWhere('slug', 'like', "%{$s}%")))
            ->when($this->status !== '', fn ($q) => $q->where('is_active', $this->status === 'active'))
            ->orderBy('sort_order')->orderBy('id')
            ->paginate($this->perPage);

        return view('livewire.categories.categories', ['rows' => $rows]);
    }
}
