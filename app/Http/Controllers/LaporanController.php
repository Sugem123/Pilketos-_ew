<?php

namespace App\Http\Controllers;

use App\Models\CalonKetua;
use App\Models\HakSuara;
use App\Models\Vote;

class LaporanController extends Controller
{
    public function index()
    {
        $calons = CalonKetua::with('kelas')
            ->withCount('votes')
            ->orderByDesc('votes_count')
            ->get();

        $totalVote = Vote::count();
        $hakSuara = HakSuara::count();
        $partisipasi = $hakSuara > 0 ? number_format(($totalVote / $hakSuara) * 100, 1) : 0;

        return view('laporan.index', compact('calons', 'totalVote', 'hakSuara', 'partisipasi'));
    }
}
