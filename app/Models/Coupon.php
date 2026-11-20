<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return ['is_active' => 'boolean', 'starts_at' => 'datetime', 'expires_at' => 'datetime'];
    }

    public function discountFor(int $amount): int
    {
        $d = $this->type === 'percent' ? intdiv($amount * min(100, $this->value), 100) : $this->value;

        return min($amount, $d);
    }
}
