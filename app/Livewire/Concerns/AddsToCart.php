<?php

namespace App\Livewire\Concerns;

use App\Models\Course;
use App\Services\CartService;

trait AddsToCart
{
    public function add(int $id): void
    {
        $course = Course::live()->findOrFail($id);
        if (auth()->check() && auth()->user()->owns($course)) {
            $this->dispatch('toast', message: __('You already own this course'));

            return;
        }
        app(CartService::class)->add($course->id);
        $this->dispatch('cart-updated');
        $this->dispatch('toast', message: __('Added to cart'));
    }
}
