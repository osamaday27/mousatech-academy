<?php

namespace App\Livewire\dashboard;

use App\Livewire\Concerns\NeedsPermission;
use App\Models\Course;
use App\Models\Order;
use App\Models\Post;
use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.admin')]
class dashboard extends Component
{
    use NeedsPermission;

    public function render()
    {
        $this->need('dashboard.view');
        $money = auth()->user()->can('orders.view');

        return view('livewire.dashboard.dashboard', [
            'money' => $money,
            'revenue' => $money ? (int) Order::where('status', 'paid')->sum('total') : 0,
            'students' => User::doesntHave('roles')->count(),
            'courses' => Course::count(),
            'orders' => $money ? Order::count() : 0,
            'pending' => $money ? Order::where('status', 'awaiting_verification')->count() : 0,
            'posts' => Post::count(),
            'recent' => $money ? Order::with('user')->latest('id')->take(8)->get() : collect(),
        ]);
    }
}
