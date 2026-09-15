<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Surat Undangan Pemilihan Ketua OSIS &amp; MPK</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/35d8865ade.js" crossorigin="anonymous"></script>
    <style>
        @page {
            size: A4 portrait;
            margin: 8mm 10mm;
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
            max-width: 210mm;
            margin: 0 auto;
            padding: 10mm 0;
        }
        .sheet {
            background: transparent;
            margin-bottom: 12mm;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .invitation-card {
            background: #ffffff;
            border: 2px solid #cbd5e1;
            border-radius: 16px;
            padding: 18px 22px;
            position: relative;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
            flex: 1;
        }
        .invitation-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 2px solid #0f172a;
            padding-bottom: 10px;
            margin-bottom: 12px;
        }
        .school-info h2 {
            font-size: 15px;
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
            font-size: 11.5px;
            line-height: 1.55;
            color: #334155;
            margin-bottom: 12px;
        }
        .voter-box {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 10px 14px;
            margin: 8px 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .voter-details .label { font-size: 9.5px; font-weight: 600; color: #64748b; text-transform: uppercase; }
        .voter-details .name { font-size: 14px; font-weight: 800; color: #0f172a; margin-top: 1px; }
        .voter-details .class-info { font-size: 11px; color: #475569; font-weight: 600; font-family: monospace; }
        
        .schedule-grid {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 8px;
            font-size: 11px;
            background: #f1f5f9;
            padding: 8px 12px;
            border-radius: 8px;
            margin-bottom: 10px;
        }
        .schedule-item strong { display: block; color: #0f172a; font-size: 9.5px; text-transform: uppercase; }

        .invitation-footer {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            font-size: 9.5px;
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
            margin-top: 30px;
            margin-bottom: 2px;
        }

        /* Garis Bantu Gunting Potong di Tengah */
        .cut-guide {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 5mm 0;
            width: 100%;
            height: 18px;
        }
        .cut-line {
            width: 100%;
            border-top: 1.5px dashed #94a3b8;
            position: absolute;
            top: 50%;
            left: 0;
            z-index: 1;
        }
        .cut-badge {
            position: relative;
            z-index: 2;
            background: #ffffff;
            padding: 2px 14px;
            border-radius: 9999px;
            border: 1px dashed #94a3b8;
            font-size: 9.5px;
            font-weight: 700;
            color: #64748b;
            letter-spacing: 0.8px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }
        .cut-badge i {
            font-size: 11px;
            color: #475569;
        }

        .no-print {
            position: fixed;
            top: 15px;
            right: 15px;
            z-index: 999;
            display: flex;
            align-items: center;
            gap: 10px;
            background: rgba(15, 23, 42, 0.9);
            padding: 8px 12px;
            border-radius: 14px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(8px);
            box-shadow: 0 10px 25px -5px rgba(0,0,0,0.5);
        }
        .mode-selector {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 11px;
            font-weight: 700;
            color: #cbd5e1;
        }
        .mode-selector select, .mode-selector input {
            background: #1e293b;
            color: #ffffff;
            border: 1px solid #475569;
            padding: 6px 10px;
            border-radius: 8px;
            font-size: 11px;
            outline: none;
        }
        .btn-print {
            background: #4f46e5;
            color: #ffffff;
            border: none;
            padding: 8px 16px;
            font-size: 12px;
            font-weight: 700;
            border-radius: 10px;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .btn-print:hover { background: #4338ca; }
        .btn-back {
            background: #1e293b;
            color: #e2e8f0;
            border: 1px solid #475569;
            padding: 8px 12px;
            font-size: 12px;
            font-weight: 600;
            border-radius: 10px;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        .btn-back:hover { background: #334155; }

        @media print {
            body { background: transparent; }
            .no-print { display: none !important; }
            .page-container { padding: 0; max-width: 100%; margin: 0; }
            .sheet {
                margin: 0;
                min-height: 275mm;
                height: 275mm;
                page-break-after: always;
                page-break-inside: avoid;
                display: flex;
                flex-direction: column;
                justify-content: space-between;
            }
            .sheet:last-child {
                page-break-after: auto;
            }
            .invitation-card {
                box-shadow: none;
                border: 1.5px solid #94a3b8;
                page-break-inside: avoid;
            }
            .cut-badge {
                background: #ffffff !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
    </style>
</head>
<body>

    <div class="no-print">
        <a href="{{ route('hak-suara.index') }}" class="btn-back">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>

        <form method="GET" action="{{ route('cetak.undangan') }}" style="display: flex; align-items: center; gap: 8px;">
            {{-- Pertahankan filter DPT jika ada --}}
            @if(request('tipe')) <input type="hidden" name="tipe" value="{{ request('tipe') }}"> @endif
            @if(request('id_kelas')) <input type="hidden" name="id_kelas" value="{{ request('id_kelas') }}"> @endif
            @if(request('status')) <input type="hidden" name="status" value="{{ request('status') }}"> @endif
            @if(request('search')) <input type="hidden" name="search" value="{{ request('search') }}"> @endif

            <div class="mode-selector">
                <span>Penerima:</span>
                <select name="mode_penerima" onchange="this.form.submit()">
                    <option value="sistem" {{ $modePenerima === 'sistem' ? 'selected' : '' }}>Diisi Sistem (DPT)</option>
                    <option value="kosong" {{ $modePenerima === 'kosong' ? 'selected' : '' }}>Kosongan (Tulis Tangan)</option>
                </select>
            </div>

            @if($modePenerima === 'kosong')
                <div class="mode-selector">
                    <span>Jumlah Lembar:</span>
                    <input type="number" name="jumlah_kosong" value="{{ $jumlahKosong }}" min="1" max="500" style="width: 65px;" onchange="this.form.submit()">
                </div>
            @endif
        </form>

        <button onclick="window.print()" class="btn-print">
            <i class="fas fa-print"></i> Cetak ({{ $pemilihs->count() }} Undangan)
        </button>
    </div>

    <div class="page-container">
        @php
            $chunks = $pemilihs->chunk(2);
        @endphp

        @forelse($chunks as $chunk)
            <div class="sheet">
                @foreach($chunk as $p)
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
                                @if($modePenerima === 'kosong')
                                    <span class="badge-kategori" style="background: #f1f5f9; color: #475569; border: 1px solid #cbd5e1;">UNDANGAN TPS</span>
                                @elseif($p->tipe === 'guru')
                                    <span class="badge-kategori badge-guru">Guru / Tendik</span>
                                @elseif($p->tipe === 'simulasi')
                                    <span class="badge-kategori" style="background: #fef3c7; color: #b45309; border: 1px solid #fde68a;">Simulasi &bull; TPS</span>
                                @else
                                    <span class="badge-kategori badge-siswa">Siswa &bull; {{ $p->kelas->name ?? 'X' }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="invitation-body">
                            <p>{{ $config['undangan_pembuka'] ?? 'Bersama ini Panitia Pemilihan Ketua OSIS mengundang Saudara/i untuk menggunakan hak pilih pada pemilihan umum ketua OSIS dengan data identitas terdaftar sebagai berikut:' }}</p>
                            
                            <div class="voter-box">
                                <div class="voter-details" style="width: 100%;">
                                    @if($modePenerima === 'kosong')
                                        <div style="display: flex; align-items: center; margin-bottom: 7px;">
                                            <span class="label" style="width: 160px; flex-shrink: 0;">Nama Pemilih Terdaftar :</span>
                                            <span style="flex: 1; border-bottom: 1.5px dotted #64748b; height: 16px;">&nbsp;</span>
                                        </div>
                                        <div style="display: flex; align-items: center;">
                                            <span class="label" style="width: 160px; flex-shrink: 0;">Kelas / Kategori :</span>
                                            <span style="flex: 1; border-bottom: 1.5px dotted #64748b; height: 16px;">&nbsp;</span>
                                        </div>
                                    @else
                                        <span class="label">Nama Pemilih Terdaftar (DPT)</span>
                                        <div class="name">{{ $p->nisn }}</div>
                                        @if($p->tipe === 'siswa')
                                            <div class="class-info">Kelas: {{ $p->kelas->name ?? '-' }}</div>
                                        @elseif($p->tipe === 'guru')
                                            <div class="class-info">Kategori: Tenaga Pendidik / Guru</div>
                                        @else
                                            <div class="class-info">Kategori: Pemilih Simulasi TPS</div>
                                        @endif
                                    @endif
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

                    @if(!$loop->last)
                        {{-- Garis Bantu Gunting Tengah Lembar A4 --}}
                        <div class="cut-guide">
                            <div class="cut-line"></div>
                            <div class="cut-badge">
                                <i class="fa-solid fa-scissors"></i>
                                <span>GARIS POTONG</span>
                                <i class="fa-solid fa-scissors" style="transform: scaleX(-1);"></i>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        @empty
            <div style="text-align: center; padding: 40px; color: #64748b;">
                Tidak ada data pemilih yang sesuai dengan filter.
            </div>
        @endforelse
    </div>

</body>
</html>
