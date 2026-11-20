<?php

namespace App\Models;

use App\Models\Concerns\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Section extends Model
{
    use Translatable;

    protected $table = 'course_sections';
    protected $guarded = [];

    public function lessons(): HasMany
    {
        return $this->hasMany(Lesson::class, 'course_section_id')->orderBy('sort_order');
    }
}
