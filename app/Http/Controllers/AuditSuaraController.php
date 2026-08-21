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

        return back()->with('success', "Status verifikasi token {$vote->hakSuara->token} diperbarui menjadi ".strtoupper($request->status));
    }

    /**
     * Quick verify: panitia inputs token from physical ballot card.
     *
     * Logic:
     * - Token exists in DPT AND has a vote record (used) → SAH
     * - Token already verified (not pending) → already processed
     * - Token exists but no vote → TIDAK SAH (didn't vote)
     * - Token not in DPT → TIDAK SAH (unknown token)
     */
    public function quickVerifyByToken(Request $request): JsonResponse
    {
        $request->validate([
            'token' => 'required|string',
        ]);

        $tokenInput = strtoupper(trim($request->token));
        $hakSuara = HakSuara::where('token', $tokenInput)->first();

        // Token not found in DPT
        if (! $hakSuara) {
            return response()->json([
                'success' => false,
                'verdict' => 'tidak_sah',
                'message' => "Token \"{$tokenInput}\" tidak terdaftar dalam DPT. Kartu TIDAK SAH.",
            ]);
        }

        // Token exists but voter never voted (no vote record)
        $vote = Vote::where('id_nisn', $hakSuara->id)->first();

        if (! $vote) {
            return response()->json([
                'success' => false,
                'verdict' => 'tidak_sah',
                'message' => "Token \"{$tokenInput}\" terdaftar, tapi pemilih tidak melakukan voting. Kartu TIDAK SAH.",
                'voter_name' => $hakSuara->nisn,
            ]);
        }

        // Already verified before (not pending)
        if ($vote->status_verifikasi !== 'pending') {
            return response()->json([
                'success' => true,
                'verdict' => 'sudah',
                'message' => "Token \"{$tokenInput}\" sudah diverifikasi sebelumnya sebagai: ".strtoupper($vote->status_verifikasi),
                'voter_name' => $hakSuara->nisn,
                'status' => $vote->status_verifikasi,
                'counts' => $this->getAuditCounts(),
            ]);
        }

        // Token valid + voted + still pending → mark SAH
        $vote->update([
            'status_verifikasi' => 'sah',
            'catatan_verifikasi' => 'Kartu fisik ditemukan di kotak suara',
            'verified_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'verdict' => 'sah',
            'message' => "Token \"{$tokenInput}\" — Suara SAH!",
            'token' => $tokenInput,
            'status' => 'sah',
            'voter_name' => $hakSuara->nisn,
            'calon' => $vote->calon->nama ?? '-',
            'counts' => $this->getAuditCounts(),
        ]);
    }

    /**
     * Hanguskan sisa: mark all remaining pending votes as tidak_sah.
     * Used after all physical ballot cards have been read.
     */
    public function hanguskanSisa(): JsonResponse
    {
        $hangusCount = Vote::where('status_verifikasi', 'pending')->count();

        Vote::where('status_verifikasi', 'pending')->update([
            'status_verifikasi' => 'tidak_sah',
            'catatan_verifikasi' => 'Kartu fisik tidak ditemukan di kotak suara — hangus',
            'verified_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => "{$hangusCount} suara yang belum diverifikasi telah dihanguskan (tidak sah).",
            'hangus_count' => $hangusCount,
            'counts' => $this->getAuditCounts(),
        ]);
    }

    /**
     * @return array{total: int, sah: int, tidak_sah: int, pending: int}
     */
    private function getAuditCounts(): array
    {
        return [
            'total' => Vote::count(),
            'sah' => Vote::where('status_verifikasi', 'sah')->count(),
            'tidak_sah' => Vote::where('status_verifikasi', 'tidak_sah')->count(),
            'pending' => Vote::where('status_verifikasi', 'pending')->count(),
        ];
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
