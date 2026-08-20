<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-950">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>LIVE COUNTING | Pemilihan Ketua OSIS</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800;900&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/35d8865ade.js" crossorigin="anonymous"></script>
    <link rel="icon" type="image/x-icon" href="{{ asset('img/logo.png') }}" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .font-heading { font-family: 'Outfit', sans-serif; }
        
        /* Ambient Animated Grid */
        .ambient-grid {
            background-size: 50px 50px;
            background-image: 
                linear-gradient(to right, rgba(255, 255, 255, 0.03) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(255, 255, 255, 0.03) 1px, transparent 1px);
        }

        /* Pulse glow animation */
        @keyframes pulse-ring {
            0% { transform: scale(0.95); opacity: 0.8; }
            50% { transform: scale(1.15); opacity: 0.3; }
            100% { transform: scale(0.95); opacity: 0.8; }
        }
        .pulse-ring {
            animation: pulse-ring 3s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
    </style>
</head>
<body class="ambient-mesh-voting text-slate-100 min-h-screen flex flex-col justify-between p-4 sm:p-6 lg:p-8 selection:bg-indigo-500 selection:text-white antialiased overflow-x-hidden relative ambient-grid">

    {{-- Top Header for Projector --}}
    <header class="w-full max-w-[1700px] mx-auto flex flex-col sm:flex-row items-center justify-between gap-4 glass-panel-dark rounded-3xl px-6 sm:px-8 py-4 border border-white/10 shadow-2xl mb-6">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-indigo-600 via-indigo-500 to-amber-400 flex items-center justify-center p-2.5 shadow-xl shadow-indigo-500/30 ring-2 ring-white/20">
                <img src="{{ !empty($config['url_logo']) ? asset($config['url_logo']) : asset('img/logo.png') }}" alt="Logo" class="w-full h-full object-contain">
            </div>
            <div>
                <div class="flex items-center gap-3">
                    <h1 class="font-heading font-black text-xl sm:text-2xl lg:text-3xl text-white tracking-tight uppercase">
                        {{ $config['nama_sekolah'] ?? 'PILKETOS' }} &bull; <span class="bg-gradient-to-r from-indigo-400 via-purple-300 to-amber-300 bg-clip-text text-transparent">LIVE COUNT</span>
                    </h1>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-bold bg-rose-500/20 text-rose-400 border border-rose-500/40 tracking-wider">
                        <span class="w-2 h-2 rounded-full bg-rose-500 animate-ping"></span>
                        LIVE STREAM
                    </span>
                </div>
                <p class="text-xs text-slate-400 font-medium mt-0.5">{{ $config['nama_kegiatan'] ?? 'Pemilihan Ketua OSIS' }} &bull; Periode {{ $config['tahun_ajaran'] ?? date('Y') }}</p>
            </div>
        </div>

        {{-- Metrics Badges on Header --}}
        <div class="flex items-center gap-3 sm:gap-6 flex-wrap justify-center">
            {{-- Mode Switcher: Quick Count vs Pleno Sah --}}
            <div class="flex items-center p-1 bg-slate-950/80 border border-slate-800 rounded-2xl">
                <button type="button" id="btn-mode-quick" onclick="switchLiveMode('quick')"
                        class="px-3.5 py-1.5 rounded-xl text-xs font-heading font-extrabold transition-all cursor-pointer bg-indigo-600 text-white shadow-md">
                    Quick Count (Live)
                </button>
                <button type="button" id="btn-mode-pleno" onclick="switchLiveMode('pleno')"
                        class="px-3.5 py-1.5 rounded-xl text-xs font-heading font-extrabold transition-all cursor-pointer text-slate-400 hover:text-white">
                    Pleno Sah (Final)
                </button>
            </div>

            <div class="text-center px-4 py-2 bg-slate-900/80 border border-slate-800 rounded-2xl">
                <span class="text-[10px] uppercase tracking-wider text-slate-400 font-mono block">Suara Masuk</span>
                <span id="header-total-vote" class="font-heading font-black text-lg sm:text-2xl text-emerald-400 font-mono">0</span>
            </div>

            <div class="text-center px-4 py-2 bg-slate-900/80 border border-slate-800 rounded-2xl">
                <span class="text-[10px] uppercase tracking-wider text-slate-400 font-mono block">Partisipasi Total</span>
                <span id="header-partisipasi" class="font-heading font-black text-lg sm:text-2xl text-indigo-400 font-mono">0%</span>
            </div>

            <div class="flex items-center gap-2">
                <button onclick="toggleFullscreen()" class="w-10 h-10 rounded-2xl bg-slate-900 hover:bg-slate-800 text-slate-400 hover:text-white border border-slate-700 flex items-center justify-center transition-all cursor-pointer shadow-lg" title="Layar Penuh">
                    <i id="fs-icon" class="fa-solid fa-expand text-sm"></i>
                </button>
            </div>
        </div>
    </header>

    {{-- Main Live Stage --}}
    <main class="w-full max-w-[1700px] mx-auto flex-1 flex flex-col justify-center gap-6 z-10">

        {{-- Candidate Showcase Cards --}}
        <div id="candidates-container" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8 items-stretch">
            {{-- Injected dynamically by Javascript for live animated transitions --}}
        </div>

        {{-- Secondary Live Analytics Row (Class tracking + Role Split + Feed) --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 mt-2">
            
            {{-- Class Tracking Progress --}}
            <div class="lg:col-span-6 glass-panel-dark rounded-3xl p-5 sm:p-6 border border-white/10 shadow-2xl flex flex-col justify-between">
                <div class="flex items-center justify-between mb-4 border-b border-slate-800 pb-3">
                    <div class="flex items-center gap-2">
                        <div class="w-2 h-4 rounded-full bg-indigo-500"></div>
                        <h3 class="font-heading font-bold text-sm sm:text-base text-white">Tracking Partisipasi per Kelas (Siswa)</h3>
                    </div>
                    <span class="text-[10px] font-mono text-indigo-300 bg-indigo-500/10 border border-indigo-500/20 px-2.5 py-1 rounded-lg font-semibold">Realtime Kelas</span>
                </div>
                <div id="kelas-progress-grid" class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-2.5 overflow-y-auto max-h-[180px] pr-1">
                    {{-- Dynamic class progress --}}
                </div>
            </div>

            {{-- Segmented Participation (Siswa vs Guru) --}}
            <div class="lg:col-span-3 glass-panel-dark rounded-3xl p-5 sm:p-6 border border-white/10 shadow-2xl flex flex-col justify-between">
                <div class="flex items-center gap-2 mb-4 border-b border-slate-800 pb-3">
                    <div class="w-2 h-4 rounded-full bg-purple-500"></div>
                    <h3 class="font-heading font-bold text-sm sm:text-base text-white">Segmentasi Pemilih</h3>
                </div>

                <div class="space-y-4 flex-1 flex flex-col justify-center">
                    {{-- Siswa --}}
                    <div>
                        <div class="flex justify-between items-center text-xs mb-1.5 font-mono">
                            <span class="text-slate-300 font-bold"><i class="fas fa-graduation-cap mr-1 text-indigo-400"></i> Siswa</span>
                            <span id="siswa-stat-text" class="text-indigo-400 font-extrabold">0%</span>
                        </div>
                        <div class="w-full bg-slate-900 border border-slate-800 rounded-full h-2.5 overflow-hidden">
                            <div id="siswa-progress-bar" class="bg-gradient-to-r from-indigo-600 to-indigo-400 h-full rounded-full transition-all duration-700" style="width: 0%"></div>
                        </div>
                        <div class="flex justify-end mt-1 text-[10px] text-slate-500 font-mono">
                            <span id="siswa-count-text">0 / 0 Suara</span>
                        </div>
                    </div>

                    {{-- Guru --}}
                    <div>
                        <div class="flex justify-between items-center text-xs mb-1.5 font-mono">
                            <span class="text-slate-300 font-bold"><i class="fas fa-chalkboard-user mr-1 text-purple-400"></i> Guru / Tendik</span>
                            <span id="guru-stat-text" class="text-purple-400 font-extrabold">0%</span>
                        </div>
                        <div class="w-full bg-slate-900 border border-slate-800 rounded-full h-2.5 overflow-hidden">
                            <div id="guru-progress-bar" class="bg-gradient-to-r from-purple-600 to-purple-400 h-full rounded-full transition-all duration-700" style="width: 0%"></div>
                        </div>
                        <div class="flex justify-end mt-1 text-[10px] text-slate-500 font-mono">
                            <span id="guru-count-text">0 / 0 Suara</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Live Votes Ticker Log --}}
            <div class="lg:col-span-3 glass-panel-dark rounded-3xl p-5 sm:p-6 border border-white/10 shadow-2xl flex flex-col justify-between">
                <div class="flex items-center justify-between mb-4 border-b border-slate-800 pb-3">
                    <div class="flex items-center gap-2">
                        <div class="w-2 h-4 rounded-full bg-emerald-500"></div>
                        <h3 class="font-heading font-bold text-sm sm:text-base text-white">Suara Masuk Terakhir</h3>
                    </div>
                    <span class="text-[10px] font-mono text-emerald-400 bg-emerald-500/10 border border-emerald-500/20 px-2 py-0.5 rounded font-semibold">Live Feed</span>
                </div>

                <div id="recent-votes-ticker" class="space-y-2 overflow-y-auto max-h-[160px] pr-1">
                    {{-- Dynamic live votes --}}
                </div>
            </div>

        </div>

    </main>

    {{-- Minimal Live Footer --}}
    <footer class="w-full max-w-[1700px] mx-auto flex flex-col sm:flex-row items-center justify-between gap-2 border-t border-white/10 pt-4 mt-6 text-xs text-slate-500 z-10">
        <div class="flex items-center gap-3">
            <span class="flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span> Auto-Sync: 3s</span>
            <span>&bull;</span>
            <span>Terakhir Diperbarui: <strong id="last-sync-time" class="text-slate-400 font-mono">--:--:--</strong></span>
        </div>
        <p>&copy; {{ date('Y') }} PILKETOS Official Live Broadcast System &bull; Demokrasi Digital Sekolah</p>
    </footer>

    {{-- Live Polling Engine --}}
    <script>
        let currentMode = 'quick';

        function switchLiveMode(mode) {
            currentMode = mode;
            const btnQuick = document.getElementById('btn-mode-quick');
            const btnPleno = document.getElementById('btn-mode-pleno');

            if (mode === 'quick') {
                btnQuick.className = 'px-3.5 py-1.5 rounded-xl text-xs font-heading font-extrabold transition-all cursor-pointer bg-indigo-600 text-white shadow-md';
                btnPleno.className = 'px-3.5 py-1.5 rounded-xl text-xs font-heading font-extrabold transition-all cursor-pointer text-slate-400 hover:text-white';
            } else {
                btnPleno.className = 'px-3.5 py-1.5 rounded-xl text-xs font-heading font-extrabold transition-all cursor-pointer bg-emerald-600 text-white shadow-md';
                btnQuick.className = 'px-3.5 py-1.5 rounded-xl text-xs font-heading font-extrabold transition-all cursor-pointer text-slate-400 hover:text-white';
            }

            fetchLiveCount();
        }

        async function fetchLiveCount() {
            try {
                const res = await fetch(`{{ route('live-count.data') }}?mode=${currentMode}`);
                const data = await res.json();
                renderLiveDashboard(data);
                document.getElementById('last-sync-time').textContent = data.updated_at;
            } catch (err) {
                console.error('Failed to sync live data:', err);
            }
        }

        function renderLiveDashboard(data) {
            // Header counters
            document.getElementById('header-total-vote').textContent = `${data.total_vote} / ${data.total_hak_suara}`;
            document.getElementById('header-partisipasi').textContent = `${data.partisipasi}%`;

            // Candidates rendering
            const maxVotes = Math.max(...data.candidates.map(c => c.votes), 0);
            const container = document.getElementById('candidates-container');
            
            container.innerHTML = data.candidates.map((c, index) => {
                const isLeading = maxVotes > 0 && c.votes === maxVotes;
                return `
                    <div class="relative bg-slate-900/90 backdrop-blur-2xl rounded-3xl border-2 ${isLeading ? 'border-amber-400/90 shadow-2xl shadow-amber-500/10 ring-4 ring-amber-400/20' : 'border-slate-800 shadow-xl'} overflow-hidden transition-all duration-500 flex flex-col justify-between group">
                        
                        ${isLeading ? `
                            <div class="absolute top-4 right-4 z-20 px-3 py-1 bg-gradient-to-r from-amber-500 to-yellow-400 text-slate-950 font-black text-xs rounded-full shadow-lg flex items-center gap-1.5 font-heading tracking-wider animate-bounce">
                                <i class="fa-solid fa-crown text-[11px]"></i>
                                UNGGUL SEMENTARA
                            </div>
                        ` : ''}

                        {{-- Card Header --}}
                        <div class="p-6 pb-4 border-b border-slate-800/80 bg-slate-950/40 flex items-center justify-between">
                            <div>
                                <span class="text-[11px] font-bold uppercase tracking-widest text-indigo-400 font-mono">KANDIDAT 0${c.nomor}</span>
                                <h2 class="font-heading font-extrabold text-xl sm:text-2xl text-white leading-tight mt-0.5">${c.nama}</h2>
                                <p class="text-xs text-slate-400 font-mono mt-0.5">Kelas ${c.kelas}</p>
                            </div>
                            <div class="w-12 h-12 rounded-2xl bg-indigo-500/10 border border-indigo-500/30 flex items-center justify-center font-heading font-black text-indigo-300 text-xl">
                                ${c.nomor}
                            </div>
                        </div>

                        {{-- Card Photo & Watermark Number (Full Photo Display) --}}
                        <div class="relative h-[20rem] sm:h-[23rem] bg-gradient-to-b from-slate-900/90 via-slate-900/60 to-slate-950 flex items-center justify-center overflow-hidden p-3">
                            <h1 class="absolute bottom-2 left-3 font-heading font-black text-slate-800/25 text-8xl select-none pointer-events-none z-0">
                                0${c.nomor}
                            </h1>

                            ${c.url_foto ? `
                                <img src="${c.url_foto}" alt="${c.nama}" class="w-full h-full object-contain object-center relative z-10 transition-transform duration-500 group-hover:scale-105 drop-shadow-2xl">
                            ` : `
                                <div class="w-28 h-28 rounded-full bg-slate-800 flex items-center justify-center text-slate-600 text-5xl relative z-10">
                                    <i class="fa-solid fa-user"></i>
                                </div>
                            `}
                        </div>

                        {{-- Card Score & Live Progress --}}
                        <div class="p-6 bg-slate-950/80 border-t border-slate-800 space-y-4">
                            <div class="flex items-end justify-between">
                                <div>
                                    <span class="text-[11px] uppercase tracking-wider text-slate-400 font-mono block">Perolehan Suara</span>
                                    <h3 class="font-heading font-black text-3xl sm:text-4xl text-white font-mono leading-none mt-1">
                                        ${c.votes} <span class="text-sm font-semibold text-slate-500 font-sans">Suara</span>
                                    </h3>
                                </div>
                                <div class="text-right">
                                    <span class="font-heading font-black text-2xl sm:text-3xl ${isLeading ? 'text-amber-400' : 'text-indigo-400'} font-mono leading-none">
                                        ${c.percentage}%
                                    </span>
                                </div>
                            </div>

                            {{-- Live Progress Bar --}}
                            <div class="w-full bg-slate-900 rounded-full h-3 border border-slate-800 overflow-hidden p-0.5">
                                <div class="h-full rounded-full transition-all duration-700 ${isLeading ? 'bg-gradient-to-r from-amber-500 to-yellow-400' : 'bg-gradient-to-r from-indigo-600 to-indigo-400'}"
                                     style="width: ${c.percentage}%"></div>
                            </div>
                        </div>

                    </div>
                `;
            }).join('');

            // Class progress rendering
            const kelasContainer = document.getElementById('kelas-progress-grid');
            kelasContainer.innerHTML = data.kelas_stats.map(k => `
                <div class="p-2.5 rounded-xl bg-slate-900/90 border border-slate-800/80 flex flex-col justify-between">
                    <div class="flex items-center justify-between text-xs mb-1 font-mono">
                        <span class="font-bold text-slate-300">${k.name}</span>
                        <span class="font-bold ${k.percentage === 100 ? 'text-emerald-400' : (k.percentage > 50 ? 'text-indigo-400' : 'text-slate-400')}">${k.percentage}%</span>
                    </div>
                    <div class="w-full bg-slate-950 rounded-full h-1.5 overflow-hidden mb-1">
                        <div class="h-full rounded-full transition-all duration-500 ${k.percentage === 100 ? 'bg-emerald-400' : 'bg-indigo-500'}" style="width: ${k.percentage}%"></div>
                    </div>
                    <div class="flex justify-between text-[9px] text-slate-500 font-mono">
                        <span>${k.total_voted}/${k.total_dpt}</span>
                    </div>
                </div>
            `).join('');

            // Siswa vs Guru stats
            document.getElementById('siswa-stat-text').textContent = `${data.partisipasi_siswa}%`;
            document.getElementById('siswa-progress-bar').style.width = `${data.partisipasi_siswa}%`;
            document.getElementById('siswa-count-text').textContent = `${data.siswa_memilih} / ${data.total_siswa} Suara`;

            document.getElementById('guru-stat-text').textContent = `${data.partisipasi_guru}%`;
            document.getElementById('guru-progress-bar').style.width = `${data.partisipasi_guru}%`;
            document.getElementById('guru-count-text').textContent = `${data.guru_memilih} / ${data.total_guru} Suara`;

            // Recent live ticker
            const ticker = document.getElementById('recent-votes-ticker');
            if (data.recent_votes.length === 0) {
                ticker.innerHTML = '<p class="text-xs text-slate-500 text-center py-6">Belum ada suara masuk.</p>';
            } else {
                ticker.innerHTML = data.recent_votes.map(v => `
                    <div class="flex items-center justify-between p-2 rounded-xl bg-slate-900/80 border border-slate-800 text-xs">
                        <div class="flex items-center gap-2 min-w-0">
                            <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                            <span class="font-bold text-white truncate text-[11px]">${v.voter}</span>
                            ${v.tipe === 'guru' ? `
                                <span class="text-[9px] px-1.5 py-0.2 bg-purple-500/20 text-purple-300 rounded font-mono">Guru</span>
                            ` : `
                                <span class="text-[9px] px-1.5 py-0.2 bg-indigo-500/20 text-indigo-300 rounded font-mono">${v.kelas || 'Siswa'}</span>
                            `}
                        </div>
                        <span class="text-[10px] font-mono text-slate-400">${v.time}</span>
                    </div>
                `).join('');
            }
        }

        // Fullscreen Mode Toggle for Projector
        function toggleFullscreen() {
            const icon = document.getElementById('fs-icon');
            if (!document.fullscreenElement) {
                document.documentElement.requestFullscreen().then(() => {
                    icon.classList.remove('fa-expand');
                    icon.classList.add('fa-compress');
                }).catch(err => console.error(err));
            } else {
                if (document.exitFullscreen) {
                    document.exitFullscreen().then(() => {
                        icon.classList.remove('fa-compress');
                        icon.classList.add('fa-expand');
                    });
                }
            }
        }

        // Initial fetch & Polling every 3 seconds
        fetchLiveCount();
        setInterval(fetchLiveCount, 3000);
    </script>
</body>
</html>
