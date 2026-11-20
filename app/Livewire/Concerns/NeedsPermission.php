<?php

namespace App\Livewire\Concerns;

trait NeedsPermission
{
    /** Livewire actions are public endpoints: authorize inside every one of them. */
    protected function need(string $permission): void
    {
        abort_unless(auth()->check() && auth()->user()->can($permission), 403);
    }
}
