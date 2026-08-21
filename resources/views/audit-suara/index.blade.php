@php
    $page_title = 'Rekonsiliasi & Audit Suara Manual';
    $page_description = 'Validasi fisik kartu pemilih dari kotak suara TPS dan tetapkan keabsahan suara';
@endphp
<x-app-layout :page_title="$page_title" :page_description="$page_description">
    <x-slot name="actions">
        <a href="{{ route('cetak.berita-acara') }}" target="_blank"
           class="inline-flex items-center gap-2 px-4 py-2.5 luxury-btn-primary text-white rounded-2xl text-xs font-bold transition-all shadow-lg shadow-indigo-600/30">
            <i class="fas fa-file-signature"></i>
            <span>Cetak Berita Acara Pleno</span>
        </a>
    </x-slot>

    <div class="space-y-8">

        {{-- Top KPI Cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            <div class="luxury-card luxury-card-hover rounded-3xl p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 font-mono block mb-1">Suara Digital Bilik</span>
                        <h3 id="stat-total-digital" class="font-heading font-black text-3xl sm:text-4xl text-white font-mono leading-none">{{ $totalSuaraDigital }}</h3>
                        <p class="text-[11px] text-slate-400 mt-2 font-medium">Total suara masuk sistem</p>
                    </div>
                    <div class="w-14 h-14 rounded-2xl bg-blue-500/10 border border-blue-500/20 text-blue-400 flex items-center justify-center text-xl shadow-lg">
                        <i class="fas fa-check-to-slot"></i>
                    </div>
                </div>
            </div>

            <div class="luxury-card luxury-card-hover rounded-3xl p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 font-mono block mb-1">Suara Sah (Fisik)</span>
                        <h3 id="stat-total-sah" class="font-heading font-black text-3xl sm:text-4xl text-emerald-400 font-mono leading-none">{{ $totalSah }}</h3>
                        <p class="text-[11px] text-slate-400 mt-2 font-mono">Diverifikasi ada di kotak</p>
                    </div>
                    <div class="w-14 h-14 rounded-2xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 flex items-center justify-center text-xl shadow-lg">
                        <i class="fa-solid fa-circle-check"></i>
                    </div>
                </div>
            </div>

            <div class="luxury-card luxury-card-hover rounded-3xl p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 font-mono block mb-1">Tidak Sah / Batal</span>
                        <h3 id="stat-total-tidak-sah" class="font-heading font-black text-3xl sm:text-4xl text-rose-400 font-mono leading-none">{{ $totalTidakSah }}</h3>
                        <p class="text-[11px] text-slate-400 mt-2 font-mono">Kartu fisik tidak ada</p>
                    </div>
                    <div class="w-14 h-14 rounded-2xl bg-rose-500/10 border border-rose-500/20 text-rose-400 flex items-center justify-center text-xl shadow-lg">
                        <i class="fas fa-ban"></i>
                    </div>
                </div>
            </div>

            <div class="luxury-card luxury-card-hover rounded-3xl p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 font-mono block mb-1">Menunggu Audit</span>
                        <h3 id="stat-total-pending" class="font-heading font-black text-3xl sm:text-4xl text-amber-400 font-mono leading-none">{{ $totalPending }}</h3>
                        <p class="text-[11px] text-slate-400 mt-2 font-mono">Belum diverifikasi</p>
                    </div>
                    <div class="w-14 h-14 rounded-2xl bg-amber-500/10 border border-amber-500/20 text-amber-400 flex items-center justify-center text-xl shadow-lg">
                        <i class="fas fa-hourglass-half"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- Comparison Candidate Scores: Quick Count vs Real Count Sah --}}
        <div class="luxury-card rounded-3xl p-6 sm:p-8">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-6 border-b border-white/5 pb-4">
                <div>
                    <h2 class="font-heading font-black text-lg sm:text-xl text-white flex items-center gap-2.5">
                        <i class="fas fa-scale-balanced text-indigo-400"></i>
                        <span>Perbandingan Suara Digital vs Hasil Pleno Sah</span>
                    </h2>
                    <p class="text-xs text-slate-400 mt-0.5">Perolehan suara resmi yang disahkan berdasarkan kartu fisik yang terkumpul di TPS</p>
                </div>
                <span class="text-xs font-mono font-bold px-3 py-1 bg-emerald-500/10 text-emerald-300 border border-emerald-500/20 rounded-xl self-start sm:self-auto">
                    Pleno Rekonsiliasi
                </span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                @foreach($calons as $c)
                    <div class="p-5 rounded-2xl bg-slate-900/80 border border-white/5 flex flex-col justify-between hover:border-indigo-500/30 transition-all">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-mono font-bold px-2 py-0.5 bg-indigo-500/10 text-indigo-300 border border-indigo-500/20 rounded">
                                No. 0{{ $c->nomor }}
                            </span>
                            <span class="text-xs font-mono text-slate-400 font-bold">Kelas {{ $c->kelas->name ?? '-' }}</span>
                        </div>
                        <h4 class="font-heading font-black text-base text-white mb-4 truncate">{{ $c->nama }}</h4>

                        <div class="grid grid-cols-2 gap-2.5 pt-3 border-t border-white/5 text-center font-mono">
                            <div class="p-2.5 bg-slate-950 rounded-xl border border-slate-800">
                                <span class="text-[10px] text-slate-500 uppercase block font-bold">Digital Masuk</span>
                                <span class="text-lg font-black text-slate-300">{{ $c->digital_votes }}</span>
                            </div>
                            <div class="p-2.5 bg-emerald-500/10 rounded-xl border border-emerald-500/20">
                                <span class="text-[10px] text-emerald-400 uppercase font-bold block">Suara Sah</span>
                                <span class="text-lg font-black text-emerald-400">{{ $c->valid_votes }}</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Quick Token Scanner / Kotak Suara Verification --}}
        <div class="luxury-card rounded-3xl p-6 sm:p-8 relative overflow-hidden border border-indigo-500/30" x-data="tokenScanner()">
            <div class="max-w-3xl mx-auto">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6">
                    <div>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-indigo-500/20 text-indigo-300 border border-indigo-500/30 mb-3">
                            <i class="fas fa-box-open"></i> Hitung Manual Kotak Suara
                        </span>
                        <h3 class="font-heading font-black text-2xl tracking-tight text-white mb-1">
                            Validasi Kartu dari Kotak Suara
                        </h3>
                        <p class="text-xs text-slate-400 leading-relaxed">
                            Ambil kartu pemilih dari kotak suara satu per satu. Ketik kode token pada kartu, lalu tekan <strong class="text-white">Enter</strong>.
                            Token yang <strong class="text-emerald-400">sudah digunakan voting</strong> akan dinyatakan <strong class="text-emerald-400">SAH</strong>.
                            Token yang <strong class="text-rose-400">tidak dikenali / belum voting</strong> akan <strong class="text-rose-400">TIDAK SAH</strong>.
                        </p>
                    </div>
                    <div class="flex items-center gap-2 self-start">
                        <span class="text-[11px] font-mono font-bold px-3 py-1.5 bg-amber-500/10 text-amber-300 border border-amber-500/20 rounded-xl whitespace-nowrap">
                            <i class="fas fa-hourglass-half mr-1"></i> Sisa: <span x-text="pendingCount">{{ $totalPending }}</span>
                        </span>
                    </div>
                </div>

                {{-- Input Area --}}
                <div class="flex flex-col sm:flex-row gap-3 mb-5">
                    <div class="relative flex-1">
                        <i class="fas fa-key absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 text-sm"></i>
                        <input type="text" x-ref="tokenInput" autofocus maxlength="10"
                               placeholder="Ketik kode token kartu..."
                               @keydown.enter.prevent="validateToken()"
                               x-model="tokenValue"
                               class="w-full pl-11 pr-4 py-4 luxury-input rounded-2xl text-white font-mono uppercase font-black text-lg tracking-[0.25em] outline-none placeholder:text-sm placeholder:tracking-normal placeholder:font-medium">
                    </div>
                    <button type="button" @click="validateToken()" :disabled="processing"
                            class="px-8 py-4 luxury-btn-primary text-white font-heading font-black text-sm rounded-2xl transition-all shadow-lg shadow-indigo-600/30 flex items-center justify-center gap-2 cursor-pointer active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed">
                        <i class="fas fa-magnifying-glass" x-show="!processing"></i>
                        <i class="fas fa-spinner fa-spin" x-show="processing" x-cloak></i>
                        <span>VALIDASI</span>
                    </button>
                </div>

                {{-- Scanned Token History --}}
                <template x-if="history.length > 0">
                    <div class="bg-slate-950/60 rounded-2xl border border-white/5 p-4 max-h-48 overflow-y-auto">
                        <div class="flex items-center justify-between mb-3">
                            <h4 class="text-[11px] font-bold uppercase tracking-wider text-slate-400 font-mono">
                                <i class="fas fa-list-check mr-1"></i> Riwayat Scan (<span x-text="history.length"></span> kartu)
                            </h4>
                            <button @click="history = []" class="text-[10px] text-slate-500 hover:text-slate-300 transition-colors">
                                <i class="fas fa-trash-can mr-0.5"></i> Bersihkan
                            </button>
                        </div>
                        <div class="space-y-1.5">
                            <template x-for="(item, idx) in history" :key="idx">
                                <div class="flex items-center justify-between px-3 py-2 rounded-xl text-xs font-mono"
                                     :class="item.verdict === 'sah' ? 'bg-emerald-500/10 border border-emerald-500/15' : item.verdict === 'sudah' ? 'bg-blue-500/10 border border-blue-500/15' : 'bg-rose-500/10 border border-rose-500/15'">
                                    <div class="flex items-center gap-2">
                                        <i :class="item.verdict === 'sah' ? 'fa-solid fa-circle-check text-emerald-400' : item.verdict === 'sudah' ? 'fa-solid fa-circle-info text-blue-400' : 'fa-solid fa-circle-xmark text-rose-400'"></i>
                                        <code class="font-black tracking-widest text-white" x-text="item.token"></code>
                                        <span class="text-slate-400" x-text="item.name || ''"></span>
                                    </div>
                                    <span class="font-bold uppercase"
                                          :class="item.verdict === 'sah' ? 'text-emerald-400' : item.verdict === 'sudah' ? 'text-blue-400' : 'text-rose-400'"
                                          x-text="item.verdict === 'sah' ? 'SAH' : item.verdict === 'sudah' ? 'SUDAH' : 'TIDAK SAH'"></span>
                                </div>
                            </template>
                        </div>
                    </div>
                </template>

                {{-- Hanguskan Sisa Button --}}
                <div class="mt-5 pt-5 border-t border-white/5 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
                    <p class="text-[11px] text-slate-500 leading-relaxed max-w-md">
                        <i class="fas fa-triangle-exclamation text-amber-500 mr-1"></i>
                        Setelah semua kartu dalam kotak suara selesai dibacakan, tekan tombol di samping untuk menghanguskan sisa token yang tidak tervalidasi.
                    </p>
                    <button type="button" @click="hanguskanSisa()"
                            :disabled="pendingCount === 0"
                            class="px-5 py-3 bg-rose-600/80 hover:bg-rose-600 disabled:bg-slate-800 disabled:text-slate-600 disabled:border-slate-700 text-white font-heading font-bold text-xs rounded-2xl transition-all shadow-lg shadow-rose-600/20 flex items-center gap-2 cursor-pointer active:scale-95 disabled:cursor-not-allowed border border-rose-500/30 disabled:border-slate-700 whitespace-nowrap">
                        <i class="fas fa-fire"></i>
                        <span>Hanguskan Sisa (<span x-text="pendingCount"></span>)</span>
                    </button>
                </div>
            </div>

            {{-- ====== FULLSCREEN VERDICT OVERLAY ====== --}}
            <div x-show="showOverlay" x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                 @click="closeOverlay()"
                 class="fixed inset-0 z-[9999] flex items-center justify-center p-4"
                 :class="overlayVerdict === 'sah' ? 'bg-emerald-950/80' : overlayVerdict === 'sudah' ? 'bg-blue-950/80' : 'bg-rose-950/80'"
                 style="backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px);"
                 x-cloak>
                <div x-show="showOverlay"
                     x-transition:enter="transition ease-out duration-400"
                     x-transition:enter-start="opacity-0 scale-75 translate-y-8"
                     x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-90"
                     @click.stop
                     class="w-full max-w-md rounded-3xl p-8 text-center shadow-2xl border"
                     :class="overlayVerdict === 'sah' ? 'bg-slate-950 border-emerald-500/30 shadow-emerald-500/20' : overlayVerdict === 'sudah' ? 'bg-slate-950 border-blue-500/30 shadow-blue-500/20' : 'bg-slate-950 border-rose-500/30 shadow-rose-500/20'">

                    {{-- Icon --}}
                    <div class="mb-5">
                        <div class="w-24 h-24 rounded-full mx-auto flex items-center justify-center text-5xl"
                             :class="overlayVerdict === 'sah' ? 'bg-emerald-500/15 text-emerald-400 ring-4 ring-emerald-500/20' : overlayVerdict === 'sudah' ? 'bg-blue-500/15 text-blue-400 ring-4 ring-blue-500/20' : 'bg-rose-500/15 text-rose-400 ring-4 ring-rose-500/20'"
                             x-show="showOverlay"
                             x-transition:enter="transition ease-out duration-500 delay-100"
                             x-transition:enter-start="scale-0 rotate-180"
                             x-transition:enter-end="scale-100 rotate-0">
                            <i :class="overlayVerdict === 'sah' ? 'fa-solid fa-circle-check' : overlayVerdict === 'sudah' ? 'fa-solid fa-circle-info' : 'fa-solid fa-circle-xmark'"></i>
                        </div>
                    </div>

                    {{-- Verdict Label --}}
                    <h2 class="font-heading font-black text-4xl mb-2 tracking-tight"
                        :class="overlayVerdict === 'sah' ? 'text-emerald-400' : overlayVerdict === 'sudah' ? 'text-blue-400' : 'text-rose-400'"
                        x-text="overlayVerdict === 'sah' ? 'SUARA SAH' : overlayVerdict === 'sudah' ? 'SUDAH DIVERIFIKASI' : 'TIDAK SAH'">
                    </h2>

                    {{-- Token Code --}}
                    <code class="inline-block px-5 py-2 bg-slate-900 border border-white/10 rounded-xl text-2xl font-black font-mono tracking-[0.3em] text-white mb-3"
                          x-text="overlayToken"></code>

                    {{-- Message --}}
                    <p class="text-sm text-slate-300 mb-1" x-text="overlayMessage"></p>
                    <p class="text-xs text-slate-500" x-show="overlayVoterName" x-text="'Pemilih: ' + overlayVoterName"></p>
                    <p class="text-xs text-slate-500 mt-0.5" x-show="overlayCalon" x-text="'Memilih: ' + overlayCalon"></p>

                    {{-- Auto-close hint --}}
                    <p class="text-[10px] text-slate-600 mt-6 font-mono">Klik di mana saja atau tekan Enter untuk lanjut</p>
                </div>
            </div>
        </div>

        {{-- Verification Table Container --}}
        <div class="luxury-card rounded-3xl overflow-hidden">
            {{-- Table Filter & Mass Action Bar --}}
            <div class="p-6 border-b border-white/5 bg-slate-950/40 flex flex-col lg:flex-row items-stretch lg:items-center justify-between gap-4">
                <form method="GET" action="{{ route('audit-suara.index') }}" class="flex flex-col sm:flex-row gap-3 flex-1">
                    <div class="relative flex-1">
                        <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 text-xs"></i>
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Cari token atau nama pemilih..."
                            class="w-full pl-10 pr-4 py-3 luxury-input rounded-2xl text-xs font-semibold outline-none">
                    </div>

                    <select name="status" class="px-4 py-3 luxury-input rounded-2xl text-xs font-semibold outline-none">
                        <option value="">Semua Status Audit</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending Audit</option>
                        <option value="sah" {{ request('status') === 'sah' ? 'selected' : '' }}>Sudah Sah</option>
                        <option value="tidak_sah" {{ request('status') === 'tidak_sah' ? 'selected' : '' }}>Tidak Sah / Batal</option>
                    </select>

                    <button type="submit" class="px-6 py-3 luxury-btn-primary text-white text-xs font-bold rounded-2xl transition-all shadow-md cursor-pointer">
                        Filter
                    </button>
                    @if(request('search') || request('status'))
                        <a href="{{ route('audit-suara.index') }}" class="px-4 py-3 bg-slate-900 border border-white/10 text-slate-400 hover:text-white text-xs font-bold rounded-2xl transition-colors text-center">
                            Reset
                        </a>
                    @endif
                </form>

                {{-- Mass Action Options --}}
                <div class="flex items-center gap-2 self-end lg:self-auto">
                    <form method="POST" action="{{ route('audit-suara.batch-verify') }}" onsubmit="return confirm('Apakah Anda yakin ingin mengesahkan SEMUA suara pending?')">
                        @csrf
                        <input type="hidden" name="action" value="sah_all">
                        <button type="submit" class="px-4 py-2.5 bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 hover:bg-emerald-500/20 text-xs font-bold rounded-2xl transition-all cursor-pointer">
                            <i class="fas fa-check-double mr-1"></i> Sahkan Semua
                        </button>
                    </form>

                    <form method="POST" action="{{ route('audit-suara.batch-verify') }}" onsubmit="return confirm('Reset semua status audit ke Pending?')">
                        @csrf
                        <input type="hidden" name="action" value="reset_all">
                        <button type="submit" class="px-4 py-2.5 bg-slate-900 text-slate-400 border border-white/10 hover:bg-slate-800 text-xs font-bold rounded-2xl transition-all cursor-pointer">
                            <i class="fas fa-rotate-left mr-1"></i> Reset Audit
                        </button>
                    </form>
                </div>
            </div>

            {{-- Audit Table --}}
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-slate-950/60 border-b border-white/5">
                        <tr>
                            <th class="px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-wider font-mono">No</th>
                            <th class="px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-wider font-mono">Token Kartu</th>
                            <th class="px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-wider font-mono">Kategori Pemilih</th>
                            <th class="px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-wider font-mono">Waktu Voting</th>
                            <th class="px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-wider font-mono">Status Audit</th>
                            <th class="text-right px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-wider font-mono">Aksi Verifikasi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5" id="audit-table-body">
                        @forelse($votes as $index => $v)
                            <tr class="hover:bg-slate-900/50 transition-colors" id="vote-row-{{ $v->hakSuara->token }}">
                                <td class="px-6 py-4 text-xs font-mono text-slate-500">{{ $index + 1 }}</td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <code class="px-3 py-1 bg-slate-950 border border-slate-800 text-amber-400 rounded-xl text-xs font-mono font-black tracking-widest select-all">
                                            {{ $v->hakSuara->token }}
                                        </code>
                                    </div>
                                    <p class="text-[10px] text-slate-400 mt-1 truncate max-w-xs">{{ $v->hakSuara->nisn }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    @if($v->hakSuara->tipe === 'guru')
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-purple-500/10 text-purple-300 border border-purple-500/20 text-[10px] font-bold rounded-xl font-mono">
                                            <i class="fas fa-chalkboard-user"></i> GURU
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-blue-500/10 text-blue-300 border border-blue-500/20 text-[10px] font-bold rounded-xl font-mono">
                                            <i class="fas fa-graduation-cap"></i> {{ $v->hakSuara->kelas->name ?? 'SISWA' }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-xs font-mono text-slate-400">
                                    {{ \Carbon\Carbon::parse($v->created_at)->format('H:i:s') }}
                                    <span class="block text-[10px] text-slate-500">{{ \Carbon\Carbon::parse($v->created_at)->format('d M Y') }}</span>
                                </td>
                                <td class="px-6 py-4" id="badge-status-{{ $v->id }}">
                                    @if($v->status_verifikasi === 'sah')
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-xs font-bold rounded-full">
                                            <i class="fa-solid fa-circle-check text-[11px]"></i> SAH
                                        </span>
                                    @elseif($v->status_verifikasi === 'tidak_sah')
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-rose-500/10 border border-rose-500/20 text-rose-400 text-xs font-bold rounded-full">
                                            <i class="fa-solid fa-ban text-[11px]"></i> TIDAK SAH
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-amber-500/10 border border-amber-500/20 text-amber-400 text-xs font-bold rounded-full">
                                            <i class="fa-solid fa-hourglass text-[11px]"></i> PENDING
                                        </span>
                                    @endif
                                    @if($v->catatan_verifikasi)
                                        <p class="text-[10px] text-slate-400 mt-1 max-w-xs truncate">{{ $v->catatan_verifikasi }}</p>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <form method="POST" action="{{ route('audit-suara.verify-single', $v) }}" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="sah">
                                            <input type="hidden" name="catatan" value="Kartu fisik ada di kotak suara">
                                            <button type="submit" title="Tandai Sah (Kartu Fisik Ada)"
                                                    class="p-2.5 rounded-xl {{ $v->status_verifikasi === 'sah' ? 'bg-emerald-600 text-white' : 'bg-emerald-500/10 text-emerald-400 hover:bg-emerald-500/20' }} text-xs font-bold transition-all cursor-pointer">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>

                                        <form method="POST" action="{{ route('audit-suara.verify-single', $v) }}" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="tidak_sah">
                                            <input type="hidden" name="catatan" value="Kartu fisik tidak ditemukan di kotak">
                                            <button type="submit" title="Tandai Tidak Sah / Batal"
                                                    class="p-2.5 rounded-xl {{ $v->status_verifikasi === 'tidak_sah' ? 'bg-rose-600 text-white' : 'bg-rose-500/10 text-rose-400 hover:bg-rose-500/20' }} text-xs font-bold transition-all cursor-pointer">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-14 text-center">
                                    <div class="w-12 h-12 rounded-2xl bg-slate-900 border border-white/5 text-slate-600 flex items-center justify-center mx-auto mb-3 text-lg">
                                        <i class="fas fa-box-open"></i>
                                    </div>
                                    <p class="text-xs font-semibold text-slate-400">Belum ada suara digital yang masuk.</p>
                                    <p class="text-[11px] text-slate-500 mt-0.5">Suara yang masuk lewat bilik e-voting akan muncul di sini untuk diverifikasi fisik.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        function tokenScanner() {
            return {
                tokenValue: '',
                processing: false,
                history: [],
                pendingCount: {{ $totalPending }},

                // Overlay state
                showOverlay: false,
                overlayVerdict: '',
                overlayToken: '',
                overlayMessage: '',
                overlayVoterName: '',
                overlayCalon: '',
                _overlayTimer: null,

                init() {
                    // Close overlay on Enter
                    document.addEventListener('keydown', (e) => {
                        if (e.key === 'Enter' && this.showOverlay) {
                            e.preventDefault();
                            this.closeOverlay();
                        }
                    });
                },

                async validateToken() {
                    const token = this.tokenValue.trim().toUpperCase();
                    if (!token || this.processing) return;

                    this.processing = true;

                    try {
                        const res = await fetch('{{ route("audit-suara.quick-verify") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({ token })
                        });

                        const data = await res.json();

                        // Add to history
                        this.history.unshift({
                            token: token,
                            verdict: data.verdict || 'tidak_sah',
                            name: data.voter_name || ''
                        });

                        // Show overlay
                        this.showVerdict(
                            data.verdict || 'tidak_sah',
                            token,
                            data.message,
                            data.voter_name || '',
                            data.calon || ''
                        );

                        // Update counts
                        if (data.counts) {
                            this.pendingCount = data.counts.pending;
                            document.getElementById('stat-total-sah').textContent = data.counts.sah;
                            document.getElementById('stat-total-tidak-sah').textContent = data.counts.tidak_sah;
                            document.getElementById('stat-total-pending').textContent = data.counts.pending;
                        }

                        // Clear input
                        this.tokenValue = '';

                    } catch (err) {
                        this.showVerdict('tidak_sah', token, 'Kesalahan komunikasi dengan server.', '', '');
                    } finally {
                        this.processing = false;
                    }
                },

                showVerdict(verdict, token, message, voterName, calon) {
                    this.overlayVerdict = verdict;
                    this.overlayToken = token;
                    this.overlayMessage = message;
                    this.overlayVoterName = voterName;
                    this.overlayCalon = calon;
                    this.showOverlay = true;

                    // Play sound effect
                    this.playSound(verdict);

                    // Auto-close after 3.5 seconds
                    clearTimeout(this._overlayTimer);
                    this._overlayTimer = setTimeout(() => this.closeOverlay(), 3500);
                },

                closeOverlay() {
                    clearTimeout(this._overlayTimer);
                    this.showOverlay = false;
                    this.$nextTick(() => {
                        this.$refs.tokenInput?.focus();
                    });
                },

                playSound(verdict) {
                    try {
                        const ctx = new (window.AudioContext || window.webkitAudioContext)();
                        const osc = ctx.createOscillator();
                        const gain = ctx.createGain();
                        osc.connect(gain);
                        gain.connect(ctx.destination);
                        gain.gain.value = 0.12;

                        if (verdict === 'sah') {
                            // Rising two-tone — success
                            osc.frequency.setValueAtTime(523, ctx.currentTime);       // C5
                            osc.frequency.setValueAtTime(784, ctx.currentTime + 0.1); // G5
                            osc.type = 'sine';
                            osc.start();
                            osc.stop(ctx.currentTime + 0.25);
                        } else if (verdict === 'sudah') {
                            // Short blip — info
                            osc.frequency.setValueAtTime(660, ctx.currentTime);
                            osc.type = 'sine';
                            osc.start();
                            osc.stop(ctx.currentTime + 0.12);
                        } else {
                            // Low buzz — error
                            osc.frequency.setValueAtTime(220, ctx.currentTime);
                            osc.type = 'square';
                            gain.gain.value = 0.06;
                            osc.start();
                            osc.stop(ctx.currentTime + 0.3);
                        }
                    } catch (e) { /* no audio support */ }
                },

                async hanguskanSisa() {
                    if (this.pendingCount === 0) return;

                    const confirmResult = await Swal.fire({
                        title: 'Hanguskan Sisa Token?',
                        html: `<p style="font-size:0.9rem;color:#9ca3af">Semua <strong style="color:#f59e0b">${this.pendingCount} suara</strong> yang belum diverifikasi akan dinyatakan <strong style="color:#f87171">TIDAK SAH</strong>.</p><p style="font-size:0.8rem;color:#6b7280;margin-top:0.5rem">Pastikan semua kartu dalam kotak suara sudah selesai dibacakan.</p>`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Ya, Hanguskan Sisa',
                        cancelButtonText: 'Batal',
                        customClass: {
                            confirmButton: '!bg-rose-600 !shadow-rose-600/40',
                        }
                    });

                    if (!confirmResult.isConfirmed) return;

                    try {
                        const res = await fetch('{{ route("audit-suara.hanguskan-sisa") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        });

                        const data = await res.json();

                        if (data.success) {
                            if (data.counts) {
                                this.pendingCount = data.counts.pending;
                                document.getElementById('stat-total-sah').textContent = data.counts.sah;
                                document.getElementById('stat-total-tidak-sah').textContent = data.counts.tidak_sah;
                                document.getElementById('stat-total-pending').textContent = data.counts.pending;
                            }

                            await Swal.fire({
                                title: 'Selesai!',
                                html: `<p style="color:#9ca3af">${data.message}</p>`,
                                icon: 'success',
                            });

                            location.reload();
                        }
                    } catch (err) {
                        await Swal.fire({ title: 'Error', text: 'Gagal menghanguskan sisa token.', icon: 'error' });
                    }
                }
            };
        }
    </script>
</x-app-layout>

