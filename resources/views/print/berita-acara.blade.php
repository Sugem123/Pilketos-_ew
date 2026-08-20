<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Berita Acara Rekapitulasi Hasil Pemilihan Ketua OSIS</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/35d8865ade.js" crossorigin="anonymous"></script>
    <style>
        @page {
            size: A4 portrait;
            margin: 12mm 15mm;
        }
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Plus Jakarta Sans', serif;
        }
        body {
            background-color: #f8fafc;
            color: #0f172a;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        .document-sheet {
            background: #ffffff;
            max-width: 210mm;
            margin: 0 auto;
            padding: 15mm 20mm;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
            line-height: 1.6;
        }
        .doc-header {
            text-align: center;
            border-bottom: 3px double #0f172a;
            padding-bottom: 12px;
            margin-bottom: 20px;
        }
        .doc-header h1 {
            font-size: 16px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .doc-header h2 {
            font-size: 14px;
            font-weight: 800;
            text-transform: uppercase;
            margin-top: 2px;
        }
        .doc-header p {
            font-size: 11px;
            color: #475569;
            margin-top: 4px;
        }

        .doc-title {
            text-align: center;
            margin-bottom: 20px;
        }
        .doc-title h3 {
            font-size: 14px;
            font-weight: 800;
            text-decoration: underline;
            text-transform: uppercase;
        }
        .doc-title span {
            font-size: 11px;
            color: #64748b;
        }

        .content-section {
            font-size: 11.5px;
            margin-bottom: 16px;
            text-align: justify;
        }
        
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
            margin: 14px 0 20px 0;
        }
        table.data-table th, table.data-table td {
            border: 1px solid #334155;
            padding: 7px 10px;
        }
        table.data-table th {
            background-color: #f1f5f9;
            font-weight: 800;
            text-align: center;
            text-transform: uppercase;
        }

        .winner-box {
            background: #f8fafc;
            border: 1.5px solid #0f172a;
            border-radius: 8px;
            padding: 12px 16px;
            margin: 16px 0;
            font-size: 12px;
        }
        .winner-box strong {
            font-size: 13px;
            color: #0f172a;
        }

        .signatures {
            margin-top: 30px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            font-size: 11px;
            page-break-inside: avoid;
        }
        .sig-block {
            text-align: center;
        }
        .sig-block .space {
            height: 55px;
        }
        .sig-block .name {
            font-weight: 800;
            text-decoration: underline;
        }

        .witness-section {
            margin-top: 30px;
            border-top: 1px dashed #cbd5e1;
            padding-top: 15px;
            font-size: 10.5px;
            page-break-inside: avoid;
        }
        .witness-grid {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 15px;
            margin-top: 10px;
            text-align: center;
        }

        .no-print {
            position: fixed;
            top: 15px;
            right: 15px;
            z-index: 999;
            display: flex;
            gap: 10px;
        }
        .btn-print {
            background: #4f46e5;
            color: #ffffff;
            border: none;
            padding: 10px 20px;
            font-size: 13px;
            font-weight: 700;
            border-radius: 10px;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .btn-back {
            background: #ffffff;
            color: #334155;
            border: 1px solid #cbd5e1;
            padding: 10px 16px;
            font-size: 13px;
            font-weight: 600;
            border-radius: 10px;
            text-decoration: none;
        }

        @media print {
            body { background: transparent; }
            .no-print { display: none !important; }
            .document-sheet { padding: 0; box-shadow: none; max-width: 100%; }
        }
    </style>
</head>
<body>

    <div class="no-print">
        <a href="{{ route('audit-suara.index') }}" class="btn-back">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
        <button onclick="window.print()" class="btn-print">
            <i class="fas fa-print"></i> Cetak Berita Acara
        </button>
    </div>

    <div class="document-sheet">
        {{-- Header --}}
        <div class="doc-header">
            @if(!empty($config['url_logo']))
                <img src="{{ asset($config['url_logo']) }}" style="height: 48px; width: 48px; object-fit: contain; margin: 0 auto 6px auto; display: block;">
            @endif
            <h1>{{ $config['nama_sekolah'] ?? 'PANITIA PEMILIHAN KETUA OSIS' }}</h1>
            <h2>{{ $config['nama_kegiatan'] ?? 'SURAT KEPUTUSAN & BERITA ACARA REKAPITULASI HASIL PEMILIHAN' }}</h2>
            <p>{{ $config['alamat_sekolah'] ?? 'TPS Bilik Suara' }} &bull; Periode {{ $config['tahun_ajaran'] ?? date('Y') }}</p>
        </div>

        {{-- Title --}}
        <div class="doc-title">
            <h3>BERITA ACARA REKONSILIASI PERHITUNGAN SUARA</h3>
            <span>Nomor: BA-PILKETOS/{{ date('Y/m') }}/001</span>
        </div>

        {{-- Preamble --}}
        <div class="content-section">
            Pada hari ini, <strong>{{ $tanggal }}</strong>, bertempat di TPS Pemilihan Ketua OSIS, telah dilaksanakan rapat pleno penghitungan suara serta rekonsiliasi keabsahan kartu suara fisik yang terkumpul di dalam kotak suara TPS terhadap data suara digital yang masuk pada aplikasi e-voting.
        </div>

        {{-- Voter & Participation Summary Table --}}
        <div class="content-section">
            <strong>I. DATA PEMILIH DAN PENGGUNA HAK PILIH</strong>
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width: 50px;">No</th>
                        <th>Uraian Rekapitulasi Pemilih</th>
                        <th style="width: 120px;">Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="text-align: center;">1</td>
                        <td>Total Pemilih Terdaftar (DPT Siswa & Guru)</td>
                        <td style="text-align: center; font-weight: bold;">{{ $totalHakSuara }}</td>
                    </tr>
                    <tr>
                        <td style="text-align: center;">2</td>
                        <td>Rincian DPT: Siswa ({{ $totalSiswa }}) &bull; Guru / Tendik ({{ $totalGuru }})</td>
                        <td style="text-align: center;">{{ $totalHakSuara }}</td>
                    </tr>
                    <tr>
                        <td style="text-align: center;">3</td>
                        <td>Total Suara Masuk di Aplikasi E-Voting</td>
                        <td style="text-align: center; font-weight: bold;">{{ $totalSuaraDigital }}</td>
                    </tr>
                    <tr>
                        <td style="text-align: center;">4</td>
                        <td><strong>Total Suara SAH (Tervalidasi Kartu Fisik Ada)</strong></td>
                        <td style="text-align: center; font-weight: 800; color: #15803d;">{{ $totalSah }}</td>
                    </tr>
                    <tr>
                        <td style="text-align: center;">5</td>
                        <td><strong>Total Suara TIDAK SAH / BATAL (Kartu Tidak Ada)</strong></td>
                        <td style="text-align: center; font-weight: 800; color: #b91c1c;">{{ $totalTidakSah }}</td>
                    </tr>
                    <tr>
                        <td style="text-align: center;">6</td>
                        <td>Suara Belum Diverifikasi (Pending)</td>
                        <td style="text-align: center;">{{ $totalPending }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        {{-- Candidate Results Table --}}
        <div class="content-section">
            <strong>II. HASIL AKHIR PEROLEHAN SUARA SAH KANDIDAT KETUA OSIS</strong>
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width: 60px;">No Urut</th>
                        <th>Nama Kandidat Calon Ketua OSIS</th>
                        <th style="width: 90px;">Kelas</th>
                        <th style="width: 100px;">Suara Digital</th>
                        <th style="width: 100px;">Suara Sah</th>
                        <th style="width: 90px;">Persentase</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($calons as $c)
                        @php
                            $pct = $totalSah > 0 ? number_format(($c->valid_votes / $totalSah) * 100, 1) : '0.0';
                        @endphp
                        <tr>
                            <td style="text-align: center; font-weight: 800;">0{{ $c->nomor }}</td>
                            <td style="font-weight: 700;">{{ $c->nama }}</td>
                            <td style="text-align: center;">{{ $c->kelas->name ?? '-' }}</td>
                            <td style="text-align: center;">{{ $c->digital_votes }}</td>
                            <td style="text-align: center; font-weight: 900; font-size: 12px;">{{ $c->valid_votes }}</td>
                            <td style="text-align: center; font-weight: 800;">{{ $pct }}%</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Decision & Winner Box --}}
        @if($winner)
            <div class="winner-box">
                Berdasarkan hasil rekapitulasi suara sah di atas, Panitia menetapkan bahwa Kandidat Nomor Urut <strong>0{{ $winner->nomor }} ({{ $winner->nama }} - Kelas {{ $winner->kelas->name ?? '-' }})</strong> dengan perolehan <strong>{{ $winner->valid_votes }} Suara Sah</strong> ditetapkan sebagai <strong>KETUA OSIS TERPILIH</strong>.
            </div>
        @endif

        <div class="content-section">
            Demikian Berita Acara ini dibuat dengan sebenarnya dan ditandatangani oleh Panitia Pemilihan serta Saksi-Saksi yang hadir pada rapat pleno.
        </div>

        {{-- Signatures --}}
        <div class="signatures">
            <div class="sig-block">
                <span>Mengetahui,</span><br>
                <strong>Kepala Sekolah / Pembina OSIS</strong>
                <div class="space"></div>
                <div class="name">_________________________</div>
                <span>NIP. ........................................</span>
            </div>

            <div class="sig-block">
                <span>Ditetapkan di TPS,</span><br>
                <strong>Ketua Panitia Pelaksana</strong>
                <div class="space"></div>
                <div class="name">{{ auth()->user()->nama_lengkap ?? 'Ketua Panitia Pilketos' }}</div>
                <span>NIP/NISN. Panitia Pelaksana</span>
            </div>
        </div>

        {{-- Witnesses --}}
        <div class="witness-section">
            <strong>SAKSI-SAKSI PASANGAN KANDIDAT:</strong>
            <div class="witness-grid">
                @foreach($calons as $c)
                    <div>
                        <span>Saksi Paslon 0{{ $c->nomor }}</span>
                        <div style="height: 35px;"></div>
                        <div style="border-bottom: 1px solid #334155; width: 80%; margin: 0 auto;"></div>
                        <span style="font-size: 9px;">( ..................................... )</span>
                    </div>
                @endforeach
            </div>
        </div>

    </div>

</body>
</html>
