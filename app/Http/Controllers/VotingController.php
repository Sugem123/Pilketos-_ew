<?php

namespace App\Http\Controllers;

use App\Models\CalonKetua;
use App\Models\HakSuara;
use App\Models\Token;
use App\Models\Vote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VotingController extends Controller
{
    public function index()
    {
        $calonOsis = CalonKetua::with('kelas')
            ->osis()
            ->withCount('votes')
            ->orderBy('nomor')
            ->get();

        $calonMpk = CalonKetua::with('kelas')
            ->mpk()
            ->withCount('votes')
            ->orderBy('nomor')
            ->get();

        $totalVote = Vote::count();
        $totalHakSuara = HakSuara::count();
        $config = json_decode(file_get_contents(base_path('config.json')), true);

        return view('voting.index', compact(
            'calonOsis', 'calonMpk', 'totalVote', 'totalHakSuara', 'config'
        ));
    }

    public function vote(Request $request)
    {
        $request->validate([
            'id_calon_osis' => 'required|exists:calon_ketua,id',
            'id_calon_mpk' => 'required|exists:calon_ketua,id',
            'nisn' => 'required|string|max:255',
            'display_token' => 'required|string',
        ]);

        $displayToken = trim((string) $request->display_token);

        $personalHakSuara = HakSuara::where('token', $displayToken)->first();

        if ($personalHakSuara) {
            if ($personalHakSuara->token_used) {
                return $this->gagal('Token pada Kartu Pemilih ini sudah hangus (kedua pemilihan sudah diikuti).');
            }

            if ($personalHakSuara->hasVotedFor(CalonKetua::TIPE_OSIS) || $personalHakSuara->hasVotedFor(CalonKetua::TIPE_MPK)) {
                return $this->gagal('Pemilih ini sudah pernah memberikan suara dan tidak dapat memilih dua kali.');
            }

            $hakSuara = $personalHakSuara;
        } else {
            $token = Token::where('token', $displayToken)
                ->where('active', true)
                ->first();

            if (! $token) {
                return $this->gagal('Token bilik suara tidak valid atau kadaluarsa.');
            }

            $hakSuara = HakSuara::where('nisn', $request->nisn)->first();

            if (! $hakSuara) {
                return $this->gagal('Nama anda tidak terdaftar sebagai pemilih sah.');
            }

            if ($hakSuara->hasVotedFor(CalonKetua::TIPE_OSIS) || $hakSuara->hasVotedFor(CalonKetua::TIPE_MPK)) {
                return $this->gagal('Pemilih ini sudah pernah memberikan suara dan tidak dapat memilih dua kali.');
            }
        }

        $calonOsis = CalonKetua::where('id', $request->id_calon_osis)->where('tipe', CalonKetua::TIPE_OSIS)->first();
        $calonMpk = CalonKetua::where('id', $request->id_calon_mpk)->where('tipe', CalonKetua::TIPE_MPK)->first();

        if (! $calonOsis || ! $calonMpk) {
            return $this->gagal('Pilihan kandidat tidak valid untuk pemilihan OSIS/MPK.');
        }

        DB::transaction(function () use ($hakSuara, $calonOsis, $calonMpk) {
            Vote::create([
                'id_calon' => $calonOsis->id,
                'id_nisn' => $hakSuara->id,
                'tipe_pemilihan' => CalonKetua::TIPE_OSIS,
            ]);

            Vote::create([
                'id_calon' => $calonMpk->id,
                'id_nisn' => $hakSuara->id,
                'tipe_pemilihan' => CalonKetua::TIPE_MPK,
            ]);

            // Kedua pemilihan selesai dalam satu sesi — hanguskan token
            $hakSuara->update(['token_used' => true]);
        });

        return response()->json([
            'success' => true,
            'message' => 'Terima kasih! Suara Ketua OSIS dan Ketua MPK berhasil dicatat.',
            'voter_name' => $hakSuara->nisn,
            'osis_name' => $calonOsis->nama,
            'mpk_name' => $calonMpk->nama,
            'selesai' => true,
        ]);
    }

    private function gagal(string $pesan)
    {
        if (request()->expectsJson() || request()->ajax()) {
            return response()->json([
                'success' => false,
                'message' => $pesan,
            ], 422);
        }

        return back()->with('error', $pesan);
    }
}
