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
            $calon['tipe'] = CalonKetua::TIPE_OSIS;
            CalonKetua::firstOrCreate(
                [
                    'tipe' => CalonKetua::TIPE_OSIS,
                    'nomor' => $calon['nomor'],
                ],
                $calon
            );
        }

        $calonMpk = [
            [
                'tipe' => CalonKetua::TIPE_MPK,
                'nomor' => 1,
                'nama' => 'Arya Danendra Kusuma',
                'id_kelas' => 11,
                'url_foto' => 'storage/foto_calon/mpk-01-arya-danendra.png',
                'visi' => 'Mewujudkan MPK sebagai lembaga legislatif siswa yang independen, kritis, dan transparan dalam mengawal aspirasi siswa serta mengawasi kinerja OSIS secara konstruktif.',
                'misi' => "1. Menampung dan memperjuangkan aspirasi seluruh siswa melalui forum dengar pendapat terbuka secara berkala.\n2. Menjalankan fungsi pengawasan dan evaluasi program kerja OSIS secara objektif, berkala, dan transparan.\n3. Mengoptimalkan komunikasi aktif antara siswa, pengurus OSIS, dan pihak sekolah.\n4. Menyelenggarakan sidang pleno evaluasi kinerja yang akuntabel dan dapat diakses laporannya oleh seluruh warga sekolah.",
            ],
            [
                'tipe' => CalonKetua::TIPE_MPK,
                'nomor' => 2,
                'nama' => 'Nabila Aulia Rahmawati',
                'id_kelas' => 12,
                'url_foto' => 'storage/foto_calon/mpk-02-nabila-aulia.png',
                'visi' => 'Menjadikan MPK sebagai mitra strategis sekolah yang responsif, berintegritas, dan solutif dalam menjembatani kebutuhan siswa dengan kebijakan sekolah.',
                'misi' => "1. Membangun kanal aspirasi digital yang cepat tanggap, aman, dan mudah dijangkau seluruh kelas.\n2. Meningkatkan kapasitas kepemimpinan dan wawasan legislatif seluruh perwakilan kelas.\n3. Mengawal realisasi anggaran dan transparansi laporan pertanggungjawaban kegiatan kesiswaan.\n4. Menegakkan kode etik dan kedisiplinan organisasi demi terciptanya iklim sekolah yang harmonis.",
            ],
            [
                'tipe' => CalonKetua::TIPE_MPK,
                'nomor' => 3,
                'nama' => 'Rayhan Bintang Pratama',
                'id_kelas' => 13,
                'url_foto' => 'storage/foto_calon/mpk-03-rayhan-bintang.png',
                'visi' => 'Transformasi MPK yang progresif, berlandaskan musyawarah mufakat, serta aktif mewujudkan iklim demokrasi sekolah yang adil dan berkeadaban.',
                'misi' => "1. Menguatkan fungsi perwakilan kelas melalui rapat koordinasi rutin dwimingguan yang produktif.\n2. Memberikan telaah kritis dan pendampingan solutif terhadap setiap rancangan program kerja OSIS.\n3. Mengadakan sosialisasi peran legislatif siswa guna membangun kesadaran demokrasi sehat di kalangan pelajar.\n4. Membuka ruang advokasi bagi hak-hak akademik dan non-akademik siswa yang membutuhkan pendampingan.",
            ],
            [
                'tipe' => CalonKetua::TIPE_MPK,
                'nomor' => 4,
                'nama' => 'Zahra Putri Ramadhani',
                'id_kelas' => 14,
                'url_foto' => 'storage/foto_calon/mpk-04-zahra-putri.png',
                'visi' => 'Membentuk MPK yang inklusif, aspiratif, dan bersinergi harmonis untuk menyuarakan keberagaman potensi siswa menuju kemajuan sekolah.',
                'misi' => "1. Menyelenggarakan polling aspirasi tematik secara digital sebelum penetapan kebijakan kesiswaan besar.\n2. Menjaga harmonisasi kerja sama bilateral antara MPK, OSIS, dan seluruh ekstrakurikuler.\n3. Mengoptimalkan sistem pengarsipan dan publikasi regulasi internal siswa agar mudah dipahami.\n4. Mengembangkan budaya musyawarah yang solutif dalam menyelesaikan setiap kendala organisasi siswa.",
            ],
            [
                'tipe' => CalonKetua::TIPE_MPK,
                'nomor' => 5,
                'nama' => 'Dimas Satria Wibowo',
                'id_kelas' => 15,
                'url_foto' => 'storage/foto_calon/mpk-05-dimas-satria.png',
                'visi' => 'Mewujudkan MPK yang berwibawa, tegas, dan akuntabel sebagai pilar penegak disiplin aturan serta pembawa perubahan positif bagi almamater.',
                'misi' => "1. Mengawal implementasi Anggaran Dasar dan Anggaran Rumah Tangga (AD/ART) OSIS/MPK secara konsisten.\n2. Meningkatkan efektivitas monitoring dan audit kepanitiaan setiap kegiatan sekolah.\n3. Menjadi wadah mediasi dan penyelesaian kendala antar kelas dengan pendekatan kekeluargaan yang berkeadilan.\n4. Mendorong keterlibatan aktif siswa dalam memberikan evaluasi terhadap fasilitas dan mutu lingkungan belajar.",
            ],
        ];

        foreach ($calonMpk as $mpk) {
            CalonKetua::firstOrCreate(
                [
                    'tipe' => CalonKetua::TIPE_MPK,
                    'nomor' => $mpk['nomor'],
                ],
                $mpk
            );
        }
    }
}
