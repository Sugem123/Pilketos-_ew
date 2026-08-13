<?php

namespace App\Http\Controllers;

use App\Models\CalonKetua;
use App\Models\HakSuara;
use App\Models\Token;
use App\Models\Vote;
use Illuminate\Http\Request;

class VotingController extends Controller
{
    public function index()
    {
        $calons = CalonKetua::with('kelas')
            ->withCount('votes')
            ->orderBy('id')
            ->get();

        $totalVote = Vote::count();
        $totalHakSuara = HakSuara::count();
        $config = json_decode(file_get_contents(base_path('config.json')), true);

        return view('voting.index', compact('calons', 'totalVote', 'totalHakSuara', 'config'));
    }

    public function vote(Request $request)
    {
        $request->validate([
            'id_calon' => 'required|exists:calon_ketua,id',
            'nisn' => 'required|string|max:255',
            'display_token' => 'required|string',
        ]);

        $token = Token::where('token', $request->display_token)
            ->where('active', true)
            ->first();

        if (! $token) {
            return back()->with('error', 'Token tidak valid atau kadaluarsa.');
        }

        $hakSuara = HakSuara::where('nisn', $request->nisn)->first();

        if (! $hakSuara) {
            return back()->with('error', 'Nama anda tidak terdaftar sebagai pemilih sah.');
        }

        $calon = CalonKetua::find($request->id_calon);
        if (! $calon) {
            return back()->with('error', 'Calon yang dipilih tidak valid.');
        }

        $existingVote = Vote::where('id_nisn', $hakSuara->id)->first();
        if ($existingVote) {
            return back()->with('error', 'Anda sudah pernah melakukan voting. Anda tidak dapat memilih dua kali.');
        }

        Vote::create([
            'id_calon' => $request->id_calon,
            'id_nisn' => $hakSuara->id,
        ]);

        return back()->with('success', 'Terima kasih telah berpartisipasi dalam pemilihan ketua OSIS!');
    }
}
