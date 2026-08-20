@php
    $page_title = 'Dashboard Utama';
    $page_description = 'Executive overview statistik suara, rekapitulasi kehadiran per kelas, dan log bilik TPS';
@endphp
<x-app-layout :page_title="$page_title" :page_description="$page_description">
    <div class="space-y-8">
        {{-- Luxury Metric Stats Grid --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            {{-- Card 1: Total DPT --}}
            <div class="luxury-card luxury-card-hover rounded-3xl p-6 relative overflow-hidden">
                <div class="flex items-center justify-between">
                    <div>
                        <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 font-mono block mb-1">Total Hak Suara</span>
                        <h3 class="font-heading font-black text-3xl sm:text-4xl text-white font-mono leading-none">{{ $hakSuara }}</h3>
                        <p class="text-[11px] text-slate-400 mt-2 font-medium">Pemilih sah terdaftar DPT</p>
                    </div>
                    <div class="w-14 h-14 rounded-2xl bg-blue-500/10 border border-blue-500/20 text-blue-400 flex items-center justify-center text-xl shadow-lg">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
            </div>

            {{-- Card 2: Partisipasi Siswa --}}
            <div class="luxury-card luxury-card-hover rounded-3xl p-6 relative overflow-hidden">
                <div class="flex items-center justify-between">
                    <div>
                        <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 font-mono block mb-1">Partisipasi Siswa</span>
                        <h3 class="font-heading font-black text-3xl sm:text-4xl text-indigo-400 font-mono leading-none">{{ $partisipasiSiswa }}%</h3>
                        <p class="text-[11px] text-slate-400 mt-2 font-mono"><strong class="text-white">{{ $siswaMemilih }}</strong> dari {{ $totalSiswa }} siswa</p>
                    </div>
                    <div class="w-14 h-14 rounded-2xl bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 flex items-center justify-center text-xl shadow-lg">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                </div>
            </div>

            {{-- Card 3: Partisipasi Guru --}}
            <div class="luxury-card luxury-card-hover rounded-3xl p-6 relative overflow-hidden">
                <div class="flex items-center justify-between">
                    <div>
                        <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 font-mono block mb-1">Partisipasi Guru</span>
                        <h3 class="font-heading font-black text-3xl sm:text-4xl text-purple-400 font-mono leading-none">{{ $partisipasiGuru }}%</h3>
                        <p class="text-[11px] text-slate-400 mt-2 font-mono"><strong class="text-white">{{ $guruMemilih }}</strong> dari {{ $totalGuru }} guru</p>
                    </div>
                    <div class="w-14 h-14 rounded-2xl bg-purple-500/10 border border-purple-500/20 text-purple-400 flex items-center justify-center text-xl shadow-lg">
                        <i class="fas fa-chalkboard-user"></i>
                    </div>
                </div>
            </div>

            {{-- Card 4: Total Suara Digital --}}
            <div class="luxury-card luxury-card-hover rounded-3xl p-6 relative overflow-hidden">
                <div class="flex items-center justify-between">
                    <div>
                        <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 font-mono block mb-1">Total Suara Masuk</span>
                        <h3 class="font-heading font-black text-3xl sm:text-4xl text-emerald-400 font-mono leading-none">{{ $totalVote }}</h3>
                        <p class="text-[11px] text-slate-400 mt-2 font-mono">Partisipasi total: <strong class="text-emerald-400">{{ $partisipasi }}%</strong></p>
                    </div>
                    <div class="w-14 h-14 rounded-2xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 flex items-center justify-center text-xl shadow-lg">
                        <i class="fa-solid fa-circle-check"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- Class Tracking Matrix (30 Classes) --}}
        <div class="luxury-card rounded-3xl p-6 sm:p-8">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6 border-b border-white/5 pb-4">
                <div>
                    <h2 class="font-heading font-black text-lg sm:text-xl text-white flex items-center gap-2.5">
                        <i class="fas fa-layer-group text-indigo-400"></i>
                        <span>Tracking Progres Kehadiran Siswa per Kelas</span>
                    </h2>
                    <p class="text-xs text-slate-400 mt-0.5">Pemantauan data pemilih yang sudah menukarkan hak suara di TPS secara realtime</p>
                </div>
                <span class="text-xs font-mono font-bold px-3 py-1 bg-indigo-500/10 text-indigo-300 border border-indigo-500/20 rounded-xl self-start sm:self-auto">
                    {{ $kelasStats->count() }} Kelas Aktif
                </span>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 lg:grid-cols-6 gap-3.5">
                @forelse($kelasStats as $ks)
                    <div class="p-3.5 rounded-2xl bg-slate-900/80 border border-white/5 hover:border-indigo-500/30 transition-all">
                        <div class="flex items-center justify-between mb-2">
                            <span class="font-heading font-black text-xs text-slate-200">Kelas {{ $ks->name }}</span>
                            <span class="text-xs font-mono font-extrabold {{ $ks->percentage == 100 ? 'text-emerald-400' : ($ks->percentage > 50 ? 'text-indigo-400' : 'text-slate-400') }}">
                                {{ $ks->percentage }}%
                            </span>
                        </div>
                        <div class="w-full bg-slate-950 rounded-full h-1.5 overflow-hidden mb-2 border border-slate-800">
                            <div class="h-full rounded-full transition-all duration-700 {{ $ks->percentage == 100 ? 'bg-emerald-400' : 'bg-gradient-to-r from-indigo-600 to-indigo-400' }}"
                                style="width: {{ $ks->percentage }}%"></div>
                        </div>
                        <div class="flex justify-between text-[10px] text-slate-400 font-mono">
                            <span>Vote: <strong class="text-white">{{ $ks->total_voted }}</strong></span>
                            <span>DPT: <strong class="text-slate-300">{{ $ks->total_dpt }}</strong></span>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-8 text-center text-slate-500 text-xs">
                        Belum ada data kelas yang terdaftar.
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Candidates Overview & Live Charts --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Candidate Live Scores --}}
            <div class="lg:col-span-2 luxury-card rounded-3xl p-6 sm:p-8 flex flex-col justify-between">
                <div class="flex items-center justify-between mb-6 border-b border-white/5 pb-4">
                    <div>
                        <h2 class="font-heading font-black text-lg sm:text-xl text-white flex items-center gap-2.5">
                            <i class="fas fa-users-viewfinder text-indigo-400"></i>
                            <span>Perolehan Suara Calon Ketua OSIS</span>
                        </h2>
                        <p class="text-xs text-slate-400 mt-0.5">Hasil kalkulasi suara sementara bilik suara</p>
                    </div>
                    <span class="px-3 py-1 text-xs font-mono font-bold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 rounded-xl">
                        Live Bilik
                    </span>
                </div>

                <div class="overflow-x-auto pb-2" style="min-height: 320px;">
                    @if ($calons->isNotEmpty())
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-5">
                            @foreach ($calons as $calon)
                                <x-candidate-card :calon="$calon" :totalVote="$totalVote" />
                            @endforeach
                        </div>
                    @else
                        <div class="flex items-center justify-center h-64 text-center">
                            <div>
                                <i class="fas fa-user-group text-slate-600 text-4xl mb-3"></i>
                                <p class="text-slate-400 text-xs">Belum ada kandidat yang terdaftar</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Doughnut Distribution --}}
            <div class="luxury-card rounded-3xl p-6 sm:p-8 flex flex-col justify-between">
                <div class="mb-4 border-b border-white/5 pb-4">
                    <h2 class="font-heading font-black text-lg text-white flex items-center gap-2">
                        <i class="fas fa-chart-pie text-indigo-400"></i>
                        <span>Sebaran Persentase</span>
                    </h2>
                    <p class="text-xs text-slate-400 mt-0.5">Distribusi perolehan suara paslon</p>
                </div>
                <div class="relative flex-1 flex items-center justify-center min-h-[260px]">
                    <canvas id="voteDistributionChart"></canvas>
                </div>
            </div>
        </div>

        {{-- Trends & Log Feed --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 luxury-card rounded-3xl p-6 sm:p-8">
                <div class="flex items-center justify-between mb-6 border-b border-white/5 pb-4">
                    <div>
                        <h2 class="font-heading font-black text-lg text-white flex items-center gap-2">
                            <i class="fas fa-chart-line text-indigo-400"></i>
                            <span>Tren Masuknya Suara</span>
                        </h2>
                        <p class="text-xs text-slate-400 mt-0.5">Aktivitas rekapitulasi periodik</p>
                    </div>
                </div>
                <div class="relative" style="height: 280px;">
                    <canvas id="voteTrendChart"></canvas>
                </div>
            </div>

            <div class="luxury-card rounded-3xl p-6 sm:p-8 flex flex-col justify-between">
                <div class="mb-4 border-b border-white/5 pb-4 flex items-center justify-between">
                    <div>
                        <h2 class="font-heading font-black text-lg text-white flex items-center gap-2">
                            <i class="fas fa-clock-rotate-left text-indigo-400"></i>
                            <span>Suara Terkini</span>
                        </h2>
                        <p class="text-xs text-slate-400 mt-0.5">Log 10 suara terakhir</p>
                    </div>
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                </div>
                <div class="space-y-2.5 flex-1 max-h-[280px] overflow-y-auto pr-1">
                    @forelse($recentVotes as $vote)
                        <div class="flex items-center gap-3 p-3 rounded-2xl bg-slate-900/80 border border-white/5 hover:border-indigo-500/20 transition-all">
                            <div class="w-8 h-8 bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 rounded-xl flex items-center justify-center flex-shrink-0 font-bold text-xs">
                                <i class="fas fa-check"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-1.5">
                                    <p class="text-xs font-bold text-white truncate">{{ $vote->hakSuara->nisn }}</p>
                                    @if($vote->hakSuara->tipe === 'guru')
                                        <span class="text-[9px] px-1.5 py-0.2 bg-purple-500/20 text-purple-300 rounded font-mono font-bold">Guru</span>
                                    @elseif($vote->hakSuara->kelas)
                                        <span class="text-[9px] px-1.5 py-0.2 bg-indigo-500/20 text-indigo-300 rounded font-mono font-bold">{{ $vote->hakSuara->kelas->name }}</span>
                                    @endif
                                </div>
                                <p class="text-[11px] text-slate-400 truncate mt-0.5">Memilih <strong class="text-indigo-400">{{ $vote->calon->nama }}</strong></p>
                            </div>
                            <span class="text-[10px] font-mono text-slate-400 flex-shrink-0 bg-slate-950 px-2 py-0.5 rounded-lg border border-slate-800">
                                {{ \Carbon\Carbon::parse($vote->created_at)->format('H:i') }}
                            </span>
                        </div>
                    @empty
                        <div class="text-center py-12">
                            <i class="fas fa-inbox text-slate-600 text-3xl mb-2"></i>
                            <p class="text-xs text-slate-500">Belum ada suara masuk.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('voteTrendChart').getContext('2d');
        const chartLabels = @json($votesByDate->pluck('date'));
        const chartData = @json($votesByDate->pluck('count'));

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: chartLabels,
                datasets: [{
                    label: 'Jumlah Suara',
                    data: chartData,
                    borderColor: '#6366f1',
                    backgroundColor: 'rgba(99, 102, 241, 0.1)',
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#6366f1',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 5
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { color: '#94a3b8', font: { family: 'Plus Jakarta Sans', size: 11 } }
                    },
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(255, 255, 255, 0.05)' },
                        ticks: { color: '#94a3b8', font: { family: 'Plus Jakarta Sans', size: 11 }, stepSize: 1 }
                    }
                }
            }
        });

        const ctxPie = document.getElementById('voteDistributionChart').getContext('2d');
        const pieLabels = @json($calons->pluck('nama'));
        const pieData = @json($calons->pluck('votes_count'));
        const pieColors = ['#6366f1', '#8b5cf6', '#f59e0b', '#10b981', '#06b6d4', '#ec4899'];

        new Chart(ctxPie, {
            type: 'doughnut',
            data: {
                labels: pieLabels,
                datasets: [{
                    data: pieData,
                    backgroundColor: pieColors.slice(0, pieData.length),
                    borderColor: '#0f172a',
                    borderWidth: 3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            color: '#e2e8f0',
                            padding: 14,
                            usePointStyle: true,
                            pointStyle: 'circle',
                            font: { family: 'Plus Jakarta Sans', size: 11 }
                        }
                    }
                },
                cutout: '70%'
            }
        });
    </script>
</x-app-layout>
