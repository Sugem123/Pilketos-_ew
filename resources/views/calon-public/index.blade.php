<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-950">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Publikasi Kandidat | {{ $config['nama_sekolah'] ?? 'PILKETOS' }}</title>
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
        .candidate-pub-card:hover .candidate-photo { transform: scale(1.06); }
    </style>
</head>
<body class="ambient-mesh-voting text-slate-100 min-h-screen antialiased overflow-x-hidden relative ambient-grid">

    {{-- Header --}}
    <header class="w-full max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8 pt-6 pb-2 z-20">
        <div class="glass-panel-dark rounded-3xl px-6 py-5 flex flex-col md:flex-row items-center justify-between gap-4 shadow-2xl border border-white/10">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-indigo-600 via-indigo-500 to-amber-400 flex items-center justify-center p-2.5 shadow-xl shadow-indigo-500/30 ring-2 ring-white/20 flex-shrink-0">
                    <img src="{{ !empty($config['url_logo']) ? asset($config['url_logo']) : asset('img/logo.png') }}" alt="Logo" class="w-full h-full object-contain">
                </div>
                <div>
                    <div class="flex items-center gap-3 flex-wrap">
                        <h1 class="font-heading font-black text-xl sm:text-2xl text-white tracking-tight uppercase">
                            {{ $config['nama_sekolah'] ?? 'PILKETOS' }}
                        </h1>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-bold bg-sky-500/20 text-sky-300 border border-sky-500/40 tracking-wider">
                            <i class="fa-solid fa-bullhorn text-[10px]"></i> PUBLIKASI KANDIDAT
                        </span>
                    </div>
                    <p class="text-xs text-slate-400 font-medium mt-0.5">{{ $config['nama_kegiatan'] ?? 'Pemilihan Ketua OSIS & Ketua MPK' }} &bull; Periode {{ $config['tahun_ajaran'] ?? date('Y') }}</p>
                </div>
            </div>

            <div class="flex items-center gap-3 flex-wrap justify-center sm:justify-end">
                <div class="text-center px-4 py-2 bg-slate-900/80 border border-slate-800 rounded-2xl hidden sm:block">
                    <span class="text-[9px] uppercase tracking-wider text-slate-400 font-mono block">Kandidat</span>
                    <span class="font-heading font-black text-lg text-indigo-400 font-mono">{{ $calonOsis->count() + $calonMpk->count() }}</span>
                </div>

                <a href="{{ route('live-count') }}" target="_blank"
                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-2xl bg-slate-900 hover:bg-slate-800 border border-white/10 text-slate-300 hover:text-white text-xs font-bold transition-all shadow-md">
                    <i class="fa-solid fa-chart-pie text-rose-400"></i>
                    <span>Live Count</span>
                </a>

                @auth
                    <a href="{{ route('dashboard') }}"
                       class="inline-flex items-center gap-2 px-5 py-2.5 rounded-2xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold transition-all shadow-lg shadow-indigo-600/30">
                        <i class="fa-solid fa-gauge"></i>
                        <span>Panel Admin</span>
                    </a>
                @else
                    <a href="{{ route('login') }}"
                       class="inline-flex items-center gap-2 px-5 py-2.5 rounded-2xl bg-gradient-to-r from-indigo-600 to-indigo-500 hover:from-indigo-500 hover:to-indigo-600 text-white text-xs font-bold transition-all shadow-lg shadow-indigo-600/30">
                        <i class="fa-solid fa-arrow-right-to-bracket"></i>
                        <span>Masuk Sesi / Login</span>
                    </a>
                @endauth
            </div>
        </div>
    </header>

    {{-- Candidate Grid --}}
    <main class="w-full max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8 py-8 lg:py-12 z-10">
        <div class="text-center max-w-2xl mx-auto mb-8">
            <h2 class="text-3xl lg:text-4xl font-extrabold font-heading text-white tracking-tight leading-tight mb-3">
                Kenali Calon Pemimpinmu
            </h2>
            <p class="text-sm lg:text-base text-slate-400">
                Pilih pemimpin terbaik untuk masa depan sekolah. Klik kartu kandidat untuk meninjau visi, misi, dan profil lengkap.
            </p>
        </div>

        {{-- WIDGET CEK STATUS DPT PEMILIH --}}
        <section class="max-w-2xl mx-auto mb-14" x-data="dptChecker()">
            <div class="bg-slate-900/90 backdrop-blur-2xl rounded-3xl border border-indigo-500/30 p-6 sm:p-7 shadow-2xl shadow-indigo-500/10">
                <div class="flex items-center gap-3.5 mb-2">
                    <div class="w-10 h-10 rounded-2xl bg-indigo-500/15 border border-indigo-500/30 text-indigo-400 flex items-center justify-center text-lg flex-shrink-0">
                        <i class="fa-solid fa-address-card"></i>
                    </div>
                    <div>
                        <h3 class="font-heading font-black text-lg text-white">Cek Status DPT Pemilih</h3>
                        <p class="text-xs text-slate-400">Ketik nama lengkap Anda untuk memeriksa apakah sudah terdaftar di DPT Pemilu OSIS &amp; MPK</p>
                    </div>
                </div>

                <form @submit.prevent="checkDpt()" class="flex flex-col sm:flex-row gap-3 mt-4">
                    <div class="relative flex-1">
                        <i class="fa-solid fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 text-xs"></i>
                        <input type="text" x-model="searchQuery" required minlength="2"
                               placeholder="Ketik nama Anda (contoh: Aditya, Faiz, Bowo)..."
                               class="w-full pl-10 pr-4 py-3.5 bg-slate-950 border border-white/10 rounded-2xl text-xs font-semibold text-white outline-none focus:border-indigo-500 transition-colors">
                    </div>
                    <button type="submit" :disabled="loading"
                            class="px-6 py-3.5 rounded-2xl bg-indigo-600 hover:bg-indigo-500 text-white font-heading font-bold text-xs shadow-lg shadow-indigo-600/30 flex items-center justify-center gap-2 cursor-pointer disabled:opacity-50">
                        <i class="fa-solid fa-magnifying-glass" x-show="!loading"></i>
                        <i class="fa-solid fa-spinner fa-spin" x-show="loading" x-cloak></i>
                        <span>Cek Status</span>
                    </button>
                </form>

                {{-- Hasil Pencarian DPT --}}
                <div x-show="searched" x-cloak class="mt-5 pt-4 border-t border-white/10">
                    <template x-if="found">
                        <div class="space-y-3">
                            <div class="flex items-center justify-between text-xs font-mono">
                                <span class="text-emerald-400 font-bold flex items-center gap-1.5">
                                    <i class="fa-solid fa-circle-check"></i>
                                    <span>TERDAFTAR DALAM DPT</span>
                                </span>
                                <span class="text-slate-500" x-text="'Ditemukan: ' + results.length + ' pemilih'"></span>
                            </div>

                            <div class="space-y-2 max-h-60 overflow-y-auto pr-1">
                                <template x-for="item in results" :key="item.id">
                                    <div class="p-3.5 rounded-2xl bg-slate-950/80 border border-white/5 flex flex-col sm:flex-row sm:items-center justify-between gap-2.5">
                                        <div>
                                            <h4 class="text-sm font-bold text-white" x-text="item.nama"></h4>
                                            <p class="text-xs text-slate-400 font-mono mt-0.5" x-text="item.tipe === 'guru' ? 'Tenaga Pendidik / Guru' : 'Siswa &bull; Kelas ' + item.kelas"></p>
                                        </div>
                                        <div class="flex items-center gap-2 self-start sm:self-auto">
                                            <span class="px-3 py-1 rounded-xl text-[10px] font-mono font-bold"
                                                  :class="item.has_voted ? 'bg-blue-500/15 text-blue-300 border border-blue-500/30' : 'bg-emerald-500/15 text-emerald-300 border border-emerald-500/30'"
                                                  x-text="item.status_label">
                                            </span>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </template>

                    <template x-if="!found">
                        <div class="p-4 rounded-2xl bg-rose-500/10 border border-rose-500/20 text-rose-300 text-xs flex items-center gap-3">
                            <i class="fa-solid fa-circle-xmark text-lg text-rose-400 flex-shrink-0"></i>
                            <span x-text="errorMessage"></span>
                        </div>
                    </template>
                </div>
            </div>
        </section>

        @if ($calonOsis->isNotEmpty())
            {{-- Seksi Ketua OSIS --}}
            <section class="mb-14">
                <div class="flex items-center gap-3 mb-6">
                    <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-2xl bg-indigo-500/15 border border-indigo-500/30 text-indigo-300 text-xs font-heading font-extrabold uppercase tracking-widest">
                        <i class="fa-solid fa-user-tie"></i> Calon Ketua OSIS
                    </span>
                    <span class="text-[10px] font-mono text-slate-500">{{ $calonOsis->count() }} kandidat</span>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8 items-stretch">
                    @foreach ($calonOsis as $calon)
                        <a href="{{ route('calon-public.show', $calon) }}"
                           class="candidate-pub-card group relative bg-slate-900/90 backdrop-blur-2xl rounded-3xl border-2 border-slate-800 hover:border-indigo-500/60 shadow-xl hover:shadow-indigo-500/10 hover:-translate-y-1.5 transition-all duration-300 overflow-hidden flex flex-col">
                            <div class="p-6 pb-4 border-b border-slate-800/80 bg-slate-950/40 flex items-center justify-between">
                                <div>
                                    <span class="text-[11px] font-bold uppercase tracking-widest text-indigo-400 font-mono">KETUA OSIS 0{{ $calon->nomor }}</span>
                                    <h2 class="font-heading font-extrabold text-xl sm:text-2xl text-white leading-tight mt-0.5">{{ $calon->nama }}</h2>
                                    <p class="text-xs text-slate-400 font-mono mt-0.5">Kelas {{ $calon->kelas->name ?? '-' }}</p>
                                </div>
                                <div class="w-12 h-12 rounded-2xl bg-indigo-500/10 border border-indigo-500/30 flex items-center justify-center font-heading font-black text-indigo-300 text-xl">
                                    {{ $calon->nomor }}
                                </div>
                            </div>

                            <div class="relative h-[20rem] sm:h-[23rem] bg-gradient-to-b from-slate-900/90 via-slate-900/60 to-slate-950 flex items-center justify-center overflow-hidden p-3">
                                <h1 class="absolute bottom-2 left-3 font-heading font-black text-slate-800/25 text-8xl select-none pointer-events-none z-0">
                                    0{{ $calon->nomor }}
                                </h1>
                                @if ($calon->url_foto)
                                    <img class="candidate-photo w-full h-full object-contain object-center relative z-10 transition-transform duration-500 drop-shadow-2xl"
                                         src="{{ asset($calon->url_foto) }}" alt="{{ $calon->nama }}" loading="lazy" decoding="async" />
                                @else
                                    <div class="w-28 h-28 rounded-full bg-slate-800 flex items-center justify-center text-slate-600 text-5xl relative z-10">
                                        <i class="fa-solid fa-user"></i>
                                    </div>
                                @endif
                            </div>

                            <div class="p-6 bg-slate-950/80 border-t border-slate-800 flex items-center justify-between">
                                <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Visi &amp; Misi</span>
                                <span class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-indigo-500/10 border border-indigo-500/30 text-indigo-300 text-xs font-bold group-hover:bg-indigo-600 group-hover:text-white transition-all">
                                    Lihat Profil <i class="fa-solid fa-arrow-right text-[10px]"></i>
                                </span>
                            </div>
                        </a>
                    @endforeach
                </div>
            </section>
        @endif

        @if ($calonMpk->isNotEmpty())
            {{-- Seksi Ketua MPK --}}
            <section class="mb-4">
                <div class="flex items-center gap-3 mb-6">
                    <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-2xl bg-emerald-500/15 border border-emerald-500/30 text-emerald-300 text-xs font-heading font-extrabold uppercase tracking-widest">
                        <i class="fa-solid fa-scale-balanced"></i> Calon Ketua MPK
                    </span>
                    <span class="text-[10px] font-mono text-slate-500">{{ $calonMpk->count() }} kandidat</span>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8 items-stretch">
                    @foreach ($calonMpk as $calon)
                        <a href="{{ route('calon-public.show', $calon) }}"
                           class="candidate-pub-card group relative bg-slate-900/90 backdrop-blur-2xl rounded-3xl border-2 border-slate-800 hover:border-emerald-500/60 shadow-xl hover:shadow-emerald-500/10 hover:-translate-y-1.5 transition-all duration-300 overflow-hidden flex flex-col">
                            <div class="p-6 pb-4 border-b border-slate-800/80 bg-slate-950/40 flex items-center justify-between">
                                <div>
                                    <span class="text-[11px] font-bold uppercase tracking-widest text-emerald-400 font-mono">KETUA MPK 0{{ $calon->nomor }}</span>
                                    <h2 class="font-heading font-extrabold text-xl sm:text-2xl text-white leading-tight mt-0.5">{{ $calon->nama }}</h2>
                                    <p class="text-xs text-slate-400 font-mono mt-0.5">Kelas {{ $calon->kelas->name ?? '-' }}</p>
                                </div>
                                <div class="w-12 h-12 rounded-2xl bg-emerald-500/10 border border-emerald-500/30 flex items-center justify-center font-heading font-black text-emerald-300 text-xl">
                                    {{ $calon->nomor }}
                                </div>
                            </div>

                            <div class="relative h-[20rem] sm:h-[23rem] bg-gradient-to-b from-slate-900/90 via-slate-900/60 to-slate-950 flex items-center justify-center overflow-hidden p-3">
                                <h1 class="absolute bottom-2 left-3 font-heading font-black text-slate-800/25 text-8xl select-none pointer-events-none z-0">
                                    0{{ $calon->nomor }}
                                </h1>
                                @if ($calon->url_foto)
                                    <img class="candidate-photo w-full h-full object-contain object-center relative z-10 transition-transform duration-500 drop-shadow-2xl"
                                         src="{{ asset($calon->url_foto) }}" alt="{{ $calon->nama }}" loading="lazy" decoding="async" />
                                @else
                                    <div class="w-28 h-28 rounded-full bg-slate-800 flex items-center justify-center text-slate-600 text-5xl relative z-10">
                                        <i class="fa-solid fa-user"></i>
                                    </div>
                                @endif
                            </div>

                            <div class="p-6 bg-slate-950/80 border-t border-slate-800 flex items-center justify-between">
                                <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Visi &amp; Misi</span>
                                <span class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-300 text-xs font-bold group-hover:bg-emerald-600 group-hover:text-white transition-all">
                                    Lihat Profil <i class="fa-solid fa-arrow-right text-[10px]"></i>
                                </span>
                            </div>
                        </a>
                    @endforeach
                </div>
            </section>
        @endif

        @if ($calonOsis->isEmpty() && $calonMpk->isEmpty())
            <div class="text-center py-20 bg-slate-900/60 rounded-3xl border border-slate-800 max-w-lg mx-auto p-8">
                <div class="w-16 h-16 rounded-2xl bg-indigo-500/10 text-indigo-400 flex items-center justify-center mx-auto mb-4 text-2xl">
                    <i class="fa-solid fa-users-slash"></i>
                </div>
                <h3 class="text-xl font-heading font-bold text-white mb-2">Belum Ada Kandidat</h3>
                <p class="text-sm text-slate-400">Daftar kandidat akan dipublikasikan oleh panitia pemilihan.</p>
            </div>
        @endif
    </main>

    <footer class="w-full border-t border-white/5 py-6 z-10 bg-slate-950/60 mt-12">
        <div class="max-w-[1400px] mx-auto px-4 sm:px-6 flex flex-col sm:flex-row items-center justify-between gap-4 text-center sm:text-left">
            <p class="text-xs text-slate-500">
                &copy; {{ date('Y') }} {{ $config['nama_sekolah'] ?? 'PILKETOS' }} &bull; Sistem E-Voting Pemilihan Ketua OSIS &amp; Ketua MPK
            </p>
            <div class="flex items-center gap-4 text-xs text-slate-400">
                <a href="{{ route('live-count') }}" class="hover:text-white transition-colors flex items-center gap-1.5">
                    <i class="fa-solid fa-chart-pie text-rose-400"></i> Live Count
                </a>
                <span class="text-slate-700">&bull;</span>
                <a href="{{ route('login') }}" class="hover:text-indigo-400 transition-colors flex items-center gap-1.5">
                    <i class="fa-solid fa-lock text-indigo-400"></i> Login Petugas / Admin
                </a>
            </div>
        </div>
    </footer>
    <script>
        function dptChecker() {
            return {
                searchQuery: '',
                loading: false,
                searched: false,
                found: false,
                results: [],
                errorMessage: '',

                async checkDpt() {
                    const q = this.searchQuery.trim();
                    if (!q || q.length < 2 || this.loading) return;

                    this.loading = true;
                    this.searched = false;

                    try {
                        const res = await fetch('{{ route("calon-public.check-dpt") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({ nama: q })
                        });

                        const data = await res.json();
                        this.searched = true;

                        if (data.success && data.data.length > 0) {
                            this.found = true;
                            this.results = data.data;
                        } else {
                            this.found = false;
                            this.results = [];
                            this.errorMessage = data.message || 'Nama tidak ditemukan dalam DPT.';
                        }
                    } catch (e) {
                        this.searched = true;
                        this.found = false;
                        this.errorMessage = 'Terjadi kesalahan saat memeriksa data. Silakan coba kembali.';
                    } finally {
                        this.loading = false;
                    }
                }
            };
        }
    </script>
</body>
</html>
