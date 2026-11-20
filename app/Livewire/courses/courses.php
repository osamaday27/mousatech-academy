<?php

namespace App\Livewire\courses;

use App\Livewire\Concerns\NeedsPermission;
use App\Livewire\crud\crud;
use App\Models\Category;
use App\Models\Course;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('components.layouts.admin')]
class courses extends Component
{
    use crud, NeedsPermission, WithFileUploads;

    public string $status = '';
    public string $categoryFilter = '';
    public array $title = ['ar' => '', 'en' => ''];
    public array $description = ['ar' => '', 'en' => ''];
    public string $slug = '';
    public $category_id = '';
    public string $instructor_name = '';
    public $price = 0;
    public $sale_price = null;
    public string $level = 'beginner';
    public $duration_minutes = 0;
    public string $course_status = 'draft';
    public bool $is_featured = false;
    public $image = null;
    public $hover_image = null;
    public ?string $currentImage = null;
    public ?string $currentHover = null;

    protected function modelclass(): string
    {
        return Course::class;
    }

    protected function perm(): string
    {
        return 'courses';
    }

    public function updatingStatus(): void
    {
        $this->resetPage();
    }

    public function updatingCategoryFilter(): void
    {
        $this->resetPage();
    }

    protected function rules(): array
    {
        return [
            'title.ar' => 'required|string|max:190',
            'title.en' => 'required|string|max:190',
            'description.ar' => 'required|string|max:5000',
            'description.en' => 'required|string|max:5000',
            'slug' => ['required', 'alpha_dash:ascii', 'max:190', Rule::unique('courses', 'slug')->ignore($this->editId)],
            'category_id' => 'required|exists:categories,id',
            'instructor_name' => 'required|string|max:120',
            'price' => 'required|numeric|min:0|max:1000000',
            'sale_price' => 'nullable|numeric|min:0|lte:price',
            'level' => 'required|in:beginner,intermediate,advanced',
            'duration_minutes' => 'required|integer|min:0|max:100000',
            'course_status' => 'required|in:draft,published',
            'is_featured' => 'boolean',
            'image' => 'nullable|mimes:jpg,jpeg,png,webp|max:4096',
            'hover_image' => 'nullable|mimes:jpg,jpeg,png,webp|max:4096',
        ];
    }

    protected function validationAttributes(): array
    {
        return [
            'title.ar' => __('admin.fields.title_ar'),
            'title.en' => __('admin.fields.title_en'),
            'description.ar' => __('admin.fields.description_ar'),
            'description.en' => __('admin.fields.description_en'),
            'slug' => __('admin.fields.slug'),
            'category_id' => __('admin.fields.category'),
            'instructor_name' => __('admin.fields.instructor'),
            'price' => __('admin.fields.price'),
            'sale_price' => __('admin.fields.sale_price'),
            'level' => __('admin.fields.level'),
            'duration_minutes' => __('admin.fields.duration'),
            'course_status' => __('admin.fields.status'),
            'is_featured' => __('admin.fields.featured'),
            'image' => __('admin.fields.image'),
            'hover_image' => __('admin.fields.hover_image'),
        ];
    }

    protected function resetForm(): void
    {
        $this->editId = null;
        $this->title = ['ar' => '', 'en' => ''];
        $this->description = ['ar' => '', 'en' => ''];
        $this->slug = '';
        $this->category_id = '';
        $this->instructor_name = '';
        $this->price = 0;
        $this->sale_price = null;
        $this->level = 'beginner';
        $this->duration_minutes = 0;
        $this->course_status = 'draft';
        $this->is_featured = false;
        $this->image = null;
        $this->hover_image = null;
        $this->currentImage = null;
        $this->currentHover = null;
        $this->resetValidation();
    }

    public function create(): void
    {
        $this->need('courses.create');
        $this->resetForm();
        $this->showForm = true;
    }

    public function edit(int $id): void
    {
        $this->need('courses.update');
        $c = Course::findOrFail($id);
        $this->resetForm();
        $this->editId = $c->id;
        $this->title = ['ar' => $c->getTranslation('title', 'ar', false), 'en' => $c->getTranslation('title', 'en', false)];
        $this->description = ['ar' => $c->getTranslation('description', 'ar', false), 'en' => $c->getTranslation('description', 'en', false)];
        $this->slug = $c->slug;
        $this->category_id = $c->category_id;
        $this->instructor_name = $c->instructor_name;
        $this->price = $c->price / 100;
        $this->sale_price = $c->sale_price !== null ? $c->sale_price / 100 : null;
        $this->level = $c->level;
        $this->duration_minutes = $c->duration_minutes;
        $this->course_status = $c->status;
        $this->is_featured = $c->is_featured;
        $this->currentImage = $c->image ? $c->imageUrl() : null;
        $this->currentHover = $c->hover_image ? $c->hoverUrl() : null;
        $this->showForm = true;
    }

    public function save(): void
    {
        $this->need($this->editId ? 'courses.update' : 'courses.create');
        $d = $this->validate();

        $c = $this->editId ? Course::findOrFail($this->editId) : new Course();
        $c->fill([
            'title' => $d['title'],
            'description' => $d['description'],
            'slug' => $d['slug'],
            'category_id' => $d['category_id'],
            'instructor_name' => $d['instructor_name'],
            'price' => (int) round($d['price'] * 100),
            'sale_price' => ($d['sale_price'] ?? '') === '' ? null : (int) round($d['sale_price'] * 100),
            'level' => $d['level'],
            'duration_minutes' => (int) $d['duration_minutes'],
            'status' => $d['course_status'],
            'is_featured' => (bool) $d['is_featured'],
        ]);
        if ($c->status === 'published' && ! $c->published_at) {
            $c->published_at = now();
        }
        if ($this->image) {
            $this->dropFile($c->image);
            $c->image = $this->image->store('courses', 'public');
        }
        if ($this->hover_image) {
            $this->dropFile($c->hover_image);
            $c->hover_image = $this->hover_image->store('courses', 'public');
        }
        $c->save();

        $this->showForm = false;
        $this->resetForm();
        $this->toast(__('admin.saved_ok'));
    }

    protected function dropFile(?string $path): void
    {
        if ($path && ! str_starts_with($path, 'http')) {
            Storage::disk('public')->delete($path);
        }
    }

    public function toggleStatus(int $id): void
    {
        $this->need('courses.update');
        $c = Course::findOrFail($id);
        $c->status = $c->status === 'published' ? 'draft' : 'published';
        if ($c->status === 'published' && ! $c->published_at) {
            $c->published_at = now();
        }
        $c->save();
        $this->toast(__('admin.status_changed'));
    }

    public function toggleFeatured(int $id): void
    {
        $this->need('courses.update');
        $c = Course::findOrFail($id);
        $c->update(['is_featured' => ! $c->is_featured]);
        $this->toast(__('admin.status_changed'));
    }

    public function render()
    {
        $s = $this->search;
        $rows = Course::query()->with('category')->withCount('enrollments')
            ->when($this->trashed === 'only', fn ($q) => $q->onlyTrashed())
            ->when($s !== '', fn ($q) => $q->where(fn ($w) => $w
                ->where('title->ar', 'like', "%{$s}%")
                ->orWhere('title->en', 'like', "%{$s}%")
                ->orWhere('instructor_name', 'like', "%{$s}%")
                ->orWhere('slug', 'like', "%{$s}%")))
            ->when($this->status !== '', fn ($q) => $q->where('status', $this->status))
            ->when($this->categoryFilter !== '', fn ($q) => $q->where('category_id', $this->categoryFilter))
            ->latest('id')
            ->paginate($this->perPage);

        return view('livewire.courses.courses', [
            'rows' => $rows,
            'categories' => Category::orderBy('sort_order')->get(),
        ]);
    }
}
