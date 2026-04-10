<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HospitalProfileComplete
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();
        
        // Check if user is a hospital and has completed their profile
        if ($user && $user->role === 'hospital') {
            if (!$user->location || !$user->latitude || !$user->longitude) {
                return redirect()->route('hospital.profile.edit')
                    ->with('info', 'Please complete your hospital profile first before accessing requests.');
            }
        }
        
        return $next($request);
    }
}
