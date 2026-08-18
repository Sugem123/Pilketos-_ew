<?php

namespace App\Http\Controllers;

use App\Models\HakSuara;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;

class HakSuaraController extends Controller
{
    public function index(Request $request)
    {
        $query = HakSuara::withCount('votes');

        if ($request->filled('search')) {
            $query->where('nisn', 'like', '%' . $request->search . '%');
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

        return view('hak-suara.index', compact('hakSuaras', 'totalHakSuara'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nisn' => [
                'required',
                'string',
                'max:255',
                'unique:hak_suara,nisn',
                'regex:/^[\p{L}\s\.\-\']+$/u',
            ],
        ], [
            'nisn.regex'  => 'Nama pemilih hanya boleh mengandung huruf, spasi, titik, dan tanda hubung.',
            'nisn.unique' => 'Nama pemilih ini sudah terdaftar dalam daftar hak suara.',
        ]);

        HakSuara::create(['nisn' => $request->nisn]);

        return redirect()->route('hak-suara.index')->with('success', 'Hak suara berhasil ditambahkan!');
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
        ]);

        try {
            $spreadsheet = IOFactory::load($request->file('file_excel')->getPathname());
            $rows = $spreadsheet->getActiveSheet()->toArray();

            $imported = 0;
            $skipped = 0;

            foreach ($rows as $index => $row) {
                if ($index === 0) {
                    continue;
                }

                $nama = trim($row[1] ?? '');

                if (empty($nama)) {
                    $skipped++;

                    continue;
                }

                $exists = HakSuara::where('nisn', $nama)->exists();
                if ($exists) {
                    $skipped++;

                    continue;
                }

                HakSuara::create(['nisn' => $nama]);
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
