<?php

namespace App\Livewire;

use App\Livewire\Concerns\AddsToCart;
use App\Models\Category;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Post;
use App\Models\User;
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
            'discover' => $base()->latest('id')->take(12)->get(),
            'posts' => Post::with('user')->withCount(['likes', 'comments'])->latest('id')->take(3)->get(),
            'stats' => [
                'courses' => Course::live()->count(),
                'students' => Enrollment::query()->distinct()->count('user_id'),
                'members' => User::count(),
                'posts' => Post::count(),
            ],
        ]);
    }
}
