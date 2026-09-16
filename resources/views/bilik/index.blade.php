@php
    $page_title = 'Manajemen Bilik Suara';
    $page_description = 'Kelola terminal PC bilik suara dan generate kode pairing (1 PC 1 Kode)';
@endphp
<x-app-layout :page_title="$page_title" :page_description="$page_description">
    <x-slot name="actions">
        <div class="flex items-center gap-2.5 flex-wrap">
            <a href="{{ route('cetak.rekap-bilik') }}" target="_blank"
               class="inline-flex items-center gap-1.5 px-3.5 py-2.5 bg-slate-900 border border-white/10 text-slate-200 hover:text-white hover:bg-slate-800 rounded-2xl text-xs font-bold transition-all shadow-md">
                <i class="fas fa-file-lines text-indigo-400"></i>
                <span>Rekap Terminal</span>
            </a>

            <a href="{{ route('cetak.bilik') }}" target="_blank"
               class="inline-flex items-center gap-1.5 px-3.5 py-2.5 luxury-btn-primary text-white rounded-2xl text-xs font-bold transition-all shadow-lg shadow-indigo-600/30">
                <i class="fas fa-print"></i>
                <span>Cetak Tanda Bilik</span>
            </a>

            <x-admin-button icon="fas fa-plus" onclick="openAddModal()">
                Tambah Bilik
            </x-admin-button>
        </div>
    </x-slot>

    <div class="space-y-8">

        {{-- KPI Cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
            <div class="luxury-card luxury-card-hover rounded-3xl p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 font-mono block mb-1">Total Bilik</span>
                        <h3 class="font-heading font-black text-3xl sm:text-4xl text-white font-mono leading-none">{{ $totalBilik }}</h3>
                        <p class="text-[11px] text-slate-500 mt-2 font-mono">Bilik terdaftar di sistem</p>
                    </div>
                    <div class="w-14 h-14 rounded-2xl bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 flex items-center justify-center text-xl shadow-lg">
                        <i class="fas fa-person-booth"></i>
                    </div>
                </div>
            </div>

            <div class="luxury-card luxury-card-hover rounded-3xl p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 font-mono block mb-1">PC Ter-pairing</span>
                        <h3 class="font-heading font-black text-3xl sm:text-4xl text-emerald-400 font-mono leading-none">{{ $pairedCount }}</h3>
                        <p class="text-[11px] text-slate-500 mt-2 font-mono">Komputer aktif memilih</p>
                    </div>
                    <div class="w-14 h-14 rounded-2xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 flex items-center justify-center text-xl shadow-lg">
                        <i class="fas fa-desktop"></i>
                    </div>
                </div>
            </div>

            <div class="luxury-card luxury-card-hover rounded-3xl p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 font-mono block mb-1">Belum Terhubung</span>
                        <h3 class="font-heading font-black text-3xl sm:text-4xl text-amber-400 font-mono leading-none">{{ $totalBilik - $pairedCount }}</h3>
                        <p class="text-[11px] text-slate-500 mt-2 font-mono">Menunggu input kode pairing</p>
                    </div>
                    <div class="w-14 h-14 rounded-2xl bg-amber-500/10 border border-amber-500/20 text-amber-400 flex items-center justify-center text-xl shadow-lg">
                        <i class="fas fa-link-slash"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- Info Petunjuk Penggunaan --}}
        <div class="p-5 bg-slate-900/90 rounded-3xl border border-indigo-500/30 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 shadow-xl">
            <div class="flex items-center gap-3.5">
                <div class="w-10 h-10 rounded-2xl bg-indigo-500/20 text-indigo-300 flex items-center justify-center text-lg flex-shrink-0">
                    <i class="fas fa-shield-halved"></i>
                </div>
                <div>
                    <h4 class="font-heading font-bold text-sm text-white">Cara Aktivasi PC Bilik Suara (Tanpa Login Admin)</h4>
                    <p class="text-xs text-slate-400 leading-relaxed mt-0.5">
                        Buka browser di PC Bilik &rarr; ketik alamat <strong>https://pilketos.sman1prambon.my.id/bilik</strong> &rarr; masukkan salah satu Kode Pairing di bawah. Siswa tidak akan bisa mengakses dashboard admin.
                    </p>
                </div>
            </div>
            <a href="{{ route('bilik.pairing-form') }}" target="_blank"
               class="px-4 py-2.5 rounded-xl bg-indigo-600/30 hover:bg-indigo-600 text-indigo-300 hover:text-white border border-indigo-500/30 text-xs font-bold transition-all whitespace-nowrap">
                <i class="fas fa-arrow-up-right-from-square mr-1"></i> Buka Halaman Aktivasi
            </a>
        </div>

        {{-- Table Daftar Bilik Suara --}}
        <div class="luxury-card rounded-3xl overflow-hidden shadow-2xl">
            <div class="p-6 border-b border-white/5 bg-slate-950/40 flex items-center justify-between">
                <div>
                    <h3 class="font-heading font-black text-base text-white">Daftar Terminal Bilik TPS</h3>
                    <p class="text-xs text-slate-400">1 Kode Pairing hanya dapat digunakan pada 1 perangkat komputer bilik</p>
                </div>
                <span class="text-xs font-mono font-bold px-3 py-1 bg-indigo-500/10 text-indigo-300 border border-indigo-500/20 rounded-xl">
                    {{ $totalBilik }} Bilik
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-slate-950/60 border-b border-white/5">
                        <tr>
                            <th class="px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-wider font-mono">No</th>
                            <th class="px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-wider font-mono">Nama Bilik</th>
                            <th class="px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-wider font-mono">Kode Pairing PC</th>
                            <th class="px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-wider font-mono">Status Koneksi</th>
                            <th class="px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-wider font-mono">Perangkat Terhubung</th>
                            <th class="text-right px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-wider font-mono">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @forelse($biliks as $index => $b)
                            <tr class="hover:bg-slate-900/50 transition-colors">
                                <td class="px-6 py-4 text-xs font-mono text-slate-500">{{ $index + 1 }}</td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-8 h-8 rounded-xl bg-slate-900 border border-white/10 flex items-center justify-center text-sm {{ $b->isPaired() ? 'text-emerald-400' : 'text-slate-500' }}">
                                            <i class="fas fa-person-booth"></i>
                                        </div>
                                        <div>
                                            <h4 class="font-bold text-white text-sm">{{ $b->nama_bilik }}</h4>
                                            <span class="text-[10px] text-slate-500 font-mono">ID: #{{ $b->id }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <code class="px-3 py-1.5 bg-slate-950 border border-slate-800 text-amber-300 rounded-xl text-sm font-mono font-black tracking-widest select-all">
                                            {{ $b->pairing_code }}
                                        </code>
                                        <button type="button" onclick="copyCode('{{ $b->pairing_code }}')" title="Salin Kode"
                                                class="p-1.5 text-slate-400 hover:text-white bg-slate-900 hover:bg-slate-800 rounded-lg text-xs transition-colors">
                                            <i class="fas fa-copy"></i>
                                        </button>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @if($b->isPaired())
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 text-xs font-bold rounded-full font-mono">
                                            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span>
                                            Terhubung &amp; Aktif
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-slate-800/80 text-slate-400 border border-white/5 text-xs font-bold rounded-full font-mono">
                                            <i class="fas fa-link-slash text-[10px]"></i> Belum Ter-pairing
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if($b->isPaired())
                                        <div class="text-xs font-mono">
                                            <p class="text-white font-bold">{{ $b->ip_address ?? 'IP Terdaftar' }}</p>
                                            <p class="text-[10px] text-slate-500 truncate max-w-xs" title="{{ $b->user_agent }}">
                                                Dipairing: {{ $b->paired_at ? $b->paired_at->translatedFormat('d M, H:i') : '-' }}
                                            </p>
                                        </div>
                                    @else
                                        <span class="text-xs text-slate-500 italic font-mono">- Siap Dipairing -</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <a href="{{ route('cetak.bilik', ['id' => $b->id]) }}" target="_blank"
                                           class="p-2 text-slate-400 hover:text-indigo-400 hover:bg-slate-800 rounded-xl text-xs transition-colors cursor-pointer"
                                           title="Cetak Tanda {{ $b->nama_bilik }}">
                                            <i class="fas fa-print"></i>
                                        </a>

                                        @if($b->isPaired())
                                            <form method="POST" action="{{ route('admin.bilik.reset', $b) }}" onsubmit="return confirm('Putus sesi PC di {{ $b->nama_bilik }} dan generate kode baru?')">
                                                @csrf
                                                <button type="submit" class="px-2.5 py-1.5 bg-amber-500/10 hover:bg-amber-500 text-amber-400 hover:text-slate-950 border border-amber-500/20 rounded-xl text-xs font-bold transition-all" title="Reset Sesi &amp; Kode Baru">
                                                    <i class="fas fa-rotate mr-1"></i> Reset
                                                </button>
                                            </form>
                                        @endif

                                        <form method="POST" action="{{ route('admin.bilik.destroy', $b) }}" onsubmit="return confirm('Hapus {{ $b->nama_bilik }}?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 text-slate-400 hover:text-rose-400 hover:bg-rose-500/10 rounded-xl text-xs transition-colors cursor-pointer" title="Hapus Bilik">
                                                <i class="fas fa-trash-can"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-14 text-center">
                                    <div class="w-14 h-14 rounded-2xl bg-slate-900 border border-white/5 text-slate-600 flex items-center justify-center mx-auto mb-3 text-xl">
                                        <i class="fas fa-person-booth"></i>
                                    </div>
                                    <p class="text-sm font-bold text-white mb-1">Belum Ada Bilik Suara</p>
                                    <p class="text-xs text-slate-400 mb-4">Tambahkan bilik suara untuk memulai e-voting TPS.</p>
                                    <button onclick="openAddModal()" class="px-4 py-2 bg-indigo-600 text-white rounded-xl text-xs font-bold shadow-md">
                                        Tambah Bilik 1
                                    </button>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    {{-- Modal Tambah Bilik Baru --}}
    <div id="add-bilik-modal" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-slate-950/80 backdrop-blur-md">
        <div class="w-full max-w-md bg-slate-900 border border-indigo-500/30 rounded-3xl p-6 sm:p-8 shadow-2xl relative text-left">
            <div class="flex items-center justify-between pb-4 border-b border-white/10 mb-5">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-xl bg-indigo-500/15 text-indigo-400 border border-indigo-500/30 flex items-center justify-center text-sm">
                        <i class="fas fa-plus"></i>
                    </div>
                    <h3 class="font-heading font-black text-lg text-white">Tambah Bilik Suara TPS</h3>
                </div>
                <button type="button" onclick="closeAddModal()" class="text-slate-400 hover:text-white p-1.5 rounded-lg bg-slate-800">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <form method="POST" action="{{ route('admin.bilik.store') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5 font-mono">
                        Nama / Identitas Bilik
                    </label>
                    <input type="text" name="nama_bilik" required
                           value="Bilik {{ $totalBilik + 1 }}"
                           placeholder="Contoh: Bilik 1, Bilik 2 (Laptop HP), Bilik Meja Guru..."
                           class="w-full px-4 py-3.5 bg-slate-950 border border-white/15 rounded-2xl text-sm font-bold text-white outline-none focus:border-indigo-500">
                    <p class="text-[11px] text-slate-500 mt-1.5">Kode pairing unik akan otomatis digenerate oleh sistem.</p>
                </div>

                <div class="pt-2 flex gap-2.5">
                    <button type="button" onclick="closeAddModal()" class="flex-1 py-3 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-bold">
                        Batal
                    </button>
                    <button type="submit" class="flex-1 py-3 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold shadow-lg shadow-indigo-600/30">
                        Buat Bilik &amp; Kode
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openAddModal() {
            const m = document.getElementById('add-bilik-modal');
            m.classList.remove('hidden');
            m.classList.add('flex');
        }
        function closeAddModal() {
            const m = document.getElementById('add-bilik-modal');
            m.classList.add('hidden');
            m.classList.remove('flex');
        }
        function copyCode(code) {
            navigator.clipboard.writeText(code).then(() => {
                alert('Kode pairing ' + code + ' berhasil disalin!');
            });
        }
    </script>
</x-app-layout>
