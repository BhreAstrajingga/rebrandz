<?php

namespace App\Http\Middleware;

use App\Support\CurrentTenant;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectToUserTenant
{
    public function __construct(private CurrentTenant $currentTenant) {}

    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && in_array((string) ($user->user_type ?? ''), ['customer'], true)) {
            $current = $this->currentTenant->get();
            $userTenant = $user->tenant;

            if ($userTenant && (! $current || (int) $current->getKey() !== (int) $userTenant->getKey())) {
                $scheme = $request->getScheme();
                $targetDomain = (string) ($userTenant->domain ?: ($userTenant->slug . '.' . (parse_url(config('app.url'), PHP_URL_HOST) ?: 'localhost')));

                $uri = ltrim($request->getRequestUri(), '/');
                if (! in_array($request->method(), ['GET', 'HEAD'], true)) {
                    // Avoid redirecting POST/PUT/PATCH/DELETE to same URI (e.g., logout), go to tenant home instead
                    $uri = 'tenant';
                }

                $url = $scheme . '://' . $targetDomain . '/' . $uri;

                return redirect()->to($url);
            }
        }

        return $next($request);
    }
}
