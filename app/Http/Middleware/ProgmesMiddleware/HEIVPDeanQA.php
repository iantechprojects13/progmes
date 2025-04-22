<?php

namespace App\Http\Middleware\ProgmesMiddleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Auth;

class HEIVPDeanQA
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::user()->role == 'Vice-President for Academic Affairs' || Auth::user()->role == 'Dean' || Auth::user()->role == 'Quality Assurance Officer') {
            return $next($request);
        }

        return redirect('/unauthorized');
    }
}
