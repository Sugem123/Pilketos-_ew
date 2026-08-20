<?php

namespace App\Http\Controllers;

use App\Models\HakSuara;
use App\Models\Kelas;
use Illuminate\Http\Request;

class PrintController extends Controller
{
    public function undangan(Request $request)
    {
        $query = HakSuara::with('kelas');

        if ($request->filled('tipe')) {
            $query->where('tipe', $request->tipe);
        }

        if ($request->filled('id_kelas')) {
            $query->where('id_kelas', $request->id_kelas);
        }

        if ($request->filled('id')) {
            $query->where('id', $request->id);
        }

        $pemilihs = $query->orderBy('tipe')->orderBy('id_kelas')->orderBy('nisn')->get();

        // Pastikan setiap pemilih memiliki token
        foreach ($pemilihs as $p) {
            if (empty($p->token)) {
                $p->update(['token' => HakSuara::generateUniqueToken()]);
            }
        }

        $config = $this->getConfig();

        // Baca dari config.json field undangan_*, fallback ke default
        $tanggal = $config['undangan_hari_tanggal'] ?? now()->translatedFormat('l, d F Y');
        $waktu = $config['undangan_waktu'] ?? '08.00 - 13.00 WIB';
        $lokasi = $config['undangan_lokasi'] ?? ($config['alamat_sekolah'] ?? 'Bilik Suara TPS Pemilihan OSIS');

        return view('print.undangan', compact('pemilihs', 'tanggal', 'waktu', 'lokasi', 'config'));
    }

    public function kartu(Request $request)
    {
        $query = HakSuara::with('kelas');

        if ($request->filled('tipe')) {
            $query->where('tipe', $request->tipe);
        }

        if ($request->filled('id_kelas')) {
            $query->where('id_kelas', $request->id_kelas);
        }

        if ($request->filled('id')) {
            $query->where('id', $request->id);
        }

        $pemilihs = $query->orderBy('tipe')->orderBy('id_kelas')->orderBy('nisn')->get();

        // Pastikan token ter-generate
        foreach ($pemilihs as $p) {
            if (empty($p->token)) {
                $p->update(['token' => HakSuara::generateUniqueToken()]);
            }
        }

        $config = $this->getConfig();
        return view('print.kartu', compact('pemilihs', 'config'));
    }

    public function beritaAcara(Request $request)
    {
        $config = $this->getConfig();
        $totalHakSuara = HakSuara::count();
        $totalSiswa = HakSuara::where('tipe', 'siswa')->count();
        $totalGuru = HakSuara::where('tipe', 'guru')->count();

        $totalSuaraDigital = \App\Models\Vote::count();
        $totalSah = \App\Models\Vote::where('status_verifikasi', 'sah')->count();
        $totalTidakSah = \App\Models\Vote::where('status_verifikasi', 'tidak_sah')->count();
        $totalPending = \App\Models\Vote::where('status_verifikasi', 'pending')->count();

        $calons = \App\Models\CalonKetua::with('kelas')
            ->withCount([
                'votes as digital_votes',
                'votes as valid_votes' => function ($q) {
                    $q->where('status_verifikasi', 'sah');
                },
                'votes as invalid_votes' => function ($q) {
                    $q->where('status_verifikasi', 'tidak_sah');
                },
            ])
            ->orderBy('nomor')
            ->get();

        $winner = $calons->sortByDesc('valid_votes')->first();
        $tanggal = now()->translatedFormat('l, d F Y');

        return view('print.berita-acara', compact(
            'totalHakSuara',
            'totalSiswa',
            'totalGuru',
            'totalSuaraDigital',
            'totalSah',
            'totalTidakSah',
            'totalPending',
            'calons',
            'winner',
            'tanggal',
            'config'
        ));
    }

    private function getConfig(): array
    {
        $path = base_path('config.json');
        if (file_exists($path)) {
            return json_decode(file_get_contents($path), true) ?: [];
        }

        return [
            'nama_sekolah' => 'SMA / SMK NEGERI PILKETOS',
            'nama_kegiatan' => 'PEMILIHAN KETUA OSIS',
            'tahun_ajaran' => '2026/2027',
            'alamat_sekolah' => 'Jl. Pendidikan No. 1',
            'url_logo' => 'img/logo.png',
            'haksuara' => 150,
        ];
    }
}

