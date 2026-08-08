<?php

namespace App\Http\Controllers;

use App\Models\CalonKetua;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CalonController extends Controller
{
    public function index()
    {
        $calons = CalonKetua::with('kelas')->orderBy('id', 'desc')->get();
        $kelas = Kelas::orderBy('name')->get();
        $editData = null;

        return view('calon.index', compact('calons', 'kelas', 'editData'));
    }

    public function edit(CalonKetua $calon)
    {
        $calons = CalonKetua::with('kelas')->orderBy('id', 'desc')->get();
        $kelas = Kelas::orderBy('name')->get();

        return view('calon.index', compact('calons', 'kelas', 'calon'))->with('editData', $calon);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:256',
            'id_kelas' => 'required|exists:kelas,id',
            'nomor' => 'required|integer',
            'foto_calon' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'visi' => 'required|string|max:521',
            'misi' => 'required|string|max:1000',
        ]);

        $path = $request->file('foto_calon')->store('foto_calon', 'public');

        CalonKetua::create([
            'nama' => $request->nama,
            'nomor' => $request->nomor,
            'visi' => $request->visi,
            'misi' => $request->misi,
            'id_kelas' => $request->id_kelas,
            'url_foto' => 'storage/'.$path,
        ]);

        return redirect()->route('calon.index')->with('success', 'Calon berhasil ditambahkan!');
    }

    public function update(Request $request, CalonKetua $calon)
    {
        $request->validate([
            'nama' => 'required|string|max:256',
            'id_kelas' => 'required|exists:kelas,id',
            'nomor' => 'required|integer',
            'foto_calon' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'visi' => 'required|string|max:521',
            'misi' => 'required|string|max:1000',
        ]);

        $data = [
            'nama' => $request->nama,
            'nomor' => $request->nomor,
            'visi' => $request->visi,
            'misi' => $request->misi,
            'id_kelas' => $request->id_kelas,
        ];

        if ($request->hasFile('foto_calon')) {
            if ($calon->url_foto) {
                Storage::disk('public')->delete(str_replace('storage/', '', $calon->url_foto));
            }
            $path = $request->file('foto_calon')->store('foto_calon', 'public');
            $data['url_foto'] = 'storage/'.$path;
        }

        $calon->update($data);

        return redirect()->route('calon.index')->with('success', 'Data calon berhasil diupdate!');
    }

    public function destroy(CalonKetua $calon)
    {
        if ($calon->url_foto) {
            Storage::disk('public')->delete(str_replace('storage/', '', $calon->url_foto));
        }
        $calon->delete();

        return redirect()->route('calon.index')->with('success', 'Calon berhasil dihapus!');
    }

    public function updateHaksuara(Request $request)
    {
        $request->validate([
            'haksuara' => 'required|integer|min:1',
        ]);

        $file = base_path('config.json');
        $config = ['haksuara' => (int) $request->haksuara];
        file_put_contents($file, json_encode($config, JSON_PRETTY_PRINT));

        return redirect()->route('calon.index')->with('success', 'Batas hak suara berhasil diupdate!');
    }
}
