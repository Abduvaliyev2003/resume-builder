<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    protected array $supported = ['en', 'uz', 'ru'];

    public function handle(Request $request, Closure $next): Response
    {
        $locale = $this->resolveLocale($request);

        app()->setLocale($locale);

        // Only persist to session on stateful (web) requests
        if (!$request->expectsJson()) {
            session(['locale' => $locale]);
        }

        return $next($request);
    }

    protected function resolveLocale(Request $request): string
    {
        // 1. Query param (one-time override, works for both web + API)
        if ($request->has('lang') && in_array($request->query('lang'), $this->supported)) {
            return $request->query('lang');
        }

        // 2. Session (web only)
        if (!$request->expectsJson() && session()->has('locale') && in_array(session('locale'), $this->supported)) {
            return session('locale');
        }

        // 3. Authenticated user preference (works for both web + API via Sanctum)
        if ($request->user()?->profile?->locale) {
            $locale = $request->user()->profile->locale;
            if (in_array($locale, $this->supported)) {
                return $locale;
            }
        }

        // 4. Browser Accept-Language header
        $browser = substr($request->server('HTTP_ACCEPT_LANGUAGE', 'en'), 0, 2);
        if (in_array($browser, $this->supported)) {
            return $browser;
        }

        // 5. App default
        return config('app.locale', 'en');
    }
}
