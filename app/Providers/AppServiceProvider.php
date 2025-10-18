<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Gate;
use App\Models\User;
use App\Support\CurrentTenant;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(CurrentTenant::class, function (): CurrentTenant {
            return new CurrentTenant();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::define('viewPulse', function (User $user): bool {
            return (string) $user->user_type === 'admin';
        });

        // Register rate limiter for login attempts (used by 'throttle:login').
        RateLimiter::for('login', function (Request $request): array {
            $ipKey = (string) $request->ip();
            $email = (string) $request->input('email');

            $limits = [];
            if ($email !== '') {
                $limits[] = Limit::perMinute(5)->by($ipKey . '|' . $email);
            }

            $limits[] = Limit::perMinute(120)->by($ipKey);

            return $limits;
        });

        RateLimiter::for('blog-public', function (Request $request): array {
            $ip = (string) $request->ip();
            $ua = (string) $request->userAgent();

            $limits = [];
            $limits[] = Limit::perMinute(60)->by($ip);
            if ($ua === '') {
                $limits[] = Limit::perMinute(20)->by('no-ua|' . $ip);
            }

            return $limits;
        });
    }
}
