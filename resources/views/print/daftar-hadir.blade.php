<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Daftar Hadir Pemilih TPS | {{ $config['nama_sekolah'] ?? 'PILKETOS' }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        @page {
            size: A4 portrait;
            margin: 12mm 15mm;
        }
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        body {
            background-color: #ffffff;
            color: #0f172a;
            font-size: 11px;
            line-height: 1.4;
        }
        .container {
            max-width: 210mm;
            margin: 0 auto;
        }
        .kop {
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 2.5px solid #0f172a;
            padding-bottom: 8px;
            margin-bottom: 12px;
        }
        .kop-logo {
            width: 50px;
            height: 50px;
            object-fit: contain;
        }
        .kop-text {
            flex: 1;
            text-align: center;
            padding: 0 10px;
        }
        .kop-text h1 {
            font-size: 14px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #0f172a;
        }
        .kop-text h2 {
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            color: #1e293b;
        }
        .kop-text p {
            font-size: 9.5px;
            color: #475569;
        }
        .document-title {
            text-align: center;
            margin-bottom: 14px;
        }
        .document-title h3 {
            font-size: 13px;
            font-weight: 800;
            text-transform: uppercase;
            text-decoration: underline;
            letter-spacing: 0.5px;
        }
        .document-title span {
            font-size: 10px;
            color: #475569;
            font-weight: 600;
        }

        table.hadir-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 16px;
        }
        table.hadir-table th, table.hadir-table td {
            border: 1px solid #334155;
            padding: 6px 8px;
            font-size: 10px;
        }
        table.hadir-table th {
            background-color: #f1f5f9;
            font-weight: 800;
            text-transform: uppercase;
            text-align: center;
            color: #0f172a;
        }
        table.hadir-table tr:nth-child(even) {
            background-color: #f8fafc;
        }

        .ttd-cell-ganjil {
            text-align: left;
            padding-left: 10px !important;
            font-size: 9.5px;
            color: #64748b;
            font-family: monospace;
        }
        .ttd-cell-genap {
            text-align: right;
            padding-right: 15px !important;
            font-size: 9.5px;
            color: #64748b;
            font-family: monospace;
        }

        .footer-ttd {
            display: flex;
            justify-content: space-between;
            margin-top: 25px;
            page-break-inside: avoid;
        }
        .ttd-box {
            text-align: center;
            width: 200px;
            font-size: 10.5px;
        }
        .ttd-space {
            height: 55px;
        }
        .ttd-name {
            font-weight: 800;
            text-decoration: underline;
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
            padding: 9px 18px;
            font-size: 12px;
            font-weight: 700;
            border-radius: 8px;
            cursor: pointer;
            box-shadow: 0 4px 10px rgba(79, 70, 229, 0.3);
        }
        .btn-back {
            background: #ffffff;
            color: #334155;
            border: 1px solid #cbd5e1;
            padding: 9px 14px;
            font-size: 12px;
            font-weight: 600;
            border-radius: 8px;
            text-decoration: none;
        }

        @media print {
            .no-print { display: none !important; }
            body { background: transparent; }
            table.hadir-table th { background-color: #f1f5f9 !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            table.hadir-table tr:nth-child(even) { background-color: #f8fafc !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        }
    </style>
</head>
<body>

    <div class="no-print">
        <a href="{{ route('hak-suara.index') }}" class="btn-back">Kembali</a>
        <button onclick="window.print()" class="btn-print">Cetak Daftar Hadir ({{ $pemilihs->count() }})</button>
    </div>

    <div class="container">
        {{-- KOP SURAT --}}
        <div class="kop">
            @if(!empty($config['url_logo']))
                <img src="{{ asset($config['url_logo']) }}" class="kop-logo" alt="Logo">
            @else
                <div style="width: 50px;"></div>
            @endif
            <div class="kop-text">
                <h1>{{ $config['undangan_judul_kop'] ?? 'PANITIA PEMILIHAN KETUA OSIS & MPK' }}</h1>
                <h2>{{ $config['nama_sekolah'] ?? 'SMA NEGERI 1 PRAMBON' }}</h2>
                <p>{{ $config['alamat_sekolah'] ?? 'JL. A.YANI SUGIHWARAS PRAMBON' }} &bull; Tahun Ajaran {{ $config['tahun_ajaran'] ?? date('Y') }}</p>
            </div>
            <div style="width: 50px;"></div>
        </div>

        {{-- JUDUL DOKUMEN --}}
        <div class="document-title">
            <h3>DAFTAR PRESENSI &amp; KEHADIRAN PEMILIH DI TPS</h3>
            <span>
                @if($selectedKelas)
                    Kelas: <strong>{{ $selectedKelas->name }}</strong> &bull; 
                @endif
                Total Pemilih Terdaftar: <strong>{{ $pemilihs->count() }} Orang</strong>
            </span>
        </div>

        {{-- TABEL PRESENSI --}}
        <table class="hadir-table">
            <thead>
                <tr>
                    <th style="width: 35px;">No</th>
                    <th>Nama Pemilih</th>
                    <th style="width: 110px;">Kategori / Kelas</th>
                    <th colspan="2" style="width: 180px;">Tanda Tangan / Paraf Kehadiran</th>
                    <th style="width: 80px;">Waktu Hadir</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pemilihs as $index => $p)
                    @php $no = $index + 1; @endphp
                    <tr>
                        <td style="text-align: center;">{{ $no }}</td>
                        <td style="font-weight: 600;">{{ $p->nisn }}</td>
                        <td style="text-align: center; font-family: monospace;">
                            {{ $p->tipe === 'guru' ? 'Guru / Tendik' : ($p->tipe === 'simulasi' ? 'Simulasi' : ($p->kelas->name ?? 'Siswa')) }}
                        </td>
                        @if($no % 2 !== 0)
                            {{-- Baris Ganjil: Tanda tangan di kolom kiri --}}
                            <td class="ttd-cell-ganjil" style="height: 32px; vertical-align: middle;">
                                {{ $no }}. ....................
                            </td>
                            <td style="background-color: #f1f5f9; width: 90px;"></td>
                        @else
                            {{-- Baris Genap: Tanda tangan di kolom kanan --}}
                            <td style="background-color: #f1f5f9; width: 90px;"></td>
                            <td class="ttd-cell-genap" style="height: 32px; vertical-align: middle;">
                                .................... {{ $no }}.
                            </td>
                        @endif
                        <td></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 20px;">Tidak ada data pemilih.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{-- TANDA TANGAN PETUGAS TPS --}}
        <div class="footer-ttd">
            <div class="ttd-box">
                <p>Saksi TPS,<br>Perwakilan Siswa / Guru</p>
                <div class="ttd-space"></div>
                <p class="ttd-name">................................................</p>
                <p>Nama Terang</p>
            </div>

            <div class="ttd-box">
                <p>Petugas Meja Presensi TPS,<br>Panitia Pelaksana</p>
                <div class="ttd-space"></div>
                <p class="ttd-name">................................................</p>
                <p>Nama Terang</p>
            </div>
        </div>
    </div>

</body>
</html>
