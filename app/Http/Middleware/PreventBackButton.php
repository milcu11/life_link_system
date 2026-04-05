<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PreventBackButton
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Add cache control headers to prevent back button navigation
        // Only apply to authenticated users or public auth-related pages
        if ($request->user() || $this->isAuthRelatedRoute($request)) {
            $response->headers->set('Cache-Control', 'no-cache, no-store, must-revalidate');
            $response->headers->set('Pragma', 'no-cache');
            $response->headers->set('Expires', '0');
        }

        return $response;
    }

    /**
     * Check if the route is authentication-related (login, register, etc.)
     */
    private function isAuthRelatedRoute(Request $request): bool
    {
        $path = $request->path();
        
        return in_array($path, [
            '/',
            'login',
            'register',
            'password/forgot',
            'password/verify',
            'password/reset'
        ]) || str_starts_with($path, 'password/');
    }
}