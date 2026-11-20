<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    public const STATUSES = ['pending', 'awaiting_verification', 'paid', 'failed', 'refunded', 'cancelled'];

    protected $guarded = [];

    protected function casts(): array
    {
        return ['paid_at' => 'datetime'];
    }

    public function getRouteKeyName(): string
    {
        return 'number';
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }

    public function badge(): string
    {
        return match ($this->status) {
            'paid' => 'bg-gold text-ink',
            'awaiting_verification' => 'bg-ink text-gold',
            'failed', 'cancelled' => 'bg-steel text-white',
            default => 'border border-silver text-steel',
        };
    }
}
