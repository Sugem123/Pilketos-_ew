<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-950">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PILKETOS | Sistem Pemilihan Ketua OSIS Modern</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/35d8865ade.js" crossorigin="anonymous"></script>
    <link rel="icon" type="image/x-icon" href="{{ asset('img/logo.png') }}" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .font-heading { font-family: 'Outfit', sans-serif; }
    </style>
</head>
<body class="ambient-mesh-voting text-slate-100 min-h-screen flex flex-col justify-between selection:bg-indigo-500 selection:text-white antialiased">
    
    {{-- Navigation --}}
    <header class="w-full max-w-7xl mx-auto px-6 py-6 flex items-center justify-between z-20">
        <div class="flex items-center gap-3.5">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-indigo-600 to-indigo-400 flex items-center justify-center p-2 shadow-lg shadow-indigo-500/25">
                <img src="{{ asset('img/logo.png') }}" alt="Logo" class="w-full h-full object-contain">
            </div>
            <div>
                <span class="font-heading font-extrabold text-xl tracking-tight text-white">PILKETOS</span>
                <span class="text-xs text-indigo-400 font-mono block -mt-1 font-semibold">PREMIUM SYSTEM</span>
            </div>
        </div>

        <div>
            @auth
                <a href="{{ route('dashboard') }}"
                   class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-semibold text-xs transition-all shadow-lg shadow-indigo-600/30">
                    <i class="fas fa-chart-pie"></i>
                    <span>Dashboard Admin</span>
                </a>
            @else
                <a href="{{ route('login') }}"
                   class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-slate-900/80 hover:bg-slate-800 text-slate-300 hover:text-white border border-slate-700 font-semibold text-xs transition-all">
                    <i class="fas fa-lock"></i>
                    <span>Login Panitia</span>
                </a>
            @endauth
        </div>
    </header>

    {{-- Hero Section --}}
    <main class="max-w-7xl mx-auto px-6 py-12 flex-1 flex flex-col items-center justify-center text-center relative z-10">
        <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-indigo-500/10 border border-indigo-500/30 text-indigo-300 text-xs font-semibold mb-6">
            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span>
            <span>Platform E-Voting Demokrasi Sekolah Generasi Baru</span>
        </div>

        <h1 class="font-heading font-extrabold text-4xl sm:text-6xl lg:text-7xl text-white tracking-tight leading-tight max-w-4xl mb-6">
            Pemilihan Ketua OSIS <br class="hidden sm:inline">
            <span class="bg-gradient-to-r from-indigo-400 via-purple-300 to-amber-300 bg-clip-text text-transparent">Jujur, Adil & Transparan</span>
        </h1>

        <p class="text-sm sm:text-base text-slate-400 max-w-2xl leading-relaxed mb-10">
            Sistem e-voting sekolah berstandar tinggi yang menjamin kecepatan rekapitulasi, kerahasiaan suara, dan pengalaman visual yang elegan.
        </p>

        <div class="flex flex-col sm:flex-row items-center gap-4">
            <a href="{{ route('voting.index') }}"
               class="w-full sm:w-auto inline-flex items-center justify-center gap-3 px-8 py-4 rounded-2xl bg-gradient-to-r from-indigo-600 to-indigo-500 hover:from-indigo-500 hover:to-indigo-600 text-white font-heading font-extrabold text-base tracking-wide transition-all shadow-xl shadow-indigo-600/40 hover:scale-105 active:scale-95">
                <i class="fa-solid fa-check-to-slot"></i>
                <span>MENUJU BILIK SUARA</span>
            </a>

            <a href="{{ route('live-count') }}" target="_blank"
               class="w-full sm:w-auto inline-flex items-center justify-center gap-2.5 px-8 py-4 rounded-2xl bg-rose-500/20 hover:bg-rose-500/30 border border-rose-500/40 text-rose-300 hover:text-white font-heading font-bold text-base transition-all shadow-lg shadow-rose-500/20">
                <i class="fa-solid fa-tower-broadcast animate-pulse text-rose-400"></i>
                <span>Live Count (Proyektor)</span>
            </a>
            
            <a href="{{ route('login') }}"
               class="w-full sm:w-auto inline-flex items-center justify-center gap-2.5 px-8 py-4 rounded-2xl bg-slate-900/80 hover:bg-slate-800 border border-slate-700 text-slate-300 hover:text-white font-heading font-bold text-base transition-all">
                <i class="fas fa-shield-halved"></i>
                <span>Panel Admin</span>
            </a>
        </div>
    </main>

    {{-- Footer --}}
    <footer class="w-full border-t border-slate-800/80 py-6 z-10 bg-slate-950/40">
        <div class="max-w-7xl mx-auto px-6 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-slate-500">
            <p>&copy; {{ date('Y') }} PILKETOS Official System &bull; Demokrasi Digital Sekolah</p>
            <div class="flex items-center gap-4">
                <span class="flex items-center gap-1.5"><i class="fas fa-circle text-emerald-400 text-[8px]"></i> Server Online</span>
                <span>Laravel {{ app()->version() }}</span>
            </div>
        </div>
    </footer>
</body>
</html>
