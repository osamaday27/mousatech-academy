<?php

namespace App\Livewire\Admin;

use App\Livewire\Concerns\NeedsPermission;
use App\Models\Course;
use App\Models\Order;
use App\Models\User;
use Livewire\Component;

class Dashboard extends Component
{
    use NeedsPermission;

    public function render()
    {
        $this->need('dashboard.view');
        $canMoney = auth()->user()->can('orders.view');

        return view('livewire.admin.dashboard', [
            'canMoney' => $canMoney,
            'revenue' => $canMoney ? (int) Order::where('status', 'paid')->sum('total') : 0,
            'students' => User::doesntHave('roles')->count(),
            'courses' => Course::count(),
            'orders' => $canMoney ? Order::count() : 0,
            'pending' => $canMoney ? Order::where('status', 'awaiting_verification')->count() : 0,
            'recent' => $canMoney ? Order::with('user')->latest('id')->take(8)->get() : collect(),
        ]);
    }
}
