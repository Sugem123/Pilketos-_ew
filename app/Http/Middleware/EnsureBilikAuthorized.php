<?php

namespace App\Http\Middleware;

use App\Models\BilikSuara;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureBilikAuthorized
{
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Izinkan jika admin/operator sedang login
        if (Auth::check()) {
            return $next($request);
        }

        // 2. Cek token pairing bilik dari session atau cookie
        $token = $request->session()->get('bilik_session_token') ?? $request->cookie('bilik_session_token');

        if ($token) {
            $bilik = BilikSuara::where('session_token', $token)
                ->where('is_active', true)
                ->first();

            if ($bilik) {
                // Update heartbeat last_seen
                $bilik->update(['last_seen_at' => now()]);
                // Share data bilik ke view jika perlu
                view()->share('currentBilik', $bilik);
                return $next($request);
            }
        }

        // 3. Jika belum ter-pairing
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => 'Akses ditolak: PC Bilik ini belum di-pairing oleh panitia.',
                'unpaired' => true,
            ], 401);
        }

        return redirect()->route('bilik.pairing-form')
            ->with('info', 'Silakan masukkan Kode Pairing Bilik untuk mengaktifkan PC bilik suara ini.');
    }
}
