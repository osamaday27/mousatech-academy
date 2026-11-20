<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use App\Models\Concerns\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Course extends Model
{
    use HasFactory, SoftDeletes, HasTranslations, Translatable;

    public array $translatable = ['title', 'description'];

    protected $guarded = [];

    protected function casts(): array
    {
        return ['is_featured' => 'boolean', 'published_at' => 'datetime'];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function sections(): HasMany
    {
        return $this->hasMany(Section::class)->orderBy('sort_order');
    }

    public function lessons(): HasMany
    {
        return $this->hasMany(Lesson::class);
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }

    public function scopeLive(Builder $q): void
    {
        $q->where('status', 'published')
            ->where(fn ($w) => $w->whereNull('published_at')->orWhere('published_at', '<=', now()));
    }

    public function hasDiscount(): bool
    {
        return $this->sale_price !== null && $this->sale_price < $this->price;
    }

    public function currentPrice(): int
    {
        return (int) ($this->hasDiscount() ? $this->sale_price : $this->price);
    }

    public function discountPercent(): int
    {
        return $this->hasDiscount() && $this->price > 0
            ? (int) round((1 - $this->sale_price / $this->price) * 100) : 0;
    }

    public function durationLabel(): string
    {
        $m = (int) $this->duration_minutes;

        return intdiv($m, 60).__('h').' '.($m % 60).__('m');
    }

    public function imageUrl(): string
    {
        return $this->url($this->image) ?? self::placeholder();
    }

    public function hoverUrl(): string
    {
        return $this->url($this->hover_image) ?? $this->imageUrl();
    }

    protected function url(?string $path): ?string
    {
        if (! $path) {
            return null;
        }

        return str_starts_with($path, 'http') ? $path : Storage::disk('public')->url($path);
    }

    public static function placeholder(): string
    {
        $svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 160 90"><rect width="160" height="90" fill="#080808"/>'
            .'<path d="M0 30h160M0 60h160M40 0v90M80 0v90M120 0v90" stroke="#D4AF37" stroke-opacity=".18"/>'
            .'<path d="M62 33 48 45l14 12M98 33l14 12-14 12" fill="none" stroke="#D4AF37" stroke-width="3"/></svg>';

        return 'data:image/svg+xml;utf8,'.rawurlencode($svg);
    }
}
