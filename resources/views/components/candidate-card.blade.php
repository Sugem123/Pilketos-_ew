@php
    $words = explode(' ', $calon->nama);
    $first = $words[0];
    $second = $words[1] ?? '';
    $third = $words[2] ?? '';
@endphp
<div class="transition-all duration-150 ease-in h-full">
    <div class="flex h-full items-center relative">
        <div
            class="bg-white w-full h-full border border-gray-100 rounded-xl shadow-sm overflow-hidden relative flex flex-col">
            <div class="flex gap-3 p-3 border-b border-gray-100 flex-shrink-0">
                <h3 class="font-bold text-lg leading-5">
                    {{ $first }}<br>
                    <span class="text-gray-500 text-sm font-medium">{{ $second }} {{ $third }}</span>
                </h3>
            </div>
            <div
                class="flex-1 min-h-0 bg-gradient-to-br from-gray-50 to-gray-200 flex items-center justify-center overflow-hidden relative">
                <h1 class="absolute top-1 left-1 font-bold opacity-20 text-6xl">{{ '0' . $calon->nomor }}</h1>
                @if ($calon->url_foto)
                    <img class="size-[140%] object-cover absolute -top-3 -right-9" src="{{ asset($calon->url_foto) }}"
                        alt="{{ $calon->nama }}" />
                @else
                    <i class="absolute bottom-0 right-0 far opacity-30 text-8xl fa-user"></i>
                @endif
            </div>
            <div class="p-3 space-y-2 flex-shrink-0">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500 font-medium">SUARA</span>
                    <span class="text-birupesat font-semibold">{{ $calon->votes_count }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500 font-medium">KELAS</span>
                    <span class="text-accent font-semibold">{{ $calon->kelas->name }}</span>
                </div>
                @if ($totalVote > 0)
                    <div class="flex gap-2 items-center">
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-birupesat h-2 rounded-full"
                                style="width: {{ ($calon->votes_count / $totalVote) * 100 }}%"></div>
                        </div>
                        <span
                            class="text-xs text-accent font-semibold">{{ number_format(($calon->votes_count / $totalVote) * 100, 1) }}%</span>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
