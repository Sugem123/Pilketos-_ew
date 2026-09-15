<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Cetak Kartu Hak Memilih TPS</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@600;700;800;900&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/35d8865ade.js" crossorigin="anonymous"></script>
    <script src="{{ asset('js/qrcode.min.js') }}"></script>
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
            padding: 6mm 0;
        }
        .voter-card {
            background: #ffffff;
            border: 2px dashed #94a3b8;
            border-radius: 14px;
            padding: 12px 15px;
            position: relative;
            page-break-inside: avoid;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            height: 70mm;
            box-shadow: 0 2px 4px rgba(0,0,0,0.04);
            overflow: hidden;
        }
        .card-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1.5px solid #0f172a;
            padding-bottom: 5px;
        }
        .card-brand {
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .card-brand img {
            width: 20px;
            height: 20px;
            object-fit: contain;
        }
        .card-title {
            font-size: 10.5px;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: -0.3px;
            font-family: 'Outfit', sans-serif;
            text-transform: uppercase;
        }
        .card-type-badge {
            font-size: 8.5px;
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
            padding: 4px 0;
            gap: 8px;
        }
        .voter-meta {
            flex: 1;
            min-width: 0;
        }
        .voter-meta .label {
            font-size: 8px;
            color: #64748b;
            text-transform: uppercase;
            font-weight: 700;
        }
        .voter-meta .voter-name {
            font-size: 12.5px;
            font-weight: 800;
            color: #0f172a;
            line-height: 1.25;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .voter-meta .class-tag {
            font-size: 10px;
            color: #4338ca;
            font-weight: 700;
            font-family: monospace;
            margin-top: 1px;
        }

        .auth-cluster {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 5px;
            flex-shrink: 0;
        }

        .qr-box {
            width: 62px;
            height: 62px;
            background: #ffffff;
            border: 1.5px solid #cbd5e1;
            border-radius: 8px;
            padding: 3px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 1px 2px rgba(0,0,0,0.05);
        }
        .qr-box canvas, .qr-box img {
            width: 54px !important;
            height: 54px !important;
            display: block;
        }

        .token-box {
            background: #0f172a;
            color: #ffffff;
            padding: 4px 10px;
            border-radius: 8px;
            text-align: center;
            border: 1px solid #1e293b;
            width: 100%;
            min-width: 82px;
        }
        .token-box .tok-label {
            font-size: 7.5px;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            font-weight: 800;
            display: block;
            line-height: 1;
            margin-bottom: 2px;
        }
        .token-box .tok-code {
            font-size: 17px;
            font-weight: 900;
            font-family: monospace;
            letter-spacing: 2px;
            color: #fbbf24;
            display: block;
            line-height: 1.1;
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
            .token-box { background: #0f172a !important; color: #ffffff !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .token-box .tok-code { color: #fbbf24 !important; }
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
                    @elseif($p->tipe === 'simulasi')
                        <span class="card-type-badge" style="background: #fef3c7; color: #b45309;">SIMULASI</span>
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
                        @elseif($p->tipe === 'guru')
                            <div class="class-tag" style="color: #6b21a8;">TENAGA PENDIDIK</div>
                        @else
                            <div class="class-tag" style="color: #b45309;">SIMULASI TPS</div>
                        @endif
                    </div>

                    {{-- QR Code + Token Box --}}
                    <div class="auth-cluster">
                        <div class="qr-box" id="qr-{{ $p->id }}" data-token="{{ $p->token }}"></div>
                        <div class="token-box">
                            <span class="tok-label">Token</span>
                            <span class="tok-code">{{ $p->token }}</span>
                        </div>
                    </div>
                </div>

                <div class="card-bottom">
                    <span class="status-warning">
                        <i class="fas fa-bolt"></i> 1x Pakai Langsung Hangus
                    </span>
                    <span>Scan QR / Ketik Token di Bilik Suara</span>
                </div>
            </div>
        @empty
            <div style="grid-column: span 2; text-align: center; padding: 40px; color: #64748b;">
                Tidak ada data pemilih yang siap dicetak.
            </div>
        @endforelse
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const qrElements = document.querySelectorAll('.qr-box');
            qrElements.forEach(function(el) {
                const token = el.getAttribute('data-token');
                if (token && typeof QRCode !== 'undefined') {
                    new QRCode(el, {
                        text: token,
                        width: 54,
                        height: 54,
                        colorDark: "#0f172a",
                        colorLight: "#ffffff",
                        correctLevel: QRCode.CorrectLevel.M
                    });
                }
            });
        });
    </script>

</body>
</html>
