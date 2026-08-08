<?php

namespace App\Http\Controllers;

use App\Models\CalonKetua;
use App\Models\HakSuara;
use App\Models\Vote;

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

        return view('dashboard.index', compact('calons', 'totalCalon', 'totalVote', 'hakSuara', 'partisipasi'));
    }
}
