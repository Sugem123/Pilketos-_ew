@php
    $page_title = 'Daftar Pemilih Tetap (DPT)';
    $page_description = 'Kelola hak suara pemilih Siswa (berdasarkan kelas) & Guru/Tendik';
@endphp
<x-app-layout :page_title="$page_title" :page_description="$page_description">
    <x-slot name="actions">
        <a href="{{ route('cetak.undangan') }}" target="_blank"
           class="inline-flex items-center gap-1.5 px-4 py-2.5 bg-slate-900 border border-white/10 text-slate-200 hover:text-white hover:bg-slate-800 rounded-2xl text-xs font-bold transition-all shadow-md">
            <i class="fas fa-envelope-open-text text-indigo-400"></i>
            <span>Cetak Undangan</span>
        </a>

        <a href="{{ route('cetak.kartu') }}" target="_blank"
           class="inline-flex items-center gap-1.5 px-4 py-2.5 luxury-btn-primary text-white rounded-2xl text-xs font-bold transition-all shadow-lg shadow-indigo-600/30">
            <i class="fas fa-address-card"></i>
            <span>Cetak Kartu Pemilih</span>
        </a>

        <x-admin-button variant="success" icon="fas fa-file-excel" onclick="openImportModal()">
            Impor Excel
        </x-admin-button>
        <x-admin-button icon="fas fa-user-plus" onclick="openSidebar('add')">
            Tambah Pemilih
        </x-admin-button>
    </x-slot>

    <div class="space-y-8">

        {{-- Statistics Row --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            <div class="luxury-card luxury-card-hover rounded-3xl p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 font-mono block mb-1">Total Pemilih</span>
                        <h3 class="font-heading font-black text-3xl sm:text-4xl text-white font-mono leading-none">{{ $totalHakSuara }}</h3>
                    </div>
                    <div class="w-14 h-14 rounded-2xl bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 flex items-center justify-center text-xl shadow-lg">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
            </div>

            <div class="luxury-card luxury-card-hover rounded-3xl p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 font-mono block mb-1">Siswa Terdaftar</span>
                        <h3 class="font-heading font-black text-3xl sm:text-4xl text-blue-400 font-mono leading-none">{{ $totalSiswa }}</h3>
                    </div>
                    <div class="w-14 h-14 rounded-2xl bg-blue-500/10 border border-blue-500/20 text-blue-400 flex items-center justify-center text-xl shadow-lg">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                </div>
            </div>

            <div class="luxury-card luxury-card-hover rounded-3xl p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 font-mono block mb-1">Guru / Tendik</span>
                        <h3 class="font-heading font-black text-3xl sm:text-4xl text-purple-400 font-mono leading-none">{{ $totalGuru }}</h3>
                    </div>
                    <div class="w-14 h-14 rounded-2xl bg-purple-500/10 border border-purple-500/20 text-purple-400 flex items-center justify-center text-xl shadow-lg">
                        <i class="fas fa-chalkboard-user"></i>
                    </div>
                </div>
            </div>

            <div class="luxury-card luxury-card-hover rounded-3xl p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 font-mono block mb-1">Sudah Memilih</span>
                        <h3 class="font-heading font-black text-3xl sm:text-4xl text-emerald-400 font-mono leading-none">{{ $hakSuaras->where('votes_count', '>', 0)->count() }}</h3>
                    </div>
                    <div class="w-14 h-14 rounded-2xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 flex items-center justify-center text-xl shadow-lg">
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
                            <tr class="hover:bg-slate-900/50 transition-colors">
                                <td class="px-6 py-4 text-xs font-mono text-slate-500">{{ $index + 1 }}</td>
                                <td class="px-6 py-4 text-xs font-bold text-white">{{ $hs->nisn }}</td>
                                <td class="px-6 py-4">
                                    <code class="px-3 py-1 bg-slate-950 border border-slate-800 rounded-xl text-xs font-mono font-black {{ $hs->token_used ? 'line-through text-slate-600' : 'text-amber-400' }}">
                                        {{ $hs->token ?? '-' }}
                                    </code>
                                    @if($hs->token_used)
                                        <span class="ml-1.5 text-[9px] text-rose-400 font-bold font-mono px-1.5 py-0.5 bg-rose-500/10 border border-rose-500/20 rounded">HANGUS</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if($hs->tipe === 'guru')
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-purple-500/10 text-purple-300 border border-purple-500/20 text-[10px] font-bold rounded-xl font-mono">
                                            <i class="fas fa-chalkboard-user"></i> GURU / TENDIK
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
                                        <a href="{{ route('cetak.kartu', ['id' => $hs->id]) }}" target="_blank"
                                           class="p-2 rounded-xl text-slate-400 hover:text-indigo-400 hover:bg-slate-800 transition-colors" title="Cetak Kartu">
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
                                <td colspan="6" class="px-6 py-14 text-center">
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

                {{-- Tipe Pemilih: Siswa vs Guru --}}
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-2 font-mono">Kategori Pemilih</label>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="flex items-center gap-2.5 p-3.5 border rounded-2xl cursor-pointer transition-all"
                            :class="tipe === 'siswa' ? 'border-indigo-500 bg-indigo-500/10 ring-2 ring-indigo-500/30' : 'border-slate-800 bg-slate-950/40'">
                            <input type="radio" name="tipe" value="siswa" x-model="tipe" class="text-indigo-600 focus:ring-indigo-500">
                            <span class="text-xs font-bold text-white"><i class="fas fa-graduation-cap mr-1 text-indigo-400"></i> Siswa</span>
                        </label>
                        <label class="flex items-center gap-2.5 p-3.5 border rounded-2xl cursor-pointer transition-all"
                            :class="tipe === 'guru' ? 'border-purple-500 bg-purple-500/10 ring-2 ring-purple-500/30' : 'border-slate-800 bg-slate-950/40'">
                            <input type="radio" name="tipe" value="guru" x-model="tipe" class="text-purple-600 focus:ring-purple-500">
                            <span class="text-xs font-bold text-white"><i class="fas fa-chalkboard-user mr-1 text-purple-400"></i> Guru / Tendik</span>
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
    </script>
</x-app-layout>

