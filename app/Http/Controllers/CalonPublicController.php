<?php

namespace App\Http\Controllers;

use App\Models\CalonKetua;
use App\Models\HakSuara;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CalonPublicController extends Controller
{
    public function index()
    {
        $calonOsis = CalonKetua::with('kelas')->osis()->orderBy('nomor')->get();
        $calonMpk = CalonKetua::with('kelas')->mpk()->orderBy('nomor')->get();

        $config = json_decode(file_get_contents(base_path('config.json')), true);

        return view('calon-public.index', compact('calonOsis', 'calonMpk', 'config'));
    }

    public function show(CalonKetua $calon)
    {
        $calon->load('kelas');
        $config = json_decode(file_get_contents(base_path('config.json')), true);
        $totalCalon = CalonKetua::where('tipe', $calon->tipe)->count();

        return view('calon-public.show', compact('calon', 'config', 'totalCalon'));
    }

    public function checkDpt(Request $request): JsonResponse
    {
        $request->validate([
            'nama' => 'required|string|min:2|max:100',
        ]);

        $query = trim($request->nama);

        $pemilihs = HakSuara::with('kelas')
            ->where('nisn', 'like', "%{$query}%")
            ->orderBy('tipe')
            ->orderBy('id_kelas')
            ->orderBy('nisn')
            ->limit(10)
            ->get();

        if ($pemilihs->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => "Nama \"{$query}\" tidak ditemukan dalam Daftar Pemilih Tetap (DPT). Pastikan ejaan nama sesuai atau hubungi panitia pemilihan.",
            ]);
        }

        $results = $pemilihs->map(function ($p) {
            $hasVoted = $p->hasVoted();
            $tipeLabel = match ($p->tipe) {
                'guru' => 'Guru / Tendik',
                'simulasi' => 'Simulasi TPS',
                default => 'Siswa',
            };
            return [
                'id' => $p->id,
                'nama' => $p->nisn,
                'tipe' => $p->tipe,
                'tipe_label' => $tipeLabel,
                'kelas' => $p->kelas->name ?? '-',
                'has_voted' => $hasVoted,
                'status_label' => $hasVoted ? 'Sudah Menggunakan Hak Pilih' : 'Belum Memilih (Siap di TPS)',
            ];
        });

        return response()->json([
            'success' => true,
            'total' => $results->count(),
            'query' => $query,
            'data' => $results,
        ]);
    }
}
