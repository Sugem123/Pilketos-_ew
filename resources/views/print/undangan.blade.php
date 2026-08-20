<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Surat Undangan Pemilihan Ketua OSIS</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/35d8865ade.js" crossorigin="anonymous"></script>
    <style>
        @page {
            size: A4 portrait;
            margin: 10mm;
        }
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        body {
            background-color: #f1f5f9;
            color: #0f172a;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        .page-container {
            display: flex;
            flex-direction: column;
            gap: 15mm;
            max-width: 210mm;
            margin: 0 auto;
            padding: 10mm 0;
        }
        .invitation-card {
            background: #ffffff;
            border: 2px solid #cbd5e1;
            border-radius: 16px;
            padding: 20px 24px;
            position: relative;
            page-break-inside: avoid;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
        }
        .invitation-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 2px solid #0f172a;
            padding-bottom: 12px;
            margin-bottom: 14px;
        }
        .school-info h2 {
            font-size: 16px;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: -0.5px;
            text-transform: uppercase;
        }
        .school-info p {
            font-size: 11px;
            color: #64748b;
            font-weight: 500;
        }
        .badge-kategori {
            display: inline-block;
            padding: 4px 10px;
            font-size: 10px;
            font-weight: 700;
            border-radius: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-family: monospace;
        }
        .badge-siswa { background: #e0e7ff; color: #3730a3; border: 1px solid #c7d2fe; }
        .badge-guru { background: #f3e8ff; color: #6b21a8; border: 1px solid #e9d5ff; }
        
        .invitation-body {
            font-size: 12px;
            line-height: 1.6;
            color: #334155;
            margin-bottom: 14px;
        }
        .voter-box {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 10px 14px;
            margin: 10px 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .voter-details .label { font-size: 10px; font-weight: 600; color: #64748b; text-transform: uppercase; }
        .voter-details .name { font-size: 14px; font-weight: 800; color: #0f172a; }
        .voter-details .class-info { font-size: 11px; color: #475569; font-weight: 600; font-family: monospace; }
        
        .token-display {
            text-align: right;
            border-left: 2px dashed #cbd5e1;
            padding-left: 14px;
        }
        .token-display .token-label { font-size: 9px; font-weight: 700; color: #64748b; text-transform: uppercase; }
        .token-display .token-code { font-size: 18px; font-weight: 900; font-family: monospace; color: #4338ca; letter-spacing: 1.5px; }

        .schedule-grid {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 8px;
            font-size: 11px;
            background: #f1f5f9;
            padding: 8px 12px;
            border-radius: 8px;
            margin-bottom: 12px;
        }
        .schedule-item strong { display: block; color: #0f172a; font-size: 10px; text-transform: uppercase; }

        .invitation-footer {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            font-size: 10px;
            color: #64748b;
            border-top: 1px solid #f1f5f9;
            padding-top: 8px;
        }
        .signature-box {
            text-align: center;
        }
        .signature-box .line {
            width: 140px;
            border-bottom: 1px solid #0f172a;
            margin-top: 35px;
            margin-bottom: 2px;
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
        .btn-print:hover { background: #4338ca; }
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
            .page-container { padding: 0; max-width: 100%; }
            .invitation-card { box-shadow: none; border: 1.5px solid #94a3b8; margin-bottom: 8mm; }
        }
    </style>
</head>
<body>

    <div class="no-print">
        <a href="{{ route('hak-suara.index') }}" class="btn-back">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
        <button onclick="window.print()" class="btn-print">
            <i class="fas fa-print"></i> Cetak Semua Undangan ({{ $pemilihs->count() }})
        </button>
    </div>

    <div class="page-container">
        @forelse($pemilihs as $p)
            <div class="invitation-card">
                <div class="invitation-header">
                    <div class="school-info">
                        <h2>{{ $config['undangan_judul_kop'] ?? $config['nama_sekolah'] ?? 'PANITIA PEMILIHAN KETUA OSIS' }}</h2>
                        <p>{{ $config['undangan_sub_kop'] ?? $config['nama_kegiatan'] ?? 'Surat Pemberitahuan Pemungutan Suara' }} &bull; Tahun Ajaran {{ $config['tahun_ajaran'] ?? date('Y') }}</p>
                    </div>
                    <div style="display: flex; align-items: center; gap: 8px;">
                        @if(!empty($config['url_logo']))
                            <img src="{{ asset($config['url_logo']) }}" style="height: 28px; width: 28px; object-fit: contain;">
                        @endif
                        @if($p->tipe === 'guru')
                            <span class="badge-kategori badge-guru">Guru / Tendik</span>
                        @else
                            <span class="badge-kategori badge-siswa">Siswa &bull; {{ $p->kelas->name ?? 'X' }}</span>
                        @endif
                    </div>
                </div>

                <div class="invitation-body">
                    <p>{{ $config['undangan_pembuka'] ?? 'Bersama ini Panitia Pemilihan Ketua OSIS mengundang Saudara/i untuk menggunakan hak pilih pada pemilihan umum ketua OSIS dengan data identitas terdaftar sebagai berikut:' }}</p>
                    
                    <div class="voter-box">
                        <div class="voter-details">
                            <span class="label">Nama Pemilih Terdaftar (DPT)</span>
                            <div class="name">{{ $p->nisn }}</div>
                            @if($p->tipe === 'siswa')
                                <div class="class-info">Kelas: {{ $p->kelas->name ?? '-' }}</div>
                            @else
                                <div class="class-info">Kategori: Tenaga Pendidik / Guru</div>
                            @endif
                        </div>
                        <div class="token-display">
                            <span class="token-label">Token Otorisasi Bilik</span>
                            <div class="token-code">{{ $p->token }}</div>
                        </div>
                    </div>

                    <div class="schedule-grid">
                        <div class="schedule-item">
                            <strong>Hari / Tanggal</strong>
                            <span>{{ $tanggal }}</span>
                        </div>
                        <div class="schedule-item">
                            <strong>Waktu Pelaksanaan</strong>
                            <span>{{ $waktu }}</span>
                        </div>
                        <div class="schedule-item">
                            <strong>Tempat / TPS</strong>
                            <span>{{ $lokasi }}</span>
                        </div>
                    </div>

                    <p style="font-size: 10.5px; color: #64748b;">
                        <em>*{{ $config['undangan_catatan_kaki'] ?? 'Harap membawa surat undangan ini atau mengingat Token Otorisasi saat dipanggil oleh panitia TPS menuju bilik suara e-voting. Satu token hanya berlaku untuk 1 (satu) kali penggunaan.' }}</em>
                    </p>
                </div>

                <div class="invitation-footer">
                    <div>
                        <span>Dicetak otomatis oleh Sistem PILKETOS Official</span>
                    </div>
                    <div class="signature-box">
                        <span>{{ $config['undangan_penandatangan'] ?? 'Ketua Panitia Pemilihan' }}</span>
                        <div class="line"></div>
                        <span style="font-size: 9px; text-transform: uppercase;">{{ $config['nama_sekolah'] ?? 'Panitia Pilketos' }}</span>
                    </div>
                </div>
            </div>
        @empty
            <div style="text-align: center; padding: 40px; color: #64748b;">
                Tidak ada data pemilih yang sesuai dengan filter.
            </div>
        @endforelse
    </div>

</body>
</html>
