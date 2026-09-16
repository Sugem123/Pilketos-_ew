<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Rekapitulasi Terminal Bilik Suara TPS | {{ $config['nama_sekolah'] ?? 'PILKETOS' }}</title>
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
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        body {
            background-color: #ffffff;
            color: #0f172a;
            font-size: 11px;
            line-height: 1.4;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
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
            padding-bottom: 10px;
            margin-bottom: 14px;
        }
        .kop-logo {
            width: 52px;
            height: 52px;
            object-fit: contain;
        }
        .kop-text {
            flex: 1;
            text-align: center;
            padding: 0 12px;
        }
        .kop-text h1 {
            font-size: 14px;
            font-weight: 800;
            text-transform: uppercase;
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
        .doc-title {
            text-align: center;
            margin-bottom: 16px;
        }
        .doc-title h3 {
            font-size: 13px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #0f172a;
        }
        .doc-title p {
            font-size: 10.5px;
            color: #475569;
            margin-top: 2px;
        }
        .meta-info {
            display: flex;
            justify-content: space-between;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 8px 12px;
            margin-bottom: 14px;
            font-size: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #94a3b8;
            padding: 7px 9px;
            font-size: 10px;
        }
        th {
            background-color: #f1f5f9;
            font-weight: 700;
            text-transform: uppercase;
            text-align: left;
            color: #1e293b;
            font-size: 9.5px;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-mono { font-family: monospace; }
        .code-tag {
            background: #0f172a;
            color: #fbbf24;
            padding: 3px 8px;
            border-radius: 6px;
            font-weight: 800;
            letter-spacing: 1px;
            font-size: 10.5px;
        }
        .status-badge {
            display: inline-block;
            padding: 2px 7px;
            border-radius: 6px;
            font-size: 9px;
            font-weight: 700;
        }
        .status-active { background: #dcfce7; color: #15803d; }
        .status-unpaired { background: #fef3c7; color: #b45309; }

        .signature-section {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
            page-break-inside: avoid;
        }
        .signature-box {
            width: 200px;
            text-align: center;
        }
        .signature-box .date {
            font-size: 10px;
            margin-bottom: 4px;
        }
        .signature-box .role {
            font-weight: 700;
            font-size: 10px;
            margin-bottom: 45px;
        }
        .signature-box .name {
            font-weight: 800;
            text-decoration: underline;
            font-size: 11px;
        }

        /* Floating Action Buttons */
        .print-actions {
            position: fixed;
            top: 20px;
            right: 20px;
            display: flex;
            gap: 10px;
            z-index: 1000;
        }
        .print-btn {
            background: #4f46e5;
            color: #ffffff;
            border: none;
            padding: 10px 18px;
            border-radius: 12px;
            font-weight: 700;
            font-size: 13px;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.4);
            display: flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
        }
        .btn-secondary {
            background: #0f172a;
            color: #f8fafc;
        }

        @media print {
            .print-actions { display: none !important; }
            body { background: #ffffff !important; }
        }
    </style>
</head>
<body>

    <div class="print-actions">
        <button type="button" onclick="window.print()" class="print-btn">
            <i class="fas fa-print"></i>
            <span>Cetak Rekapitulasi</span>
        </button>
        <a href="{{ route('admin.bilik.index') }}" class="print-btn btn-secondary">
            <i class="fas fa-arrow-left"></i>
            <span>Kembali</span>
        </a>
    </div>

    <div class="container">
        {{-- Kop Surat --}}
        <div class="kop">
            <img src="{{ !empty($config['url_logo']) ? asset($config['url_logo']) : asset('img/logo.png') }}"
                 alt="Logo" class="kop-logo">
            <div class="kop-text">
                <h1>{{ $config['undangan_judul_kop'] ?? 'PANITIA PEMILIHAN KETUA & WAKIL KETUA OSIS' }}</h1>
                <h2>{{ $config['nama_sekolah'] ?? 'SMA NEGERI 1 PRAMBON' }}</h2>
                <p>{{ $config['alamat_sekolah'] ?? 'JL. A. YANI SUGIHWARAS PRAMBON' }} &bull; TAHUN AJARAN {{ $config['tahun_ajaran'] ?? date('Y') }}</p>
            </div>
            <img src="{{ asset('img/logo.png') }}" alt="Logo" class="kop-logo" style="opacity: 0.9;">
        </div>

        {{-- Judul Dokumen --}}
        <div class="doc-title">
            <h3>BERKAS REKAPITULASI KODE PAIRING & TERMINAL BILIK SUARA TPS</h3>
            <p>Dokumen Resmi Pegangan Koordinator TPS dan Tim Teknis IT Pemilihan</p>
        </div>

        {{-- Info Meta --}}
        <div class="meta-info">
            <div>
                <strong>Lokasi TPS:</strong> {{ $config['undangan_lokasi'] ?? 'SMAN 1 Prambon' }}
            </div>
            <div>
                <strong>Tanggal Pemilihan:</strong> {{ $tanggal }}
            </div>
            <div>
                <strong>Total Bilik Terdaftar:</strong> {{ $biliks->count() }} Terminal
            </div>
        </div>

        {{-- Tabel Rekap Bilik --}}
        <table>
            <thead>
                <tr>
                    <th class="text-center" style="width: 32px;">No</th>
                    <th>Nama Terminal Bilik</th>
                    <th class="text-center" style="width: 125px;">Kode Pairing</th>
                    <th class="text-center" style="width: 100px;">Status Sesi</th>
                    <th>Alamat IP / Perangkat Terhubung</th>
                    <th class="text-center" style="width: 85px;">Waktu Pairing</th>
                    <th class="text-center" style="width: 75px;">Paraf Operator</th>
                </tr>
            </thead>
            <tbody>
                @forelse($biliks as $idx => $b)
                    <tr>
                        <td class="text-center font-mono">{{ $idx + 1 }}</td>
                        <td>
                            <strong>{{ $b->nama_bilik }}</strong>
                            <span style="color: #64748b; font-size: 9px; font-family: monospace; display: block;">ID Terminal: #{{ $b->id }}</span>
                        </td>
                        <td class="text-center">
                            <span class="code-tag font-mono">{{ $b->pairing_code }}</span>
                        </td>
                        <td class="text-center">
                            @if($b->isPaired())
                                <span class="status-badge status-active">TERHUBUNG</span>
                            @else
                                <span class="status-badge status-unpaired">SIAP PAIRING</span>
                            @endif
                        </td>
                        <td>
                            @if($b->isPaired())
                                <span class="font-mono font-bold">{{ $b->ip_address ?? '-' }}</span>
                                <div style="font-size: 8.5px; color: #64748b; max-width: 180px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                    {{ $b->user_agent ?? '' }}
                                </div>
                            @else
                                <span style="color: #94a3b8; font-style: italic;">Belum tersambung</span>
                            @endif
                        </td>
                        <td class="text-center font-mono" style="font-size: 9px;">
                            {{ $b->paired_at ? $b->paired_at->translatedFormat('H:i:s') : '-' }}
                        </td>
                        <td></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center" style="padding: 20px; color: #94a3b8;">
                            Belum ada data bilik suara yang dibuat.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{-- Petunjuk Pengamanan Dokumen --}}
        <div style="font-size: 9px; color: #64748b; line-height: 1.4; border-left: 3px solid #6366f1; padding-left: 8px; margin-bottom: 25px;">
            <p><strong>Catatan Rahasia Petugas TPS:</strong></p>
            <p>&bull; Kode pairing bilik suara bersifat rahasia dan hanya diberikan kepada petugas operator bilik yang bertugas di masing-masing komputer.</p>
            <p>&bull; Satu kode pairing hanya dapat digunakan pada satu perangkat komputer. Jika komputer mengalami kendala, lakukan reset sesi di admin untuk membuat kode baru.</p>
        </div>

        {{-- Tanda Tangan Pengesahan --}}
        <div class="signature-section">
            <div class="signature-box">
                <div class="date">&nbsp;</div>
                <div class="role">Koordinator Teknis IT TPS</div>
                <div class="signature-space" style="height: 45px;"></div>
                <div class="name">( &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; )</div>
            </div>

            <div class="signature-box">
                <div class="date">{{ $config['undangan_lokasi'] ?? 'Prambon' }}, {{ $tanggal }}</div>
                <div class="role">{{ $config['undangan_penandatangan'] ?? 'Ketua Panitia / KPPS' }}</div>
                <div class="signature-space" style="height: 45px;"></div>
                <div class="name">{{ $config['undangan_nama_pejabat'] ?? 'Panitia Pelaksana' }}</div>
            </div>
        </div>
    </div>

</body>
</html>
