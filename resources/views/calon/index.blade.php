@php
    $page_title = 'Data Calon';
    $page_description = 'Kelola data calon ketua OSIS';

    $calonData = $calons->mapWithKeys(fn($c) => [$c->id => [
        'nama' => $c->nama,
        'id_kelas' => $c->id_kelas,
        'kelas' => $c->kelas->name ?? '-',
        'nomor' => $c->nomor,
        'visi' => $c->visi,
        'misi' => $c->misi,
        'url_foto' => $c->url_foto ? asset($c->url_foto) : '',
    ]]);
@endphp
<x-app-layout :page_title="$page_title" :page_description="$page_description">
    <x-slot name="actions">
        <x-admin-button icon="fas fa-plus" onclick="openSidebar('add')">
            Tambah Calon
        </x-admin-button>
    </x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">

        {{-- Daftar Calon --}}
        <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden flex flex-col">
            <div class="p-5 border-b border-gray-100 flex items-center justify-between">
                <h2 class="text-base font-semibold text-accent">Daftar Calon</h2>
                <span class="text-sm text-gray-400">{{ $calons->count() }} calon</span>
            </div>
            <div class="divide-y divide-gray-100 flex-1 overflow-y-auto">
                @forelse($calons as $calon)
                    <div onclick="selectCandidate({{ $calon->id }})"
                         id="row-{{ $calon->id }}"
                         class="candidate-row p-4 flex items-center gap-4 cursor-pointer hover:bg-gray-50 transition-colors group">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center overflow-hidden flex-shrink-0">
                            @if($calon->url_foto)
                                <img src="{{ asset($calon->url_foto) }}" alt="{{ $calon->nama }}" class="w-full h-full object-cover">
                            @else
                                <i class="fas fa-user text-gray-400 text-lg"></i>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 mb-0.5">
                                <span class="w-5 h-5 rounded-full bg-birupesat/10 text-birupesat text-xs font-bold flex items-center justify-center flex-shrink-0">{{ $calon->nomor }}</span>
                                <h3 class="font-semibold text-accent text-sm truncate">{{ $calon->nama }}</h3>
                            </div>
                            <p class="text-xs text-gray-400">{{ $calon->kelas->name }}</p>
                        </div>
                        <i class="fas fa-chevron-right text-gray-300 text-xs group-hover:text-accent transition-colors"></i>
                    </div>
                @empty
                    <div class="p-12 text-center">
                        <i class="fas fa-user-group text-neutral-300 text-4xl mb-3"></i>
                        <p class="text-gray-400 text-sm">Belum ada calon terdaftar.</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Detail Calon --}}
        <div class="lg:col-span-3 bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden flex flex-col">
            {{-- Empty state --}}
            <div id="detail-empty" class="flex-1 flex flex-col items-center justify-center p-12 text-center">
                <div class="w-16 h-16 rounded-2xl bg-gray-100 flex items-center justify-center mb-4">
                    <i class="fas fa-hand-pointer text-gray-300 text-2xl"></i>
                </div>
                <h3 class="font-semibold text-gray-400 mb-1">Pilih calon</h3>
                <p class="text-sm text-gray-300">Klik salah satu calon di daftar untuk melihat detail</p>
            </div>

            {{-- Detail content --}}
            <div id="detail-content" class="hidden flex-1 flex flex-col">
                <div class="p-5 border-b border-gray-100 flex items-center justify-between">
                    <h2 class="text-base font-semibold text-accent">Detail Calon</h2>
                    <div class="flex items-center gap-2">
                        <x-admin-button variant="ghost" icon="fas fa-pen-to-square" id="btn-edit"
                            class="text-gray-400 hover:text-accent hover:bg-gray-100" title="Edit">
                        </x-admin-button>
                        <x-admin-button variant="ghost" icon="fas fa-trash-can" id="btn-delete"
                            class="text-gray-400 hover:text-red-600 hover:bg-red-50" title="Hapus">
                        </x-admin-button>
                    </div>
                </div>

                <div class="p-6 flex-1 overflow-y-auto">
                    {{-- Photo + identity --}}
                    <div class="flex items-center gap-5 mb-6">
                        <div id="detail-foto-wrap" class="w-24 h-24 rounded-2xl bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center overflow-hidden flex-shrink-0 shadow-sm">
                            <img id="detail-foto" src="" alt="" class="w-full h-full object-cover hidden">
                            <i id="detail-foto-placeholder" class="fas fa-user text-gray-400 text-3xl"></i>
                        </div>
                        <div>
                            <div class="flex items-center gap-2 mb-1">
                                <span id="detail-nomor" class="px-2.5 py-0.5 bg-birupesat/10 text-birupesat text-xs font-bold rounded-full"></span>
                            </div>
                            <h3 id="detail-nama" class="text-xl font-bold text-accent leading-tight"></h3>
                            <p id="detail-kelas" class="text-sm text-gray-500 mt-0.5"></p>
                        </div>
                    </div>

                    {{-- Visi --}}
                    <div class="mb-5">
                        <div class="flex items-center gap-2 mb-2">
                            <div class="w-1 h-4 rounded-full bg-birupesat"></div>
                            <h4 class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Visi</h4>
                        </div>
                        <p id="detail-visi" class="text-sm text-gray-600 leading-relaxed bg-gray-50 rounded-xl p-4"></p>
                    </div>

                    {{-- Misi --}}
                    <div>
                        <div class="flex items-center gap-2 mb-2">
                            <div class="w-1 h-4 rounded-full bg-accent"></div>
                            <h4 class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Misi</h4>
                        </div>
                        <p id="detail-misi" class="text-sm text-gray-600 leading-relaxed bg-gray-50 rounded-xl p-4 whitespace-pre-line"></p>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- Sidebar Form --}}
    <div id="secondary-sidebar" class="fixed inset-y-0 right-0 w-full sm:w-[480px] bg-white shadow-2xl z-50 transform translate-x-full transition-transform duration-300 ease-in-out flex flex-col">
        <div class="flex items-center justify-between p-6 border-b border-gray-100">
            <h2 id="sidebar-title" class="text-lg font-bold text-accent">Tambah Calon</h2>
            <x-admin-button variant="ghost" icon="fas fa-times" onclick="closeSidebar()"
                class="text-gray-400 hover:text-accent hover:bg-gray-100 text-lg">
            </x-admin-button>
        </div>
        <div class="flex-1 overflow-y-auto p-6">
            <form id="calon-form" action="{{ route('calon.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                @csrf
                <input type="hidden" id="form-method" name="_method" value="POST">
                <input type="hidden" id="calon-id" name="id" value="">

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Nama Lengkap</label>
                    <input type="text" id="input-nama" name="nama" required
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-accent focus:border-transparent outline-none">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Kelas</label>
                        <select id="input-kelas" name="id_kelas" required
                                class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-accent focus:border-transparent outline-none">
                            <option value="">Pilih Kelas</option>
                            @foreach($kelas as $k)
                                <option value="{{ $k->id }}">{{ $k->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Nomor Urut</label>
                        <input type="number" id="input-nomor" name="nomor" required
                               class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-accent focus:border-transparent outline-none">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Upload Foto</label>
                    <div class="flex gap-4">
                        <div id="preview-container" class="hidden w-24 h-24 rounded-lg border border-gray-200 overflow-hidden flex-shrink-0"
                             style="background-image: linear-gradient(45deg, #eee 25%, transparent 25%, transparent 75%, #eee 75%, #eee), linear-gradient(45deg, #eee 25%, transparent 25%, transparent 75%, #eee 75%, #eee); background-size: 20px 20px; background-position: 0 0, 10px 10px;">
                            <img id="preview-image" src="" alt="Preview" class="w-full h-full object-contain">
                        </div>
                        <label for="foto-input" class="flex-1 flex flex-col items-center justify-center py-8 border-2 border-dashed border-gray-300 rounded-xl text-sm text-gray-400 hover:border-accent hover:text-accent cursor-pointer transition-all">
                            <span>Klik untuk memilih foto</span>
                            <input type="file" id="foto-input" name="foto_calon" class="hidden" accept="image/*">
                        </label>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Visi</label>
                    <textarea id="input-visi" name="visi" rows="3" required
                              class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-accent focus:border-transparent outline-none resize-none"></textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Misi</label>
                    <textarea id="input-misi" name="misi" rows="3" required
                              class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-accent focus:border-transparent outline-none resize-none"></textarea>
                </div>

                <div class="flex gap-3 pt-4">
                    <x-admin-button type="submit" class="flex-1" icon="fas fa-check">
                        Simpan
                    </x-admin-button>
                    <x-admin-button variant="secondary" type="button" onclick="closeSidebar()">
                        Batal
                    </x-admin-button>
                </div>
            </form>
        </div>
    </div>

    <div id="sidebar-backdrop" class="fixed inset-0 bg-black/50 z-40 hidden transition-opacity" onclick="closeSidebar()"></div>

    <script>
        const calonData = @json($calonData);
        let selectedId = null;

        function selectCandidate(id) {
            // Update row highlight
            document.querySelectorAll('.candidate-row').forEach(r => {
                r.classList.remove('bg-birupesat/5', 'border-l-4', 'border-birupesat');
            });
            const row = document.getElementById('row-' + id);
            if (row) {
                row.classList.add('bg-birupesat/5', 'border-l-4', 'border-birupesat');
            }

            selectedId = id;
            const d = calonData[id];

            // Show detail panel
            document.getElementById('detail-empty').classList.add('hidden');
            document.getElementById('detail-content').classList.remove('hidden');

            // Populate fields
            document.getElementById('detail-nama').textContent = d.nama;
            document.getElementById('detail-kelas').textContent = 'Kelas ' + d.kelas;
            document.getElementById('detail-nomor').textContent = 'No. ' + d.nomor;
            document.getElementById('detail-visi').textContent = d.visi;
            document.getElementById('detail-misi').textContent = d.misi;

            const fotoEl = document.getElementById('detail-foto');
            const placeholderEl = document.getElementById('detail-foto-placeholder');
            if (d.url_foto) {
                fotoEl.src = d.url_foto;
                fotoEl.classList.remove('hidden');
                placeholderEl.classList.add('hidden');
            } else {
                fotoEl.classList.add('hidden');
                placeholderEl.classList.remove('hidden');
            }

            // Wire action buttons
            document.getElementById('btn-edit').onclick = () => openSidebar('edit', id);
            document.getElementById('btn-delete').onclick = () =>
                confirmDelete(
                    '{{ url('/admin/calon') }}/' + id,
                    'Hapus Calon',
                    'Apakah Anda yakin ingin menghapus calon ' + d.nama + '? Foto juga akan dihapus.'
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
                title.textContent = 'Edit Calon';
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
            } else {
                title.textContent = 'Tambah Calon Baru';
                form.action = '{{ route('calon.store') }}';
                methodInput.value = 'POST';
                idInput.value = '';
                form.reset();
                document.getElementById('preview-container').classList.add('hidden');
            }

            sidebar.classList.remove('translate-x-full');
            backdrop.classList.remove('hidden');
        }

        function closeSidebar() {
            document.getElementById('secondary-sidebar').classList.add('translate-x-full');
            document.getElementById('sidebar-backdrop').classList.add('hidden');
        }

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

        // Auto-select first candidate if any
        @if($calons->isNotEmpty())
            selectCandidate({{ $calons->first()->id }});
        @endif
    </script>
</x-app-layout>
