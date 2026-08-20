<?php

namespace Database\Seeders;

use App\Models\HakSuara;
use App\Models\Kelas;
use Illuminate\Database\Seeder;

class HakSuaraSeeder extends Seeder
{
    public function run(): void
    {
        $k1 = Kelas::first();
        $k2 = Kelas::skip(1)->first();
        $k3 = Kelas::skip(2)->first();

        $dpt = [
            ['nisn' => 'Budi Santoso', 'tipe' => 'siswa', 'id_kelas' => $k1?->id],
            ['nisn' => 'Siti Rahmawati', 'tipe' => 'siswa', 'id_kelas' => $k1?->id],
            ['nisn' => 'Ahmad Fauzi', 'tipe' => 'siswa', 'id_kelas' => $k2?->id],
            ['nisn' => 'Dewi Lestari', 'tipe' => 'siswa', 'id_kelas' => $k2?->id],
            ['nisn' => 'Rian Pratama', 'tipe' => 'siswa', 'id_kelas' => $k3?->id],
            ['nisn' => 'Drs. Joko Susilo, M.Pd', 'tipe' => 'guru', 'id_kelas' => null],
            ['nisn' => 'Nurul Hidayati, S.Pd', 'tipe' => 'guru', 'id_kelas' => null],
            ['nisn' => 'Bambang Irawan, S.Kom', 'tipe' => 'guru', 'id_kelas' => null],
        ];

        foreach ($dpt as $item) {
            HakSuara::firstOrCreate(['nisn' => $item['nisn']], $item);
        }
    }
}

