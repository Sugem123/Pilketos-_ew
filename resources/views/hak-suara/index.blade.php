@php
    $page_title = 'Daftar Pemilih Tetap (DPT)';
    $page_description = 'Kelola hak suara pemilih Siswa (berdasarkan kelas) & Guru/Tendik';
@endphp
<x-app-layout :page_title="$page_title" :page_description="$page_description">
    <x-slot name="actions">
        <div class="flex items-center gap-2 flex-wrap">
            <a href="{{ route('cetak.dpt', request()->query()) }}" target="_blank"
               class="inline-flex items-center gap-1.5 px-3.5 py-2.5 bg-slate-900 border border-white/10 text-slate-200 hover:text-white hover:bg-slate-800 rounded-2xl text-xs font-bold transition-all shadow-md">
                <i class="fas fa-book text-amber-400"></i>
                <span>Daftar DPT</span>
            </a>

            <a href="{{ route('cetak.daftar-hadir', request()->query()) }}" target="_blank"
               class="inline-flex items-center gap-1.5 px-3.5 py-2.5 bg-slate-900 border border-white/10 text-slate-200 hover:text-white hover:bg-slate-800 rounded-2xl text-xs font-bold transition-all shadow-md">
                <i class="fas fa-clipboard-check text-emerald-400"></i>
                <span>Daftar Hadir</span>
            </a>

            <a href="{{ route('cetak.undangan', request()->query()) }}" target="_blank"
               class="inline-flex items-center gap-1.5 px-3.5 py-2.5 bg-slate-900 border border-white/10 text-slate-200 hover:text-white hover:bg-slate-800 rounded-2xl text-xs font-bold transition-all shadow-md">
                <i class="fas fa-envelope-open-text text-indigo-400"></i>
                <span>Undangan</span>
            </a>

            <a href="{{ route('cetak.kartu', request()->query()) }}" target="_blank"
               class="inline-flex items-center gap-1.5 px-3.5 py-2.5 luxury-btn-primary text-white rounded-2xl text-xs font-bold transition-all shadow-lg shadow-indigo-600/30">
                <i class="fas fa-address-card"></i>
                <span>Kartu Pemilih</span>
            </a>

            {{-- Indikator Keranjang Cetak Custom --}}
            <button type="button" onclick="openBasketModal()" id="btn-top-basket"
                    class="hidden inline-flex items-center gap-1.5 px-3.5 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white rounded-2xl text-xs font-bold transition-all shadow-lg shadow-indigo-600/30 cursor-pointer animate-pulse"
                    title="Buka Keranjang Cetak Custom">
                <i class="fas fa-basket-shopping text-indigo-200"></i>
                <span>Keranjang (<strong id="top-basket-count">0</strong>)</span>
            </button>

            {{-- Batch Toggle Token Simulasi --}}
            <form method="POST" action="{{ route('hak-suara.toggle-batch') }}" class="inline" onsubmit="return confirm('Nonaktifkan seluruh token pemilih kategori Simulasi TPS?')">
                @csrf
                <input type="hidden" name="action" value="disable_simulasi">
                <button type="submit" class="inline-flex items-center gap-1.5 px-3.5 py-2.5 bg-amber-500/15 border border-amber-500/30 hover:bg-amber-500/25 text-amber-300 rounded-2xl text-xs font-bold transition-all shadow-sm cursor-pointer" title="Nonaktifkan semua token simulasi">
                    <i class="fas fa-ban text-[11px]"></i>
                    <span>Disable Simulasi</span>
                </button>
            </form>

            <form method="POST" action="{{ route('hak-suara.toggle-batch') }}" class="inline" onsubmit="return confirm('Aktifkan kembali seluruh token pemilih kategori Simulasi TPS?')">
                @csrf
                <input type="hidden" name="action" value="enable_simulasi">
                <button type="submit" class="inline-flex items-center gap-1.5 px-3.5 py-2.5 bg-emerald-500/15 border border-emerald-500/30 hover:bg-emerald-500/25 text-emerald-300 rounded-2xl text-xs font-bold transition-all shadow-sm cursor-pointer" title="Aktifkan semua token simulasi">
                    <i class="fas fa-circle-check text-[11px]"></i>
                    <span>Enable Simulasi</span>
                </button>
            </form>

            <x-admin-button variant="success" icon="fas fa-file-excel" onclick="openImportModal()">
                Impor Excel
            </x-admin-button>
            <x-admin-button icon="fas fa-user-plus" onclick="openSidebar('add')">
                Tambah
            </x-admin-button>
        </div>
    </x-slot>

    <div class="space-y-8">

        {{-- Statistics Row --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
            <div class="luxury-card luxury-card-hover rounded-3xl p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 font-mono block mb-1">Total Pemilih</span>
                        <h3 class="font-heading font-black text-2xl sm:text-3xl text-white font-mono leading-none">{{ $totalHakSuara }}</h3>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 flex items-center justify-center text-lg shadow-lg">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
            </div>

            <div class="luxury-card luxury-card-hover rounded-3xl p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 font-mono block mb-1">Siswa DPT</span>
                        <h3 class="font-heading font-black text-2xl sm:text-3xl text-blue-400 font-mono leading-none">{{ $totalSiswa }}</h3>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-blue-500/10 border border-blue-500/20 text-blue-400 flex items-center justify-center text-lg shadow-lg">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                </div>
            </div>

            <div class="luxury-card luxury-card-hover rounded-3xl p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 font-mono block mb-1">Guru / Tendik</span>
                        <h3 class="font-heading font-black text-2xl sm:text-3xl text-purple-400 font-mono leading-none">{{ $totalGuru }}</h3>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-purple-500/10 border border-purple-500/20 text-purple-400 flex items-center justify-center text-lg shadow-lg">
                        <i class="fas fa-chalkboard-user"></i>
                    </div>
                </div>
            </div>

            <div class="luxury-card luxury-card-hover rounded-3xl p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 font-mono block mb-1">Simulasi TPS</span>
                        <h3 class="font-heading font-black text-2xl sm:text-3xl text-amber-400 font-mono leading-none">{{ $totalSimulasi }}</h3>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-amber-500/10 border border-amber-500/20 text-amber-400 flex items-center justify-center text-lg shadow-lg">
                        <i class="fas fa-flask"></i>
                    </div>
                </div>
            </div>

            <div class="luxury-card luxury-card-hover rounded-3xl p-5 sm:col-span-2 lg:col-span-1">
                <div class="flex items-center justify-between">
                    <div>
                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 font-mono block mb-1">Sudah Memilih</span>
                        <h3 class="font-heading font-black text-2xl sm:text-3xl text-emerald-400 font-mono leading-none">{{ $hakSuaras->where('votes_count', '>', 0)->count() }}</h3>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 flex items-center justify-center text-lg shadow-lg">
                        <i class="fa-solid fa-circle-check"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- Table Container --}}
        <div class="luxury-card rounded-3xl overflow-hidden">
            {{-- Filter Bar --}}
            <div class="p-6 border-b border-white/5 bg-slate-950/40">
                <form method="GET" action="{{ route('hak-suara.index') }}" class="flex flex-col sm:flex-row gap-3.5">
                    <div class="relative flex-1">
                        <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 text-xs"></i>
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Cari nama pemilih..."
                            class="w-full pl-10 pr-4 py-3 luxury-input rounded-2xl text-xs font-semibold outline-none">
                    </div>
                    
                    <select name="tipe" class="px-4 py-3 luxury-input rounded-2xl text-xs font-semibold outline-none">
                        <option value="">Semua Kategori</option>
                        <option value="siswa" {{ request('tipe') === 'siswa' ? 'selected' : '' }}>Siswa</option>
                        <option value="guru" {{ request('tipe') === 'guru' ? 'selected' : '' }}>Guru / Tendik</option>
                        <option value="simulasi" {{ request('tipe') === 'simulasi' ? 'selected' : '' }}>Simulasi TPS</option>
                    </select>

                    <select name="id_kelas" class="px-4 py-3 luxury-input rounded-2xl text-xs font-semibold outline-none">
                        <option value="">Semua Kelas</option>
                        @foreach($kelas as $k)
                            <option value="{{ $k->id }}" {{ request('id_kelas') == $k->id ? 'selected' : '' }}>{{ $k->name }}</option>
                        @endforeach
                    </select>

                    <select name="status" class="px-4 py-3 luxury-input rounded-2xl text-xs font-semibold outline-none">
                        <option value="">Semua Status</option>
                        <option value="sudah" {{ request('status') === 'sudah' ? 'selected' : '' }}>Sudah Memilih</option>
                        <option value="belum" {{ request('status') === 'belum' ? 'selected' : '' }}>Belum Memilih</option>
                    </select>

                    <button type="submit" class="px-6 py-3 luxury-btn-primary text-white text-xs font-bold rounded-2xl transition-all shadow-md cursor-pointer">
                        Filter
                    </button>
                    @if(request('search') || request('tipe') || request('id_kelas') || request('status'))
                        <a href="{{ route('hak-suara.index') }}" class="px-4 py-3 bg-slate-900 border border-white/10 text-slate-400 hover:text-white text-xs font-bold rounded-2xl transition-colors text-center">
                            Reset
                        </a>
                    @endif
                </form>
            </div>

            {{-- Table --}}
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-slate-950/60 border-b border-white/5">
                        <tr>
                            <th class="px-4 py-4 text-center w-12">
                                <input type="checkbox" id="check-all-voters" onchange="toggleSelectAll(this)"
                                       class="w-4 h-4 rounded bg-slate-900 border-slate-700 text-indigo-600 focus:ring-0 cursor-pointer"
                                       title="Pilih Semua di Halaman Ini">
                            </th>
                            <th class="px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-wider font-mono">No</th>
                            <th class="px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-wider font-mono">Nama Pemilih</th>
                            <th class="px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-wider font-mono">Token Bilik</th>
                            <th class="px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-wider font-mono">Kategori / Kelas</th>
                            <th class="px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-wider font-mono">Status Suara</th>
                            <th class="text-right px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-wider font-mono">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @forelse($hakSuaras as $index => $hs)
                            @php
                                $isTokenActive = isset($hs->is_active) ? (bool)$hs->is_active : true;
                                $kelasLabel = $hs->kelas->name ?? ($hs->tipe === 'guru' ? 'GURU / TENDIK' : ($hs->tipe === 'simulasi' ? 'SIMULASI' : '-'));
                            @endphp
                            <tr id="voter-row-{{ $hs->id }}" class="hover:bg-slate-900/50 transition-colors">
                                <td class="px-4 py-4 text-center">
                                    <input type="checkbox" value="{{ $hs->id }}"
                                           id="check-voter-{{ $hs->id }}"
                                           data-id="{{ $hs->id }}"
                                           data-nama="{{ $hs->nisn }}"
                                           data-kelas="{{ $kelasLabel }}"
                                           data-token="{{ $hs->token ?? '-' }}"
                                           data-tipe="{{ $hs->tipe }}"
                                           class="voter-checkbox w-4 h-4 rounded bg-slate-900 border-slate-700 text-indigo-600 focus:ring-0 cursor-pointer"
                                           onchange="handleCheckboxChange(this)">
                                </td>
                                <td class="px-6 py-4 text-xs font-mono text-slate-500">{{ $index + 1 }}</td>
                                <td class="px-6 py-4 text-xs font-bold text-white">{{ $hs->nisn }}</td>
                                <td class="px-6 py-4">
                                    @if($hs->token)
                                        <div class="flex items-center gap-1.5 flex-wrap">
                                            <code class="px-3 py-1 rounded-xl text-xs font-mono font-black {{ !$isTokenActive ? 'bg-slate-900 border border-rose-500/20 text-slate-500 line-through' : ($hs->token_used ? 'bg-slate-950 border border-slate-800 line-through text-slate-600' : 'bg-slate-950 border border-slate-800 text-amber-400') }}">
                                                {{ $hs->token }}
                                            </code>
                                            @if(!$isTokenActive)
                                                <span class="text-[9px] text-rose-400 font-bold font-mono px-1.5 py-0.5 bg-rose-500/15 border border-rose-500/30 rounded">DISABLED</span>
                                            @elseif($hs->token_used)
                                                <span class="text-[9px] text-rose-400 font-bold font-mono px-1.5 py-0.5 bg-rose-500/10 border border-rose-500/20 rounded">HANGUS</span>
                                            @else
                                                <span class="text-[9px] text-emerald-400 font-bold font-mono px-1.5 py-0.5 bg-emerald-500/10 border border-emerald-500/20 rounded">AKTIF</span>
                                            @endif
                                        </div>
                                    @else
                                        <span class="px-2.5 py-1 bg-slate-900 border border-white/5 rounded-xl text-[10px] font-mono text-slate-500 italic">
                                            Belum Di-generate (Admin)
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if($hs->tipe === 'guru')
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-purple-500/10 text-purple-300 border border-purple-500/20 text-[10px] font-bold rounded-xl font-mono">
                                            <i class="fas fa-chalkboard-user"></i> GURU / TENDIK
                                        </span>
                                    @elseif($hs->tipe === 'simulasi')
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-amber-500/10 text-amber-300 border border-amber-500/20 text-[10px] font-bold rounded-xl font-mono">
                                            <i class="fas fa-flask"></i> SIMULASI TPS
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-blue-500/10 text-blue-300 border border-blue-500/20 text-[10px] font-bold rounded-xl font-mono">
                                            <i class="fas fa-graduation-cap"></i> {{ $hs->kelas->name ?? 'SISWA' }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if($hs->votes_count > 0 || $hs->token_used)
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-xs font-bold rounded-full">
                                            <i class="fa-solid fa-circle-check text-[10px]"></i> Sudah Memilih
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-amber-500/10 border border-amber-500/20 text-amber-400 text-xs font-bold rounded-full">
                                            <i class="fa-solid fa-clock text-[10px]"></i> Belum Memilih
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-1.5">
                                        {{-- Tombol Cepat Masuk/Hapus Keranjang --}}
                                        <button type="button" id="btn-basket-row-{{ $hs->id }}"
                                                onclick="handleRowBasketBtn({{ $hs->id }})"
                                                class="p-2 rounded-xl text-slate-400 hover:text-indigo-400 hover:bg-slate-800 transition-colors cursor-pointer"
                                                title="Tambah / Hapus dari Keranjang Cetak">
                                            <i class="fas fa-cart-plus text-xs" id="icon-basket-row-{{ $hs->id }}"></i>
                                        </button>

                                        {{-- Toggle Disable/Enable Token Button --}}
                                        @if($hs->token)
                                            <form method="POST" action="{{ route('hak-suara.toggle-token', $hs) }}" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                @if($isTokenActive)
                                                    <button type="submit" class="p-2 rounded-xl text-slate-400 hover:text-amber-400 hover:bg-amber-500/10 transition-colors cursor-pointer"
                                                            title="Nonaktifkan Token (Disable)">
                                                        <i class="fas fa-ban text-xs"></i>
                                                    </button>
                                                @else
                                                    <button type="submit" class="p-2 rounded-xl text-emerald-400 hover:text-emerald-300 hover:bg-emerald-500/15 transition-colors cursor-pointer"
                                                            title="Aktifkan Token (Enable)">
                                                        <i class="fas fa-circle-check text-xs"></i>
                                                    </button>
                                                @endif
                                            </form>
                                        @endif

                                        <a href="{{ route('cetak.kartu', ['id' => $hs->id]) }}" target="_blank"
                                           class="p-2 rounded-xl text-slate-400 hover:text-indigo-400 hover:bg-slate-800 transition-colors" title="Cetak Kartu Individual">
                                            <i class="fas fa-print text-xs"></i>
                                        </a>
                                        <x-admin-button variant="ghost" icon="fas fa-trash-can"
                                            onclick="confirmDelete('{{ route('hak-suara.destroy', $hs) }}', 'Hapus Pemilih', 'Apakah Anda yakin ingin menghapus {{ e(addslashes($hs->nisn)) }} dari daftar pemilih?')"
                                            class="text-slate-400 hover:text-rose-400 hover:bg-rose-500/10"
                                            title="Hapus">
                                        </x-admin-button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-14 text-center">
                                    <div class="w-12 h-12 rounded-2xl bg-slate-900 border border-white/5 text-slate-600 flex items-center justify-center mx-auto mb-3 text-lg">
                                        <i class="fas fa-users"></i>
                                    </div>
                                    <p class="text-xs font-semibold text-slate-400">Belum ada data pemilih.</p>
                                    <p class="text-[11px] text-slate-500 mt-0.5">Tambah manual atau unggah file Excel.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Slide-in Sidebar Form --}}
    <div id="secondary-sidebar" class="fixed inset-y-0 right-0 w-full sm:w-[460px] bg-slate-900 border-l border-white/10 shadow-2xl z-50 transform translate-x-full transition-transform duration-300 ease-in-out flex flex-col pointer-events-none text-slate-100">
        <div class="flex items-center justify-between p-6 border-b border-white/5 bg-slate-950/60">
            <h2 class="font-heading font-black text-lg text-white">Tambah Data Pemilih</h2>
            <button onclick="closeSidebar()" class="w-8 h-8 rounded-xl text-slate-400 hover:text-white hover:bg-slate-800 flex items-center justify-center transition-colors">
                <i class="fas fa-times text-base"></i>
            </button>
        </div>
        <div class="flex-1 overflow-y-auto p-6 sm:p-8">
            <form action="{{ route('hak-suara.store') }}" method="POST" class="space-y-5" x-data="{ tipe: 'siswa' }">
                @csrf
                @if ($errors->any())
                    <div class="bg-rose-500/10 border border-rose-500/30 text-rose-400 rounded-2xl p-4">
                        <ul class="text-xs space-y-1 font-medium">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Tipe Pemilih: Siswa vs Guru vs Simulasi --}}
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-2 font-mono">Kategori Pemilih</label>
                    <div class="grid grid-cols-3 gap-2.5">
                        <label class="flex items-center gap-2 p-3 border rounded-2xl cursor-pointer transition-all"
                            :class="tipe === 'siswa' ? 'border-indigo-500 bg-indigo-500/10 ring-2 ring-indigo-500/30' : 'border-slate-800 bg-slate-950/40'">
                            <input type="radio" name="tipe" value="siswa" x-model="tipe" class="text-indigo-600 focus:ring-indigo-500">
                            <span class="text-xs font-bold text-white"><i class="fas fa-graduation-cap mr-1 text-indigo-400"></i> Siswa</span>
                        </label>
                        <label class="flex items-center gap-2 p-3 border rounded-2xl cursor-pointer transition-all"
                            :class="tipe === 'guru' ? 'border-purple-500 bg-purple-500/10 ring-2 ring-purple-500/30' : 'border-slate-800 bg-slate-950/40'">
                            <input type="radio" name="tipe" value="guru" x-model="tipe" class="text-purple-600 focus:ring-purple-500">
                            <span class="text-xs font-bold text-white"><i class="fas fa-chalkboard-user mr-1 text-purple-400"></i> Guru</span>
                        </label>
                        <label class="flex items-center gap-2 p-3 border rounded-2xl cursor-pointer transition-all"
                            :class="tipe === 'simulasi' ? 'border-amber-500 bg-amber-500/10 ring-2 ring-amber-500/30' : 'border-slate-800 bg-slate-950/40'">
                            <input type="radio" name="tipe" value="simulasi" x-model="tipe" class="text-amber-500 focus:ring-amber-500">
                            <span class="text-xs font-bold text-white"><i class="fas fa-flask mr-1 text-amber-400"></i> Simulasi</span>
                        </label>
                    </div>
                </div>

                {{-- Kelas (Hanya untuk Siswa) --}}
                <div x-show="tipe === 'siswa'" x-transition>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5 font-mono">Kelas Siswa</label>
                    <select name="id_kelas" :required="tipe === 'siswa'"
                        class="w-full px-4 py-3 luxury-input rounded-2xl outline-none text-sm font-semibold">
                        <option value="">Pilih Kelas</option>
                        @foreach ($kelas as $k)
                            <option value="{{ $k->id }}" {{ old('id_kelas') == $k->id ? 'selected' : '' }}>{{ $k->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5 font-mono">Nama Lengkap Pemilih</label>
                    <input type="text" name="nisn" required
                           value="{{ old('nisn') }}"
                           class="w-full px-4 py-3 luxury-input rounded-2xl outline-none text-sm font-semibold"
                           placeholder="Contoh: Shabira Syahla">
                    <p class="text-[11px] text-slate-500 mt-1.5">Gunakan nama yang sama persis saat konfirmasi suara di bilik voting.</p>
                </div>

                <div class="flex gap-3 pt-4 border-t border-white/5">
                    <x-admin-button type="submit" class="flex-1" icon="fas fa-check">
                        Simpan Data
                    </x-admin-button>
                    <x-admin-button variant="secondary" type="button" onclick="closeSidebar()">
                        Batal
                    </x-admin-button>
                </div>
            </form>
        </div>
    </div>

    <div id="sidebar-backdrop" class="fixed inset-0 bg-slate-950/80 backdrop-blur-md z-40 hidden transition-opacity" onclick="closeSidebar()"></div>

    <script>
        function openSidebar() {
            const s = document.getElementById('secondary-sidebar');
            s.classList.remove('translate-x-full');
            s.classList.remove('pointer-events-none');
            s.classList.add('pointer-events-auto');
            document.getElementById('sidebar-backdrop').classList.remove('hidden');
        }
        function closeSidebar() {
            const s = document.getElementById('secondary-sidebar');
            s.classList.add('translate-x-full');
            s.classList.remove('pointer-events-auto');
            s.classList.add('pointer-events-none');
            document.getElementById('sidebar-backdrop').classList.add('hidden');
        }

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeSidebar();
        });

        @if ($errors->any())
            openSidebar();
        @endif

        function openImportModal() {
            Swal.fire({
                title: 'Import DPT dari Excel',
                html: `
                    <div class="text-left space-y-3">
                        <p class="text-xs text-slate-400">Format Excel: <strong>Kolom A:</strong> No, <strong>Kolom B:</strong> Nama, <strong>Kolom C:</strong> Kelas (contoh: <em>X-1</em>), <strong>Kolom D:</strong> Tipe (<em>siswa</em> / <em>guru</em>).</p>
                        
                        <div>
                            <label class="block text-xs font-bold text-slate-300 mb-1">Tipe Default (Jika Kolom D kosong):</label>
                            <select id="import-tipe-default" class="w-full px-3 py-2.5 bg-slate-800 border border-slate-700 rounded-xl text-xs text-slate-200 outline-none">
                                <option value="siswa">Siswa</option>
                                <option value="guru">Guru / Tendik</option>
                            </select>
                        </div>

                        <div class="pt-2">
                            <label id="import-label" class="flex flex-col items-center justify-center w-full py-6 border-2 border-dashed border-slate-700 hover:border-indigo-500 bg-slate-900 rounded-2xl text-xs text-slate-400 hover:text-indigo-400 cursor-pointer transition-all">
                                <i class="fas fa-file-excel text-3xl mb-2 text-emerald-500"></i>
                                <span id="import-filename" class="font-medium">Pilih file spreadsheet (.xlsx/.xls)</span>
                                <input id="import-file-input" type="file" accept=".xls,.xlsx" class="hidden">
                            </label>
                        </div>
                    </div>
                `,
                showCancelButton: true,
                confirmButtonText: 'Mulai Impor',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                didOpen: () => {
                    const input = document.getElementById('import-file-input');
                    const label = document.getElementById('import-label');
                    input.addEventListener('change', function () {
                        if (this.files[0]) {
                            document.getElementById('import-filename').textContent = this.files[0].name;
                            label.classList.add('border-emerald-500', 'text-emerald-400');
                        }
                    });
                },
                preConfirm: () => {
                    const file = document.getElementById('import-file-input').files[0];
                    const tipeDefault = document.getElementById('import-tipe-default').value;
                    if (!file) {
                        Swal.showValidationMessage('Pilih file Excel terlebih dahulu');
                        return false;
                    }
                    return { file, tipeDefault };
                }
            }).then((result) => {
                if (result.isConfirmed && result.value) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '{{ route('hak-suara.import') }}';
                    form.enctype = 'multipart/form-data';

                    const csrf = document.createElement('input');
                    csrf.type = 'hidden';
                    csrf.name = '_token';
                    csrf.value = '{{ csrf_token() }}';

                    const tipeInput = document.createElement('input');
                    tipeInput.type = 'hidden';
                    tipeInput.name = 'tipe_import';
                    tipeInput.value = result.value.tipeDefault;

                    const fileInput = document.createElement('input');
                    fileInput.type = 'file';
                    fileInput.name = 'file_excel';

                    const dt = new DataTransfer();
                    dt.items.add(result.value.file);
                    fileInput.files = dt.files;

                    form.appendChild(csrf);
                    form.appendChild(tipeInput);
                    form.appendChild(fileInput);
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }

        // ── KERANJANG CETAK CUSTOM (PRINT BASKET ENGINE) ──
        const BASKET_STORAGE_KEY = 'pilketos_print_basket';

        function getPrintBasket() {
            try {
                return JSON.parse(sessionStorage.getItem(BASKET_STORAGE_KEY)) || {};
            } catch (e) {
                return {};
            }
        }

        function savePrintBasket(basket) {
            sessionStorage.setItem(BASKET_STORAGE_KEY, JSON.stringify(basket));
            syncBasketUI();
        }

        function toggleVoterItem(id, nama, kelas, token, tipe, isChecked) {
            const basket = getPrintBasket();
            if (isChecked) {
                basket[id] = { id, nama, kelas, token, tipe };
            } else {
                delete basket[id];
            }
            savePrintBasket(basket);
        }

        function handleCheckboxChange(el) {
            const id = el.dataset.id;
            const nama = el.dataset.nama;
            const kelas = el.dataset.kelas;
            const token = el.dataset.token;
            const tipe = el.dataset.tipe;
            toggleVoterItem(id, nama, kelas, token, tipe, el.checked);
        }

        function handleRowBasketBtn(id) {
            const cb = document.getElementById('check-voter-' + id);
            if (cb) {
                cb.checked = !cb.checked;
                handleCheckboxChange(cb);
            }
        }

        function toggleSelectAll(masterCb) {
            const isChecked = masterCb.checked;
            const checkboxes = document.querySelectorAll('.voter-checkbox');
            const basket = getPrintBasket();

            checkboxes.forEach(cb => {
                cb.checked = isChecked;
                const id = cb.dataset.id;
                const nama = cb.dataset.nama;
                const kelas = cb.dataset.kelas;
                const token = cb.dataset.token;
                const tipe = cb.dataset.tipe;

                if (isChecked) {
                    basket[id] = { id, nama, kelas, token, tipe };
                } else {
                    delete basket[id];
                }
            });

            savePrintBasket(basket);
        }

        function clearBasket() {
            sessionStorage.removeItem(BASKET_STORAGE_KEY);
            syncBasketUI();
        }

        function syncBasketUI() {
            const basket = getPrintBasket();
            const count = Object.keys(basket).length;

            // Top button
            const topBtn = document.getElementById('btn-top-basket');
            const topCount = document.getElementById('top-basket-count');
            if (topBtn && topCount) {
                topCount.textContent = count;
                if (count > 0) {
                    topBtn.classList.remove('hidden');
                } else {
                    topBtn.classList.add('hidden');
                }
            }

            // Floating bottom bar
            const bottomBar = document.getElementById('print-basket-bar');
            const badgeCount = document.getElementById('basket-badge-count');
            if (bottomBar && badgeCount) {
                badgeCount.textContent = count;
                if (count > 0) {
                    bottomBar.style.display = 'block';
                    bottomBar.classList.remove('hidden');
                } else {
                    bottomBar.style.display = 'none';
                    bottomBar.classList.add('hidden');
                }
            }

            // Sync individual checkboxes and row highlights on current page
            let allChecked = true;
            let hasCheckboxes = false;

            document.querySelectorAll('.voter-checkbox').forEach(cb => {
                hasCheckboxes = true;
                const id = cb.dataset.id;
                const isIn = !!basket[id];
                cb.checked = isIn;

                const row = document.getElementById('voter-row-' + id);
                if (row) {
                    if (isIn) {
                        row.classList.add('bg-indigo-950/40', 'border-l-4', 'border-indigo-500');
                    } else {
                        row.classList.remove('bg-indigo-950/40', 'border-l-4', 'border-indigo-500');
                    }
                }

                const iconBtn = document.getElementById('icon-basket-row-' + id);
                if (iconBtn) {
                    if (isIn) {
                        iconBtn.className = 'fas fa-check text-xs text-indigo-400 font-bold';
                        iconBtn.parentElement.title = 'Hapus dari Keranjang Cetak';
                    } else {
                        iconBtn.className = 'fas fa-cart-plus text-xs text-slate-400';
                        iconBtn.parentElement.title = 'Masukkan ke Keranjang Cetak';
                    }
                }

                if (!isIn) {
                    allChecked = false;
                }
            });

            const masterCb = document.getElementById('check-all-voters');
            if (masterCb) {
                masterCb.checked = hasCheckboxes && allChecked;
            }
        }

        function printBasket(type) {
            const basket = getPrintBasket();
            const ids = Object.keys(basket);

            if (ids.length === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Keranjang Kosong',
                    text: 'Silakan pilih satu atau beberapa pemilih terlebih dahulu.',
                    confirmButtonText: 'Mengerti'
                });
                return;
            }

            const form = document.getElementById('form-print-basket');
            const inputIds = document.getElementById('form-print-basket-ids');

            if (type === 'kartu') {
                form.action = '{{ route('cetak.kartu') }}';
            } else if (type === 'undangan') {
                form.action = '{{ route('cetak.undangan') }}';
            }

            inputIds.value = ids.join(',');
            form.submit();
        }

        function openBasketModal() {
            const basket = getPrintBasket();
            const items = Object.values(basket);

            if (items.length === 0) {
                Swal.fire({
                    icon: 'info',
                    title: 'Keranjang Cetak Kosong',
                    text: 'Centang kotak pada baris pemilih untuk memasukkannya ke keranjang cetak custom.',
                    confirmButtonText: 'Tutup'
                });
                return;
            }

            let listHtml = `
                <div class="text-left max-h-72 overflow-y-auto pr-1 divide-y divide-white/5 border border-white/10 rounded-2xl p-2 bg-slate-950/80 mb-4">
            `;

            items.forEach(item => {
                listHtml += `
                    <div class="py-2.5 px-3 flex items-center justify-between gap-3 text-xs">
                        <div class="min-w-0 flex-1">
                            <div class="font-bold text-white truncate">${item.nama}</div>
                            <div class="text-[10px] text-slate-400 font-mono flex items-center gap-2 mt-0.5">
                                <span class="text-indigo-400 font-semibold">${item.kelas}</span>
                                <span>&bull;</span>
                                <span class="text-amber-400">Token: ${item.token}</span>
                            </div>
                        </div>
                        <button type="button" onclick="removeItemFromModal(${item.id})" class="p-1.5 rounded-lg text-slate-400 hover:text-rose-400 hover:bg-rose-500/10 transition-colors cursor-pointer" title="Hapus dari keranjang">
                            <i class="fas fa-times text-xs"></i>
                        </button>
                    </div>
                `;
            });

            listHtml += `</div>`;

            Swal.fire({
                title: `Keranjang Cetak (${items.length} Pemilih)`,
                html: `
                    <div class="text-left">
                        <p class="text-xs text-slate-400 mb-3">Daftar pemilih yang siap dicetak khusus:</p>
                        ${listHtml}
                        <div class="grid grid-cols-2 gap-2.5 pt-2">
                            <button type="button" onclick="printBasket('kartu'); Swal.close();"
                                    style="background: linear-gradient(135deg, #4f46e5 0%, #3730a3 100%); color: #ffffff !important; font-weight: 700; border-radius: 14px; padding: 12px 14px; border: 1px solid rgba(255,255,255,0.15); box-shadow: 0 4px 14px rgba(79, 70, 229, 0.4); display: flex; align-items: center; justify-content: center; gap: 8px; width: 100%; cursor: pointer; font-size: 13px;">
                                <i class="fas fa-address-card"></i> Cetak Kartu (${items.length})
                            </button>
                            <button type="button" onclick="printBasket('undangan'); Swal.close();"
                                    style="background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%); color: #ffffff !important; font-weight: 700; border-radius: 14px; padding: 12px 14px; border: 1px solid rgba(255,255,255,0.15); box-shadow: 0 4px 14px rgba(2, 132, 199, 0.4); display: flex; align-items: center; justify-content: center; gap: 8px; width: 100%; cursor: pointer; font-size: 13px;">
                                <i class="fas fa-envelope-open-text"></i> Cetak Undangan (${items.length})
                            </button>
                        </div>
                    </div>
                `,
                showCancelButton: true,
                showDenyButton: true,
                denyButtonText: 'Kosongkan Keranjang',
                cancelButtonText: 'Tutup',
                showConfirmButton: false,
                denyButtonColor: '#ef4444'
            }).then((res) => {
                if (res.isDenied) {
                    clearBasket();
                }
            });
        }

        function removeItemFromModal(id) {
            const basket = getPrintBasket();
            delete basket[id];
            savePrintBasket(basket);
            if (Object.keys(basket).length === 0) {
                Swal.close();
            } else {
                openBasketModal();
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            syncBasketUI();
        });
    </script>

    {{-- ====== FLOATING BASKET BAR (BOTTOM STICKY) ====== --}}
    <div id="print-basket-bar" class="fixed bottom-6 left-1/2 -translate-x-1/2 z-50 hidden transition-all duration-300 max-w-2xl w-[94%] sm:w-auto" style="display: none;">
        <div class="glass-panel-dark rounded-3xl px-4 py-3 sm:px-6 sm:py-3.5 border-2 border-indigo-500/50 shadow-2xl shadow-indigo-500/40 flex items-center justify-between sm:justify-start gap-3.5 bg-slate-950/95 backdrop-blur-2xl">
            <div class="flex items-center gap-2.5 pr-3 border-r border-white/10">
                <div class="w-9 h-9 rounded-2xl bg-indigo-500/20 text-indigo-300 border border-indigo-500/30 flex items-center justify-center text-xs shadow-inner">
                    <i class="fas fa-basket-shopping"></i>
                </div>
                <div class="text-left">
                    <span class="text-[9px] uppercase font-mono tracking-wider text-slate-400 block leading-none">Keranjang Cetak</span>
                    <span class="text-sm font-heading font-black text-white"><span id="basket-badge-count">0</span> Dipilih</span>
                </div>
            </div>

            <div class="flex items-center gap-2 flex-wrap">
                <button type="button" onclick="printBasket('kartu')" class="inline-flex items-center gap-1.5 px-4 py-2 luxury-btn-primary text-white rounded-xl text-xs font-bold transition-all shadow-md cursor-pointer hover:scale-105">
                    <i class="fas fa-address-card"></i>
                    <span>Cetak Kartu</span>
                </button>

                <button type="button" onclick="printBasket('undangan')"
                        style="background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%); color: #ffffff !important;"
                        class="inline-flex items-center gap-1.5 px-4 py-2 border border-sky-400/40 rounded-xl text-xs font-bold transition-all shadow-md cursor-pointer hover:scale-105">
                    <i class="fas fa-envelope-open-text"></i>
                    <span>Cetak Undangan</span>
                </button>

                <button type="button" onclick="openBasketModal()" class="inline-flex items-center gap-1 px-3 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded-xl text-xs font-bold transition-colors cursor-pointer" title="Lihat rincian pemilih di keranjang">
                    <i class="fas fa-list-check text-xs"></i>
                    <span class="hidden sm:inline">Rincian</span>
                </button>

                <button type="button" onclick="clearBasket()" class="p-2 text-slate-400 hover:text-rose-400 hover:bg-rose-500/10 rounded-xl transition-colors cursor-pointer" title="Kosongkan Keranjang">
                    <i class="fas fa-trash-can text-xs"></i>
                </button>
            </div>
        </div>
    </div>

    {{-- Form Rahasia Pengiriman ID Terpilih ke Halaman Cetak (Target _blank) --}}
    <form id="form-print-basket" method="POST" target="_blank" class="hidden">
        @csrf
        <input type="hidden" name="ids" id="form-print-basket-ids">
    </form>
</x-app-layout>

