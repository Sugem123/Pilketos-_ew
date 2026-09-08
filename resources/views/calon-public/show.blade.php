<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-950">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $calon->nama }} | Publikasi Kandidat</title>
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
<body class="ambient-mesh-voting text-slate-100 min-h-screen antialiased overflow-x-hidden relative ambient-grid">

    {{-- Header --}}
    <header class="w-full max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8 pt-6 pb-2 z-20">
        <div class="glass-panel-dark rounded-3xl px-6 py-4 flex items-center justify-between shadow-2xl border border-white/10">
            <div class="flex items-center gap-4">
                <div class="w-11 h-11 rounded-2xl bg-gradient-to-tr from-indigo-600 via-indigo-500 to-amber-400 flex items-center justify-center p-2 shadow-xl shadow-indigo-500/30 ring-2 ring-white/20">
                    <img src="{{ !empty($config['url_logo']) ? asset($config['url_logo']) : asset('img/logo.png') }}" alt="Logo" class="w-full h-full object-contain">
                </div>
                <div>
                    <h1 class="font-heading font-black text-lg sm:text-xl text-white tracking-tight uppercase">
                        {{ $config['nama_sekolah'] ?? 'PILKETOS' }}
                    </h1>
                    <p class="text-xs text-slate-400 font-medium">{{ $config['nama_kegiatan'] ?? 'Pemilihan Ketua OSIS' }} &bull; {{ $config['tahun_ajaran'] ?? date('Y') }}</p>
                </div>
            </div>
            <a href="{{ route('calon-public.index') }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 rounded-2xl bg-slate-900 hover:bg-slate-800 border border-white/10 text-slate-300 hover:text-white text-xs font-bold transition-all">
                <i class="fa-solid fa-arrow-left text-indigo-400"></i> Daftar Kandidat
            </a>
        </div>
    </header>

    {{-- Profile --}}
    <main class="w-full max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8 py-8 lg:py-12 z-10">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 sm:gap-8 items-start">

            {{-- Photo Card --}}
            <div class="lg:col-span-5 relative bg-slate-900/90 backdrop-blur-2xl rounded-3xl border-2 border-indigo-500/30 shadow-2xl shadow-indigo-500/10 overflow-hidden">
                <div class="p-6 pb-4 border-b border-slate-800/80 bg-slate-950/40 flex items-center justify-between">
                    <span class="text-[11px] font-bold uppercase tracking-widest {{ $calon->tipe === 'mpk' ? 'text-emerald-400' : 'text-indigo-400' }} font-mono">{{ strtoupper($calon->labelTipe()) }} 0{{ $calon->nomor }}</span>
                    <div class="w-12 h-12 rounded-2xl bg-indigo-500/10 border border-indigo-500/30 flex items-center justify-center font-heading font-black text-indigo-300 text-xl">
                        {{ $calon->nomor }}
                    </div>
                </div>
                <div class="relative h-[24rem] bg-gradient-to-b from-slate-900/90 via-slate-900/60 to-slate-950 flex items-center justify-center overflow-hidden p-4">
                    <h1 class="absolute bottom-2 left-3 font-heading font-black text-slate-800/25 text-9xl select-none pointer-events-none z-0">
                        0{{ $calon->nomor }}
                    </h1>
                    @if ($calon->url_foto)
                        <img class="w-full h-full object-contain object-center relative z-10 drop-shadow-2xl"
                             src="{{ asset($calon->url_foto) }}" alt="{{ $calon->nama }}" loading="eager" decoding="async" />
                    @else
                        <div class="w-32 h-32 rounded-full bg-slate-800 flex items-center justify-center text-slate-600 text-6xl relative z-10">
                            <i class="fa-solid fa-user"></i>
                        </div>
                    @endif
                </div>
                <div class="p-6 bg-slate-950/80 border-t border-slate-800">
                    <h2 class="font-heading font-black text-2xl text-white leading-tight">{{ $calon->nama }}</h2>
                    <p class="text-sm text-slate-400 font-mono mt-1">Kelas {{ $calon->kelas->name ?? '-' }}</p>
                </div>
            </div>

            {{-- Visi Misi --}}
            <div class="lg:col-span-7 space-y-6">
                <div class="bg-slate-900/90 backdrop-blur-2xl rounded-3xl border border-slate-800 shadow-2xl overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-800/80 bg-slate-950/40 flex items-center gap-2.5">
                        <div class="w-1.5 h-5 rounded-full bg-gradient-to-b from-indigo-400 to-indigo-600"></div>
                        <h3 class="font-heading font-bold text-base text-white uppercase tracking-wide">Visi</h3>
                    </div>
                    <div class="p-6">
                        <p class="text-sm lg:text-base text-slate-300 leading-relaxed bg-slate-950/60 border border-slate-800 rounded-2xl p-5 shadow-inner">{{ $calon->visi }}</p>
                    </div>
                </div>

                <div class="bg-slate-900/90 backdrop-blur-2xl rounded-3xl border border-slate-800 shadow-2xl overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-800/80 bg-slate-950/40 flex items-center gap-2.5">
                        <div class="w-1.5 h-5 rounded-full bg-gradient-to-b from-amber-400 to-amber-600"></div>
                        <h3 class="font-heading font-bold text-base text-white uppercase tracking-wide">Misi</h3>
                    </div>
                    <div class="p-6">
                        <p class="text-sm lg:text-base text-slate-300 leading-relaxed bg-slate-950/60 border border-slate-800 rounded-2xl p-5 shadow-inner whitespace-pre-line">{{ $calon->misi }}</p>
                    </div>
                </div>

                {{-- Profile info --}}
                <div class="bg-slate-900/90 backdrop-blur-2xl rounded-3xl border border-slate-800 shadow-2xl overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-800/80 bg-slate-950/40 flex items-center gap-2.5">
                        <div class="w-1.5 h-5 rounded-full bg-gradient-to-b from-emerald-400 to-emerald-600"></div>
                        <h3 class="font-heading font-bold text-base text-white uppercase tracking-wide">Informasi Profil</h3>
                    </div>
                    <div class="p-6 grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="p-4 rounded-2xl bg-slate-950/60 border border-slate-800">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 font-mono block mb-1">Nama Lengkap</span>
                            <span class="text-sm font-bold text-white">{{ $calon->nama }}</span>
                        </div>
                        <div class="p-4 rounded-2xl bg-slate-950/60 border border-slate-800">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 font-mono block mb-1">Nomor Urut</span>
                            <span class="text-sm font-bold text-white font-mono">{{ $calon->nomor }}</span>
                        </div>
                        <div class="p-4 rounded-2xl bg-slate-950/60 border border-slate-800">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 font-mono block mb-1">Posisi</span>
                            <span class="text-sm font-bold text-white">{{ $calon->labelTipe() }}</span>
                        </div>
                        <div class="p-4 rounded-2xl bg-slate-950/60 border border-slate-800">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 font-mono block mb-1">Kelas</span>
                            <span class="text-sm font-bold text-white font-mono">{{ $calon->kelas->name ?? '-' }}</span>
                        </div>
                        <div class="p-4 rounded-2xl bg-slate-950/60 border border-slate-800">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 font-mono block mb-1">Jumlah Kandidat</span>
                            <span class="text-sm font-bold text-white font-mono">{{ $totalCalon }} Calon</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <footer class="w-full border-t border-white/5 py-4 z-10">
        <div class="max-w-[1200px] mx-auto px-4 text-center">
            <p class="text-xs text-slate-500">
                &copy; {{ date('Y') }} {{ $config['nama_sekolah'] ?? 'PILKETOS' }} &bull; Halaman Publikasi Resmi Kandidat
            </p>
        </div>
    </footer>
</body>
</html>
