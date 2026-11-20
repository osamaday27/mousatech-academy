<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Coupon extends Model
{
    use HasFactory, SoftDeletes;

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
