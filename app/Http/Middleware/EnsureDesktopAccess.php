<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureDesktopAccess
{
    /**
     * Handle an incoming request.
     * Mengizinkan akses admin dari desktop maupun mobile (HP).
     */
    public function handle(Request $request, Closure $next): Response
    {
        return $next($request);
    }
}
