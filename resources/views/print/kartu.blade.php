<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Cetak Kartu Hak Memilih TPS</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@600;700;800;900&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/35d8865ade.js" crossorigin="anonymous"></script>
    <style>
        @page {
            size: A4 portrait;
            margin: 8mm;
        }
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        body {
            background-color: #f8fafc;
            color: #0f172a;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        .sheet {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 6mm;
            max-width: 210mm;
            margin: 0 auto;
            padding: 8mm 0;
        }
        .voter-card {
            background: #ffffff;
            border: 2px dashed #94a3b8;
            border-radius: 14px;
            padding: 14px 16px;
            position: relative;
            page-break-inside: avoid;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            height: 64mm;
            box-shadow: 0 2px 4px rgba(0,0,0,0.04);
            overflow: hidden;
        }
        .card-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1.5px solid #0f172a;
            padding-bottom: 6px;
        }
        .card-brand {
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .card-brand img {
            width: 20px;
            height: 20px;
            object-contain: contain;
        }
        .card-title {
            font-size: 11px;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: -0.3px;
            font-family: 'Outfit', sans-serif;
            text-transform: uppercase;
        }
        .card-type-badge {
            font-size: 9px;
            font-weight: 800;
            padding: 2px 6px;
            border-radius: 6px;
            font-family: monospace;
            text-transform: uppercase;
        }
        .type-siswa { background: #e0e7ff; color: #3730a3; }
        .type-guru { background: #f3e8ff; color: #6b21a8; }

        .card-center {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 6px 0;
        }
        .voter-meta .label {
            font-size: 8.5px;
            color: #64748b;
            text-transform: uppercase;
            font-weight: 700;
        }
        .voter-meta .voter-name {
            font-size: 13px;
            font-weight: 800;
            color: #0f172a;
            line-height: 1.2;
            max-width: 170px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .voter-meta .class-tag {
            font-size: 10px;
            color: #4338ca;
            font-weight: 700;
            font-family: monospace;
        }

        .token-box {
            background: #0f172a;
            color: #ffffff;
            padding: 6px 12px;
            border-radius: 10px;
            text-align: center;
            border: 1px solid #1e293b;
        }
        .token-box .tok-label {
            font-size: 7.5px;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 700;
            display: block;
        }
        .token-box .tok-code {
            font-size: 16px;
            font-weight: 900;
            font-family: monospace;
            letter-spacing: 2px;
            color: #fbbf24;
        }

        .card-bottom {
            background: #f1f5f9;
            border-radius: 8px;
            padding: 4px 8px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 8px;
            color: #475569;
        }
        .card-bottom .status-warning {
            font-weight: 700;
            color: #b91c1c;
            display: flex;
            align-items: center;
            gap: 4px;
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
            .sheet { padding: 0; max-width: 100%; gap: 4mm; }
            .voter-card { box-shadow: none; border: 1.5px dashed #64748b; }
        }
    </style>
</head>
<body>

    <div class="no-print">
        <a href="{{ route('hak-suara.index') }}" class="btn-back">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
        <button onclick="window.print()" class="btn-print">
            <i class="fas fa-print"></i> Cetak Semua Kartu ({{ $pemilihs->count() }})
        </button>
    </div>

    <div class="sheet">
        @forelse($pemilihs as $p)
            <div class="voter-card">
                <div class="card-top">
                    <div class="card-brand">
                        <img src="{{ !empty($config['url_logo']) ? asset($config['url_logo']) : asset('img/logo.png') }}" alt="Logo">
                        <span class="card-title">{{ $config['nama_sekolah'] ?? 'KARTU PEMILIH' }}</span>
                    </div>
                    @if($p->tipe === 'guru')
                        <span class="card-type-badge type-guru">GURU</span>
                    @else
                        <span class="card-type-badge type-siswa">SISWA</span>
                    @endif
                </div>

                <div class="card-center">
                    <div class="voter-meta">
                        <span class="label">Nama Pemilih:</span>
                        <div class="voter-name" title="{{ $p->nisn }}">{{ $p->nisn }}</div>
                        @if($p->tipe === 'siswa')
                            <div class="class-tag">KELAS {{ $p->kelas->name ?? '-' }}</div>
                        @else
                            <div class="class-tag" style="color: #6b21a8;">TENAGA PENDIDIK</div>
                        @endif
                    </div>

                    <div class="token-box">
                        <span class="tok-label">Token Bilik</span>
                        <span class="tok-code">{{ $p->token }}</span>
                    </div>
                </div>

                <div class="card-bottom">
                    <span class="status-warning">
                        <i class="fas fa-bolt"></i> 1x Pakai Langsung Hangus
                    </span>
                    <span>Bawa kartu ke bilik suara TPS</span>
                </div>
            </div>
        @empty
            <div style="grid-column: span 2; text-align: center; padding: 40px; color: #64748b;">
                Tidak ada data pemilih yang siap dicetak.
            </div>
        @endforelse
    </div>

</body>
</html>
