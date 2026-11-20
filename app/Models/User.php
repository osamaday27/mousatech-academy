<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = ['name', 'email', 'phone', 'password', 'avatar'];
    protected $hidden = ['password', 'remember_token'];
    protected ?array $permCache = null;

    protected function casts(): array
    {
        return ['email_verified_at' => 'datetime', 'password' => 'hashed'];
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }

    public function enrolledCourses(): BelongsToMany
    {
        return $this->belongsToMany(Course::class, 'course_enrollments')->withTimestamps();
    }

    public function isSuper(): bool
    {
        return $this->roles()->where('slug', 'super-admin')->exists();
    }

    public function permissionSlugs(): array
    {
        return $this->permCache ??= $this->roles()->with('permissions')->get()
            ->flatMap(fn ($r) => $r->permissions->pluck('slug'))->unique()->values()->all();
    }

    public function hasPermission(string $slug): bool
    {
        return in_array($slug, $this->permissionSlugs(), true);
    }

    public function owns(Course $course): bool
    {
        return $this->enrollments()->where('course_id', $course->id)->exists();
    }
}
