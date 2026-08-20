<?php

namespace Database\Seeders;

use App\Models\Kelas;
use Illuminate\Database\Seeder;

class KelasSeeder extends Seeder
{
    public function run(): void
    {
        $levels = ['X', 'XI', 'XII'];
        $kelas = [];

        foreach ($levels as $level) {
            for ($i = 1; $i <= 10; $i++) {
                $kelas[] = "{$level}-{$i}";
            }
        }

        foreach ($kelas as $name) {
            Kelas::firstOrCreate(['name' => $name]);
        }
    }
}

