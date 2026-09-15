<?php

namespace App\Http\Controllers;

use App\Models\BilikSuara;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BilikController extends Controller
{
    /**
     * Tampilan form aktivasi pairing di PC Bilik Suara
     */
    public function showPairingForm(Request $request)
    {
        $token = $request->session()->get('bilik_session_token') ?? $request->cookie('bilik_session_token');

        if ($token) {
            $bilik = BilikSuara::where('session_token', $token)->where('is_active', true)->first();
            if ($bilik) {
                return redirect()->route('voting.index');
            }
        }

        $config = json_decode(file_get_contents(base_path('config.json')), true) ?: [];

        return view('bilik.pairing', compact('config'));
    }

    /**
     * Proses submit kode pairing dari PC Bilik Suara
     */
    public function submitPairing(Request $request)
    {
        $request->validate([
            'pairing_code' => 'required|string|max:20',
        ]);

        $code = strtoupper(trim($request->pairing_code));
        $bilik = BilikSuara::where('pairing_code', $code)->first();

        if (! $bilik || ! $bilik->is_active) {
            return back()->withErrors(['pairing_code' => 'Kode pairing bilik tidak ditemukan atau tidak aktif.'])->withInput();
        }

        // Aturan: 1 Kode untuk 1 PC
        if ($bilik->isPaired()) {
            return back()->withErrors([
                'pairing_code' => "Kode pairing ini sudah dipakai pada {$bilik->nama_bilik} di perangkat lain. Minta Admin untuk melakukan reset pairing jika ingin memindahkan ke PC ini.",
            ])->withInput();
        }

        // Generate session token unik untuk PC ini
        $sessionToken = Str::random(60);

        $bilik->update([
            'session_token' => $sessionToken,
            'ip_address' => $request->ip(),
            'user_agent' => substr((string) $request->userAgent(), 0, 255),
            'paired_at' => now(),
            'last_seen_at' => now(),
        ]);

        // Simpan token ke Session dan Cookie (30 hari)
        $request->session()->put('bilik_session_token', $sessionToken);
        cookie()->queue(cookie('bilik_session_token', $sessionToken, 60 * 24 * 30));

        return redirect()->route('voting.index')
            ->with('success', "Aktivasi sukses! Selamat bertugas di {$bilik->nama_bilik}.");
    }

    /**
     * Putus pairing bilik dari PC
     */
    public function unpair(Request $request)
    {
        $token = $request->session()->get('bilik_session_token') ?? $request->cookie('bilik_session_token');

        if ($token) {
            BilikSuara::where('session_token', $token)->update([
                'session_token' => null,
            ]);
        }

        $request->session()->forget('bilik_session_token');
        cookie()->queue(cookie()->forget('bilik_session_token'));

        return redirect()->route('bilik.pairing-form')
            ->with('info', 'Sesi bilik suara telah diputus.');
    }

    // ─── MANAJEMEN BILIK DI PENGATURAN ADMIN ───

    public function index()
    {
        $biliks = BilikSuara::orderBy('id')->get();
        $totalBilik = $biliks->count();
        $pairedCount = $biliks->filter->isPaired()->count();
        $config = json_decode(file_get_contents(base_path('config.json')), true) ?: [];

        return view('bilik.index', compact('biliks', 'totalBilik', 'pairedCount', 'config'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_bilik' => 'required|string|max:100',
        ]);

        BilikSuara::create([
            'nama_bilik' => trim($request->nama_bilik),
            'pairing_code' => BilikSuara::generatePairingCode(),
            'is_active' => true,
        ]);

        return redirect()->route('admin.bilik.index')->with('success', 'Bilik suara baru berhasil ditambahkan!');
    }

    public function reset(BilikSuara $bilik)
    {
        $bilik->update([
            'session_token' => null,
            'paired_at' => null,
            'pairing_code' => BilikSuara::generatePairingCode(),
        ]);

        return redirect()->route('admin.bilik.index')->with('success', "Sesi pairing {$bilik->nama_bilik} berhasil di-reset dengan kode baru!");
    }

    public function toggleActive(BilikSuara $bilik)
    {
        $bilik->update([
            'is_active' => ! $bilik->is_active,
        ]);

        $status = $bilik->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return redirect()->route('admin.bilik.index')->with('success', "{$bilik->nama_bilik} berhasil {$status}!");
    }

    public function destroy(BilikSuara $bilik)
    {
        $bilik->delete();
        return redirect()->route('admin.bilik.index')->with('success', 'Bilik suara berhasil dihapus!');
    }
}
