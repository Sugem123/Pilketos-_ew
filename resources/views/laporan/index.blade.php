@php $page_title = 'Laporan'; @endphp
<x-app-layout>
    <div class="space-y-6">
        <div>
            <h1 class="text-2xl font-bold text-accent">Laporan Pemilihan</h1>
            <p class="text-gray-500 mt-1">Hasil dan statistik pemilihan ketua OSIS</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-vote-yea text-blue-600 text-lg"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Total Suara Masuk</p>
                        <h3 class="text-2xl font-bold text-gray-800">{{ $totalVote }}</h3>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-users text-purple-600 text-lg"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Total Hak Suara</p>
                        <h3 class="text-2xl font-bold text-gray-800">{{ $hakSuara }}</h3>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-percentage text-green-600 text-lg"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Partisipasi</p>
                        <h3 class="text-2xl font-bold text-gray-800">{{ $partisipasi }}%</h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-lg font-semibold text-accent mb-4">Hasil per Calon</h2>
                <div class="space-y-4">
                    @forelse($calons as $index => $calon)
                        <div class="flex items-center gap-4">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0 {{ $index === 0 ? 'bg-yellow-100 text-yellow-600' : 'bg-gray-100 text-gray-500' }}">
                                @if($index === 0)
                                    <i class="fas fa-crown"></i>
                                @else
                                    <span class="text-sm font-medium">{{ $index + 1 }}</span>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between mb-1">
                                    <span class="text-sm font-medium text-accent truncate">{{ $calon->nama }}</span>
                                    <span class="text-sm font-semibold text-birupesat">{{ $calon->votes_count }} suara</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-birupesat h-2 rounded-full transition-all" style="width: {{ $totalVote > 0 ? ($calon->votes_count / $totalVote) * 100 : 0 }}%"></div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500 text-center py-8">Belum ada data.</p>
                    @endforelse
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-lg font-semibold text-accent mb-4">Grafik Distribusi</h2>
                <div class="relative" style="height: 300px;">
                    <canvas id="voteChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('voteChart').getContext('2d');
        const calonLabels = @json($calons->pluck('nama'));
        const voteData = @json($calons->pluck('votes_count'));

        new Chart(ctx, {
            type: 'polarArea',
            data: {
                labels: calonLabels,
                datasets: [{
                    label: 'Jumlah Suara',
                    data: voteData,
                    backgroundColor: [
                        'rgba(47, 37, 117, 0.7)',
                        'rgba(59, 130, 246, 0.7)',
                        'rgba(16, 185, 129, 0.7)',
                        'rgba(245, 158, 11, 0.7)',
                        'rgba(239, 68, 68, 0.7)',
                        'rgba(139, 92, 246, 0.7)',
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { font: { family: 'Montserrat' } }
                    }
                }
            }
        });
    </script>
</x-app-layout>
