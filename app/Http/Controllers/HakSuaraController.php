<?php

namespace App\Http\Controllers;

use App\Models\HakSuara;
use App\Models\Kelas;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;

class HakSuaraController extends Controller
{
    public function index(Request $request)
    {
        $query = HakSuara::with(['kelas'])->withCount('votes');

        if ($request->filled('search')) {
            $query->where('nisn', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('tipe')) {
            $query->where('tipe', $request->tipe);
        }

        if ($request->filled('id_kelas')) {
            $query->where('id_kelas', $request->id_kelas);
        }

        if ($request->filled('status')) {
            if ($request->status === 'sudah') {
                $query->has('votes');
            } elseif ($request->status === 'belum') {
                $query->doesntHave('votes');
            }
        }

        $hakSuaras = $query->orderBy('id', 'desc')->get();
        $totalHakSuara = HakSuara::count();
        $totalSiswa = HakSuara::where('tipe', 'siswa')->count();
        $totalGuru = HakSuara::where('tipe', 'guru')->count();
        $kelas = Kelas::orderBy('name')->get();

        return view('hak-suara.index', compact('hakSuaras', 'totalHakSuara', 'totalSiswa', 'totalGuru', 'kelas'));
    }

    public function store(Request $request)
    {
        $rules = [
            'nisn' => [
                'required',
                'string',
                'max:255',
                'unique:hak_suara,nisn',
                'regex:/^[\p{L}\s\.\-\']+$/u',
            ],
            'tipe' => 'required|in:siswa,guru',
            'id_kelas' => 'nullable|exists:kelas,id',
        ];

        if ($request->tipe === 'siswa') {
            $rules['id_kelas'] = 'required|exists:kelas,id';
        }

        $request->validate($rules, [
            'nisn.regex'  => 'Nama pemilih hanya boleh mengandung huruf, spasi, titik, dan tanda hubung.',
            'nisn.unique' => 'Nama pemilih ini sudah terdaftar dalam daftar hak suara.',
            'id_kelas.required' => 'Kelas wajib dipilih untuk pemilih tipe Siswa.',
        ]);

        HakSuara::create([
            'nisn' => $request->nisn,
            'tipe' => $request->tipe,
            'id_kelas' => $request->tipe === 'siswa' ? $request->id_kelas : null,
            'token' => HakSuara::generateUniqueToken(),
            'token_used' => false,
        ]);

        return redirect()->route('hak-suara.index')->with('success', 'Hak suara dan token pemilih berhasil ditambahkan!');
    }

    public function destroy(HakSuara $hakSuara)
    {
        if ($hakSuara->hasVoted()) {
            return redirect()->route('hak-suara.index')->with('error', 'Tidak dapat menghapus karena pemilih sudah pernah voting!');
        }

        $hakSuara->delete();

        return redirect()->route('hak-suara.index')->with('success', 'Hak suara berhasil dihapus!');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file_excel' => 'required|file|mimes:xls,xlsx',
            'tipe_import' => 'nullable|in:siswa,guru',
        ]);

        try {
            $spreadsheet = IOFactory::load($request->file('file_excel')->getPathname());
            $rows = $spreadsheet->getActiveSheet()->toArray();

            $imported = 0;
            $skipped = 0;

            // Cache kelas mapping by name (case-insensitive)
            $kelasMap = Kelas::all()->keyBy(fn ($k) => strtolower(trim($k->name)));

            $tipeDefault = $request->input('tipe_import', 'siswa');

            foreach ($rows as $index => $row) {
                if ($index === 0) {
                    continue;
                }

                $nama = trim($row[1] ?? '');
                $kelasNama = trim($row[2] ?? '');
                $tipeRow = strtolower(trim($row[3] ?? ''));

                if (empty($nama)) {
                    $skipped++;
                    continue;
                }

                $exists = HakSuara::where('nisn', $nama)->exists();
                if ($exists) {
                    $skipped++;
                    continue;
                }

                $tipe = in_array($tipeRow, ['guru', 'siswa']) ? $tipeRow : $tipeDefault;
                $idKelas = null;

                if ($tipe === 'siswa' && !empty($kelasNama)) {
                    $kelasObj = $kelasMap->get(strtolower($kelasNama));
                    if ($kelasObj) {
                        $idKelas = $kelasObj->id;
                    }
                }

                HakSuara::create([
                    'nisn' => $nama,
                    'tipe' => $tipe,
                    'id_kelas' => $idKelas,
                    'token' => HakSuara::generateUniqueToken(),
                    'token_used' => false,
                ]);

                $imported++;
            }

            return redirect()->route('hak-suara.index')->with('success', "Import selesai! {$imported} data ditambahkan, {$skipped} data dilewati (duplikat/kosong).");
        } catch (\Exception $e) {
            return redirect()->route('hak-suara.index')->with('error', 'Gagal mengimport file: '.$e->getMessage());
        }
    }

    public function downloadSample()
    {
        $file = public_path('downloads/sample_hak_suara.xlsx');
        if (file_exists($file)) {
            return response()->download($file, 'sample_hak_suara.xlsx');
        }

        return redirect()->route('hak-suara.index')->with('error', 'File sample tidak ditemukan.');
    }
}

