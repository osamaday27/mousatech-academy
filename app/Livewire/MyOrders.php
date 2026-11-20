<?php

namespace App\Livewire;

use App\Models\Order;
use Livewire\Component;

class MyOrders extends Component
{
    public function render()
    {
        return view('livewire.my-orders', [
            'orders' => Order::where('user_id', auth()->id())->latest('id')->get(),
        ]);
    }
}
