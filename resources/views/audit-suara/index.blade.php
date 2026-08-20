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

        {{-- Quick Token Scanner / Checklist Input --}}
        <div class="luxury-card rounded-3xl p-6 sm:p-8 relative overflow-hidden border border-indigo-500/30">
            <div class="max-w-2xl">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-indigo-500/20 text-indigo-300 border border-indigo-500/30 mb-3">
                    <i class="fas fa-barcode"></i> Fast Token Verification
                </span>
                <h3 class="font-heading font-black text-2xl tracking-tight text-white mb-2">
                    Input dan Verifikasi Cepat Token Kotak Suara
                </h3>
                <p class="text-xs text-slate-400 leading-relaxed mb-6">
                    Ambil kartu pemilih dari kotak suara TPS satu per satu, ketik kode token yang tertera pada kartu fisik, lalu tekan tombol <strong>SAH</strong> jika kartu valid atau <strong>BATAL</strong> jika token tidak memenuhi syarat.
                </p>

                <div class="flex flex-col sm:flex-row gap-3">
                    <div class="relative flex-1">
                        <i class="fas fa-key absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 text-sm"></i>
                        <input type="text" id="quick-token-input" autofocus maxlength="10"
                               placeholder="Ketik Kode Token (Contoh: A8F3K9)..."
                               class="w-full pl-11 pr-4 py-3.5 luxury-input rounded-2xl text-white font-mono uppercase font-black text-base tracking-widest outline-none">
                    </div>
                    <button type="button" onclick="submitQuickVerify('sah')"
                            class="px-6 py-3.5 bg-emerald-600 hover:bg-emerald-500 text-white font-heading font-black text-xs sm:text-sm rounded-2xl transition-all shadow-lg shadow-emerald-600/30 flex items-center justify-center gap-2 cursor-pointer active:scale-95">
                        <i class="fas fa-check"></i>
                        <span>SAH (KARTU ADA)</span>
                    </button>
                    <button type="button" onclick="submitQuickVerify('tidak_sah')"
                            class="px-6 py-3.5 bg-rose-600/80 hover:bg-rose-600 text-white font-heading font-bold text-xs sm:text-sm rounded-2xl transition-all shadow-lg shadow-rose-600/20 flex items-center justify-center gap-2 cursor-pointer active:scale-95">
                        <i class="fas fa-times"></i>
                        <span>TIDAK SAH</span>
                    </button>
                </div>

                <div id="quick-verify-alert" class="mt-4 hidden p-4 rounded-2xl text-xs font-semibold flex items-center justify-between">
                    <span id="quick-verify-msg"></span>
                    <button onclick="document.getElementById('quick-verify-alert').classList.add('hidden')" class="text-slate-400 hover:text-white ml-2">
                        <i class="fas fa-times"></i>
                    </button>
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
        async function submitQuickVerify(status) {
            const input = document.getElementById('quick-token-input');
            const token = input.value.trim();
            const alertBox = document.getElementById('quick-verify-alert');
            const alertMsg = document.getElementById('quick-verify-msg');

            if (!token) {
                alertBox.className = 'mt-4 p-4 rounded-2xl text-xs font-semibold flex items-center justify-between bg-rose-500/20 text-rose-300 border border-rose-500/30';
                alertMsg.textContent = 'Silakan ketik atau scan kode token terlebih dahulu!';
                alertBox.classList.remove('hidden');
                input.focus();
                return;
            }

            try {
                const res = await fetch('{{ route('audit-suara.quick-verify') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ token, status })
                });

                const data = await res.json();

                if (!data.success) {
                    alertBox.className = 'mt-4 p-4 rounded-2xl text-xs font-semibold flex items-center justify-between bg-rose-500/20 text-rose-300 border border-rose-500/30';
                    alertMsg.textContent = data.message;
                    alertBox.classList.remove('hidden');
                } else {
                    alertBox.className = 'mt-4 p-4 rounded-2xl text-xs font-semibold flex items-center justify-between bg-emerald-500/20 text-emerald-300 border border-emerald-500/30';
                    alertMsg.textContent = data.message + ` (${data.voter_name})`;
                    alertBox.classList.remove('hidden');

                    if (data.counts) {
                        document.getElementById('stat-total-sah').textContent = data.counts.sah;
                        document.getElementById('stat-total-tidak-sah').textContent = data.counts.tidak_sah;
                        document.getElementById('stat-total-pending').textContent = data.counts.pending;
                    }

                    input.value = '';
                    input.focus();

                    setTimeout(() => location.reload(), 1200);
                }
            } catch (err) {
                alertBox.className = 'mt-4 p-4 rounded-2xl text-xs font-semibold flex items-center justify-between bg-rose-500/20 text-rose-300 border border-rose-500/30';
                alertMsg.textContent = 'Terjadi kesalahan komunikasi dengan server.';
                alertBox.classList.remove('hidden');
            }
        }

        document.getElementById('quick-token-input').addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                submitQuickVerify('sah');
            }
        });
    </script>
</x-app-layout>

