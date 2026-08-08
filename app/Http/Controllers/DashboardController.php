<?php

namespace App\Http\Controllers;

use App\Models\CalonKetua;
use App\Models\HakSuara;
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

        $recentVotes = Vote::with(['calon', 'hakSuara'])
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        $votesByDate = Vote::select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as count'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return view('dashboard.index', compact('calons', 'totalCalon', 'totalVote', 'hakSuara', 'partisipasi', 'recentVotes', 'votesByDate'));
    }
}
