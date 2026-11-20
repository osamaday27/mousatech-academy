<?php

namespace App\Models;

use App\Models\Concerns\Translatable;
use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    use Translatable;

    protected $guarded = [];

    protected function casts(): array
    {
        return ['is_preview' => 'boolean'];
    }
}
