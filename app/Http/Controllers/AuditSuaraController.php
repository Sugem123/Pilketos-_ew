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
                $q->where('token', 'like', "%{$search}%");
            });
        }

        $votes = $query->orderByDesc('id')->get();

        $totalSuaraDigital = Vote::count();
        $totalSah = Vote::where('status_verifikasi', 'sah')->count();
        $totalTidakSah = Vote::where('status_verifikasi', 'tidak_sah')->count();
        $totalPending = Vote::where('status_verifikasi', 'pending')->count();

        // Rekapitulasi perolehan suara per calon (Digital vs Sah Manual)
        $calons = CalonKetua::with(['kelas', 'kelasWakil1', 'kelasWakil2'])
            ->withCount([
                'votes as digital_votes',
                'votes as valid_votes' => function ($q) {
                    $q->where('status_verifikasi', 'sah');
                },
                'votes as invalid_votes' => function ($q) {
                    $q->where('status_verifikasi', 'tidak_sah');
                },
            ])->orderBy('nomor')->get();

        $calonOsis = $calons->where('tipe', 'osis')->values();
        $calonMpk = $calons->where('tipe', 'mpk')->values();

        $totalVoteOsis = Vote::whereHas('calon', fn ($q) => $q->where('tipe', 'osis'))->count();
        $totalVoteMpk = Vote::whereHas('calon', fn ($q) => $q->where('tipe', 'mpk'))->count();

        return view('audit-suara.index', compact(
            'votes',
            'totalSuaraDigital',
            'totalSah',
            'totalTidakSah',
            'totalPending',
            'calons',
            'calonOsis',
            'calonMpk',
            'totalVoteOsis',
            'totalVoteMpk'
        ));
    }

    public function verifySingle(Request $request, Vote $vote)
    {
        $request->validate([
            'status' => 'required|in:sah,tidak_sah,pending',
            'catatan' => 'nullable|string|max:255',
        ]);

        // Verifikasi fisik kartu berlaku serentak untuk kedua pemilihan (OSIS & MPK) milik pemilih ini
        Vote::where('id_nisn', $vote->id_nisn)->update([
            'status_verifikasi' => $request->status,
            'catatan_verifikasi' => $request->catatan,
            'verified_at' => $request->status !== 'pending' ? now() : null,
        ]);

        return back()->with('success', "Status verifikasi kartu token {$vote->hakSuara->token} (OSIS & MPK) diperbarui menjadi ".strtoupper($request->status));
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
        $votes = Vote::where('id_nisn', $hakSuara->id)->get();

        if ($votes->isEmpty()) {
            return response()->json([
                'success' => false,
                'verdict' => 'tidak_sah',
                'message' => "Token \"{$tokenInput}\" terdaftar, tapi tidak ada catatan suara bilik. Kartu TIDAK SAH.",
                'kategori' => $hakSuara->tipe === 'guru' ? 'Guru / Tendik' : ($hakSuara->kelas->name ?? 'Siswa'),
            ]);
        }

        // Already verified before (none pending)
        $pendingVotes = $votes->where('status_verifikasi', 'pending');
        if ($pendingVotes->isEmpty()) {
            $firstStatus = $votes->first()->status_verifikasi ?? 'sah';
            return response()->json([
                'success' => true,
                'verdict' => 'sudah',
                'message' => "Token \"{$tokenInput}\" sudah diverifikasi sebelumnya sebagai: ".strtoupper($firstStatus),
                'status' => $firstStatus,
                'kategori' => $hakSuara->tipe === 'guru' ? 'Guru / Tendik' : ($hakSuara->kelas->name ?? 'Siswa'),
                'counts' => $this->getAuditCounts(),
            ]);
        }

        // Token valid + voted + pending → mark ALL votes (both OSIS & MPK) as SAH!
        Vote::where('id_nisn', $hakSuara->id)->update([
            'status_verifikasi' => 'sah',
            'catatan_verifikasi' => 'Kartu fisik ditemukan di kotak suara',
            'verified_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'verdict' => 'sah',
            'message' => "Token \"{$tokenInput}\" — Suara OSIS & MPK SAH!",
            'token' => $tokenInput,
            'status' => 'sah',
            'kategori' => $hakSuara->tipe === 'guru' ? 'Guru / Tendik' : ($hakSuara->kelas->name ?? 'Siswa'),
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

    public function liveData(Request $request): JsonResponse
    {
        $calons = CalonKetua::withCount([
            'votes as digital_votes',
            'votes as valid_votes' => function ($q) {
                $q->where('status_verifikasi', 'sah');
            },
            'votes as invalid_votes' => function ($q) {
                $q->where('status_verifikasi', 'tidak_sah');
            },
        ])->orderBy('nomor')->get();

        $candidateStats = $calons->mapWithKeys(fn ($c) => [
            $c->id => [
                'id' => $c->id,
                'digital' => $c->digital_votes,
                'valid' => $c->valid_votes,
            ],
        ])->toArray();

        $totalVoteOsis = Vote::whereHas('calon', fn ($q) => $q->where('tipe', 'osis'))->count();
        $totalVoteMpk = Vote::whereHas('calon', fn ($q) => $q->where('tipe', 'mpk'))->count();

        $recentVotes = Vote::with('hakSuara.kelas')
            ->orderByDesc('id')
            ->limit(50)
            ->get()
            ->map(fn ($v) => [
                'id' => $v->id,
                'token' => $v->hakSuara->token ?? '',
                'status' => $v->status_verifikasi,
                'catatan' => $v->catatan_verifikasi,
            ]);

        return response()->json([
            'success' => true,
            'counts' => $this->getAuditCounts(),
            'candidates' => $candidateStats,
            'total_osis' => $totalVoteOsis,
            'total_mpk' => $totalVoteMpk,
            'recent_votes' => $recentVotes,
            'updated_at' => now()->format('H:i:s'),
        ]);
    }

    /**
     * @return array{total: int, sah: int, tidak_sah: int, pending: int, candidates: array}
     */
    private function getAuditCounts(): array
    {
        $candidateStats = CalonKetua::withCount([
            'votes as digital_votes',
            'votes as valid_votes' => function ($q) {
                $q->where('status_verifikasi', 'sah');
            },
        ])->get()->mapWithKeys(fn ($c) => [
            $c->id => [
                'id' => $c->id,
                'digital' => $c->digital_votes,
                'valid' => $c->valid_votes,
            ],
        ])->toArray();

        return [
            'total' => Vote::count(),
            'sah' => Vote::where('status_verifikasi', 'sah')->count(),
            'tidak_sah' => Vote::where('status_verifikasi', 'tidak_sah')->count(),
            'pending' => Vote::where('status_verifikasi', 'pending')->count(),
            'candidates' => $candidateStats,
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

    /**
     * Generate session ID pairing laptop <-> HP untuk remote scanner
     */
    public function createRemoteSession(): JsonResponse
    {
        $sessionId = strtoupper(substr(str_shuffle('ABCDEFGHJKLMNPQRSTUVWXYZ23456789'), 0, 8));
        \Illuminate\Support\Facades\Cache::put("audit_pair_{$sessionId}", [
            'created_at' => now()->timestamp,
            'devices' => [], // list device [id => ['name' => ..., 'status' => 'pending|approved|rejected', 'last_ping' => ...]]
        ], now()->addHours(4));

        $url = route('audit-suara.remote-view', ['session' => $sessionId]);

        return response()->json([
            'success' => true,
            'session_id' => $sessionId,
            'url' => $url,
        ]);
    }

    /**
     * Tampilan Scanner Kamera di HP Panitia
     */
    public function remoteView(string $session)
    {
        $session = strtoupper(trim($session));
        $cacheData = \Illuminate\Support\Facades\Cache::get("audit_pair_{$session}");

        if (! $cacheData) {
            return view('audit-suara.remote-expired');
        }

        $config = $this->getConfig();

        return view('audit-suara.remote', compact('session', 'config'));
    }

    /**
     * Pendaftaran Device HP (Meminta Izin / Approval Admin Laptop)
     */
    public function remoteJoin(Request $request): JsonResponse
    {
        $request->validate([
            'session_id' => 'required|string',
            'device_name' => 'required|string|max:40',
        ]);

        $sessionId = strtoupper(trim($request->session_id));
        $pair = \Illuminate\Support\Facades\Cache::get("audit_pair_{$sessionId}");

        if (! $pair) {
            return response()->json(['success' => false, 'message' => 'Sesi pairing kadaluarsa.'], 404);
        }

        $deviceId = 'dev_'.substr(md5(uniqid((string) mt_rand(), true)), 0, 10);
        $name = trim($request->device_name);

        $pair['devices'][$deviceId] = [
            'id' => $deviceId,
            'name' => $name,
            'status' => 'pending', // Menunggu approval admin laptop
            'joined_at' => now()->format('H:i:s'),
            'last_ping' => now()->timestamp,
        ];

        \Illuminate\Support\Facades\Cache::put("audit_pair_{$sessionId}", $pair, now()->addHours(4));

        return response()->json([
            'success' => true,
            'device_id' => $deviceId,
            'device_name' => $name,
            'status' => 'pending',
        ]);
    }

    /**
     * Cek status approval device oleh HP
     */
    public function remoteDeviceStatus(string $session, string $deviceId): JsonResponse
    {
        $sessionId = strtoupper(trim($session));
        $pair = \Illuminate\Support\Facades\Cache::get("audit_pair_{$sessionId}");

        if (! $pair || ! isset($pair['devices'][$deviceId])) {
            return response()->json(['success' => false, 'status' => 'disconnected']);
        }

        $device = $pair['devices'][$deviceId];
        $pair['devices'][$deviceId]['last_ping'] = now()->timestamp;
        \Illuminate\Support\Facades\Cache::put("audit_pair_{$sessionId}", $pair, now()->addHours(4));

        return response()->json([
            'success' => true,
            'status' => $device['status'], // 'pending', 'approved', 'rejected'
            'device_name' => $device['name'],
        ]);
    }

    /**
     * Admin Laptop menyetujui (approve), menolak (reject), atau memutus (kick) HP
     */
    public function deviceAction(Request $request): JsonResponse
    {
        $request->validate([
            'session_id' => 'required|string',
            'device_id' => 'required|string',
            'action' => 'required|in:approve,reject,kick',
        ]);

        $sessionId = strtoupper(trim($request->session_id));
        $deviceId = trim($request->device_id);
        $action = $request->action;

        $pair = \Illuminate\Support\Facades\Cache::get("audit_pair_{$sessionId}");

        if (! $pair || ! isset($pair['devices'][$deviceId])) {
            return response()->json(['success' => false, 'message' => 'Device tidak ditemukan.'], 404);
        }

        if ($action === 'approve') {
            $pair['devices'][$deviceId]['status'] = 'approved';
        } elseif ($action === 'reject') {
            $pair['devices'][$deviceId]['status'] = 'rejected';
        } elseif ($action === 'kick') {
            unset($pair['devices'][$deviceId]);
        }

        \Illuminate\Support\Facades\Cache::put("audit_pair_{$sessionId}", $pair, now()->addHours(4));

        return response()->json([
            'success' => true,
            'action' => $action,
            'device_id' => $deviceId,
        ]);
    }

    /**
     * HP mengirimkan token yang di-scan ke Laptop (hanya jika approved & token valid)
     */
    public function remotePush(Request $request): JsonResponse
    {
        $request->validate([
            'session_id' => 'required|string',
            'device_id' => 'required|string',
            'token' => 'required|string',
        ]);

        $sessionId = strtoupper(trim($request->session_id));
        $deviceId = trim($request->device_id);
        $rawToken = trim($request->token);

        $pair = \Illuminate\Support\Facades\Cache::get("audit_pair_{$sessionId}");
        if (! $pair) {
            return response()->json(['success' => false, 'message' => 'Sesi pairing kadaluarsa.'], 404);
        }

        // 1. Validasi Otorisasi Device (Harus Approved)
        if (! isset($pair['devices'][$deviceId]) || $pair['devices'][$deviceId]['status'] !== 'approved') {
            return response()->json([
                'success' => false,
                'message' => 'Perangkat ini belum disetujui atau koneksi telah diputus oleh admin laptop.',
            ], 403);
        }

        $deviceName = $pair['devices'][$deviceId]['name'] ?? 'HP Panitia';

        // 2. Validasi Keamanan Isi QR Code: HANYA BOLEH FORMAT TOKEN PILKETOS (6 karakter alfanumerik)
        $cleanToken = strtoupper(trim($rawToken));
        if (! preg_match('/^[A-Z0-9]{6}$/', $cleanToken)) {
            return response()->json([
                'success' => false,
                'invalid_qr' => true,
                'message' => 'QR Code Ditolak: Bukan QR token kartu pemilih Pilketos resmi (hanya 6 karakter alfanumerik).',
            ], 422);
        }

        // Perbarui ping device
        $pair['devices'][$deviceId]['last_ping'] = now()->timestamp;
        \Illuminate\Support\Facades\Cache::put("audit_pair_{$sessionId}", $pair, now()->addHours(4));

        // Masukkan ke antrian polling laptop
        $queue = \Illuminate\Support\Facades\Cache::get("audit_queue_{$sessionId}", []);
        $queue[] = [
            'token' => $cleanToken,
            'device_name' => $deviceName,
            'time' => now()->format('H:i:s'),
        ];
        \Illuminate\Support\Facades\Cache::put("audit_queue_{$sessionId}", $queue, now()->addMinutes(10));

        return response()->json([
            'success' => true,
            'message' => "Token {$cleanToken} berhasil dikirim ke laptop!",
            'token' => $cleanToken,
        ]);
    }

    /**
     * Putus koneksi dari HP
     */
    public function remoteDisconnect(Request $request): JsonResponse
    {
        $sessionId = strtoupper(trim((string) $request->session_id));
        $deviceId = trim((string) $request->device_id);

        $pair = \Illuminate\Support\Facades\Cache::get("audit_pair_{$sessionId}");
        if ($pair && isset($pair['devices'][$deviceId])) {
            unset($pair['devices'][$deviceId]);
            \Illuminate\Support\Facades\Cache::put("audit_pair_{$sessionId}", $pair, now()->addHours(4));
        }

        return response()->json(['success' => true]);
    }

    /**
     * Laptop mem-poll token yang dikirim oleh HP & memantau status device
     */
    public function remotePoll(string $session): JsonResponse
    {
        $sessionId = strtoupper(trim($session));
        $pair = \Illuminate\Support\Facades\Cache::get("audit_pair_{$sessionId}");

        if (! $pair) {
            return response()->json(['success' => false, 'expired' => true]);
        }

        $queue = \Illuminate\Support\Facades\Cache::get("audit_queue_{$sessionId}", []);
        \Illuminate\Support\Facades\Cache::forget("audit_queue_{$sessionId}");

        // Filter devices: hanya tampilkan device yang aktif dalam 2 menit terakhir
        $devices = [];
        $now = now()->timestamp;
        foreach ($pair['devices'] as $dId => $d) {
            $isOnline = ($now - ($d['last_ping'] ?? 0)) < 40;
            $devices[] = [
                'id' => $d['id'],
                'name' => $d['name'],
                'status' => $d['status'],
                'is_online' => $isOnline,
                'joined_at' => $d['joined_at'] ?? '-',
            ];
        }

        return response()->json([
            'success' => true,
            'devices' => $devices,
            'tokens' => $queue,
        ]);
    }

    private function getConfig(): array
    {
        $path = base_path('config.json');
        if (file_exists($path)) {
            return json_decode(file_get_contents($path), true) ?: [];
        }

        return [];
    }
}
