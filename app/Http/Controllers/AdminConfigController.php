<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AdminConfigController extends Controller
{
    public function index()
    {
        $users = User::orderBy('id', 'desc')->get();
        $config = $this->getConfig();

        return view('admin-config.index', compact('users', 'config'));
    }

    public function updateSchoolProfile(Request $request)
    {
        $request->validate([
            'nama_sekolah' => 'required|string|max:255',
            'nama_kegiatan' => 'required|string|max:255',
            'tahun_ajaran' => 'required|string|max:50',
            'alamat_sekolah' => 'nullable|string|max:255',
            'haksuara' => 'required|integer|min:1',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,svg,webp|max:3072',
        ]);

        $config = $this->getConfig();

        $config['nama_sekolah'] = $request->nama_sekolah;
        $config['nama_kegiatan'] = $request->nama_kegiatan;
        $config['tahun_ajaran'] = $request->tahun_ajaran;
        $config['alamat_sekolah'] = $request->alamat_sekolah ?? '';
        $config['haksuara'] = (int) $request->haksuara;

        if ($request->hasFile('logo') && $request->file('logo')->isValid()) {
            // Delete old uploaded logo if in storage
            if (!empty($config['url_logo']) && str_starts_with($config['url_logo'], 'storage/')) {
                $oldPath = str_replace('storage/', '', $config['url_logo']);
                if (Storage::disk('public')->exists($oldPath)) {
                    Storage::disk('public')->delete($oldPath);
                }
            }

            $path = $request->file('logo')->store('branding', 'public');
            $config['url_logo'] = 'storage/' . $path;
        }

        file_put_contents(base_path('config.json'), json_encode($config, JSON_PRETTY_PRINT));

        return redirect()->route('admin-config.index')->with('success', 'Profil sekolah dan logo berhasil diperbarui!');
    }

    public function updateUndanganTemplate(Request $request)
    {
        $request->validate([
            'undangan_judul_kop' => 'required|string|max:255',
            'undangan_sub_kop' => 'required|string|max:255',
            'undangan_mode_penerima' => 'required|in:sistem,kosong',
            'undangan_pembuka' => 'required|string|max:1000',
            'undangan_hari_tanggal' => 'required|string|max:100',
            'undangan_waktu' => 'required|string|max:100',
            'undangan_lokasi' => 'required|string|max:255',
            'undangan_catatan_kaki' => 'nullable|string|max:500',
            'undangan_penandatangan' => 'required|string|max:100',
            'undangan_nama_pejabat' => 'required|string|max:150',
            'undangan_jabatan_pejabat' => 'nullable|string|max:150',
            'undangan_ttd_file' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:3072',
            'hapus_ttd' => 'nullable',
        ]);

        $config = $this->getConfig();

        // Handle upload scan tanda tangan ketua pelaksana
        if ($request->hasFile('undangan_ttd_file') && $request->file('undangan_ttd_file')->isValid()) {
            if (!empty($config['url_ttd_undangan'])) {
                $oldPath = str_replace('storage/', '', $config['url_ttd_undangan']);
                if (Storage::disk('public')->exists($oldPath)) {
                    Storage::disk('public')->delete($oldPath);
                }
            }
            $path = $request->file('undangan_ttd_file')->store('branding', 'public');
            $config['url_ttd_undangan'] = 'storage/' . $path;
        } elseif ($request->filled('hapus_ttd')) {
            if (!empty($config['url_ttd_undangan'])) {
                $oldPath = str_replace('storage/', '', $config['url_ttd_undangan']);
                if (Storage::disk('public')->exists($oldPath)) {
                    Storage::disk('public')->delete($oldPath);
                }
            }
            $config['url_ttd_undangan'] = null;
        }

        $config['undangan_judul_kop'] = $request->undangan_judul_kop;
        $config['undangan_sub_kop'] = $request->undangan_sub_kop;
        $config['undangan_mode_penerima'] = $request->undangan_mode_penerima;
        $config['undangan_pembuka'] = $request->undangan_pembuka;
        $config['undangan_hari_tanggal'] = $request->undangan_hari_tanggal;
        $config['undangan_waktu'] = $request->undangan_waktu;
        $config['undangan_lokasi'] = $request->undangan_lokasi;
        $config['undangan_catatan_kaki'] = $request->undangan_catatan_kaki ?? '';
        $config['undangan_penandatangan'] = $request->undangan_penandatangan;
        $config['undangan_nama_pejabat'] = $request->undangan_nama_pejabat;
        $config['undangan_jabatan_pejabat'] = $request->undangan_jabatan_pejabat ?: $request->undangan_penandatangan;

        file_put_contents(base_path('config.json'), json_encode($config, JSON_PRETTY_PRINT));

        return redirect()->route('admin-config.index')->with('success', 'Format surat undangan dan tanda tangan berhasil diperbarui!');
    }

    public function updateVotingSettings(Request $request)
    {
        $request->validate([
            'voting_timer' => 'required|integer|min:5|max:3600',
            'livecount_interval' => 'required|integer|min:1|max:1440',
            'livecount_status' => 'required|in:open,closed',
            'livecount_closed_message' => 'nullable|string|max:500',
            'jumlah_calon_osis' => 'required|integer|min:1|max:20',
            'jumlah_calon_mpk' => 'required|integer|min:1|max:20',
        ]);

        $config = $this->getConfig();

        $config['voting_timer'] = (int) $request->voting_timer;
        $config['livecount_interval'] = (int) $request->livecount_interval;
        $config['livecount_status'] = $request->livecount_status;
        $config['livecount_closed_message'] = $request->livecount_closed_message ?: 'Perolehan suara langsung (Live Count) ditutup sementara oleh panitia.';
        $config['jumlah_calon_osis'] = (int) $request->jumlah_calon_osis;
        $config['jumlah_calon_mpk'] = (int) $request->jumlah_calon_mpk;

        file_put_contents(base_path('config.json'), json_encode($config, JSON_PRETTY_PRINT));

        return redirect()->route('admin-config.index')->with('success', 'Pengaturan voting & live count berhasil diperbarui!');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_lengkap' => 'required|string|max:256',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'nullable|in:admin,operator',
        ]);

        User::create([
            'nama_lengkap' => $request->nama_lengkap,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->input('role', User::ROLE_ADMIN),
        ]);

        return redirect()->route('admin-config.index')->with('success', 'Akun pengguna berhasil ditambahkan!');
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'nama_lengkap' => 'required|string|max:256',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'password' => 'nullable|string|min:6|confirmed',
            'role' => 'nullable|in:admin,operator',
        ]);

        $data = [
            'nama_lengkap' => $request->nama_lengkap,
            'email' => $request->email,
        ];

        if ($request->filled('role')) {
            $data['role'] = $request->role;
        }

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('admin-config.index')->with('success', 'Data akun berhasil diupdate!');
    }

    public function destroy(User $user)
    {
        if ($user->id === Auth::id()) {
            return redirect()->route('admin-config.index')->with('error', 'Tidak dapat menghapus akun sendiri!');
        }

        $user->delete();

        return redirect()->route('admin-config.index')->with('success', 'Admin berhasil dihapus!');
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

