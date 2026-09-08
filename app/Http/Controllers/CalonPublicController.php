<?php

namespace App\Http\Controllers;

use App\Models\CalonKetua;

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
}
