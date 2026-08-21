<?php

namespace Database\Seeders;

use App\Models\CalonKetua;
use Illuminate\Database\Seeder;

class CalonSeeder extends Seeder
{
    public function run(): void
    {
        $calons = [
            [
                'nama' => 'Shabira Syahla Alvaliza',
                'nomor' => 1,
                'id_kelas' => 4,
                'url_foto' => 'storage/foto_calon/shabira-syahla-alvaliza.png',
                'visi' => 'Menjadikan OSIS sebagai wadah yang inklusif, kreatif, dan berprestasi untuk seluruh siswa, serta menciptakan lingkungan sekolah yang harmonis dan berintegritas.',
                'misi' => '1. Mengoptimalkan peran OSIS sebagai penghubung antara siswa dan sekolah.
2. Mengembangkan program kerja yang kreatif dan bermanfaat bagi seluruh siswa.
3. Meningkatkan kegiatan akademik dan non-akademik yang mendukung prestasi siswa.
4. Mempererat kekeluargaan dan solidaritas antar siswa melalui kegiatan positif.
5. Menjadi aspirasi siswa dan menjunjung tinggi transparansi dalam setiap kegiatan.',
            ],
            [
                'nama' => 'Faiz Nabil Akram',
                'nomor' => 2,
                'id_kelas' => 7,
                'url_foto' => 'storage/foto_calon/faiz-nabil-akram.png',
                'visi' => 'Mewujudkan OSIS yang profesional, berwawasan lingkungan, dan berorientasi pada pengembangan karakter siswa untuk menyongsong generasi emas Indonesia.',
                'misi' => '1. Membangun OSIS yang profesional dengan sistem manajemen yang terstruktur dan akuntabel.
2. Menggalakkan program peduli lingkungan seperti go green dan pengelolaan sampah.
3. Menyelenggarakan pelatihan kepemimpinan dan soft skill bagi seluruh siswa.
4. Memperbanyak kegiatan sosial dan bakti masyarakat untuk menumbuhkan kepedulian sosial.
5. Mendorong siswa berprestasi melalui kompetisi dan pembinaan yang berkelanjutan.',
            ],
            [
                'nama' => 'Fakih Abdul Karim',
                'nomor' => 3,
                'id_kelas' => 5,
                'url_foto' => 'storage/foto_calon/fakih-abdul-karim.png',
                'visi' => 'Menjadikan OSIS sebagai organisasi yang progresif, inovatif, dan mampu menjawab tantangan zaman melalui berbagai program unggulan yang berdampak nyata.',
                'misi' => '1. Mendorong inovasi dan kreativitas siswa melalui program-program unggulan berbasis teknologi.
2. Memperkuat komunikasi dan kolaborasi antar organisasi ekstrakurikuler di sekolah.
3. Mengadakan seminar, workshop, dan diskusi ilmiah untuk meningkatkan wawasan siswa.
4. Menjalin kerjasama dengan pihak eksternal untuk memperluas jaringan dan peluang siswa.
5. Menciptakan budaya disiplin, mandiri, dan berintegritas di lingkungan sekolah.',
            ],
        ];

        foreach ($calons as $calon) {
            CalonKetua::firstOrCreate(
                ['nomor' => $calon['nomor']],
                $calon
            );
        }
    }
}
