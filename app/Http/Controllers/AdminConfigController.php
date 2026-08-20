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
            'undangan_pembuka' => 'required|string|max:1000',
            'undangan_hari_tanggal' => 'required|string|max:100',
            'undangan_waktu' => 'required|string|max:100',
            'undangan_lokasi' => 'required|string|max:255',
            'undangan_catatan_kaki' => 'nullable|string|max:500',
            'undangan_penandatangan' => 'required|string|max:100',
        ]);

        $config = $this->getConfig();

        $config['undangan_judul_kop'] = $request->undangan_judul_kop;
        $config['undangan_sub_kop'] = $request->undangan_sub_kop;
        $config['undangan_pembuka'] = $request->undangan_pembuka;
        $config['undangan_hari_tanggal'] = $request->undangan_hari_tanggal;
        $config['undangan_waktu'] = $request->undangan_waktu;
        $config['undangan_lokasi'] = $request->undangan_lokasi;
        $config['undangan_catatan_kaki'] = $request->undangan_catatan_kaki ?? '';
        $config['undangan_penandatangan'] = $request->undangan_penandatangan;

        file_put_contents(base_path('config.json'), json_encode($config, JSON_PRETTY_PRINT));

        return redirect()->route('admin-config.index')->with('success', 'Format surat undangan panggilan berhasil diperbarui!');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_lengkap' => 'required|string|max:256',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        User::create([
            'nama_lengkap' => $request->nama_lengkap,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('admin-config.index')->with('success', 'Admin berhasil ditambahkan!');
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'nama_lengkap' => 'required|string|max:256',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        $data = [
            'nama_lengkap' => $request->nama_lengkap,
            'email' => $request->email,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('admin-config.index')->with('success', 'Data admin berhasil diupdate!');
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

