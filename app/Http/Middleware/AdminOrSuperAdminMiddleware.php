<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminOrSuperAdminMiddleware
{
    public function handle($request, Closure $next)
    {
        if (!auth()->check() || !in_array(auth()->user()->role, ['admin', 'superadmin'])) {
            abort(403, 'Unauthorized');
        }
        return $next($request);
    }
}
