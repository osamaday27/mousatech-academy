<?php

namespace App\Livewire;

use App\Models\Order;
use Livewire\Component;

class OrderShow extends Component
{
    public Order $order;

    public function mount(Order $order): void
    {
        abort_unless($order->user_id === auth()->id() || auth()->user()->can('orders.view'), 403);
        $this->order = $order;
    }

    public function render()
    {
        return view('livewire.order-show', ['items' => $this->order->items, 'payment' => $this->order->payment]);
    }
}
