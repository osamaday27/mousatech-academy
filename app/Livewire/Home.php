<?php

namespace App\Livewire;

use App\Livewire\Concerns\AddsToCart;
use App\Models\Category;
use App\Models\Course;
use Livewire\Component;

class Home extends Component
{
    use AddsToCart;

    public function render()
    {
        $base = fn () => Course::live()->with('category');

        return view('livewire.home', [
            'categories' => Category::where('is_active', true)->orderBy('sort_order')
                ->withCount(['courses' => fn ($q) => $q->live()])->get(),
            'featured' => $base()->where('is_featured', true)->latest('id')->take(4)->get(),
            'best' => $base()->withCount('enrollments')->orderByDesc('enrollments_count')->take(4)->get(),
            'latest' => $base()->latest('id')->take(4)->get(),
        ]);
    }
}
