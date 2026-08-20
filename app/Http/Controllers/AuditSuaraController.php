<?php

namespace App\Http\Controllers;

use App\Models\CalonKetua;
use App\Models\HakSuara;
use App\Models\Vote;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuditSuaraController extends Controller
{
    public function index(Request $request)
    {
        $query = Vote::with(['hakSuara.kelas']);

        if ($request->filled('status')) {
            $query->where('status_verifikasi', $request->status);
        }

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->whereHas('hakSuara', function ($q) use ($search) {
                $q->where('token', 'like', "%{$search}%")
                  ->orWhere('nisn', 'like', "%{$search}%");
            });
        }

        $votes = $query->orderByDesc('id')->get();

        $totalSuaraDigital = Vote::count();
        $totalSah = Vote::where('status_verifikasi', 'sah')->count();
        $totalTidakSah = Vote::where('status_verifikasi', 'tidak_sah')->count();
        $totalPending = Vote::where('status_verifikasi', 'pending')->count();

        // Rekapitulasi perolehan suara per calon (Digital vs Sah Manual)
        $calons = CalonKetua::withCount([
            'votes as digital_votes',
            'votes as valid_votes' => function ($q) {
                $q->where('status_verifikasi', 'sah');
            },
            'votes as invalid_votes' => function ($q) {
                $q->where('status_verifikasi', 'tidak_sah');
            },
        ])->orderBy('nomor')->get();

        return view('audit-suara.index', compact(
            'votes',
            'totalSuaraDigital',
            'totalSah',
            'totalTidakSah',
            'totalPending',
            'calons'
        ));
    }

    public function verifySingle(Request $request, Vote $vote)
    {
        $request->validate([
            'status' => 'required|in:sah,tidak_sah,pending',
            'catatan' => 'nullable|string|max:255',
        ]);

        $vote->update([
            'status_verifikasi' => $request->status,
            'catatan_verifikasi' => $request->catatan,
            'verified_at' => $request->status !== 'pending' ? now() : null,
        ]);

        return back()->with('success', "Status verifikasi token {$vote->hakSuara->token} diperbarui menjadi " . strtoupper($request->status));
    }

    public function quickVerifyByToken(Request $request): JsonResponse
    {
        $request->validate([
            'token' => 'required|string',
            'status' => 'required|in:sah,tidak_sah',
            'catatan' => 'nullable|string|max:255',
        ]);

        $tokenInput = strtoupper(trim($request->token));
        $hakSuara = HakSuara::where('token', $tokenInput)->first();

        if (! $hakSuara) {
            return response()->json([
                'success' => false,
                'message' => "Token '{$tokenInput}' tidak ditemukan dalam daftar DPT.",
            ], 404);
        }

        $vote = Vote::where('id_nisn', $hakSuara->id)->first();

        if (! $vote) {
            return response()->json([
                'success' => false,
                'message' => "Token '{$tokenInput}' terdaftar, tetapi pemilih belum pernah melakukan voting di bilik suara.",
            ], 400);
        }

        $vote->update([
            'status_verifikasi' => $request->status,
            'catatan_verifikasi' => $request->catatan ?? ($request->status === 'sah' ? 'Kartu fisik ditemukan di kotak suara' : 'Kartu fisik tidak sesuai / tidak ditemukan'),
            'verified_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => "Token {$tokenInput} berhasil diverifikasi sebagai: " . strtoupper($request->status),
            'token' => $tokenInput,
            'status' => $request->status,
            'voter_name' => $hakSuara->nisn,
            'counts' => [
                'total' => Vote::count(),
                'sah' => Vote::where('status_verifikasi', 'sah')->count(),
                'tidak_sah' => Vote::where('status_verifikasi', 'tidak_sah')->count(),
                'pending' => Vote::where('status_verifikasi', 'pending')->count(),
            ],
        ]);
    }

    public function batchVerifyAll(Request $request)
    {
        $request->validate([
            'action' => 'required|in:sah_all,reset_all',
        ]);

        if ($request->action === 'sah_all') {
            Vote::where('status_verifikasi', 'pending')->update([
                'status_verifikasi' => 'sah',
                'catatan_verifikasi' => 'Verifikasi massal sah sesuai kotak fisik',
                'verified_at' => now(),
            ]);
            $msg = 'Semua suara pending berhasil disahkan secara massal.';
        } else {
            Vote::query()->update([
                'status_verifikasi' => 'pending',
                'catatan_verifikasi' => null,
                'verified_at' => null,
            ]);
            $msg = 'Semua status verifikasi suara berhasil di-reset ke pending.';
        }

        return redirect()->route('audit-suara.index')->with('success', $msg);
    }
}

