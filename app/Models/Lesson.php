<?php

namespace App\Models;

use App\Models\Concerns\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    use HasFactory, SoftDeletes, HasTranslations, Translatable;

    public array $translatable = ['title'];

    protected $guarded = [];

    protected function casts(): array
    {
        return ['is_preview' => 'boolean'];
    }
}
