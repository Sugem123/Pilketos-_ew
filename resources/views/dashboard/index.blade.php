@php
    $page_title = 'Dashboard';
    $page_description = 'Selamat datang di sistem pemilihan ketua OSIS';
@endphp
<x-app-layout :page_title="$page_title" :page_description="$page_description">
    <div class="space-y-6">
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Jumlah Calon</p>
                        <h3 class="text-2xl font-bold text-gray-800">{{ $totalCalon }}</h3>
                    </div>
                    <div class="w-10 h-10 bg-accent rounded-lg flex items-center justify-center">
                        <i class="fas fa-user-group text-primary text-sm"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Total Hak Suara</p>
                        <h3 class="text-2xl font-bold text-gray-800">{{ $hakSuara }}</h3>
                    </div>
                    <div class="w-10 h-10 bg-accent rounded-lg flex items-center justify-center">
                        <i class="fas fa-users text-primary text-sm"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Total Votes</p>
                        <h3 class="text-2xl font-bold text-gray-800">{{ $totalVote }}</h3>
                    </div>
                    <div class="w-10 h-10 bg-accent rounded-lg flex items-center justify-center">
                        <i class="fa-regular fa-circle-check text-primary text-base"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Tingkat Partisipasi</p>
                        <h3 class="text-2xl font-bold text-gray-800">{{ $partisipasi }}%</h3>
                    </div>
                    <div class="w-10 h-10 bg-accent rounded-lg flex items-center justify-center">
                        <i class="fas fa-chart-simple text-primary text-sm"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-lg font-semibold text-accent mb-4">Tren Suara Masuk</h2>
                <div class="relative" style="height: 300px;">
                    <canvas id="voteTrendChart"></canvas>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-lg font-semibold text-accent mb-4">Suara Terkini</h2>
                <div class="space-y-3 max-h-[300px] overflow-y-auto">
                    @forelse($recentVotes as $vote)
                        <div class="flex items-center gap-3 p-2 rounded-lg hover:bg-gray-50">
                            <div
                                class="w-8 h-8 bg-birupesat/20 rounded-full flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-check text-birupesat text-xs"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-accent truncate">{{ $vote->hakSuara->nisn }}</p>
                                <p class="text-xs text-gray-500">memilih {{ $vote->calon->nama }}</p>
                            </div>
                            <span
                                class="text-xs text-gray-400 flex-shrink-0">{{ \Carbon\Carbon::parse($vote->created_at)->diffForHumans() }}</span>
                        </div>
                    @empty
                        <p class="text-gray-500 text-center py-8">Belum ada suara masuk.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-lg font-semibold text-accent mb-4">Calon Ketos</h2>
                <div class="overflow-x-auto scrollbar-thin hover:scrollbar-visible" style="height: 300px;">
                    @if ($calons->isNotEmpty())
                        <div class="flex gap-4 h-full" style="min-width: min-content;">
                            @foreach ($calons as $calon)
                                <div class="w-[10rem] flex-shrink-0 h-full">
                                    <x-candidate-card :calon="$calon" :totalVote="$totalVote" />
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="flex items-center justify-center h-full text-center">
                            <div>
                                <i class="fas fa-user-group text-neutral-400 text-4xl mb-3"></i>
                                <p class="text-gray-500 text-sm">Belum ada calon</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-lg font-semibold text-accent mb-4">Distribusi Suara</h2>
                <div class="relative" style="height: 300px;">
                    <canvas id="voteDistributionChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <style>
        .scrollbar-thin::-webkit-scrollbar {
            height: 4px;
        }

        .scrollbar-thin::-webkit-scrollbar-track {
            background: transparent;
        }

        .scrollbar-thin::-webkit-scrollbar-thumb {
            background: transparent;
            border-radius: 9999px;
            transition: background 0.2s;
        }

        .scrollbar-visible::-webkit-scrollbar-thumb {
            background: #d1d5db;
        }

        .scrollbar-visible::-webkit-scrollbar-thumb:hover {
            background: #9ca3af;
        }

        .scrollbar-thin {
            scrollbar-width: thin;
            scrollbar-color: transparent transparent;
        }

        .scrollbar-thin:hover {
            scrollbar-color: #d1d5db transparent;
        }
    </style>

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
                    borderColor: '#2f2575',
                    backgroundColor: 'rgba(47, 37, 117, 0.1)',
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#2f2575',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            font: {
                                family: 'Montserrat'
                            }
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: '#f3f4f6'
                        },
                        ticks: {
                            font: {
                                family: 'Montserrat'
                            },
                            stepSize: 1
                        }
                    }
                }
            }
        });

        const ctxPie = document.getElementById('voteDistributionChart').getContext('2d');
        const pieLabels = @json($calons->pluck('nama'));
        const pieData = @json($calons->pluck('votes_count'));
        const pieColors = ['#2f2575', '#4a3f9a', '#6b5fc7', '#8d80d8', '#b0a3e8', '#d4c9f5'];

        new Chart(ctxPie, {
            type: 'pie',
            data: {
                labels: pieLabels,
                datasets: [{
                    data: pieData,
                    backgroundColor: pieColors.slice(0, pieData.length),
                    borderColor: '#fff',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 12,
                            usePointStyle: true,
                            pointStyle: 'circle',
                            font: {
                                family: 'Montserrat',
                                size: 11
                            }
                        }
                    }
                }
            }
        });
    </script>
</x-app-layout>
