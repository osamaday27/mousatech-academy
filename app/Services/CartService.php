<?php

namespace App\Services;

use App\Models\Course;
use Illuminate\Support\Collection;

class CartService
{
    private const KEY = 'cart';

    public function ids(): array
    {
        return array_values(array_unique(array_map('intval', (array) session(self::KEY, []))));
    }

    public function add(int $id): void
    {
        session([self::KEY => array_values(array_unique([...$this->ids(), $id]))]);
    }

    public function remove(int $id): void
    {
        session([self::KEY => array_values(array_diff($this->ids(), [$id]))]);
    }

    public function clear(): void
    {
        session()->forget(self::KEY);
    }

    public function count(): int
    {
        return count($this->ids());
    }

    public function courses(): Collection
    {
        return Course::live()->with('category')->whereIn('id', $this->ids())->get();
    }
}
