<?php

namespace App\Livewire\Admin;

use App\Livewire\Concerns\NeedsPermission;
use App\Models\Order;
use App\Services\OrderService;
use DomainException;
use Livewire\Component;
use Livewire\WithPagination;

class Orders extends Component
{
    use NeedsPermission, WithPagination;

    public string $status = '';
    public string $search = '';

    public function updated(): void
    {
        $this->resetPage();
    }

    public function approve(int $id): void
    {
        $this->need('payments.verify');
        try {
            app(OrderService::class)->markPaid(Order::findOrFail($id), auth()->id());
        } catch (DomainException $e) {
            $this->addError('order', $e->getMessage());
        }
    }

    public function reject(int $id): void
    {
        $this->need('payments.verify');
        app(OrderService::class)->reject(Order::findOrFail($id), auth()->id());
    }

    public function render()
    {
        $this->need('orders.view');
        $orders = Order::with(['user', 'payment', 'items'])
            ->when($this->status !== '', fn ($q) => $q->where('status', $this->status))
            ->when($this->search !== '', fn ($q) => $q->where(fn ($w) => $w
                ->where('number', 'like', "%{$this->search}%")
                ->orWhereHas('user', fn ($u) => $u->where('email', 'like', "%{$this->search}%")->orWhere('name', 'like', "%{$this->search}%"))))
            ->latest('id')->paginate(15);

        return view('livewire.admin.orders', ['orders' => $orders, 'statuses' => Order::STATUSES]);
    }
}
