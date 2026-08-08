@php $page_title = 'Dashboard'; @endphp
<x-app-layout>
    <div class="space-y-6">
        <div>
            <h1 class="text-2xl font-bold text-accent">Dashboard</h1>
            <p class="text-gray-500 mt-1">Selamat datang di sistem pemilihan ketua OSIS</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Jumlah Calon</p>
                        <h3 class="text-3xl font-bold text-gray-800">{{ $totalCalon }}</h3>
                    </div>
                    <div class="w-12 h-12 bg-accent rounded-lg flex items-center justify-center">
                        <i class="fas fa-user-group text-primary text-lg"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Total Votes</p>
                        <h3 class="text-3xl font-bold text-gray-800">{{ $totalVote }}</h3>
                    </div>
                    <div class="w-12 h-12 bg-accent rounded-lg flex items-center justify-center">
                        <i class="fa-regular fa-circle-check text-primary text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Tingkat Partisipasi</p>
                        <h3 class="text-3xl font-bold text-gray-800">{{ $partisipasi }}%</h3>
                    </div>
                    <div class="w-12 h-12 bg-accent rounded-lg flex items-center justify-center">
                        <i class="fas fa-chart-simple text-primary text-lg"></i>
                    </div>
                </div>
            </div>
        </div>

        <div>
            <h2 class="text-lg font-semibold text-accent mb-4">Calon Ketos</h2>

            @if($calons->isNotEmpty())
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                    @foreach($calons as $calon)
                        @php
                            $words = explode(' ', $calon->nama);
                            $first = $words[0];
                            $second = $words[1] ?? '';
                            $third = $words[2] ?? '';
                        @endphp
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow">
                            <div class="flex gap-3 p-4 border-b border-gray-100">
                                <h3 class="font-bold text-lg leading-tight">
                                    {{ $first }}<br>
                                    <span class="text-gray-500 text-sm font-medium">{{ $second }} {{ $third }}</span>
                                </h3>
                            </div>
                            <div class="h-40 bg-gradient-to-br from-gray-50 to-gray-200 flex items-center justify-center overflow-hidden relative">
                                <h1 class="absolute top-2 left-3 font-black opacity-20 text-6xl">{{ '0' . $calon->nomor }}</h1>
                                @if($calon->url_foto)
                                    <img class="size-[140%] object-cover absolute -top-3 -right-9" src="{{ asset($calon->url_foto) }}" alt="{{ $calon->nama }}" />
                                @else
                                    <i class="absolute bottom-0 right-0 far opacity-30 text-8xl fa-user"></i>
                                @endif
                            </div>
                            <div class="p-4 space-y-2">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500 font-medium">VOTERS</span>
                                    <span class="text-birupesat font-semibold">{{ $calon->votes_count }}</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500 font-medium">KELAS</span>
                                    <span class="text-accent font-semibold">{{ $calon->kelas->name }}</span>
                                </div>
                                @if($totalVote > 0)
                                    <div class="flex gap-2 items-center">
                                        <div class="w-full bg-gray-200 rounded-full h-2">
                                            <div class="bg-birupesat h-2 rounded-full" style="width: {{ ($calon->votes_count / $totalVote) * 100 }}%"></div>
                                        </div>
                                        <span class="text-xs text-accent font-semibold">{{ number_format(($calon->votes_count / $totalVote) * 100, 1) }}%</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12 bg-white rounded-xl border border-gray-100">
                    <i class="fas fa-user-group text-neutral-400 text-5xl mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada calon</h3>
                    <p class="text-gray-500">Silakan tambahkan calon ketua OSIS terlebih dahulu.</p>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
