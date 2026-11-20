<?php

namespace App\Livewire;

use App\Services\CartService;
use App\Services\OrderService;
use DomainException;
use Livewire\Component;
use Livewire\WithFileUploads;

class Checkout extends Component
{
    use WithFileUploads;

    public string $coupon = '';
    public ?string $applied = null;
    public string $method = 'vodafone';
    public $proof = null;

    public function mount()
    {
        if (app(CartService::class)->count() === 0) {
            return $this->redirect(route('cart'));
        }
    }

    public function apply(): void
    {
        $this->applied = trim($this->coupon) ?: null;
    }

    protected function payable()
    {
        $user = auth()->user();

        return app(CartService::class)->courses()->reject(fn ($c) => $user->owns($c))->values();
    }

    public function place()
    {
        $this->validate([
            'method' => 'required|in:vodafone'.(config('academy.xpay_enabled') ? ',xpay' : ''),
            'proof' => 'nullable|mimes:jpg,jpeg,png,webp|max:4096',
        ]);

        try {
            $path = $this->proof ? $this->proof->store('proofs', 'local') : null;
            $order = app(OrderService::class)->create(auth()->user(), $this->payable(), $this->applied, $this->method, $path);
        } catch (DomainException $e) {
            $this->addError('cart', $e->getMessage());

            return;
        }

        app(CartService::class)->clear();

        return $this->redirect(route('orders.show', $order));
    }

    public function render()
    {
        $courses = $this->payable();

        return view('livewire.checkout', [
            'courses' => $courses,
            'q' => app(OrderService::class)->quote($courses, $this->applied, auth()->user()),
        ]);
    }
}
