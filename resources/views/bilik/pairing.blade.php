<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-950">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Aktivasi Bilik Suara TPS | {{ $config['nama_sekolah'] ?? 'PILKETOS' }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800;900&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/35d8865ade.js" crossorigin="anonymous"></script>
    <link rel="icon" type="image/x-icon" href="{{ asset('img/logo.png') }}" />
    @vite(['resources/css/app.css'])
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
<body class="ambient-mesh-voting text-slate-100 min-h-screen flex flex-col justify-between p-4 sm:p-8 antialiased overflow-x-hidden relative ambient-grid select-none">

    {{-- Top Header --}}
    <header class="w-full max-w-4xl mx-auto flex items-center justify-between glass-panel-dark rounded-3xl px-6 py-4 border border-white/10 shadow-2xl">
        <div class="flex items-center gap-3.5">
            <div class="w-10 h-10 rounded-2xl bg-gradient-to-tr from-indigo-600 to-indigo-400 flex items-center justify-center p-2 shadow-lg shadow-indigo-500/30">
                <img src="{{ !empty($config['url_logo']) ? asset($config['url_logo']) : asset('img/logo.png') }}" alt="Logo" class="w-full h-full object-contain">
            </div>
            <div>
                <h1 class="font-heading font-black text-sm sm:text-base text-white uppercase tracking-tight">
                    {{ $config['nama_sekolah'] ?? 'PILKETOS' }}
                </h1>
                <p class="text-[11px] text-slate-400 font-mono">Terminal Bilik Suara E-Voting</p>
            </div>
        </div>
        <a href="{{ url('/') }}" class="px-4 py-2 rounded-xl bg-slate-900 hover:bg-slate-800 border border-white/10 text-xs font-bold text-slate-300 hover:text-white transition-all">
            <i class="fa-solid fa-arrow-left mr-1"></i> Beranda
        </a>
    </header>

    {{-- Main Pairing Card --}}
    <main class="w-full max-w-md mx-auto my-auto p-4">
        <div class="glass-panel-dark rounded-3xl p-8 sm:p-10 border border-indigo-500/30 shadow-2xl shadow-indigo-500/10 text-center relative overflow-hidden">
            <div class="w-20 h-20 rounded-3xl bg-indigo-500/15 border border-indigo-500/30 text-indigo-400 flex items-center justify-center mx-auto mb-5 text-3xl shadow-xl">
                <i class="fa-solid fa-person-booth"></i>
            </div>

            <span class="inline-block px-3 py-1 rounded-full bg-indigo-500/20 text-indigo-300 border border-indigo-500/30 text-[10px] font-mono font-bold uppercase tracking-widest mb-3">
                Pairing Terminal PC
            </span>

            <h2 class="font-heading font-black text-2xl sm:text-3xl text-white tracking-tight mb-2">
                Aktivasi Bilik Suara
            </h2>

            <p class="text-xs text-slate-400 leading-relaxed mb-6">
                Masukkan kode pairing bilik yang digenerate oleh Admin TPS untuk mengaktifkan komputer ini sebagai bilik suara resmi.
            </p>

            @if ($errors->any())
                <div class="mb-5 p-3.5 rounded-2xl bg-rose-500/15 border border-rose-500/30 text-rose-300 text-xs text-left">
                    <div class="flex items-center gap-2 font-bold mb-1">
                        <i class="fa-solid fa-circle-xmark text-rose-400"></i>
                        <span>Gagal Mengaktivasi</span>
                    </div>
                    <ul class="list-disc list-inside space-y-0.5 text-[11px]">
                        @foreach ($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('info'))
                <div class="mb-5 p-3.5 rounded-2xl bg-blue-500/15 border border-blue-500/30 text-blue-300 text-xs text-left flex items-center gap-2">
                    <i class="fa-solid fa-circle-info text-blue-400"></i>
                    <span>{{ session('info') }}</span>
                </div>
            @endif

            <form method="POST" action="{{ route('bilik.pairing-submit') }}" class="space-y-5">
                @csrf
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-2 font-mono text-left">
                        Kode Pairing Bilik
                    </label>
                    <input type="text" name="pairing_code" value="{{ old('pairing_code') }}" required autofocus
                           placeholder="Contoh: BLK-AB12C"
                           class="w-full px-4 py-3.5 bg-slate-950/80 border border-white/15 rounded-2xl text-center font-mono font-black text-lg sm:text-xl tracking-[0.2em] uppercase text-white outline-none focus:border-indigo-500 transition-colors placeholder:tracking-normal placeholder:font-medium placeholder:text-slate-600">
                    <p class="text-[11px] text-slate-500 text-left mt-1.5">
                        <i class="fa-solid fa-shield-halved text-emerald-400 mr-1"></i> 1 Kode Pairing hanya berlaku untuk 1 perangkat PC bilik.
                    </p>
                </div>

                <button type="submit"
                        class="w-full py-4 rounded-2xl bg-gradient-to-r from-indigo-600 to-indigo-500 hover:from-indigo-500 hover:to-indigo-600 text-white font-heading font-black text-sm tracking-wider shadow-xl shadow-indigo-500/30 flex items-center justify-center gap-2 transition-all cursor-pointer">
                    <i class="fa-solid fa-link"></i>
                    <span>SAMBUNGKAN PC BILIK</span>
                </button>
            </form>

            <div class="mt-6 pt-5 border-t border-white/5 text-[11px] text-slate-500 flex items-center justify-between">
                <span>TPS Panitia Pilketos</span>
                <a href="{{ route('login') }}" class="text-indigo-400 hover:text-white transition-colors">
                    Login Admin &rarr;
                </a>
            </div>
        </div>
    </main>

    {{-- Footer --}}
    <footer class="w-full max-w-4xl mx-auto text-center py-4 text-xs text-slate-500 border-t border-white/5">
        &copy; {{ date('Y') }} {{ $config['nama_sekolah'] ?? 'PILKETOS' }} &bull; Sistem Bilik Suara E-Voting Terintegrasi
    </footer>

</body>
</html>
