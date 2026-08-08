@php $page_title = 'Data Calon'; @endphp
<x-app-layout>
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-accent">Data Calon Ketua OSIS</h1>
                <p class="text-gray-500 mt-1">Kelola data calon ketua OSIS</p>
            </div>
            <button onclick="openSidebar('add')" class="bg-accent text-secondary px-5 py-2.5 rounded-xl font-medium hover:bg-gray-800 transition-colors flex items-center gap-2">
                <i class="fas fa-plus"></i> Tambah Calon
            </button>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-100 flex items-center justify-between">
                <h2 class="text-lg font-semibold text-accent">Daftar Calon</h2>
                <span class="text-sm text-gray-500">{{ $calons->count() }} calon terdaftar</span>
            </div>

            <div class="divide-y divide-gray-100">
                @forelse($calons as $calon)
                    <div class="p-6 flex items-center gap-6 hover:bg-gray-50 transition-colors">
                        <div class="w-16 h-16 rounded-xl bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center overflow-hidden flex-shrink-0">
                            @if($calon->url_foto)
                                <img src="{{ asset($calon->url_foto) }}" alt="{{ $calon->nama }}" class="w-full h-full object-cover">
                            @else
                                <i class="fas fa-user text-gray-400 text-xl"></i>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-3 mb-1">
                                <h3 class="font-semibold text-accent">{{ $calon->nama }}</h3>
                                <span class="px-2 py-0.5 bg-birupesat/10 text-birupesat text-xs font-medium rounded-full">No. {{ $calon->nomor }}</span>
                            </div>
                            <p class="text-sm text-gray-500">Kelas {{ $calon->kelas->name }}</p>
                            <p class="text-sm text-gray-400 mt-1 truncate">{{ Str::limit($calon->visi, 80) }}</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <a href="{{ route('calon.edit', $calon) }}" onclick="event.preventDefault(); openSidebar('edit', {{ $calon->id }})" class="p-2 text-gray-400 hover:text-accent hover:bg-gray-100 rounded-lg transition-colors" title="Edit">
                                <i class="fas fa-pen-to-square"></i>
                            </a>
                            <button onclick="confirmDelete('{{ route('calon.destroy', $calon) }}', 'Hapus Calon', 'Apakah Anda yakin ingin menghapus calon {{ $calon->nama }}? Foto juga akan dihapus.')" class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Hapus">
                                <i class="fas fa-trash-can"></i>
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="p-12 text-center">
                        <i class="fas fa-user-group text-neutral-300 text-5xl mb-4"></i>
                        <p class="text-gray-500">Belum ada calon terdaftar.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="font-semibold text-accent mb-4">Pengaturan Batas Hak Suara</h3>
            @php
                $file = base_path('config.json');
                $config = json_decode(file_get_contents($file), true);
            @endphp
            <form action="{{ route('calon.haksuara') }}" method="POST" class="flex items-end gap-4">
                @csrf
                <div class="flex-1">
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Batas Maksimal Suara</label>
                    <input type="number" name="haksuara" value="{{ $config['haksuara'] }}" min="1" required
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-accent focus:border-transparent outline-none">
                </div>
                <button type="submit" class="bg-accent text-secondary px-5 py-2.5 rounded-xl font-medium hover:bg-gray-800 transition-colors">
                    Simpan
                </button>
            </form>
        </div>
    </div>

    <div id="secondary-sidebar" class="fixed inset-y-0 right-0 w-full sm:w-[480px] bg-white shadow-2xl z-50 transform translate-x-full transition-transform duration-300 ease-in-out flex flex-col">
        <div class="flex items-center justify-between p-6 border-b border-gray-100">
            <h2 id="sidebar-title" class="text-lg font-bold text-accent">Tambah Calon</h2>
            <button onclick="closeSidebar()" class="p-2 text-gray-400 hover:text-accent hover:bg-gray-100 rounded-lg transition-colors">
                <i class="fas fa-times text-lg"></i>
            </button>
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
                    <button type="submit" class="flex-1 bg-accent text-secondary py-2.5 rounded-xl font-medium hover:bg-gray-800 transition-colors">
                        Simpan
                    </button>
                    <button type="button" onclick="closeSidebar()" class="px-6 py-2.5 text-gray-600 bg-gray-100 rounded-xl font-medium hover:bg-gray-200 transition-colors">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div id="sidebar-backdrop" class="fixed inset-0 bg-black/50 z-40 hidden transition-opacity" onclick="closeSidebar()"></div>

    <script>
        const calonData = {};
        @foreach($calons as $calon)
            calonData[{{ $calon->id }}] = {
                nama: '{{ addslashes($calon->nama) }}',
                id_kelas: {{ $calon->id_kelas }},
                nomor: {{ $calon->nomor }},
                visi: '{{ addslashes($calon->visi) }}',
                misi: '{{ addslashes($calon->misi) }}',
                url_foto: '{{ $calon->url_foto ? asset($calon->url_foto) : '' }}'
            };
        @endforeach

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
    </script>
</x-app-layout>
