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
            'foto_calon' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'foto_cropped_base64' => 'nullable|string',
            'visi' => 'required|string|max:521',
            'misi' => 'required|string|max:1000',
        ], [
            'nomor.min'     => 'Nomor urut harus minimal 1.',
            'nomor.max'     => 'Nomor urut maksimal 99.',
            'nomor.integer' => 'Nomor urut harus berupa angka bulat.',
        ]);

        if (! $request->hasFile('foto_calon') && ! $request->filled('foto_cropped_base64')) {
            return back()->withErrors(['foto_calon' => 'Foto kandidat wajib diunggah.'])->withInput();
        }

        // Jika nomor sudah terpakai, geser nomor calon lain ke nomor tertinggi + 1
        $exists = CalonKetua::where('tipe', $tipe)->where('nomor', $request->nomor)->first();
        if ($exists) {
            $maxNomor = CalonKetua::where('tipe', $tipe)->max('nomor') ?? 0;
            $exists->update(['nomor' => $maxNomor + 1]);
        }

        $urlFoto = $this->processPhoto($request);

        CalonKetua::create([
            'tipe' => $tipe,
            'nama' => $request->nama,
            'nomor' => $request->nomor,
            'visi' => $request->visi,
            'misi' => $request->misi,
            'id_kelas' => $request->id_kelas,
            'url_foto' => $urlFoto,
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
            'foto_cropped_base64' => 'nullable|string',
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

        $urlFoto = $this->processPhoto($request, $calon->url_foto);
        if ($urlFoto) {
            $data['url_foto'] = $urlFoto;
        }

        $calon->update($data);

        return redirect()->route('calon.index', ['tipe' => $tipe])
            ->with('success', 'Data kandidat '.strtoupper($tipe).' berhasil diupdate!');
    }

    private function processPhoto(Request $request, ?string $oldPhoto = null): ?string
    {
        // 1. Jika ada hasil crop gambar (base64)
        if ($request->filled('foto_cropped_base64')) {
            $base64 = $request->foto_cropped_base64;
            if (preg_match('/^data:image\/(\w+);base64,/', $base64)) {
                $data = substr($base64, strpos($base64, ',') + 1);
                $binary = base64_decode($data);
                if ($binary !== false) {
                    if ($oldPhoto) {
                        $oldPath = str_replace('storage/', '', $oldPhoto);
                        if (Storage::disk('public')->exists($oldPath)) {
                            Storage::disk('public')->delete($oldPath);
                        }
                    }
                    $filename = 'foto_calon/' . uniqid('calon_') . '.png';
                    Storage::disk('public')->put($filename, $binary);
                    return 'storage/' . $filename;
                }
            }
        }

        // 2. Jika ada upload file biasa
        if ($request->hasFile('foto_calon') && $request->file('foto_calon')->isValid()) {
            if ($oldPhoto) {
                $oldPath = str_replace('storage/', '', $oldPhoto);
                if (Storage::disk('public')->exists($oldPath)) {
                    Storage::disk('public')->delete($oldPath);
                }
            }
            $path = $request->file('foto_calon')->store('foto_calon', 'public');
            return 'storage/' . $path;
        }

        return null;
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
