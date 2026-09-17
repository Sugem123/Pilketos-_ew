@php
    $page_title = 'Rekonsiliasi & Audit Suara Manual';
    $page_description = 'Validasi fisik kartu pemilih dari kotak suara TPS dan tetapkan keabsahan suara';
@endphp
<x-app-layout :page_title="$page_title" :page_description="$page_description">
    <x-slot name="actions">
        <div class="flex items-center gap-2.5 flex-wrap">
            {{-- Live Sync Badge & Indicator --}}
            <div class="inline-flex items-center gap-2 px-3.5 py-2 bg-slate-900 border border-emerald-500/30 text-emerald-400 rounded-2xl text-xs font-mono font-bold shadow-md">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span>
                <span>Live Realtime: <strong id="audit-sync-time" class="text-white">--:--:--</strong></span>
            </div>

            <a href="{{ route('cetak.berita-acara') }}" target="_blank"
               class="inline-flex items-center gap-2 px-4 py-2.5 luxury-btn-primary text-white rounded-2xl text-xs font-bold transition-all shadow-lg shadow-indigo-600/30">
                <i class="fas fa-file-signature"></i>
                <span>Cetak Berita Acara Pleno</span>
            </a>
        </div>
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

        {{-- ====== PAPAN SKOR PENGHITUNGAN SUARA MANUAL (1 LAYAR - TANPA FOTO) ====== --}}
        <div class="luxury-card rounded-3xl p-6 sm:p-8 space-y-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-white/10 pb-5">
                <div>
                    <h2 class="font-heading font-black text-lg sm:text-2xl text-white flex items-center gap-2.5">
                        <i class="fas fa-scale-balanced text-indigo-400"></i>
                        <span>Papan Rekonsiliasi &amp; Penghitungan Manual (Pleno TPS)</span>
                    </h2>
                    <p class="text-xs text-slate-400 mt-1">
                        Membandingkan suara tercatat bilik digital dengan kartu fisik yang sah di dalam kotak suara.
                    </p>
                </div>
                <div class="flex items-center gap-2 self-start sm:self-auto font-mono text-xs">
                    <span class="px-3 py-1.5 rounded-xl bg-emerald-500/10 text-emerald-300 border border-emerald-500/20 font-bold">
                        <i class="fas fa-check-double mr-1"></i> Mode Pleno Sah
                    </span>
                </div>
            </div>

            {{-- 1. SEKSI KETUA OSIS (3 PASLON SEJAJAR) --}}
            <div class="space-y-3">
                <div class="flex items-center justify-between px-1">
                    <span class="inline-flex items-center gap-2 text-xs font-heading font-extrabold uppercase tracking-widest text-indigo-400">
                        <i class="fas fa-user-tie"></i> Kandidat Ketua OSIS (3 Pasangan)
                    </span>
                    <span class="text-[11px] font-mono text-slate-400">
                        Total Suara Bilik: <strong id="osis-board-total" class="text-indigo-300">{{ $totalVoteOsis ?? 0 }}</strong>
                    </span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    @foreach($calonOsis as $c)
                        <div class="p-4 sm:p-5 rounded-2xl bg-slate-900/90 border border-white/10 hover:border-indigo-500/40 transition-all flex flex-col justify-between shadow-lg">
                            <div>
                                <div class="flex items-start justify-between gap-2 mb-2">
                                    <div class="min-w-0 flex-1">
                                        <div class="flex items-center gap-2 mb-1">
                                            <span class="w-7 h-7 rounded-lg bg-indigo-500/20 text-indigo-300 border border-indigo-500/30 font-mono font-black text-xs flex items-center justify-center flex-shrink-0">
                                                0{{ $c->nomor }}
                                            </span>
                                            <span class="text-[11px] font-mono font-bold text-slate-400 bg-slate-950 px-2 py-0.5 rounded border border-white/5">
                                                {{ $c->kelas->name ?? '-' }}
                                            </span>
                                        </div>
                                        <h4 class="font-heading font-black text-sm sm:text-base text-white leading-tight truncate" title="{{ $c->nama }}">
                                            {{ $c->nama }}
                                        </h4>
                                    </div>
                                </div>

                                @if(!empty($c->nama_wakil_1) || !empty($c->nama_wakil_2))
                                    <div class="mt-2 pt-2 border-t border-white/5 space-y-1 text-[11px] text-slate-300 font-mono">
                                        @if(!empty($c->nama_wakil_1))
                                            <p class="truncate flex items-center gap-1.5">
                                                <span class="text-[9px] px-1 py-0.2 rounded bg-indigo-500/20 text-indigo-300 font-bold">W1</span>
                                                <span class="truncate">{{ $c->nama_wakil_1 }}</span>
                                                @if($c->kelasWakil1)
                                                    <span class="text-slate-500 text-[10px]">({{ $c->kelasWakil1->name }})</span>
                                                @endif
                                            </p>
                                        @endif
                                        @if(!empty($c->nama_wakil_2))
                                            <p class="truncate flex items-center gap-1.5">
                                                <span class="text-[9px] px-1 py-0.2 rounded bg-indigo-500/20 text-indigo-300 font-bold">W2</span>
                                                <span class="truncate">{{ $c->nama_wakil_2 }}</span>
                                                @if($c->kelasWakil2)
                                                    <span class="text-slate-500 text-[10px]">({{ $c->kelasWakil2->name }})</span>
                                                @endif
                                            </p>
                                        @endif
                                    </div>
                                @endif
                            </div>

                            <div class="grid grid-cols-2 gap-2 pt-3 mt-3 border-t border-white/10 text-center font-mono">
                                <div class="p-2.5 rounded-xl bg-emerald-500/10 border border-emerald-500/30">
                                    <span class="text-[9px] uppercase font-bold text-emerald-400 block tracking-wider">Suara Sah (Fisik)</span>
                                    <span class="text-2xl sm:text-3xl font-black text-emerald-400 leading-none mt-1 block" id="candidate-sah-{{ $c->id }}">{{ $c->valid_votes }}</span>
                                </div>
                                <div class="p-2.5 rounded-xl bg-slate-950 border border-slate-800">
                                    <span class="text-[9px] uppercase font-bold text-slate-500 block tracking-wider">Digital Bilik</span>
                                    <span class="text-lg sm:text-xl font-bold text-slate-300 leading-none mt-1.5 block" id="candidate-digital-{{ $c->id }}">{{ $c->digital_votes }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- 2. SEKSI KETUA MPK (5 PASLON SEJAJAR DALAM 1 BARIS GRID) --}}
            <div class="space-y-3 pt-3 border-t border-white/10">
                <div class="flex items-center justify-between px-1">
                    <span class="inline-flex items-center gap-2 text-xs font-heading font-extrabold uppercase tracking-widest text-emerald-400">
                        <i class="fas fa-scale-balanced"></i> Kandidat Ketua MPK (5 Pasangan)
                    </span>
                    <span class="text-[11px] font-mono text-slate-400">
                        Total Suara Bilik: <strong id="mpk-board-total" class="text-emerald-300">{{ $totalVoteMpk ?? 0 }}</strong>
                    </span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
                    @foreach($calonMpk as $c)
                        <div class="p-3.5 sm:p-4 rounded-2xl bg-slate-900/90 border border-white/10 hover:border-emerald-500/40 transition-all flex flex-col justify-between shadow-lg">
                            <div>
                                <div class="flex items-start justify-between gap-2 mb-1.5">
                                    <div class="min-w-0 flex-1">
                                        <div class="flex items-center gap-1.5 mb-1">
                                            <span class="w-6 h-6 rounded-lg bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 font-mono font-black text-xs flex items-center justify-center flex-shrink-0">
                                                0{{ $c->nomor }}
                                            </span>
                                            <span class="text-[10px] font-mono font-bold text-slate-400 bg-slate-950 px-1.5 py-0.5 rounded border border-white/5">
                                                {{ $c->kelas->name ?? '-' }}
                                            </span>
                                        </div>
                                        <h4 class="font-heading font-black text-xs sm:text-sm text-white leading-tight truncate" title="{{ $c->nama }}">
                                            {{ $c->nama }}
                                        </h4>
                                    </div>
                                </div>

                                @if(!empty($c->nama_wakil_1) || !empty($c->nama_wakil_2))
                                    <div class="mt-1.5 pt-1.5 border-t border-white/5 space-y-0.5 text-[10px] text-slate-300 font-mono">
                                        @if(!empty($c->nama_wakil_1))
                                            <p class="truncate flex items-center gap-1">
                                                <span class="text-[8px] px-1 py-0.2 rounded bg-emerald-500/20 text-emerald-300 font-bold">W1</span>
                                                <span class="truncate">{{ $c->nama_wakil_1 }}</span>
                                            </p>
                                        @endif
                                        @if(!empty($c->nama_wakil_2))
                                            <p class="truncate flex items-center gap-1">
                                                <span class="text-[8px] px-1 py-0.2 rounded bg-emerald-500/20 text-emerald-300 font-bold">W2</span>
                                                <span class="truncate">{{ $c->nama_wakil_2 }}</span>
                                            </p>
                                        @endif
                                    </div>
                                @endif
                            </div>

                            <div class="grid grid-cols-2 gap-1.5 pt-2.5 mt-2.5 border-t border-white/10 text-center font-mono">
                                <div class="p-2 rounded-xl bg-emerald-500/10 border border-emerald-500/30">
                                    <span class="text-[8px] uppercase font-bold text-emerald-400 block tracking-wider">Sah</span>
                                    <span class="text-xl sm:text-2xl font-black text-emerald-400 leading-none mt-1 block" id="candidate-sah-{{ $c->id }}">{{ $c->valid_votes }}</span>
                                </div>
                                <div class="p-2 rounded-xl bg-slate-950 border border-slate-800">
                                    <span class="text-[8px] uppercase font-bold text-slate-500 block tracking-wider">Bilik</span>
                                    <span class="text-base sm:text-lg font-bold text-slate-300 leading-none mt-1 block" id="candidate-digital-{{ $c->id }}">{{ $c->digital_votes }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
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
                            Pilih mode input: gunakan <strong class="text-indigo-300">Kamera Scanner QR</strong> atau <strong class="text-indigo-300">Input Manual / Barcode Gun</strong>.
                            Token yang <strong class="text-emerald-400">sudah digunakan voting</strong> akan dinyatakan <strong class="text-emerald-400">SAH</strong>.
                        </p>
                    </div>
                    <div class="flex items-center gap-2 self-start">
                        <span class="text-[11px] font-mono font-bold px-3 py-1.5 bg-amber-500/10 text-amber-300 border border-amber-500/20 rounded-xl whitespace-nowrap">
                            <i class="fas fa-hourglass-half mr-1"></i> Sisa: <span x-text="pendingCount">{{ $totalPending }}</span>
                        </span>
                    </div>
                </div>

                {{-- Mode Switcher Tabs + Wireless HP Scanner + Auto/Manual Toggle --}}
                <div class="flex flex-col md:flex-row items-stretch md:items-center justify-between gap-4 mb-6">
                    <div class="flex items-center gap-2 p-1.5 bg-slate-950/80 rounded-2xl border border-white/10 flex-wrap">
                        <button type="button" @click="setMode('manual')"
                                class="px-4 py-2.5 rounded-xl text-xs font-heading font-bold transition-all flex items-center gap-2"
                                :class="activeMode === 'manual' ? 'bg-indigo-600 text-white shadow-md shadow-indigo-600/30' : 'text-slate-400 hover:text-white'">
                            <i class="fas fa-keyboard"></i>
                            <span>Input Manual / Gun</span>
                        </button>
                        <button type="button" @click="setMode('camera')"
                                class="px-4 py-2.5 rounded-xl text-xs font-heading font-bold transition-all flex items-center gap-2"
                                :class="activeMode === 'camera' ? 'bg-emerald-600 text-white shadow-md shadow-emerald-600/30' : 'text-slate-400 hover:text-white'">
                            <i class="fas fa-video"></i>
                            <span>Kamera Laptop</span>
                            <span class="w-2 h-2 rounded-full" :class="cameraRunning ? 'bg-emerald-400 animate-ping' : 'bg-slate-600'"></span>
                        </button>
                        <button type="button" @click="openPairingModal()"
                                class="px-4 py-2.5 rounded-xl text-xs font-heading font-bold transition-all flex items-center gap-2 bg-gradient-to-r from-teal-600 to-emerald-600 text-white shadow-md hover:brightness-110">
                            <i class="fas fa-mobile-screen-button"></i>
                            <span>Sambungkan HP Scanner</span>
                            <span class="w-2 h-2 rounded-full" :class="remoteConnected ? 'bg-emerald-300 animate-ping' : 'bg-amber-400'"></span>
                        </button>
                    </div>

                    {{-- Toggle Validasi Otomatis vs Manual --}}
                    <div class="flex items-center gap-3 p-2 bg-slate-950/80 rounded-2xl border border-white/10 self-start md:self-auto">
                        <div class="text-right">
                            <span class="text-[10px] font-bold uppercase tracking-wider block font-mono"
                                  :class="autoValidate ? 'text-emerald-400' : 'text-amber-400'"
                                  x-text="autoValidate ? 'VALIDASI OTOMATIS: ON' : 'VALIDASI MANUAL: ON'"></span>
                            <span class="text-[9px] text-slate-500 block leading-none"
                                  x-text="autoValidate ? 'Scan langsung sahkan suara' : 'Scan masuk teks, klik tombol validasi'"></span>
                        </div>
                        <button type="button" @click="autoValidate = !autoValidate"
                                class="w-12 h-7 rounded-full p-1 transition-colors relative cursor-pointer"
                                :class="autoValidate ? 'bg-emerald-600' : 'bg-slate-800'">
                            <div class="w-5 h-5 rounded-full bg-white transition-transform shadow-md"
                                 :class="autoValidate ? 'translate-x-5' : 'translate-x-0'"></div>
                        </button>
                    </div>
                </div>

                {{-- Status Bar Wireless HP --}}
                <div x-show="pairingSession" x-cloak class="mb-5 p-3.5 rounded-2xl bg-teal-950/40 border border-teal-500/30 flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs font-mono">
                    <div class="flex items-center gap-2.5 flex-wrap">
                        <i class="fas fa-mobile-screen-button text-teal-400 text-sm"></i>
                        <span class="text-slate-300">Scanner HP:</span>
                        <code class="px-2 py-0.5 rounded bg-slate-900 text-amber-300 font-bold tracking-widest text-[11px]" x-text="pairingSession"></code>

                        {{-- Indikator Perangkat --}}
                        <template x-if="pendingDevicesCount > 0">
                            <span @click="openPairingModal()" class="cursor-pointer px-2.5 py-1 rounded-xl bg-amber-500/20 text-amber-300 border border-amber-500/30 font-bold animate-pulse">
                                <i class="fas fa-bell mr-1"></i> <span x-text="pendingDevicesCount"></span> HP Menunggu Izin!
                            </span>
                        </template>

                        <template x-if="approvedDevicesCount > 0">
                            <span class="flex items-center gap-1.5 text-emerald-400 font-bold">
                                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span>
                                <span x-text="approvedDevicesSummary"></span>
                            </span>
                        </template>

                        <template x-if="approvedDevicesCount === 0 && pendingDevicesCount === 0">
                            <span class="text-slate-500 italic">Belum ada HP terhubung</span>
                        </template>
                    </div>

                    <div class="flex items-center gap-2 self-end sm:self-auto">
                        <button type="button" @click="openPairingModal()" class="px-3 py-1.5 rounded-xl bg-teal-600/30 hover:bg-teal-600 text-teal-300 hover:text-white transition-all font-bold text-[11px] flex items-center gap-1">
                            <i class="fas fa-sliders"></i> Kelola HP (<span x-text="devices.length"></span>)
                        </button>
                    </div>
                </div>

                {{-- CAMERA SCANNER PANEL --}}
                <div x-show="activeMode === 'camera'" x-cloak class="mb-5 p-5 bg-slate-950/90 rounded-3xl border border-emerald-500/30 shadow-2xl">
                    <div class="flex flex-col sm:flex-row items-center justify-between gap-3 mb-4">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-xl bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 flex items-center justify-center text-sm">
                                <i class="fas fa-camera"></i>
                            </div>
                            <div>
                                <h4 class="font-heading font-bold text-sm text-white">Scanner Kamera QR</h4>
                                <p class="text-[11px] text-slate-400">Arahkan QR Code pada kartu suara ke lensa kamera</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-2 w-full sm:w-auto">
                            <select x-model="selectedCameraId" @change="changeCamera()" x-show="cameras.length > 1"
                                    class="px-3 py-2 bg-slate-900 border border-white/10 rounded-xl text-xs text-slate-200 outline-none">
                                <template x-for="cam in cameras" :key="cam.id">
                                    <option :value="cam.id" x-text="cam.label || 'Kamera ' + cam.id"></option>
                                </template>
                            </select>

                            <button type="button" @click="toggleCameraState()"
                                    class="px-4 py-2 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5 cursor-pointer shadow-md"
                                    :class="cameraRunning ? 'bg-rose-600 hover:bg-rose-700 text-white' : 'bg-emerald-600 hover:bg-emerald-700 text-white'">
                                <i :class="cameraRunning ? 'fas fa-stop' : 'fas fa-play'"></i>
                                <span x-text="cameraRunning ? 'Hentikan Kamera' : 'Nyalakan Kamera'"></span>
                            </button>
                        </div>
                    </div>

                    {{-- Camera Viewport Container --}}
                    <div class="relative w-full max-w-md mx-auto aspect-square sm:aspect-video rounded-2xl overflow-hidden bg-black border border-slate-800 flex items-center justify-center">
                        <div id="qr-camera-reader" class="w-full h-full"></div>
                        <div x-show="!cameraRunning" class="absolute inset-0 flex flex-col items-center justify-center p-6 text-center bg-slate-950/90">
                            <div class="w-16 h-16 rounded-2xl bg-slate-900 border border-white/10 flex items-center justify-center text-slate-500 text-2xl mb-3">
                                <i class="fas fa-video-slash"></i>
                            </div>
                            <p class="text-xs font-bold text-white mb-1">Kamera Nonaktif</p>
                            <p class="text-[11px] text-slate-400 max-w-xs mb-3">Tekan tombol "Nyalakan Kamera" untuk mulai memindai QR Code kartu suara</p>
                            <button type="button" @click="startCamera()" class="px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold transition-all shadow-lg shadow-emerald-600/20 flex items-center gap-2">
                                <i class="fas fa-play"></i> Mulai Scan
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Input Area Manual & Gun Scanner --}}
                <div class="flex flex-col sm:flex-row gap-3 mb-5">
                    <div class="relative flex-1">
                        <i class="fas fa-key absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 text-sm"></i>
                        <input type="text" x-ref="tokenInput" maxlength="10"
                               :placeholder="activeMode === 'camera' ? 'Atau ketik token manual di sini...' : 'Ketik kode token kartu atau scan dengan barcode gun...'"
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
                                        <span class="text-slate-500 font-mono text-[11px]" x-show="item.kategori" x-text="'(' + item.kategori + ')'"></span>
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

            {{-- ====== MODAL PAIRING HP SCANNER & MANAJEMEN MULTI-HP ====== --}}
            <div x-show="showPairingModal" x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                 class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-md overflow-y-auto"
                 x-cloak>
                <div @click.stop class="w-full max-w-lg bg-slate-900 border border-teal-500/30 rounded-3xl p-6 sm:p-8 shadow-2xl relative text-center my-8">
                    <button type="button" @click="closePairingModal()" class="absolute top-5 right-5 text-slate-400 hover:text-white p-2 rounded-xl bg-slate-800/60">
                        <i class="fas fa-times text-sm"></i>
                    </button>

                    <div class="w-12 h-12 rounded-2xl bg-teal-500/15 border border-teal-500/30 text-teal-300 flex items-center justify-center mx-auto mb-3 text-xl shadow-lg">
                        <i class="fas fa-mobile-screen-button"></i>
                    </div>

                    <h3 class="font-heading font-black text-xl text-white mb-1">Manajemen HP Scanner (Multi-HP)</h3>
                    <p class="text-xs text-slate-400 max-w-xs mx-auto mb-5 leading-relaxed">
                        Scan QR Code di bawah dengan smartphone panitia. Setiap HP yang terhubung harus disetujui (approve) oleh Admin di sini.
                    </p>

                    {{-- Canvas QR Code Pairing --}}
                    <div class="p-3 bg-white rounded-2xl inline-block shadow-xl mb-4 border-2 border-teal-500/30">
                        <div id="pairing-qr-canvas" class="w-[160px] h-[160px] flex items-center justify-center">
                            <span class="text-xs text-slate-400 font-mono">Membuat QR...</span>
                        </div>
                    </div>

                    <div class="space-y-4 text-left">
                        <div class="flex items-center justify-between px-3 py-2 bg-slate-950/60 rounded-xl border border-white/5 text-xs font-mono">
                            <span class="text-slate-400">Kode Sesi Pairing:</span>
                            <code class="px-2 py-0.5 bg-slate-900 text-amber-300 font-bold rounded" x-text="pairingSession"></code>
                            <button type="button" @click="copyPairingUrl()" class="text-teal-400 hover:text-white text-[11px] font-bold">
                                <i class="fas fa-copy"></i> Salin Link
                            </button>
                        </div>

                        {{-- Section Permintaan Izin Masuk (Pending Approval) --}}
                        <div x-show="pendingDevices.length > 0" class="p-4 rounded-2xl bg-amber-500/10 border border-amber-500/30 space-y-2">
                            <div class="flex items-center justify-between text-xs font-mono font-bold text-amber-300">
                                <span><i class="fas fa-bell mr-1 animate-bounce"></i> Permintaan Koneksi Masuk:</span>
                                <span x-text="pendingDevices.length + ' HP'"></span>
                            </div>
                            <template x-for="dev in pendingDevices" :key="dev.id">
                                <div class="p-3 bg-slate-950/90 rounded-xl border border-amber-500/20 flex items-center justify-between gap-3">
                                    <div>
                                        <h5 class="text-xs font-bold text-white" x-text="dev.name"></h5>
                                        <span class="text-[10px] text-slate-400 font-mono" x-text="'Masuk pukul ' + dev.joined_at"></span>
                                    </div>
                                    <div class="flex items-center gap-1.5">
                                        <button type="button" @click="handleDevice(dev.id, 'approve')"
                                                class="px-3 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs flex items-center gap-1">
                                            <i class="fas fa-check"></i> Setujui
                                        </button>
                                        <button type="button" @click="handleDevice(dev.id, 'reject')"
                                                class="px-3 py-1.5 rounded-lg bg-rose-600/80 hover:bg-rose-600 text-white font-bold text-xs flex items-center gap-1">
                                            <i class="fas fa-times"></i> Tolak
                                        </button>
                                    </div>
                                </div>
                            </template>
                        </div>

                        {{-- Section Daftar HP Aktif / Terkoneksi (Approved) --}}
                        <div class="p-4 rounded-2xl bg-slate-950/80 border border-white/5 space-y-2">
                            <div class="flex items-center justify-between text-xs font-mono font-bold text-slate-400">
                                <span><i class="fas fa-network-wired mr-1 text-teal-400"></i> HP Yang Sedang Terhubung:</span>
                                <span class="text-emerald-400" x-text="approvedDevices.length + ' Aktif'"></span>
                            </div>

                            <template x-for="dev in approvedDevices" :key="dev.id">
                                <div class="p-3 bg-slate-900/90 rounded-xl border border-emerald-500/30 flex items-center justify-between gap-3">
                                    <div class="flex items-center gap-2.5">
                                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 animate-ping"></span>
                                        <div>
                                            <h5 class="text-xs font-bold text-white" x-text="dev.name"></h5>
                                            <span class="text-[10px] text-emerald-400 font-mono">Status: Terkoneksi &bull; Aktif</span>
                                        </div>
                                    </div>
                                    <button type="button" @click="handleDevice(dev.id, 'kick')"
                                            class="px-3 py-1.5 rounded-lg bg-rose-500/10 text-rose-400 border border-rose-500/20 hover:bg-rose-500 hover:text-white font-bold text-[11px] transition-all">
                                        <i class="fas fa-power-off mr-1"></i> Putus
                                    </button>
                                </div>
                            </template>

                            <div x-show="approvedDevices.length === 0" class="text-center py-4 text-xs text-slate-500 font-mono">
                                Belum ada HP yang disetujui. Scan QR di atas untuk menghubungkan.
                            </div>
                        </div>

                        <div class="pt-2 text-center">
                            <button type="button" @click="closePairingModal()" class="w-full py-3 rounded-xl bg-teal-600 hover:bg-teal-500 text-white text-xs font-bold shadow-md">
                                Selesai &amp; Mulai Hitung Suara
                            </button>
                        </div>
                    </div>
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
                    <p class="text-sm text-slate-300 mb-2" x-text="overlayMessage"></p>
                    <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-xl bg-slate-900 border border-white/10 text-xs font-mono text-slate-300">
                        <i class="fas fa-shield-halved text-emerald-400"></i>
                        <span>Asas Rahasia Terjaga</span>
                        <template x-if="overlayKategori">
                            <span class="text-indigo-300" x-text="'&bull; ' + overlayKategori"></span>
                        </template>
                    </div>
                    <p class="text-[11px] text-teal-400 font-mono mt-2.5 font-bold" x-show="overlayDeviceName" x-text="'Discan via: ' + overlayDeviceName"></p>

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
                            placeholder="Cari kode token kartu pemilih..."
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
                            <th class="px-4 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-wider font-mono text-center w-12">No</th>
                            <th class="px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-wider font-mono">Token Kartu</th>
                            <th class="px-5 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-wider font-mono">Surat Suara</th>
                            <th class="px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-wider font-mono">Kategori Pemilih</th>
                            <th class="px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-wider font-mono">Waktu Voting</th>
                            <th class="px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-wider font-mono">Status Audit</th>
                            <th class="text-right px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-wider font-mono">Aksi Verifikasi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5" id="audit-table-body">
                        @forelse($votes as $index => $v)
                            <tr class="hover:bg-slate-900/50 transition-colors" id="vote-row-{{ $v->id }}">
                                <td class="px-4 py-4 text-xs font-mono text-slate-500 text-center">{{ $index + 1 }}</td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <code class="px-3 py-1 bg-slate-950 border border-slate-800 text-amber-400 rounded-xl text-xs font-mono font-black tracking-widest select-all">
                                            {{ $v->hakSuara->token }}
                                        </code>
                                    </div>
                                    <p class="text-[10px] text-slate-500 font-mono mt-1 flex items-center gap-1">
                                        <i class="fas fa-shield-halved text-[9px] text-emerald-400/80"></i>
                                        <span>Identitas Dirahasiakan</span>
                                    </p>
                                </td>
                                <td class="px-5 py-4">
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 text-[11px] font-mono font-black rounded-xl uppercase shadow-sm {{ $v->tipe_pemilihan === 'mpk' ? 'bg-emerald-500/15 text-emerald-300 border border-emerald-500/30' : 'bg-indigo-500/15 text-indigo-300 border border-indigo-500/30' }}">
                                        <i class="{{ $v->tipe_pemilihan === 'mpk' ? 'fa-solid fa-scale-balanced' : 'fa-solid fa-user-tie' }} text-[10px]"></i>
                                        {{ strtoupper($v->tipe_pemilihan) }}
                                    </span>
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

    <script src="{{ asset('js/qrcode.min.js') }}"></script>
    <script src="{{ asset('js/html5-qrcode.min.js') }}"></script>
    <script>
        function tokenScanner() {
            return {
                activeMode: 'manual', // 'manual' or 'camera'
                autoValidate: true,   // true: langsung sahkan, false: isi token ke input lalu panitia klik Validasi
                tokenValue: '',
                processing: false,
                history: [],
                pendingCount: {{ $totalPending }},

                // Wireless Remote HP Pairing State
                showPairingModal: false,
                pairingSession: '',
                pairingUrl: '',
                devices: [],
                _pollInterval: null,
                _qrRendered: false,

                get pendingDevices() {
                    return this.devices.filter(d => d.status === 'pending');
                },
                get pendingDevicesCount() {
                    return this.pendingDevices.length;
                },
                get approvedDevices() {
                    return this.devices.filter(d => d.status === 'approved');
                },
                get approvedDevicesCount() {
                    return this.approvedDevices.length;
                },
                get approvedDevicesSummary() {
                    const count = this.approvedDevicesCount;
                    if (count === 0) return 'Belum ada HP terhubung';
                    const names = this.approvedDevices.map(d => d.name).join(', ');
                    return `HP Terkoneksi (${count} HP): ${names}`;
                },

                // Camera Scanner State
                cameraRunning: false,
                html5QrCode: null,
                cameras: [],
                selectedCameraId: '',
                lastScannedCode: '',
                lastScanTime: 0,

                // Overlay state
                showOverlay: false,
                overlayVerdict: '',
                overlayToken: '',
                overlayMessage: '',
                overlayKategori: '',
                overlayDeviceName: '',
                _overlayTimer: null,
                _syncInterval: null,

                init() {
                    // Close overlay on Enter
                    document.addEventListener('keydown', (e) => {
                        if (e.key === 'Enter' && this.showOverlay) {
                            e.preventDefault();
                            this.closeOverlay();
                        }
                    });

                    // Start realtime live sync auto-refresh
                    this.startLiveSync();
                },

                async syncAuditData() {
                    try {
                        const res = await fetch('{{ route("audit-suara.live-data") }}');
                        const data = await res.json();
                        if (!data.success) return;

                        // 1. Update KPI numbers
                        if (data.counts) {
                            this.pendingCount = data.counts.pending;
                            const elDig = document.getElementById('stat-total-digital');
                            const elSah = document.getElementById('stat-total-sah');
                            const elTdk = document.getElementById('stat-total-tidak-sah');
                            const elPnd = document.getElementById('stat-total-pending');
                            if (elDig) elDig.textContent = data.counts.total;
                            if (elSah) elSah.textContent = data.counts.sah;
                            if (elTdk) elTdk.textContent = data.counts.tidak_sah;
                            if (elPnd) elPnd.textContent = data.counts.pending;
                        }

                        // 2. Update Candidate Scoreboards (OSIS & MPK)
                        if (data.candidates) {
                            Object.values(data.candidates).forEach(c => {
                                const sahEl = document.getElementById('candidate-sah-' + c.id);
                                const digEl = document.getElementById('candidate-digital-' + c.id);
                                if (sahEl) sahEl.textContent = c.valid;
                                if (digEl) digEl.textContent = c.digital;
                            });
                        }

                        // 3. Update category header badges
                        const osisTotal = document.getElementById('osis-board-total');
                        const mpkTotal = document.getElementById('mpk-board-total');
                        if (osisTotal) osisTotal.textContent = data.total_osis;
                        if (mpkTotal) mpkTotal.textContent = data.total_mpk;

                        // 4. Update status badges on visible table rows
                        if (data.recent_votes && data.recent_votes.length) {
                            data.recent_votes.forEach(rv => {
                                const badgeContainer = document.getElementById('badge-status-' + rv.id);
                                if (badgeContainer) {
                                    if (rv.status === 'sah') {
                                        badgeContainer.innerHTML = `
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-xs font-bold rounded-full">
                                                <i class="fa-solid fa-circle-check text-[11px]"></i> SAH
                                            </span>
                                            ${rv.catatan ? `<p class="text-[10px] text-slate-400 mt-1 max-w-xs truncate">${rv.catatan}</p>` : ''}
                                        `;
                                    } else if (rv.status === 'tidak_sah') {
                                        badgeContainer.innerHTML = `
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-rose-500/10 border border-rose-500/20 text-rose-400 text-xs font-bold rounded-full">
                                                <i class="fa-solid fa-ban text-[11px]"></i> TIDAK SAH
                                            </span>
                                            ${rv.catatan ? `<p class="text-[10px] text-slate-400 mt-1 max-w-xs truncate">${rv.catatan}</p>` : ''}
                                        `;
                                    } else {
                                        badgeContainer.innerHTML = `
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-amber-500/10 border border-amber-500/20 text-amber-400 text-xs font-bold rounded-full">
                                                <i class="fa-solid fa-hourglass text-[11px]"></i> PENDING
                                            </span>
                                        `;
                                    }
                                }
                            });
                        }

                        // 5. Update timestamp
                        const syncTime = document.getElementById('audit-sync-time');
                        if (syncTime) syncTime.textContent = data.updated_at || '--:--:--';

                    } catch (e) {
                        console.error('Audit live sync error:', e);
                    }
                },

                startLiveSync() {
                    if (this._syncInterval) clearInterval(this._syncInterval);
                    this.syncAuditData();
                    this._syncInterval = setInterval(() => {
                        this.syncAuditData();
                    }, 3000); // Polling otomatis setiap 3 detik
                },

                setMode(mode) {
                    this.activeMode = mode;
                    if (mode === 'camera') {
                        this.startCamera();
                    } else {
                        this.stopCamera();
                        this.$nextTick(() => {
                            this.$refs.tokenInput?.focus();
                        });
                    }
                },

                async openPairingModal() {
                    this.showPairingModal = true;

                    if (!this.pairingSession) {
                        try {
                            const res = await fetch('{{ route("audit-suara.create-remote") }}');
                            const data = await res.json();
                            if (data.success) {
                                this.pairingSession = data.session_id;
                                this.pairingUrl = data.url;

                                this.$nextTick(() => {
                                    const qrEl = document.getElementById('pairing-qr-canvas');
                                    if (qrEl && typeof QRCode !== 'undefined') {
                                        qrEl.innerHTML = '';
                                        new QRCode(qrEl, {
                                            text: this.pairingUrl,
                                            width: 180,
                                            height: 180,
                                            colorDark: "#020617",
                                            colorLight: "#ffffff",
                                            correctLevel: QRCode.CorrectLevel.M
                                        });
                                        this._qrRendered = true;
                                    }
                                });

                                this.startRemotePolling();
                            }
                        } catch (e) {
                            alert('Gagal membuat sesi pairing wireless.');
                        }
                    } else {
                        this.startRemotePolling();
                    }
                },

                closePairingModal() {
                    this.showPairingModal = false;
                },

                async handleDevice(deviceId, action) {
                    try {
                        const res = await fetch('{{ route("audit-suara.device-action") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                session_id: this.pairingSession,
                                device_id: deviceId,
                                action: action
                            })
                        });
                        const data = await res.json();
                        if (data.success) {
                            if (action === 'kick') {
                                this.devices = this.devices.filter(d => d.id !== deviceId);
                            } else if (action === 'approve') {
                                const d = this.devices.find(dev => dev.id === deviceId);
                                if (d) d.status = 'approved';
                            } else if (action === 'reject') {
                                const d = this.devices.find(dev => dev.id === deviceId);
                                if (d) d.status = 'rejected';
                            }
                        }
                    } catch (e) {
                        alert('Gagal memproses aksi perangkat.');
                    }
                },

                copyPairingUrl() {
                    if (navigator.clipboard && this.pairingUrl) {
                        navigator.clipboard.writeText(this.pairingUrl);
                        alert('Link scanner HP berhasil disalin!');
                    }
                },

                startRemotePolling() {
                    if (this._pollInterval) return;

                    this._pollInterval = setInterval(async () => {
                        if (!this.pairingSession) return;

                        try {
                            const res = await fetch(`{{ url('admin/audit-suara/remote-poll') }}/${this.pairingSession}`);
                            const data = await res.json();

                            if (data.expired) {
                                this.remoteConnected = false;
                                clearInterval(this._pollInterval);
                                this._pollInterval = null;
                                return;
                            }

                            this.devices = data.devices || [];

                            // Handle token yang masuk dari HP
                            if (data.tokens && data.tokens.length > 0) {
                                for (const item of data.tokens) {
                                    const token = item.token.trim().toUpperCase();
                                    const devName = item.device_name || '';
                                    this.tokenValue = token;

                                    if (this.autoValidate) {
                                        // Mode Validasi Otomatis: langsung eksekusi validasi
                                        await this.validateToken(devName);
                                    } else {
                                        // Mode Manual: cukup masukkan token ke textbox dan mainkan suara info
                                        this.playSound('sudah');
                                        this.$nextTick(() => {
                                            this.$refs.tokenInput?.focus();
                                        });
                                    }
                                }
                            }
                        } catch (e) { }
                    }, 850);
                },

                async startCamera() {
                    if (this.cameraRunning) return;

                    try {
                        if (typeof Html5Qrcode === 'undefined') {
                            alert('Scanner library gagal dimuat.');
                            return;
                        }

                        if (!this.html5QrCode) {
                            this.html5QrCode = new Html5Qrcode("qr-camera-reader");
                        }

                        // Ambil daftar kamera jika belum
                        if (this.cameras.length === 0) {
                            const devices = await Html5Qrcode.getCameras();
                            if (devices && devices.length) {
                                this.cameras = devices;
                                const backCam = devices.find(d => /back|rear|environment/i.test(d.label));
                                this.selectedCameraId = backCam ? backCam.id : devices[0].id;
                            } else {
                                alert('Tidak ada perangkat kamera yang terdeteksi.');
                                return;
                            }
                        }

                        const config = {
                            fps: 12,
                            qrbox: { width: 240, height: 240 },
                            aspectRatio: 1.0
                        };

                        await this.html5QrCode.start(
                            this.selectedCameraId ? { deviceId: { exact: this.selectedCameraId } } : { facingMode: "environment" },
                            config,
                            (decodedText) => this.onScanSuccess(decodedText),
                            (errorMessage) => { }
                        );

                        this.cameraRunning = true;

                    } catch (err) {
                        console.error('Error kamera:', err);
                        this.cameraRunning = false;
                        alert('Gagal mengakses kamera: ' + (err.message || err));
                    }
                },

                async stopCamera() {
                    if (this.html5QrCode && this.cameraRunning) {
                        try {
                            await this.html5QrCode.stop();
                        } catch (e) { }
                        this.cameraRunning = false;
                    }
                },

                async toggleCameraState() {
                    if (this.cameraRunning) {
                        await this.stopCamera();
                    } else {
                        await this.startCamera();
                    }
                },

                async changeCamera() {
                    if (this.cameraRunning) {
                        await this.stopCamera();
                        await this.startCamera();
                    }
                },

                onScanSuccess(decodedText) {
                    if (this.processing) return;

                    const token = decodedText.trim().toUpperCase();
                    const now = Date.now();

                    // Debounce token yang sama dalam jeda 3 detik
                    if (token === this.lastScannedCode && (now - this.lastScanTime) < 3000) {
                        return;
                    }

                    this.lastScannedCode = token;
                    this.lastScanTime = now;

                    this.tokenValue = token;

                    if (this.autoValidate) {
                        this.validateToken();
                    } else {
                        this.playSound('sudah');
                        this.$nextTick(() => {
                            this.$refs.tokenInput?.focus();
                        });
                    }
                },

                async validateToken(fromDevice = '') {
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
                            kategori: data.kategori || '',
                            device: fromDevice
                        });

                        // Show overlay
                        this.showVerdict(
                            data.verdict || 'tidak_sah',
                            token,
                            data.message,
                            data.kategori || '',
                            fromDevice
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
                        this.showVerdict('tidak_sah', token, 'Kesalahan komunikasi dengan server.', '', fromDevice);
                    } finally {
                        this.processing = false;
                        this.syncAuditData();
                    }
                },

                showVerdict(verdict, token, message, kategori = '', deviceName = '') {
                    this.overlayVerdict = verdict;
                    this.overlayToken = token;
                    this.overlayMessage = message;
                    this.overlayKategori = kategori;
                    this.overlayDeviceName = deviceName;
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

