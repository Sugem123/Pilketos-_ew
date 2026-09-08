<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdminRole
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user() || ! $request->user()->isAdmin()) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Akses ditolak: Fitur ini hanya dapat diakses oleh Administrator.',
                ], 403);
            }

            return redirect()->route('calon.index')
                ->with('error', 'Akses terbatas untuk akun Operator. Anda diarahkan ke menu yang diizinkan.');
        }

        return $next($request);
    }
}
