<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Cetak Tanda Meja Bilik Suara | {{ $config['nama_sekolah'] ?? 'PILKETOS' }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@700;800;900&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/35d8865ade.js" crossorigin="anonymous"></script>
    <script src="{{ asset('js/qrcode.min.js') }}"></script>
    <style>
        @page {
            size: A4 portrait;
            margin: 10mm 12mm;
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
        .sheet-container {
            max-width: 210mm;
            margin: 0 auto;
        }
        .booth-page {
            background: #ffffff;
            border: 2px solid #cbd5e1;
            border-radius: 20px;
            padding: 24px 28px;
            margin-bottom: 20mm;
            page-break-after: always;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            min-height: 275mm;
            position: relative;
        }
        .booth-page:last-child {
            page-break-after: auto;
            margin-bottom: 0;
        }

        /* Kop Surat Resmi */
        .kop {
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 3px double #0f172a;
            padding-bottom: 12px;
            margin-bottom: 16px;
        }
        .kop-logo {
            width: 58px;
            height: 58px;
            object-fit: contain;
        }
        .kop-center {
            flex: 1;
            text-align: center;
            padding: 0 12px;
        }
        .kop-center h1 {
            font-size: 15px;
            font-weight: 800;
            text-transform: uppercase;
            color: #0f172a;
            letter-spacing: 0.5px;
        }
        .kop-center h2 {
            font-size: 12.5px;
            font-weight: 700;
            color: #1e293b;
            text-transform: uppercase;
            margin-top: 1px;
        }
        .kop-center p {
            font-size: 10px;
            color: #64748b;
            margin-top: 2px;
        }

        /* Booth Header Banner */
        .booth-banner {
            background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 100%);
            color: #ffffff;
            border-radius: 18px;
            padding: 22px 18px;
            text-align: center;
            margin-bottom: 20px;
            border: 2px solid #4338ca;
            box-shadow: 0 8px 20px rgba(15, 23, 42, 0.15);
        }
        .booth-badge {
            display: inline-block;
            background: rgba(99, 102, 241, 0.25);
            color: #a5b4fc;
            border: 1px solid rgba(165, 180, 252, 0.4);
            padding: 4px 14px;
            border-radius: 999px;
            font-size: 10px;
            font-weight: 800;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            font-family: monospace;
            margin-bottom: 8px;
        }
        .booth-title {
            font-family: 'Outfit', sans-serif;
            font-size: 38px;
            font-weight: 900;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: #ffffff;
            line-height: 1.1;
        }
        .booth-subtitle {
            font-size: 12px;
            color: #cbd5e1;
            margin-top: 6px;
            font-weight: 500;
        }

        /* Pairing Code & QR Box */
        .activation-card {
            background: #f8fafc;
            border: 2px dashed #6366f1;
            border-radius: 18px;
            padding: 18px 22px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            margin-bottom: 20px;
        }
        .qr-section {
            display: flex;
            flex-direction: column;
            align-items: center;
            flex-shrink: 0;
            background: #ffffff;
            padding: 10px;
            border-radius: 14px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 2px 6px rgba(0,0,0,0.04);
        }
        .qr-code-box {
            width: 100px;
            height: 100px;
        }
        .qr-caption {
            font-size: 9px;
            font-weight: 700;
            color: #475569;
            margin-top: 6px;
            text-transform: uppercase;
            font-family: monospace;
        }
        .code-section {
            flex: 1;
            text-align: left;
        }
        .code-label {
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
            color: #475569;
            letter-spacing: 0.5px;
            display: flex;
            align-items: center;
            gap: 6px;
            margin-bottom: 4px;
        }
        .code-display {
            display: inline-block;
            background: #0f172a;
            color: #fbbf24;
            border: 2px solid #d97706;
            padding: 8px 18px;
            border-radius: 12px;
            font-size: 26px;
            font-weight: 900;
            font-family: monospace;
            letter-spacing: 3px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .code-instruction {
            font-size: 11px;
            color: #334155;
            margin-top: 8px;
            line-height: 1.4;
        }

        /* Panduan Step by Step */
        .guide-box {
            background: #ffffff;
            border: 1.5px solid #e2e8f0;
            border-radius: 16px;
            padding: 16px 20px;
            margin-bottom: 20px;
        }
        .guide-title {
            font-size: 12px;
            font-weight: 800;
            text-transform: uppercase;
            color: #0f172a;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .guide-steps {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 12px;
        }
        .step-item {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 10px 12px;
            text-align: left;
        }
        .step-num {
            display: inline-flex;
            width: 20px;
            height: 20px;
            background: #4f46e5;
            color: #ffffff;
            border-radius: 50%;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            font-weight: 800;
            margin-bottom: 4px;
        }
        .step-text {
            font-size: 10.5px;
            color: #334155;
            line-height: 1.35;
        }

        /* Footer & Signatures */
        .booth-footer {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            border-top: 1.5px solid #e2e8f0;
            padding-top: 14px;
            margin-top: auto;
        }
        .security-notice {
            max-width: 60%;
            font-size: 9.5px;
            color: #64748b;
            line-height: 1.4;
        }
        .signature-box {
            text-align: center;
            min-width: 140px;
        }
        .sig-date {
            font-size: 10px;
            color: #475569;
            margin-bottom: 2px;
        }
        .sig-title {
            font-size: 10px;
            font-weight: 700;
            color: #0f172a;
        }
        .sig-space {
            height: 40px;
        }
        .sig-name {
            font-size: 11px;
            font-weight: 800;
            text-decoration: underline;
            color: #0f172a;
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
        .print-btn:hover {
            background: #4338ca;
        }
        .btn-secondary {
            background: #0f172a;
            color: #f8fafc;
        }
        .btn-secondary:hover {
            background: #1e293b;
        }

        @media print {
            .print-actions {
                display: none !important;
            }
            body {
                background: #ffffff !important;
            }
            .sheet-container {
                max-width: 100% !important;
            }
            .booth-page {
                box-shadow: none !important;
                margin-bottom: 0 !important;
                border: 2px solid #0f172a !important;
            }
        }
    </style>
</head>
<body>

    {{-- Floating Buttons --}}
    <div class="print-actions">
        <button type="button" onclick="window.print()" class="print-btn">
            <i class="fas fa-print"></i>
            <span>Cetak Tanda Bilik</span>
        </button>
        <a href="{{ route('admin.bilik.index') }}" class="print-btn btn-secondary">
            <i class="fas fa-arrow-left"></i>
            <span>Kembali ke Admin</span>
        </a>
    </div>

    <div class="sheet-container">
        @forelse($biliks as $b)
            <div class="booth-page">
                {{-- Kop Sekolah Resmi --}}
                <div class="kop">
                    <img src="{{ !empty($config['url_logo']) ? asset($config['url_logo']) : asset('img/logo.png') }}"
                         alt="Logo" class="kop-logo">
                    <div class="kop-center">
                        <h1>{{ $config['undangan_judul_kop'] ?? 'PANITIA PEMILIHAN KETUA & WAKIL KETUA OSIS' }}</h1>
                        <h2>{{ $config['nama_sekolah'] ?? 'SMA NEGERI 1 PRAMBON' }}</h2>
                        <p>{{ $config['alamat_sekolah'] ?? 'JL. A. YANI SUGIHWARAS PRAMBON' }} &bull; TAHUN AJARAN {{ $config['tahun_ajaran'] ?? date('Y') }}</p>
                    </div>
                    <img src="{{ asset('img/logo.png') }}" alt="Logo" class="kop-logo" style="opacity: 0.9;">
                </div>

                {{-- Booth Banner Signage --}}
                <div class="booth-banner">
                    <span class="booth-badge">
                        <i class="fas fa-shield-halved mr-1"></i> TERMINAL RESMI TPS E-VOTING
                    </span>
                    <h2 class="booth-title">{{ strtoupper($b->nama_bilik) }}</h2>
                    <p class="booth-subtitle">Gunakan perangkat ini secara tertib, jujur, adil, dan rahasia</p>
                </div>

                {{-- Activation & Pairing Box --}}
                <div class="activation-card">
                    <div class="code-section">
                        <div class="code-label">
                            <i class="fas fa-key text-indigo-600"></i>
                            <span>Kode Pairing PC Bilik Ini</span>
                        </div>
                        <div class="code-display">
                            {{ $b->pairing_code }}
                        </div>
                        <p class="code-instruction">
                            Kunjungi <strong>{{ $pairingBaseUrl }}</strong> pada komputer ini, lalu masukkan kode di atas untuk mengaktifkan bilik suara e-voting.
                        </p>
                    </div>

                    <div class="qr-section">
                        <div class="qr-code-box" id="qr-bilik-{{ $b->id }}" data-url="{{ $pairingBaseUrl }}?code={{ $b->pairing_code }}"></div>
                        <span class="qr-caption">Scan Aktivasi</span>
                    </div>
                </div>

                {{-- Panduan Aktivasi Cepat --}}
                <div class="guide-box">
                    <div class="guide-title">
                        <i class="fas fa-list-check text-indigo-600"></i>
                        <span>Petunjuk Penggunaan Petugas TPS</span>
                    </div>
                    <div class="guide-steps">
                        <div class="step-item">
                            <span class="step-num">1</span>
                            <p class="step-text">Buka browser di PC bilik ini &amp; ketik <strong>{{ $pairingBaseUrl }}</strong></p>
                        </div>
                        <div class="step-item">
                            <span class="step-num">2</span>
                            <p class="step-text">Masukkan kode pairing <strong>{{ $b->pairing_code }}</strong> (1 kode untuk 1 PC)</p>
                        </div>
                        <div class="step-item">
                            <span class="step-num">3</span>
                            <p class="step-text">Layar bilik otomatis masuk mode <strong>Layar Penuh Terkunci</strong></p>
                        </div>
                    </div>
                </div>

                {{-- Footer & Pengesahan --}}
                <div class="booth-footer">
                    <div class="security-notice">
                        <p><strong>Peringatan Keamanan Bilik Suara:</strong></p>
                        <p>1. Satu kode pairing hanya dapat digunakan pada satu perangkat komputer.</p>
                        <p>2. Dilarang menutup browser atau mengubah koneksi bilik suara tanpa koordinasi Ketua KPPS/Teknisi IT.</p>
                        <p>3. Untuk keluar dari mode layar penuh di PC, panitia menekan kombinasi <strong>Ctrl + C + B</strong>.</p>
                    </div>

                    <div class="signature-box">
                        <div class="sig-date">{{ $config['undangan_lokasi'] ?? 'Prambon' }}</div>
                        <div class="sig-title">{{ $config['undangan_penandatangan'] ?? 'Ketua Panitia / KPPS' }}</div>
                        <div class="sig-space"></div>
                        <div class="sig-name">{{ $config['undangan_nama_pejabat'] ?? 'Panitia Pelaksana' }}</div>
                    </div>
                </div>
            </div>
        @empty
            <div style="text-align: center; padding: 60px; color: #64748b;">
                Tidak ada data bilik suara yang dipilih untuk dicetak.
            </div>
        @endforelse
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const qrElements = document.querySelectorAll('.qr-code-box');
            qrElements.forEach(function(el) {
                const url = el.getAttribute('data-url');
                if (url && typeof QRCode !== 'undefined') {
                    new QRCode(el, {
                        text: url,
                        width: 100,
                        height: 100,
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
