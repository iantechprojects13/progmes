<?php

namespace App\Http\Middleware\ProgmesMiddleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Auth;

class CHEDAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (
            Auth::user()->role == 'Super Admin'||
            Auth::user()->role == 'Admin' ||
            Auth::user()->role == 'Regional Director' ||
            Auth::user()->role == 'Chief Education Program Specialist' ||
            Auth::user()->role == 'Supervising Education Program Specialist'
        ) {
            return $next($request);
        }
        
        return redirect('/unauthorized');
    }
}
