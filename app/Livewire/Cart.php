<?php

namespace App\Livewire;

use App\Services\CartService;
use App\Services\OrderService;
use Livewire\Component;

class Cart extends Component
{
    public function remove(int $id): void
    {
        app(CartService::class)->remove($id);
        $this->dispatch('cart-updated');
    }

    public function render()
    {
        $courses = app(CartService::class)->courses();

        return view('livewire.cart', [
            'courses' => $courses,
            'q' => app(OrderService::class)->quote($courses, null, null),
        ]);
    }
}
