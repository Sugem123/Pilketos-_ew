<?php

namespace App\Http\Controllers;

use App\Models\CalonKetua;
use App\Models\HakSuara;
use App\Models\Kelas;
use App\Models\Vote;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $calons = CalonKetua::with('kelas')
            ->withCount('votes')
            ->orderByDesc('votes_count')
            ->get();

        $totalCalon = CalonKetua::count();
        $totalVote = Vote::count();
        $hakSuara = HakSuara::count();
        $partisipasi = $hakSuara > 0 ? number_format(($totalVote / $hakSuara) * 100, 1) : 0;

        $totalSiswa = HakSuara::where('tipe', 'siswa')->count();
        $siswaMemilih = HakSuara::where('tipe', 'siswa')->has('votes')->count();
        $partisipasiSiswa = $totalSiswa > 0 ? number_format(($siswaMemilih / $totalSiswa) * 100, 1) : 0;

        $totalGuru = HakSuara::where('tipe', 'guru')->count();
        $guruMemilih = HakSuara::where('tipe', 'guru')->has('votes')->count();
        $partisipasiGuru = $totalGuru > 0 ? number_format(($guruMemilih / $totalGuru) * 100, 1) : 0;

        // Class-level tracking (Siswa per kelas)
        $kelasStats = Kelas::withCount([
            'hakSuara as total_dpt',
            'hakSuara as total_voted' => function ($q) {
                $q->has('votes');
            },
        ])->get()->map(function ($k) {
            $k->percentage = $k->total_dpt > 0 ? round(($k->total_voted / $k->total_dpt) * 100, 1) : 0;
            return $k;
        })->sortBy('id')->values();

        $recentVotes = Vote::with(['calon', 'hakSuara.kelas'])
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        $votesByDate = Vote::select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as count'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return view('dashboard.index', compact(
            'calons',
            'totalCalon',
            'totalVote',
            'hakSuara',
            'partisipasi',
            'totalSiswa',
            'siswaMemilih',
            'partisipasiSiswa',
            'totalGuru',
            'guruMemilih',
            'partisipasiGuru',
            'kelasStats',
            'recentVotes',
            'votesByDate'
        ));
    }
}

