<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-950">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $calon->nama }} | Profil {{ $calon->labelTipe() }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800;900&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/35d8865ade.js" crossorigin="anonymous"></script>
    <link rel="icon" type="image/x-icon" href="{{ asset('img/logo.png') }}" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .font-heading { font-family: 'Outfit', sans-serif; }
        .ambient-grid {
            background-size: 50px 50px;
            background-image:
                linear-gradient(to right, rgba(255, 255, 255, 0.03) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(255, 255, 255, 0.03) 1px, transparent 1px);
        }
    </style>
</head>
<body class="ambient-mesh-voting text-slate-100 min-h-screen antialiased overflow-x-hidden relative ambient-grid flex flex-col justify-between">

    {{-- Header Top Bar --}}
    <header class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6 pb-2 z-20">
        <div class="glass-panel-dark rounded-3xl px-6 py-4 flex flex-col sm:flex-row items-center justify-between gap-4 shadow-2xl border border-white/10">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr {{ $calon->tipe === 'mpk' ? 'from-emerald-600 via-emerald-500 to-teal-400 shadow-emerald-500/30' : 'from-indigo-600 via-indigo-500 to-amber-400 shadow-indigo-500/30' }} flex items-center justify-center p-2.5 shadow-xl ring-2 ring-white/20 flex-shrink-0">
                    <img src="{{ !empty($config['url_logo']) ? asset($config['url_logo']) : asset('img/logo.png') }}" alt="Logo" class="w-full h-full object-contain">
                </div>
                <div>
                    <div class="flex items-center gap-2.5 flex-wrap">
                        <h1 class="font-heading font-black text-lg sm:text-xl text-white tracking-tight uppercase">
                            {{ $config['nama_sekolah'] ?? 'PILKETOS' }}
                        </h1>
                        <span class="inline-flex items-center gap-1.5 px-3 py-0.5 rounded-full text-[11px] font-bold {{ $calon->tipe === 'mpk' ? 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/40' : 'bg-indigo-500/20 text-indigo-300 border border-indigo-500/40' }} tracking-wider">
                            <i class="{{ $calon->tipe === 'mpk' ? 'fa-solid fa-scale-balanced' : 'fa-solid fa-user-tie' }} text-[10px]"></i>
                            {{ strtoupper($calon->labelTipe()) }}
                        </span>
                    </div>
                    <p class="text-xs text-slate-400 font-medium mt-0.5">{{ $config['nama_kegiatan'] ?? 'Pemilihan Umum OSIS & MPK' }} &bull; Periode {{ $config['tahun_ajaran'] ?? date('Y') }}</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('calon-public.index') }}"
                   class="inline-flex items-center gap-2 px-5 py-2.5 rounded-2xl bg-slate-900 hover:bg-slate-800 border border-white/10 text-slate-200 hover:text-white text-xs font-bold transition-all shadow-md">
                    <i class="fa-solid fa-arrow-left text-slate-400"></i> Kembali ke Daftar
                </a>
            </div>
        </div>
    </header>

    {{-- Main Profile Content --}}
    <main class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 lg:py-10 z-10 flex-1">
        <div class="grid grid-cols-1 lg:grid-cols-5 gap-8 items-start">

            {{-- Kolom Kiri: Kartu Foto & Identitas --}}
            <div class="lg:col-span-2 flex flex-col gap-6">
                <div class="relative bg-slate-900/90 backdrop-blur-2xl rounded-3xl border-2 {{ $calon->tipe === 'mpk' ? 'border-emerald-500/30 shadow-emerald-500/10' : 'border-indigo-500/30 shadow-indigo-500/10' }} shadow-2xl overflow-hidden">
                    {{-- Header Kartu --}}
                    <div class="p-6 pb-4 border-b border-slate-800/80 bg-slate-950/50 flex items-center justify-between">
                        <div>
                            <span class="text-[11px] font-bold uppercase tracking-widest {{ $calon->tipe === 'mpk' ? 'text-emerald-400' : 'text-indigo-400' }} font-mono">
                                {{ $calon->labelTipe() }}
                            </span>
                            <h3 class="font-heading font-black text-xs text-slate-400">Kandidat Nomor Urut</h3>
                        </div>
                        <div class="w-14 h-14 rounded-2xl {{ $calon->tipe === 'mpk' ? 'bg-emerald-500/15 border-emerald-500/30 text-emerald-300' : 'bg-indigo-500/15 border-indigo-500/30 text-indigo-300' }} border flex items-center justify-center font-heading font-black text-2xl shadow-lg">
                            0{{ $calon->nomor }}
                        </div>
                    </div>

                    {{-- Foto Display --}}
                    <div class="relative h-80 sm:h-96 bg-gradient-to-b from-slate-900/90 via-slate-900/60 to-slate-950 flex items-center justify-center overflow-hidden p-6">
                        <h1 class="absolute bottom-1 left-2 font-heading font-black text-slate-800/20 text-9xl select-none pointer-events-none z-0">
                            0{{ $calon->nomor }}
                        </h1>
                        @if ($calon->url_foto)
                            <img class="w-full h-full object-contain object-center relative z-10 drop-shadow-2xl transition-transform duration-500 hover:scale-105"
                                 src="{{ asset($calon->url_foto) }}" alt="{{ $calon->nama }}" loading="eager" decoding="async" />
                        @else
                            <div class="w-32 h-32 rounded-full bg-slate-800 flex items-center justify-center text-slate-600 text-6xl relative z-10 shadow-inner">
                                <i class="fa-solid fa-user"></i>
                            </div>
                        @endif
                    </div>

                    {{-- Footer Kartu --}}
                    <div class="p-6 bg-slate-950/80 border-t border-slate-800">
                        <span class="inline-block px-3 py-1 rounded-xl text-[10px] font-mono font-bold uppercase tracking-wider {{ $calon->tipe === 'mpk' ? 'bg-emerald-500/15 text-emerald-300 border border-emerald-500/20' : 'bg-indigo-500/15 text-indigo-300 border border-indigo-500/20' }} mb-2">
                            Kelas {{ $calon->kelas->name ?? '-' }}
                        </span>
                        <h2 class="font-heading font-extrabold text-2xl sm:text-3xl text-white leading-tight">
                            {{ $calon->nama }}
                        </h2>

                        @if(!empty($calon->nama_wakil_1) || !empty($calon->nama_wakil_2))
                            <div class="mt-4 pt-4 border-t border-slate-800/80 space-y-2">
                                <span class="text-[10px] uppercase font-mono font-bold tracking-wider text-slate-400 block">
                                    Pasangan Calon Wakil:
                                </span>
                                @if(!empty($calon->nama_wakil_1))
                                    <div class="flex items-center gap-2 text-sm text-slate-200">
                                        <span class="text-[10px] font-mono font-bold uppercase px-2 py-0.5 rounded {{ $calon->tipe === 'mpk' ? 'bg-emerald-500/20 text-emerald-300' : 'bg-indigo-500/20 text-indigo-300' }}">Wakil 1</span>
                                        <span class="font-semibold">{{ $calon->nama_wakil_1 }}</span>
                                    </div>
                                @endif
                                @if(!empty($calon->nama_wakil_2))
                                    <div class="flex items-center gap-2 text-sm text-slate-200">
                                        <span class="text-[10px] font-mono font-bold uppercase px-2 py-0.5 rounded {{ $calon->tipe === 'mpk' ? 'bg-emerald-500/20 text-emerald-300' : 'bg-indigo-500/20 text-indigo-300' }}">Wakil 2</span>
                                        <span class="font-semibold">{{ $calon->nama_wakil_2 }}</span>
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Quick Meta Grid --}}
                <div class="bg-slate-900/80 backdrop-blur-xl rounded-3xl border border-slate-800 p-5 shadow-xl grid grid-cols-2 gap-3">
                    <div class="p-4 rounded-2xl bg-slate-950/60 border border-slate-800/80">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 font-mono block mb-1">Jabatan</span>
                        <span class="text-sm font-bold text-white flex items-center gap-1.5">
                            <i class="{{ $calon->tipe === 'mpk' ? 'fa-solid fa-scale-balanced text-emerald-400' : 'fa-solid fa-user-tie text-indigo-400' }} text-xs"></i>
                            {{ $calon->labelTipe() }}
                        </span>
                    </div>
                    <div class="p-4 rounded-2xl bg-slate-950/60 border border-slate-800/80">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 font-mono block mb-1">Nomor Urut</span>
                        <span class="text-sm font-bold text-white font-mono">0{{ $calon->nomor }}</span>
                    </div>
                    <div class="p-4 rounded-2xl bg-slate-950/60 border border-slate-800/80">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 font-mono block mb-1">Kelas</span>
                        <span class="text-sm font-bold text-white font-mono">{{ $calon->kelas->name ?? '-' }}</span>
                    </div>
                    <div class="p-4 rounded-2xl bg-slate-950/60 border border-slate-800/80">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 font-mono block mb-1">Jumlah Paslon</span>
                        <span class="text-sm font-bold text-white font-mono">{{ $totalCalon }} Kandidat</span>
                    </div>
                </div>
            </div>

            {{-- Kolom Kanan: Visi, Misi & Detail Profil --}}
            <div class="lg:col-span-3 space-y-6">

                {{-- Visi Box --}}
                <div class="bg-slate-900/90 backdrop-blur-2xl rounded-3xl border border-slate-800 shadow-2xl overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-800/80 bg-slate-950/50 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-indigo-500/10 border border-indigo-500/20 flex items-center justify-center text-indigo-400">
                                <i class="fa-solid fa-eye text-sm"></i>
                            </div>
                            <div>
                                <h3 class="font-heading font-extrabold text-base text-white uppercase tracking-wider">Visi Kandidat</h3>
                                <p class="text-[11px] text-slate-400">Pandangan dan komitmen utama kandidat</p>
                            </div>
                        </div>
                        <span class="text-[10px] font-mono text-indigo-300 font-bold px-3 py-1 bg-indigo-500/10 rounded-xl border border-indigo-500/20">
                            VISI
                        </span>
                    </div>
                    <div class="p-6 sm:p-8">
                        <div class="bg-slate-950/70 border border-slate-800/90 rounded-2xl p-6 shadow-inner relative">
                            <i class="fa-solid fa-quote-left text-slate-800 text-3xl absolute top-4 left-4 -z-0 select-none opacity-40"></i>
                            <p class="text-sm sm:text-base text-slate-200 leading-relaxed relative z-10 font-normal">
                                {{ $calon->visi }}
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Misi Box --}}
                <div class="bg-slate-900/90 backdrop-blur-2xl rounded-3xl border border-slate-800 shadow-2xl overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-800/80 bg-slate-950/50 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-amber-500/10 border border-amber-500/20 flex items-center justify-center text-amber-400">
                                <i class="fa-solid fa-bullseye text-sm"></i>
                            </div>
                            <div>
                                <h3 class="font-heading font-extrabold text-base text-white uppercase tracking-wider">Misi &amp; Program Kerja</h3>
                                <p class="text-[11px] text-slate-400">Rencana aksi konkret yang akan diwujudkan</p>
                            </div>
                        </div>
                        <span class="text-[10px] font-mono text-amber-300 font-bold px-3 py-1 bg-amber-500/10 rounded-xl border border-amber-500/20">
                            MISI
                        </span>
                    </div>
                    <div class="p-6 sm:p-8">
                        <div class="bg-slate-950/70 border border-slate-800/90 rounded-2xl p-6 shadow-inner">
                            <div class="text-sm sm:text-base text-slate-300 leading-relaxed whitespace-pre-line space-y-3 font-normal">
                                {{ $calon->misi }}
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Navigasi Bawah --}}
                <div class="pt-2 flex flex-col sm:flex-row items-center justify-between gap-4">
                    <a href="{{ route('calon-public.index') }}"
                       class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3 rounded-2xl bg-slate-900 hover:bg-slate-800 border border-white/10 text-slate-300 hover:text-white text-xs font-bold transition-all shadow-md">
                        <i class="fa-solid fa-list text-slate-400"></i> Lihat Kandidat Lainnya
                    </a>
                    <a href="{{ url('/') }}"
                       class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3 rounded-2xl bg-gradient-to-r from-indigo-600 to-indigo-500 hover:from-indigo-500 hover:to-indigo-600 text-white text-xs font-bold transition-all shadow-xl shadow-indigo-500/25">
                        <i class="fa-solid fa-check-to-slot"></i> Menuju Bilik Suara
                    </a>
                </div>

            </div>

        </div>
    </main>

    {{-- Footer --}}
    <footer class="w-full border-t border-white/5 py-5 z-10 bg-slate-950/60 mt-10">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <p class="text-xs text-slate-400">
                &copy; {{ date('Y') }} {{ $config['nama_sekolah'] ?? 'PILKETOS' }} &bull; Sistem Pemilihan Terintegrasi Ketua OSIS &amp; Ketua MPK
            </p>
        </div>
    </footer>

</body>
</html>
