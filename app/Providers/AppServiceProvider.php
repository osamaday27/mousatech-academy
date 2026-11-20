<?php

namespace App\Providers;

use App\Http\Middleware\SetLocale;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
    }

    public function boot(): void
    {
        // Keep the chosen language on Livewire's AJAX requests too.
        Livewire::addPersistentMiddleware([SetLocale::class]);

        // RBAC: abilities that look like "orders.view" are resolved from the user's roles.
        Gate::before(function ($user, string $ability) {
            if (! $user) {
                return null;
            }
            if ($user->isSuper()) {
                return true;
            }

            return str_contains($ability, '.') && $user->hasPermission($ability) ? true : null;
        });
    }
}
