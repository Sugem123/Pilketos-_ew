<?php

namespace App\Http\Controllers;

use App\Models\CalonKetua;
use App\Models\HakSuara;
use App\Models\Kelas;
use App\Models\Vote;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LiveCountController extends Controller
{
    public function index()
    {
        $calons = CalonKetua::with('kelas')
            ->withCount('votes')
            ->orderBy('nomor')
            ->get();

        $totalVote = Vote::count();
        $totalHakSuara = HakSuara::count();
        $config = json_decode(file_get_contents(base_path('config.json')), true);

        return view('live-count', compact('calons', 'totalVote', 'totalHakSuara', 'config'));
    }

    public function data(Request $request): JsonResponse
    {
        $mode = $request->query('mode', 'quick'); // 'quick' (suara digital) or 'pleno' (hanya suara sah)

        $calons = CalonKetua::with('kelas')
            ->withCount([
                'votes as digital_votes',
                'votes as valid_votes' => function ($q) {
                    $q->where('status_verifikasi', 'sah');
                },
            ])
            ->orderBy('nomor')
            ->get();

        $totalDigitalVote = Vote::count();
        $totalSahVote = Vote::where('status_verifikasi', 'sah')->count();
        $totalTidakSahVote = Vote::where('status_verifikasi', 'tidak_sah')->count();
        $totalPendingVote = Vote::where('status_verifikasi', 'pending')->count();

        $activeVoteCount = $mode === 'pleno' ? $totalSahVote : $totalDigitalVote;

        $totalHakSuara = HakSuara::count();
        $totalSiswa = HakSuara::where('tipe', 'siswa')->count();
        $siswaMemilih = HakSuara::where('tipe', 'siswa')->has('votes')->count();
        $totalGuru = HakSuara::where('tipe', 'guru')->count();
        $guruMemilih = HakSuara::where('tipe', 'guru')->has('votes')->count();

        $recentVotes = Vote::with(['calon', 'hakSuara.kelas'])
            ->when($mode === 'pleno', fn ($q) => $q->where('status_verifikasi', 'sah'))
            ->orderByDesc('created_at')
            ->limit(6)
            ->get()
            ->map(function ($vote) {
                return [
                    'id' => $vote->id,
                    'voter' => $vote->hakSuara->nisn,
                    'tipe' => $vote->hakSuara->tipe,
                    'kelas' => $vote->hakSuara->kelas->name ?? null,
                    'candidate' => $vote->calon->nama,
                    'candidate_nomor' => $vote->calon->nomor,
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

        $candidateData = $calons->map(function ($c) use ($activeVoteCount, $mode) {
            $votes = $mode === 'pleno' ? $c->valid_votes : $c->digital_votes;
            return [
                'id' => $c->id,
                'nomor' => $c->nomor,
                'nama' => $c->nama,
                'kelas' => $c->kelas->name ?? '-',
                'url_foto' => $c->url_foto ? asset($c->url_foto) : null,
                'votes' => $votes,
                'digital_votes' => $c->digital_votes,
                'valid_votes' => $c->valid_votes,
                'percentage' => $activeVoteCount > 0 ? round(($votes / $activeVoteCount) * 100, 1) : 0,
            ];
        });

        return response()->json([
            'mode' => $mode,
            'total_vote' => $activeVoteCount,
            'total_digital_vote' => $totalDigitalVote,
            'total_sah' => $totalSahVote,
            'total_tidak_sah' => $totalTidakSahVote,
            'total_pending' => $totalPendingVote,
            'total_hak_suara' => $totalHakSuara,
            'partisipasi' => $totalHakSuara > 0 ? round(($activeVoteCount / $totalHakSuara) * 100, 1) : 0,
            'total_siswa' => $totalSiswa,
            'siswa_memilih' => $siswaMemilih,
            'partisipasi_siswa' => $totalSiswa > 0 ? round(($siswaMemilih / $totalSiswa) * 100, 1) : 0,
            'total_guru' => $totalGuru,
            'guru_memilih' => $guruMemilih,
            'partisipasi_guru' => $totalGuru > 0 ? round(($guruMemilih / $totalGuru) * 100, 1) : 0,
            'candidates' => $candidateData,
            'recent_votes' => $recentVotes,
            'kelas_stats' => $kelasStats,
            'updated_at' => now()->format('H:i:s'),
        ]);
    }
}

