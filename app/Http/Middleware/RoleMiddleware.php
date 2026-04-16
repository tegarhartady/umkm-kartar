<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (Response)  $next
     * @param  string  $role
     * @return Response
     */
    public function handle($request, Closure $next, $role)
    {
        if (!auth()->check() || auth()->user()->role !== $role) {
            abort(403, 'Unauthorized');
        }
        return $next($request);
    }
}
