<?php

namespace App\Livewire\Admin;

use App\Livewire\Concerns\NeedsPermission;
use App\Models\Category;
use App\Models\Course;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class Courses extends Component
{
    use NeedsPermission, WithFileUploads, WithPagination;

    public bool $showForm = false;
    public ?int $courseId = null;
    public string $title_ar = '';
    public string $title_en = '';
    public string $slug = '';
    public string $description_ar = '';
    public string $description_en = '';
    public $category_id = '';
    public string $instructor_name = '';
    public $price = 0;
    public $sale_price = null;
    public string $level = 'beginner';
    public $duration_minutes = 0;
    public string $status = 'draft';
    public bool $is_featured = false;
    public $image = null;
    public $hover_image = null;

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
        $this->courseId = $c->id;
        foreach (['title_ar', 'title_en', 'slug', 'description_ar', 'description_en', 'category_id', 'instructor_name', 'level', 'duration_minutes', 'status', 'is_featured'] as $f) {
            $this->$f = $c->$f;
        }
        $this->price = $c->price / 100;
        $this->sale_price = $c->sale_price !== null ? $c->sale_price / 100 : null;
        $this->showForm = true;
    }

    protected function resetForm(): void
    {
        $this->reset(['courseId', 'title_ar', 'title_en', 'slug', 'description_ar', 'description_en', 'category_id', 'instructor_name', 'price', 'sale_price', 'level', 'duration_minutes', 'status', 'is_featured', 'image', 'hover_image']);
        $this->resetValidation();
    }

    public function save(): void
    {
        $this->need($this->courseId ? 'courses.update' : 'courses.create');
        $d = $this->validate([
            'title_ar' => 'required|string|max:190',
            'title_en' => 'required|string|max:190',
            'slug' => ['required', 'alpha_dash', 'max:190', Rule::unique('courses', 'slug')->ignore($this->courseId)],
            'description_ar' => 'required|string',
            'description_en' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'instructor_name' => 'required|string|max:120',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0|lte:price',
            'level' => 'required|in:beginner,intermediate,advanced',
            'duration_minutes' => 'required|integer|min:0',
            'status' => 'required|in:draft,published',
            'is_featured' => 'boolean',
            'image' => 'nullable|mimes:jpg,jpeg,png,webp|max:4096',
            'hover_image' => 'nullable|mimes:jpg,jpeg,png,webp|max:4096',
        ]);

        $data = collect($d)->except(['image', 'hover_image', 'price', 'sale_price'])->all();
        $data['price'] = (int) round($d['price'] * 100);
        $data['sale_price'] = ($d['sale_price'] ?? '') === '' || $d['sale_price'] === null ? null : (int) round($d['sale_price'] * 100);

        $course = $this->courseId ? Course::findOrFail($this->courseId) : new Course();
        $course->fill($data);
        if ($course->status === 'published' && ! $course->published_at) {
            $course->published_at = now();
        }
        if ($this->image) {
            $course->image = $this->image->store('courses', 'public');
        }
        if ($this->hover_image) {
            $course->hover_image = $this->hover_image->store('courses', 'public');
        }
        $course->save();

        $this->showForm = false;
        $this->resetForm();
    }

    public function delete(int $id): void
    {
        $this->need('courses.delete');
        $c = Course::findOrFail($id);
        if ($c->enrollments()->exists() || $c->hasMany(\App\Models\OrderItem::class)->exists()) {
            $this->addError('delete', __('This course has students or orders. Set it to draft instead.'));

            return;
        }
        $c->delete();
    }

    public function render()
    {
        $this->need('courses.update');

        return view('livewire.admin.courses', [
            'list' => Course::with('category')->latest('id')->paginate(10),
            'categories' => Category::orderBy('sort_order')->get(),
        ]);
    }
}
