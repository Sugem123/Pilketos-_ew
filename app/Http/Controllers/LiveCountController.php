<?php

namespace App\Http\Controllers;

use App\Models\CalonKetua;
use App\Models\HakSuara;
use App\Models\Kelas;
use App\Models\Vote;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LiveCountController extends Controller
{
    public function index()
    {
        $config = json_decode(file_get_contents(base_path('config.json')), true) ?: [];
        $isClosed = ($config['livecount_status'] ?? 'open') === 'closed';

        // Jika live count ditutup dan pengunjung bukan admin/panitia yang login
        if ($isClosed && ! Auth::check()) {
            return view('live-count-closed', compact('config'));
        }

        $calonOsis = CalonKetua::with('kelas')->osis()->orderBy('nomor')->get();
        $calonMpk = CalonKetua::with('kelas')->mpk()->orderBy('nomor')->get();

        $totalVote = Vote::count();
        $totalHakSuara = HakSuara::count();

        return view('live-count', compact(
            'calonOsis', 'calonMpk', 'totalVote', 'totalHakSuara', 'config', 'isClosed'
        ));
    }

    public function data(Request $request): JsonResponse
    {
        $config = json_decode(file_get_contents(base_path('config.json')), true) ?: [];
        $isClosed = ($config['livecount_status'] ?? 'open') === 'closed';

        if ($isClosed && ! Auth::check()) {
            return response()->json([
                'closed' => true,
                'message' => $config['livecount_closed_message'] ?? 'Live count ditutup sementara.',
            ]);
        }

        $mode = $request->query('mode', 'quick'); // 'quick' (suara digital) or 'pleno' (hanya suara sah)

        $calons = CalonKetua::with('kelas')
            ->withCount([
                'votes as digital_votes' => function ($q) {
                    $q->where('tipe_pemilihan', 'osis');
                },
                'votes as valid_votes' => function ($q) {
                    $q->where('tipe_pemilihan', 'osis')->where('status_verifikasi', 'sah');
                },
                'votes as mpk_digital_votes' => function ($q) {
                    $q->where('tipe_pemilihan', 'mpk');
                },
                'votes as mpk_valid_votes' => function ($q) {
                    $q->where('tipe_pemilihan', 'mpk')->where('status_verifikasi', 'sah');
                },
            ])
            ->orderBy('nomor')
            ->get();

        $totalOsisVote = Vote::where('tipe_pemilihan', 'osis')->count();
        $totalMpkVote = Vote::where('tipe_pemilihan', 'mpk')->count();

        $totalOsisSah = Vote::where('tipe_pemilihan', 'osis')->where('status_verifikasi', 'sah')->count();
        $totalMpkSah = Vote::where('tipe_pemilihan', 'mpk')->where('status_verifikasi', 'sah')->count();

        $totalOsisTidakSah = Vote::where('tipe_pemilihan', 'osis')->where('status_verifikasi', 'tidak_sah')->count();
        $totalMpkTidakSah = Vote::where('tipe_pemilihan', 'mpk')->where('status_verifikasi', 'tidak_sah')->count();

        $totalOsisPending = Vote::where('tipe_pemilihan', 'osis')->where('status_verifikasi', 'pending')->count();
        $totalMpkPending = Vote::where('tipe_pemilihan', 'mpk')->where('status_verifikasi', 'pending')->count();

        $totalDigitalVote = $totalOsisVote + $totalMpkVote;
        $totalSahVote = $totalOsisSah + $totalMpkSah;
        $totalTidakSahVote = $totalOsisTidakSah + $totalMpkTidakSah;
        $totalPendingVote = $totalOsisPending + $totalMpkPending;

        $totalHakSuara = HakSuara::count();
        $totalSiswa = HakSuara::where('tipe', 'siswa')->count();
        $siswaMemilih = HakSuara::where('tipe', 'siswa')->has('votes')->count();
        $totalGuru = HakSuara::where('tipe', 'guru')->count();
        $guruMemilih = HakSuara::where('tipe', 'guru')->has('votes')->count();

        $recentVotes = Vote::with(['calon', 'hakSuara.kelas'])
            ->when($mode === 'pleno', fn ($q) => $q->where('status_verifikasi', 'sah'))
            ->orderByDesc('created_at')
            ->limit(8)
            ->get()
            ->map(function ($vote) {
                return [
                    'id' => $vote->id,
                    'voter' => $vote->hakSuara->nisn,
                    'tipe' => $vote->hakSuara->tipe,
                    'kelas' => $vote->hakSuara->kelas->name ?? null,
                    'candidate' => $vote->calon->nama,
                    'candidate_nomor' => $vote->calon->nomor,
                    'tipe_pemilihan' => $vote->tipe_pemilihan,
                    'status_verifikasi' => $vote->status_verifikasi,
                    'time' => \Carbon\Carbon::parse($vote->created_at)->format('H:i:s'),
                    'diff' => \Carbon\Carbon::parse($vote->created_at)->diffForHumans(),
                ];
            });

        $kelasStats = Kelas::withCount([
            'hakSuara as total_dpt',
            'hakSuara as total_voted' => function ($q) {
                $q->has('votes');
            },
        ])->get()->map(function ($k) {
            return [
                'id' => $k->id,
                'name' => $k->name,
                'total_dpt' => $k->total_dpt,
                'total_voted' => $k->total_voted,
                'percentage' => $k->total_dpt > 0 ? round(($k->total_voted / $k->total_dpt) * 100, 1) : 0,
            ];
        })->sortBy('id')->values();

        $osisData = $calons->filter(fn ($c) => $c->tipe === 'osis')->values();
        $mpkData = $calons->filter(fn ($c) => $c->tipe === 'mpk')->values();

        $osisActive = $mode === 'pleno' ? $totalOsisSah : $totalOsisVote;
        $mpkActive = $mode === 'pleno' ? $totalMpkSah : $totalMpkVote;

        $mapOsis = fn ($c) => [
            'id' => $c->id,
            'nomor' => $c->nomor,
            'nama' => $c->nama,
            'kelas' => $c->kelas->name ?? '-',
            'url_foto' => $c->url_foto ? asset($c->url_foto) : null,
            'votes' => $mode === 'pleno' ? $c->valid_votes : $c->digital_votes,
            'digital_votes' => $c->digital_votes,
            'valid_votes' => $c->valid_votes,
            'percentage' => $osisActive > 0 ? round((($mode === 'pleno' ? $c->valid_votes : $c->digital_votes) / $osisActive) * 100, 1) : 0,
        ];

        $mapMpk = fn ($c) => [
            'id' => $c->id,
            'nomor' => $c->nomor,
            'nama' => $c->nama,
            'kelas' => $c->kelas->name ?? '-',
            'url_foto' => $c->url_foto ? asset($c->url_foto) : null,
            'votes' => $mode === 'pleno' ? $c->mpk_valid_votes : $c->mpk_digital_votes,
            'digital_votes' => $c->mpk_digital_votes,
            'valid_votes' => $c->mpk_valid_votes,
            'percentage' => $mpkActive > 0 ? round((($mode === 'pleno' ? $c->mpk_valid_votes : $c->mpk_digital_votes) / $mpkActive) * 100, 1) : 0,
        ];

        return response()->json([
            'mode' => $mode,
            'total_vote' => $osisActive + $mpkActive,
            'total_digital_vote' => $totalDigitalVote,
            'total_sah' => $totalSahVote,
            'total_tidak_sah' => $totalTidakSahVote,
            'total_pending' => $totalPendingVote,

            // Pemilihan OSIS
            'osis_total_vote' => $osisActive,
            'osis_total_digital' => $totalOsisVote,
            'osis_total_sah' => $totalOsisSah,
            'osis_total_tidak_sah' => $totalOsisTidakSah,
            'osis_total_pending' => $totalOsisPending,

            // Pemilihan MPK
            'mpk_total_vote' => $mpkActive,
            'mpk_total_digital' => $totalMpkVote,
            'mpk_total_sah' => $totalMpkSah,
            'mpk_total_tidak_sah' => $totalMpkTidakSah,
            'mpk_total_pending' => $totalMpkPending,

            'total_hak_suara' => $totalHakSuara,
            'partisipasi' => $totalHakSuara > 0 ? round(($siswaMemilih + $guruMemilih) / $totalHakSuara * 100, 1) : 0,
            'total_siswa' => $totalSiswa,
            'siswa_memilih' => $siswaMemilih,
            'partisipasi_siswa' => $totalSiswa > 0 ? round(($siswaMemilih / $totalSiswa) * 100, 1) : 0,
            'total_guru' => $totalGuru,
            'guru_memilih' => $guruMemilih,
            'partisipasi_guru' => $totalGuru > 0 ? round(($guruMemilih / $totalGuru) * 100, 1) : 0,
            'osis_candidates' => $osisData->map($mapOsis)->values(),
            'mpk_candidates' => $mpkData->map($mapMpk)->values(),
            'recent_votes' => $recentVotes,
            'kelas_stats' => $kelasStats,
            'updated_at' => now()->format('H:i:s'),
        ]);
    }
}
