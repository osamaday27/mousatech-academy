<?php

namespace App\Livewire;

use App\Livewire\Concerns\AddsToCart;
use App\Models\Category;
use App\Models\Course;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class CourseIndex extends Component
{
    use AddsToCart, WithPagination;

    #[Url(as: 'q')]
    public string $q = '';
    #[Url]
    public string $cat = '';
    #[Url]
    public string $level = '';
    #[Url]
    public string $sort = 'latest';

    public function updated(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $s = $this->q;
        $courses = Course::live()->with('category')
            ->when($s !== '', fn ($b) => $b->where(fn ($w) => $w
                ->where('title_ar', 'like', "%{$s}%")
                ->orWhere('title_en', 'like', "%{$s}%")
                ->orWhere('instructor_name', 'like', "%{$s}%")))
            ->when($this->cat !== '', fn ($b) => $b->whereHas('category', fn ($c) => $c->where('slug', $this->cat)))
            ->when($this->level !== '', fn ($b) => $b->where('level', $this->level));

        match ($this->sort) {
            'price_asc' => $courses->orderByRaw('COALESCE(sale_price, price) asc'),
            'price_desc' => $courses->orderByRaw('COALESCE(sale_price, price) desc'),
            'popular' => $courses->withCount('enrollments')->orderByDesc('enrollments_count'),
            default => $courses->latest('id'),
        };

        return view('livewire.course-index', [
            'courses' => $courses->paginate(12),
            'categories' => Category::where('is_active', true)->orderBy('sort_order')->get(),
        ]);
    }
}
