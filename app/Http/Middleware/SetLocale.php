<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Resolves the active locale from the URL prefix. The default locale is served
 * from the site root, every other locale from its own prefix (for example `/en`).
 */
class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): Response  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        app()->setLocale(static::resolve($request->segment(1)));

        return $next($request);
    }

    /**
     * Match a URL segment against the configured locale prefixes.
     */
    public static function resolve(?string $segment): string
    {
        foreach (static::locales() as $locale => $settings) {
            if (filled($settings['prefix'] ?? null) && $settings['prefix'] === $segment) {
                return $locale;
            }
        }

        return static::default();
    }

    /**
     * The locale served from the site root.
     */
    public static function default(): string
    {
        return (string) array_key_first(static::locales());
    }

    /**
     * @return array<string, array{label: string, short: string, prefix: string|null}>
     */
    protected static function locales(): array
    {
        return config('site.locales', []);
    }
}
