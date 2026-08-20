@php
    $words = explode(' ', $calon->nama);
    $first = $words[0];
    $second = $words[1] ?? '';
    $third = $words[2] ?? '';
@endphp
<div class="h-full">
    <div class="luxury-card rounded-2xl overflow-hidden flex flex-col h-full group hover:border-indigo-500/40 transition-all duration-300">
        {{-- Card Header --}}
        <div class="p-4 border-b border-white/5 flex-shrink-0 flex items-center justify-between bg-slate-950/40">
            <div>
                <span class="text-[9px] font-mono font-bold tracking-widest text-indigo-400 uppercase">NO. 0{{ $calon->nomor }}</span>
                <h3 class="font-heading font-black text-sm text-white leading-tight truncate mt-0.5">
                    {{ $first }} {{ $second }}
                </h3>
            </div>
            <span class="text-[10px] font-mono font-bold px-2 py-0.5 rounded-md bg-slate-900 text-slate-300 border border-slate-700">
                {{ $calon->kelas->name }}
            </span>
        </div>

        {{-- Photo Canvas (Full Contain Display) --}}
        <div class="flex-1 min-h-[170px] bg-gradient-to-b from-slate-900 via-slate-900/80 to-slate-950 flex items-center justify-center overflow-hidden relative group-hover:brightness-105 transition-all p-3">
            <h1 class="absolute bottom-1 left-2 font-heading font-black text-slate-800/30 text-7xl select-none z-0">
                0{{ $calon->nomor }}
            </h1>
            @if ($calon->url_foto)
                <img class="w-full h-full object-contain object-center relative z-10 transition-transform duration-500 group-hover:scale-105 drop-shadow-xl"
                    src="{{ asset($calon->url_foto) }}"
                    alt="{{ $calon->nama }}" />
            @else
                <i class="fas fa-user text-slate-600 text-5xl relative z-10"></i>
            @endif
        </div>

        {{-- Vote Stats Footer --}}
        <div class="p-4 bg-slate-950/70 space-y-2 flex-shrink-0 border-t border-white/5">
            <div class="flex justify-between items-center text-xs">
                <span class="text-slate-400 font-medium">Perolehan</span>
                <span class="font-bold font-mono text-emerald-400 bg-emerald-500/10 border border-emerald-500/20 px-2 py-0.5 rounded-md">{{ $calon->votes_count }} Suara</span>
            </div>
            
            @php
                $percentage = $totalVote > 0 ? ($calon->votes_count / $totalVote) * 100 : 0;
            @endphp
            <div>
                <div class="w-full bg-slate-900 rounded-full h-1.5 overflow-hidden border border-slate-800">
                    <div class="bg-gradient-to-r from-indigo-500 to-indigo-400 h-full rounded-full transition-all duration-700"
                        style="width: {{ $percentage }}%"></div>
                </div>
                <div class="flex justify-end mt-1">
                    <span class="text-[10px] font-bold font-mono text-slate-400">{{ number_format($percentage, 1) }}%</span>
                </div>
            </div>
        </div>
    </div>
</div>
