@php
    $page_title = 'Data Kandidat';
    $page_description = 'Kelola profil, foto, nomor urut, visi & misi calon ketua OSIS';

    $calonData = $calons->mapWithKeys(
        fn($c) => [
            $c->id => [
                'nama' => $c->nama,
                'id_kelas' => $c->id_kelas,
                'kelas' => $c->kelas->name ?? '-',
                'nomor' => $c->nomor,
                'visi' => $c->visi,
                'misi' => $c->misi,
                'url_foto' => $c->url_foto ? asset($c->url_foto) : '',
            ],
        ],
    );
@endphp
<x-app-layout :page_title="$page_title" :page_description="$page_description">
    <x-slot name="actions">
        <x-admin-button icon="fas fa-plus" onclick="openSidebar('add')">
            Tambah Kandidat
        </x-admin-button>
    </x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">

        {{-- Daftar Calon --}}
        <div class="lg:col-span-2 luxury-card rounded-3xl overflow-hidden flex flex-col">
            <div class="p-6 border-b border-white/5 flex items-center justify-between bg-slate-950/40">
                <div>
                    <h2 class="font-heading font-black text-base text-white">Daftar Kandidat</h2>
                    <p class="text-xs text-slate-400 mt-0.5">Pilih salah satu untuk melihat profil</p>
                </div>
                <span class="text-xs font-semibold px-3 py-1 bg-indigo-500/10 text-indigo-300 border border-indigo-500/20 rounded-xl font-mono">{{ $calons->count() }} Paslon</span>
            </div>
            <div class="divide-y divide-white/5 flex-1 overflow-y-auto max-h-[600px]">
                @forelse($calons as $calon)
                    <div onclick="selectCandidate({{ $calon->id }})" id="row-{{ $calon->id }}"
                        class="candidate-row p-5 flex items-center gap-4 cursor-pointer hover:bg-slate-900/60 transition-all group">
                        <div class="w-14 h-14 rounded-2xl bg-slate-900 border border-white/10 flex items-center justify-center overflow-hidden flex-shrink-0 shadow-md p-1 group-hover:scale-105 transition-transform">
                            @if ($calon->url_foto)
                                <img src="{{ asset($calon->url_foto) }}" alt="{{ $calon->nama }}"
                                    class="w-full h-full object-contain object-center">
                            @else
                                <i class="fas fa-user text-slate-600 text-lg"></i>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 mb-0.5">
                                <span class="w-6 h-6 rounded-lg bg-indigo-500/10 text-indigo-300 border border-indigo-500/20 text-xs font-mono font-bold flex items-center justify-center flex-shrink-0">
                                    {{ $calon->nomor }}
                                </span>
                                <h3 class="font-bold text-white text-sm truncate">{{ $calon->nama }}</h3>
                            </div>
                            <p class="text-xs text-slate-400 font-mono">Kelas {{ $calon->kelas->name }}</p>
                        </div>
                        <i class="fas fa-chevron-right text-slate-600 text-xs group-hover:text-indigo-400 group-hover:translate-x-0.5 transition-all"></i>
                    </div>
                @empty
                    <div class="p-12 text-center">
                        <i class="fas fa-user-group text-slate-600 text-4xl mb-3"></i>
                        <p class="text-slate-400 text-xs">Belum ada calon terdaftar.</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Detail Calon --}}
        <div class="lg:col-span-3 luxury-card rounded-3xl overflow-hidden flex flex-col">
            {{-- Empty state --}}
            <div id="detail-empty" class="flex-1 flex flex-col items-center justify-center p-16 text-center">
                <div class="w-16 h-16 rounded-2xl bg-slate-900 border border-white/10 flex items-center justify-center mb-4 text-indigo-400">
                    <i class="fas fa-id-badge text-2xl"></i>
                </div>
                <h3 class="font-heading font-black text-white text-base mb-1">Pilih Kandidat</h3>
                <p class="text-xs text-slate-400 max-w-xs">Klik salah satu kandidat di daftar sebelah kiri untuk meninjau visi, misi, dan detail profil.</p>
            </div>

            {{-- Detail content --}}
            <div id="detail-content" class="hidden flex-1 flex flex-col">
                <div class="p-6 border-b border-white/5 flex items-center justify-between bg-slate-950/40">
                    <h2 class="font-heading font-black text-base text-white">Detail Profil Paslon</h2>
                    <div class="flex items-center gap-2">
                        <x-admin-button variant="secondary" size="sm" icon="fas fa-pen-to-square" id="btn-edit">
                            Edit
                        </x-admin-button>
                        <x-admin-button variant="danger" size="sm" icon="fas fa-trash-can" id="btn-delete">
                            Hapus
                        </x-admin-button>
                    </div>
                </div>

                <div class="p-6 sm:p-8 flex-1 overflow-y-auto max-h-[600px]">
                    <div class="flex flex-col sm:flex-row gap-8">
                        {{-- Photo --}}
                        <div id="detail-foto-wrap"
                            class="w-full sm:w-60 h-80 rounded-3xl bg-slate-900 border border-white/10 flex items-center justify-center overflow-hidden flex-shrink-0 shadow-2xl p-4">
                            <img id="detail-foto" src="" alt=""
                                class="w-full h-full object-contain object-center hidden drop-shadow-2xl">
                        </div>

                        {{-- Identity + Visi + Misi --}}
                        <div class="flex-1 flex flex-col gap-6">
                            <div>
                                <span id="detail-nomor"
                                    class="inline-block px-3 py-1 bg-indigo-500/10 border border-indigo-500/20 text-indigo-300 text-xs font-mono font-bold rounded-xl mb-2"></span>
                                <h3 id="detail-nama" class="font-heading font-black text-2xl sm:text-3xl text-white leading-tight"></h3>
                                <p id="detail-kelas" class="text-xs font-bold text-slate-400 mt-1 font-mono"></p>
                            </div>

                            {{-- Visi --}}
                            <div>
                                <div class="flex items-center gap-2 mb-2">
                                    <div class="w-1.5 h-4 rounded-full bg-indigo-500"></div>
                                    <h4 class="text-xs font-bold text-indigo-300 uppercase tracking-wider font-mono">Visi</h4>
                                </div>
                                <p id="detail-visi"
                                    class="text-xs text-slate-300 leading-relaxed bg-slate-900/90 border border-white/5 rounded-2xl p-4 shadow-inner"></p>
                            </div>

                            {{-- Misi --}}
                            <div>
                                <div class="flex items-center gap-2 mb-2">
                                    <div class="w-1.5 h-4 rounded-full bg-amber-500"></div>
                                    <h4 class="text-xs font-bold text-amber-300 uppercase tracking-wider font-mono">Misi</h4>
                                </div>
                                <p id="detail-misi"
                                    class="text-xs text-slate-300 leading-relaxed bg-slate-900/90 border border-white/5 rounded-2xl p-4 whitespace-pre-line shadow-inner"></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- Sidebar Form Slide-in --}}
    <div id="secondary-sidebar"
        class="fixed inset-y-0 right-0 w-full sm:w-[480px] bg-slate-900 border-l border-white/10 shadow-2xl z-50 transform translate-x-full transition-transform duration-300 ease-in-out flex flex-col pointer-events-none text-slate-100">
        <div class="flex items-center justify-between p-6 border-b border-white/5 bg-slate-950/60">
            <h2 id="sidebar-title" class="font-heading font-black text-lg text-white">Tambah Kandidat</h2>
            <button onclick="closeSidebar()" class="w-8 h-8 rounded-xl text-slate-400 hover:text-white hover:bg-slate-800 flex items-center justify-center transition-colors">
                <i class="fas fa-times text-base"></i>
            </button>
        </div>
        <div class="flex-1 overflow-y-auto p-6 sm:p-8">
            <form id="calon-form" action="{{ route('calon.store') }}" method="POST" enctype="multipart/form-data"
                class="space-y-5">
                @csrf
                <input type="hidden" id="form-method" name="_method" value="POST">
                <input type="hidden" id="calon-id" name="id" value="">

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5 font-mono">Nama Lengkap</label>
                    <input type="text" id="input-nama" name="nama" required
                        class="w-full px-4 py-3 luxury-input rounded-2xl outline-none text-sm font-semibold">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5 font-mono">Kelas</label>
                        <select id="input-kelas" name="id_kelas" required
                            class="w-full px-4 py-3 luxury-input rounded-2xl outline-none text-sm font-semibold">
                            <option value="">Pilih Kelas</option>
                            @foreach ($kelas as $k)
                                <option value="{{ $k->id }}">{{ $k->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5 font-mono">Nomor Urut</label>
                        <input type="number" id="input-nomor" name="nomor" required min="1"
                            class="w-full px-4 py-3 luxury-input rounded-2xl outline-none text-sm font-bold font-mono">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5 font-mono">Foto Kandidat</label>
                    <div class="flex gap-4 items-center">
                        <div id="preview-container"
                            class="hidden w-24 h-28 rounded-2xl border border-white/10 overflow-hidden flex-shrink-0 shadow-lg bg-slate-950 p-2 flex items-center justify-center">
                            <img id="preview-image" src="" alt="Preview"
                                class="w-full h-full object-contain">
                        </div>
                        <label for="foto-input"
                            class="flex-1 flex flex-col items-center justify-center py-6 border-2 border-dashed border-slate-700 hover:border-indigo-500 bg-slate-950/60 rounded-2xl text-xs font-bold text-slate-400 hover:text-indigo-400 cursor-pointer transition-all">
                            <i class="fas fa-cloud-arrow-up text-2xl mb-1.5 text-slate-500"></i>
                            <span>Pilih file foto</span>
                            <input type="file" id="foto-input" name="foto_calon" class="hidden"
                                accept="image/*">
                        </label>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5 font-mono">Visi</label>
                    <textarea id="input-visi" name="visi" rows="3" required
                        class="w-full px-4 py-3 luxury-input rounded-2xl outline-none text-xs resize-none leading-relaxed"></textarea>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5 font-mono">Misi</label>
                    <textarea id="input-misi" name="misi" rows="4" required
                        class="w-full px-4 py-3 luxury-input rounded-2xl outline-none text-xs resize-none leading-relaxed"></textarea>
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

    <div id="sidebar-backdrop" class="fixed inset-0 bg-slate-950/80 backdrop-blur-md z-40 hidden transition-opacity"
        onclick="closeSidebar()"></div>

    <script>
        const calonData = @json($calonData);
        let selectedId = null;

        function selectCandidate(id) {
            document.querySelectorAll('.candidate-row').forEach(r => {
                r.classList.remove('bg-indigo-500/10', 'border-l-4', 'border-indigo-500');
            });
            const row = document.getElementById('row-' + id);
            if (row) {
                row.classList.add('bg-indigo-500/10', 'border-l-4', 'border-indigo-500');
            }

            selectedId = id;
            const d = calonData[id];

            document.getElementById('detail-empty').classList.add('hidden');
            document.getElementById('detail-content').classList.remove('hidden');

            document.getElementById('detail-nama').textContent = d.nama;
            document.getElementById('detail-kelas').textContent = 'Kelas ' + d.kelas;
            document.getElementById('detail-nomor').textContent = 'PASLON 0' + d.nomor;
            document.getElementById('detail-visi').textContent = d.visi;
            document.getElementById('detail-misi').textContent = d.misi;

            const fotoEl = document.getElementById('detail-foto');
            if (d.url_foto) {
                fotoEl.src = d.url_foto;
                fotoEl.classList.remove('hidden');
            } else {
                fotoEl.src = '';
                fotoEl.classList.add('hidden');
            }

            document.getElementById('btn-edit').onclick = () => openSidebar('edit', id);
            document.getElementById('btn-delete').onclick = () =>
                confirmDelete(
                    '{{ url('/admin/calon') }}/' + id,
                    'Hapus Calon',
                    'Apakah Anda yakin ingin menghapus calon ' + d.nama + '? Foto dan data terkait juga akan dihapus.'
                );
        }

        function openSidebar(mode, id = null) {
            const sidebar = document.getElementById('secondary-sidebar');
            const backdrop = document.getElementById('sidebar-backdrop');
            const title = document.getElementById('sidebar-title');
            const form = document.getElementById('calon-form');
            const methodInput = document.getElementById('form-method');
            const idInput = document.getElementById('calon-id');

            if (mode === 'edit' && id) {
                const data = calonData[id];
                title.textContent = 'Edit Data Paslon';
                form.action = '{{ url('/admin/calon') }}/' + id;
                methodInput.value = 'PUT';
                idInput.value = id;
                document.getElementById('input-nama').value = data.nama;
                document.getElementById('input-kelas').value = data.id_kelas;
                document.getElementById('input-nomor').value = data.nomor;
                document.getElementById('input-visi').value = data.visi;
                document.getElementById('input-misi').value = data.misi;

                if (data.url_foto) {
                    document.getElementById('preview-image').src = data.url_foto;
                    document.getElementById('preview-container').classList.remove('hidden');
                } else {
                    document.getElementById('preview-container').classList.add('hidden');
                }
                document.getElementById('foto-input').value = '';
            } else {
                title.textContent = 'Tambah Paslon Baru';
                form.action = '{{ route('calon.store') }}';
                methodInput.value = 'POST';
                idInput.value = '';
                form.reset();
                document.getElementById('preview-container').classList.add('hidden');
            }

            sidebar.classList.remove('translate-x-full');
            sidebar.classList.remove('pointer-events-none');
            sidebar.classList.add('pointer-events-auto');
            backdrop.classList.remove('hidden');
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

        document.getElementById('foto-input').addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('preview-image').src = e.target.result;
                    document.getElementById('preview-container').classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            }
        });

        @if ($calons->isNotEmpty())
            selectCandidate({{ $calons->first()->id }});
        @endif
    </script>
</x-app-layout>
