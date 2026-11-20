<?php

namespace App\Livewire\orders;

use App\Livewire\Concerns\NeedsPermission;
use App\Models\Order;
use App\Services\OrderService;
use DomainException;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.admin')]
class orders extends Component
{
    use NeedsPermission, WithPagination;

    public string $status = '';
    public string $search = '';
    public int $perPage = 15;

    public function paginationView()
    {
        return 'livewire::bootstrap';
    }

    public function updatingStatus(): void
    {
        $this->resetPage();
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingPerPage(): void
    {
        $this->resetPage();
    }

    public function approve(int $id): void
    {
        $this->need('payments.verify');
        try {
            app(OrderService::class)->markPaid(Order::findOrFail($id), auth()->id());
            $this->dispatch('admin-toast', message: __('admin.order_approved'), icon: 'success');
        } catch (DomainException $e) {
            $this->dispatch('admin-toast', message: $e->getMessage(), icon: 'error');
        }
    }

    public function reject(int $id): void
    {
        $this->need('payments.verify');
        app(OrderService::class)->reject(Order::findOrFail($id), auth()->id());
        $this->dispatch('admin-toast', message: __('admin.order_rejected'), icon: 'info');
    }

    public function render()
    {
        $this->need('orders.view');
        $s = $this->search;
        $rows = Order::with(['user', 'payment', 'items'])
            ->when($this->status !== '', fn ($q) => $q->where('status', $this->status))
            ->when($s !== '', fn ($q) => $q->where(fn ($w) => $w
                ->where('number', 'like', "%{$s}%")
                ->orWhereHas('user', fn ($u) => $u->where('email', 'like', "%{$s}%")->orWhere('name', 'like', "%{$s}%"))))
            ->latest('id')->paginate($this->perPage);

        return view('livewire.orders.orders', ['rows' => $rows, 'statuses' => Order::STATUSES]);
    }
}
