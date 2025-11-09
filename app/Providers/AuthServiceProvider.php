<?php

namespace App\Providers;

use Illuminate\Foundation\Auth\User as AuthUser;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Gate::before(function (AuthUser $user, string $ability): ?bool {
            // Always allow Super Admin role, or user_type system/admin
            if (method_exists($user, 'hasRole') && $user->hasRole('Super Admin')) {
                return true;
            }

            if (in_array((string) ($user->user_type ?? ''), ['system', 'admin'], true)) {
                return true;
            }

            return null;
        });
    }
}
