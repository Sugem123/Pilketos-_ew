<?php

namespace App\Http\Controllers;

use App\Models\CalonKetua;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CalonController extends Controller
{
    public function index(Request $request)
    {
        $tipe = $request->query('tipe', CalonKetua::TIPE_OSIS);

        if (! in_array($tipe, [CalonKetua::TIPE_OSIS, CalonKetua::TIPE_MPK])) {
            $tipe = CalonKetua::TIPE_OSIS;
        }

        $calons = CalonKetua::with('kelas')
            ->where('tipe', $tipe)
            ->orderBy('nomor')
            ->get();

        $kelas = Kelas::orderBy('name')->get();
        $config = json_decode(file_get_contents(base_path('config.json')), true);

        return view('calon.index', compact('calons', 'kelas', 'tipe', 'config'));
    }

    public function store(Request $request)
    {
        $tipe = $request->input('tipe', CalonKetua::TIPE_OSIS);

        if (! in_array($tipe, [CalonKetua::TIPE_OSIS, CalonKetua::TIPE_MPK])) {
            $tipe = CalonKetua::TIPE_OSIS;
        }

        $request->validate([
            'nama' => 'required|string|max:256',
            'id_kelas' => 'required|exists:kelas,id',
            'nomor' => 'required|integer|min:1|max:99',
            'foto_calon' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'visi' => 'required|string|max:521',
            'misi' => 'required|string|max:1000',
        ], [
            'nomor.min'     => 'Nomor urut harus minimal 1.',
            'nomor.max'     => 'Nomor urut maksimal 99.',
            'nomor.integer' => 'Nomor urut harus berupa angka bulat.',
        ]);

        // Jika nomor sudah terpakai, geser nomor calon lain ke nomor tertinggi + 1
        $exists = CalonKetua::where('tipe', $tipe)->where('nomor', $request->nomor)->first();
        if ($exists) {
            $maxNomor = CalonKetua::where('tipe', $tipe)->max('nomor') ?? 0;
            $exists->update(['nomor' => $maxNomor + 1]);
        }

        $path = $request->file('foto_calon')->store('foto_calon', 'public');

        CalonKetua::create([
            'tipe' => $tipe,
            'nama' => $request->nama,
            'nomor' => $request->nomor,
            'visi' => $request->visi,
            'misi' => $request->misi,
            'id_kelas' => $request->id_kelas,
            'url_foto' => 'storage/'.$path,
        ]);

        return redirect()->route('calon.index', ['tipe' => $tipe])
            ->with('success', 'Kandidat '.strtoupper($tipe).' berhasil ditambahkan!');
    }

    public function update(Request $request, CalonKetua $calon)
    {
        $tipe = $calon->tipe;

        $request->validate([
            'nama' => 'required|string|max:256',
            'id_kelas' => 'required|exists:kelas,id',
            'nomor' => 'required|integer|min:1|max:99',
            'foto_calon' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'visi' => 'required|string|max:521',
            'misi' => 'required|string|max:1000',
        ], [
            'nomor.min'     => 'Nomor urut harus minimal 1.',
            'nomor.max'     => 'Nomor urut maksimal 99.',
            'nomor.integer' => 'Nomor urut harus berupa angka bulat.',
        ]);

        $newNomor = (int) $request->nomor;
        $oldNomor = (int) $calon->nomor;

        // Auto-Swap: Jika nomor urut baru sudah dipakai paslon lain di tipe ini, tukar posisinya
        if ($newNomor !== $oldNomor) {
            $conflictCalon = CalonKetua::where('tipe', $tipe)
                ->where('nomor', $newNomor)
                ->where('id', '!=', $calon->id)
                ->first();

            if ($conflictCalon) {
                // Tukar: Calon yang bertabrakan diberi nomor lama dari calon ini
                $conflictCalon->update(['nomor' => $oldNomor]);
            }
        }

        $data = [
            'nama' => $request->nama,
            'nomor' => $newNomor,
            'visi' => $request->visi,
            'misi' => $request->misi,
            'id_kelas' => $request->id_kelas,
        ];

        if ($request->hasFile('foto_calon') && $request->file('foto_calon')->isValid()) {
            if ($calon->url_foto) {
                $oldPath = str_replace('storage/', '', $calon->url_foto);
                if (Storage::disk('public')->exists($oldPath)) {
                    Storage::disk('public')->delete($oldPath);
                }
            }
            $path = $request->file('foto_calon')->store('foto_calon', 'public');
            $data['url_foto'] = 'storage/'.$path;
        }

        $calon->update($data);

        return redirect()->route('calon.index', ['tipe' => $tipe])
            ->with('success', 'Data kandidat '.strtoupper($tipe).' berhasil diupdate!');
    }

    public function destroy(CalonKetua $calon)
    {
        $tipe = $calon->tipe;

        if ($calon->votes()->exists()) {
            return redirect()->route('calon.index', ['tipe' => $tipe])
                ->with('error', 'Kandidat sudah memiliki suara dan tidak dapat dihapus.');
        }

        if ($calon->url_foto) {
            Storage::disk('public')->delete(str_replace('storage/', '', $calon->url_foto));
        }
        $calon->delete();

        return redirect()->route('calon.index', ['tipe' => $tipe])
            ->with('success', 'Kandidat berhasil dihapus!');
    }

    /**
     * Tukar nomor urut kandidat naik atau turun (1 klik)
     */
    public function reorder(Request $request, CalonKetua $calon)
    {
        $request->validate([
            'direction' => 'required|in:up,down',
        ]);

        $tipe = $calon->tipe;
        $currentNomor = (int) $calon->nomor;

        if ($request->direction === 'up') {
            // Cari kandidat dengan nomor urut tepat di atasnya
            $neighbor = CalonKetua::where('tipe', $tipe)
                ->where('nomor', '<', $currentNomor)
                ->orderByDesc('nomor')
                ->first();
        } else {
            // Cari kandidat dengan nomor urut tepat di bawahnya
            $neighbor = CalonKetua::where('tipe', $tipe)
                ->where('nomor', '>', $currentNomor)
                ->orderBy('nomor')
                ->first();
        }

        if (! $neighbor) {
            return redirect()->route('calon.index', ['tipe' => $tipe])
                ->with('info', 'Nomor urut sudah berada di batas paling ujung.');
        }

        // Swap nomor
        $neighborNomor = (int) $neighbor->nomor;
        $neighbor->update(['nomor' => $currentNomor]);
        $calon->update(['nomor' => $neighborNomor]);

        return redirect()->route('calon.index', ['tipe' => $tipe])
            ->with('success', "Nomor urut {$calon->nama} berhasil ditukar menjadi 0{$neighborNomor}!");
    }
}
