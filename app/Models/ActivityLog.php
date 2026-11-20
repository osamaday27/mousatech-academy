<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return ['meta' => 'array'];
    }

    public static function record(string $action, ?Model $subject = null, array $meta = []): void
    {
        static::create([
            'user_id' => auth()->id(),
            'action' => $action,
            'subject_type' => $subject?->getMorphClass(),
            'subject_id' => $subject?->getKey(),
            'meta' => $meta ?: null,
        ]);
    }
}
