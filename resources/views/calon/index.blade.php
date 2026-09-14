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
                'tipe' => $c->tipe,
                'visi' => $c->visi,
                'misi' => $c->misi,
                'url_foto' => $c->url_foto ? asset($c->url_foto) : '',
            ],
        ],
    );

    $maxKandidat = (int) ($tipe === 'mpk' ? ($config['jumlah_calon_mpk'] ?? 5) : ($config['jumlah_calon_osis'] ?? 3));
    $labelTipe = $tipe === 'mpk' ? 'Ketua MPK' : 'Ketua OSIS';
@endphp
<x-app-layout :page_title="$page_title" :page_description="$page_description">
    @push('head')
        <link rel="stylesheet" href="{{ asset('css/cropper.min.css') }}">
        <script src="{{ asset('js/cropper.min.js') }}"></script>
    @endpush
    <x-slot name="actions">
        <a href="{{ route('calon-public.index') }}" target="_blank"
           class="inline-flex items-center gap-1.5 px-4 py-2.5 bg-slate-900 border border-white/10 text-slate-200 hover:text-white hover:bg-slate-800 rounded-2xl text-xs font-bold transition-all shadow-md">
            <i class="fas fa-bullhorn text-sky-400"></i>
            <span>Lihat Publikasi</span>
        </a>
        <div class="flex items-center p-1 bg-slate-900 border border-white/10 rounded-2xl">
            <a href="{{ route('calon.index', ['tipe' => 'osis']) }}"
               class="px-4 py-2 rounded-xl text-xs font-heading font-extrabold transition-all {{ $tipe === 'osis' ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-400 hover:text-white' }}">
                <i class="fas fa-user-tie mr-1"></i> Ketua OSIS
            </a>
            <a href="{{ route('calon.index', ['tipe' => 'mpk']) }}"
               class="px-4 py-2 rounded-xl text-xs font-heading font-extrabold transition-all {{ $tipe === 'mpk' ? 'bg-emerald-600 text-white shadow-md' : 'text-slate-400 hover:text-white' }}">
                <i class="fas fa-scale-balanced mr-1"></i> Ketua MPK
            </a>
        </div>
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

                        {{-- Tombol Tukar Nomor Urut Cepat --}}
                        <div class="flex items-center gap-1 flex-shrink-0" onclick="event.stopPropagation()">
                            <form method="POST" action="{{ route('calon.reorder', $calon) }}">
                                @csrf
                                <input type="hidden" name="direction" value="up">
                                <button type="submit" title="Tukar Naik Nomor Urut"
                                        class="w-7 h-7 rounded-lg bg-slate-800 hover:bg-indigo-600 text-slate-400 hover:text-white flex items-center justify-center text-[10px] transition-colors border border-white/5">
                                    <i class="fas fa-chevron-up"></i>
                                </button>
                            </form>
                            <form method="POST" action="{{ route('calon.reorder', $calon) }}">
                                @csrf
                                <input type="hidden" name="direction" value="down">
                                <button type="submit" title="Tukar Turun Nomor Urut"
                                        class="w-7 h-7 rounded-lg bg-slate-800 hover:bg-indigo-600 text-slate-400 hover:text-white flex items-center justify-center text-[10px] transition-colors border border-white/5">
                                    <i class="fas fa-chevron-down"></i>
                                </button>
                            </form>
                        </div>
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
                <input type="hidden" id="input-tipe" name="tipe" value="{{ $tipe }}">

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
                    <input type="number" id="input-nomor" name="nomor" required min="1" max="99"
                        class="w-full px-4 py-3 luxury-input rounded-2xl outline-none text-sm font-bold font-mono">
                    <p class="text-[11px] text-slate-500 mt-1.5">Nomor urut kandidat (1&ndash;99). Jika nomor sudah ada, posisi paslon otomatis ditukar.</p>
                </div>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5 font-mono">Foto Kandidat</label>
                    <input type="hidden" id="foto-cropped-base64" name="foto_cropped_base64" value="">

                    <div class="flex gap-4 items-center">
                        <div id="preview-container" class="hidden flex-col items-center gap-2 flex-shrink-0">
                            <div class="w-24 h-32 rounded-2xl border border-white/10 overflow-hidden shadow-lg bg-slate-950 p-1 flex items-center justify-center">
                                <img id="preview-image" src="" alt="Preview" class="w-full h-full object-contain">
                            </div>
                            <button type="button" onclick="startCroppingCurrent()"
                                    class="px-2.5 py-1 bg-indigo-600/80 hover:bg-indigo-600 text-white rounded-lg text-[10px] font-bold flex items-center gap-1 shadow-md cursor-pointer transition-colors">
                                <i class="fas fa-crop-simple"></i> Crop Foto
                            </button>
                        </div>
                        <label for="foto-input"
                            class="flex-1 flex flex-col items-center justify-center py-6 border-2 border-dashed border-slate-700 hover:border-indigo-500 bg-slate-950/60 rounded-2xl text-xs font-bold text-slate-400 hover:text-indigo-400 cursor-pointer transition-all">
                            <i class="fas fa-cloud-arrow-up text-2xl mb-1.5 text-slate-500"></i>
                            <span id="foto-label-text">Pilih file foto</span>
                            <span class="text-[10px] text-slate-500 font-normal mt-0.5">Format: JPG, PNG, WEBP</span>
                            <input type="file" id="foto-input" name="foto_calon" class="hidden" accept="image/*">
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

    {{-- ====== MODAL CROPPER FOTO KANDIDAT ====== --}}
    <div id="cropper-modal" class="fixed inset-0 z-[99999] hidden items-center justify-center p-4 bg-slate-950/90 backdrop-blur-md">
        <div class="w-full max-w-xl bg-slate-900 border border-indigo-500/40 rounded-3xl p-6 shadow-2xl flex flex-col max-h-[92vh] relative z-10">
            <div class="flex items-center justify-between pb-4 border-b border-white/10">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-xl bg-indigo-500/15 text-indigo-400 border border-indigo-500/30 flex items-center justify-center text-sm">
                        <i class="fas fa-crop-simple"></i>
                    </div>
                    <div>
                        <h3 class="font-heading font-black text-base text-white">Sesuaikan &amp; Crop Foto Kandidat</h3>
                        <p class="text-[11px] text-slate-400">Atur bingkai rasio portrait 3:4 agar foto paslon rapi</p>
                    </div>
                </div>
                <button type="button" onclick="closeCropperModal()" class="text-slate-400 hover:text-white p-2 rounded-xl bg-slate-800">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            {{-- Container Viewport Gambar Cropper --}}
            <div class="my-4 w-full bg-slate-950 rounded-2xl border border-white/10 overflow-hidden flex items-center justify-center relative" style="height: 380px;">
                <img id="cropper-target-img" src="" alt="Target Crop" class="max-w-full block" style="display: block; max-width: 100%;">
            </div>

            {{-- Toolbar Tombol Kontrol --}}
            <div class="flex items-center justify-between gap-2 p-2 bg-slate-950/80 rounded-2xl border border-white/5 mb-4 flex-wrap">
                <div class="flex items-center gap-1.5">
                    <button type="button" onclick="cropperAction('zoom', 0.1)" class="px-3 py-1.5 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded-xl text-xs font-bold" title="Perbesar">
                        <i class="fas fa-magnifying-glass-plus mr-1"></i> Zoom In
                    </button>
                    <button type="button" onclick="cropperAction('zoom', -0.1)" class="px-3 py-1.5 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded-xl text-xs font-bold" title="Perkecil">
                        <i class="fas fa-magnifying-glass-minus mr-1"></i> Zoom Out
                    </button>
                </div>
                <div class="flex items-center gap-1.5">
                    <button type="button" onclick="cropperAction('rotate', -90)" class="px-3 py-1.5 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded-xl text-xs font-bold" title="Putar Kiri 90°">
                        <i class="fas fa-rotate-left mr-1"></i> Putar Kiri
                    </button>
                    <button type="button" onclick="cropperAction('rotate', 90)" class="px-3 py-1.5 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded-xl text-xs font-bold" title="Putar Kanan 90°">
                        <i class="fas fa-rotate-right mr-1"></i> Putar Kanan
                    </button>
                    <button type="button" onclick="cropperAction('reset')" class="px-3 py-1.5 bg-slate-800 hover:bg-slate-700 text-slate-400 hover:text-white rounded-xl text-xs font-bold" title="Reset">
                        <i class="fas fa-undo"></i>
                    </button>
                </div>
            </div>

            {{-- Tombol Batal & Terapkan --}}
            <div class="flex items-center justify-end gap-3 pt-3 border-t border-white/10">
                <button type="button" onclick="closeCropperModal()" class="px-5 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-bold">
                    Batal
                </button>
                <button type="button" onclick="applyCrop()" class="px-6 py-2.5 rounded-xl bg-gradient-to-r from-indigo-600 to-indigo-500 hover:from-indigo-500 hover:to-indigo-600 text-white text-xs font-bold shadow-lg shadow-indigo-500/30 flex items-center gap-2 cursor-pointer">
                    <i class="fas fa-check"></i>
                    <span>Terapkan Hasil Crop</span>
                </button>
            </div>
        </div>
    </div>

    <script>
        const calonData = @json($calonData);
        let selectedId = null;
        let cropperInstance = null;

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

            if (mode === 'edit' && id) {
                const data = calonData[id];
                title.textContent = 'Edit Data Paslon';
                form.action = '{{ url('/admin/calon') }}/' + id;
                methodInput.value = 'PUT';
                document.getElementById('input-nama').value = data.nama;
                document.getElementById('input-kelas').value = data.id_kelas;
                document.getElementById('input-nomor').value = data.nomor;
                document.getElementById('input-visi').value = data.visi;
                document.getElementById('input-misi').value = data.misi;
                document.getElementById('foto-cropped-base64').value = '';

                if (data.url_foto) {
                    document.getElementById('preview-image').src = data.url_foto;
                    document.getElementById('preview-container').classList.remove('hidden');
                    document.getElementById('preview-container').classList.add('flex');
                } else {
                    document.getElementById('preview-container').classList.add('hidden');
                    document.getElementById('preview-container').classList.remove('flex');
                }
                document.getElementById('foto-input').value = '';
            } else {
                title.textContent = 'Tambah Paslon Baru';
                form.action = '{{ route('calon.store') }}';
                methodInput.value = 'POST';
                form.reset();
                document.getElementById('input-tipe').value = '{{ $tipe }}';
                document.getElementById('foto-cropped-base64').value = '';
                document.getElementById('preview-container').classList.add('hidden');
                document.getElementById('preview-container').classList.remove('flex');
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
            if (e.key === 'Escape') {
                if (!document.getElementById('cropper-modal').classList.contains('hidden')) {
                    closeCropperModal();
                } else {
                    closeSidebar();
                }
            }
        });

        // Event saat memilih file foto baru di komputer / HP
        document.getElementById('foto-input').addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('preview-image').src = e.target.result;
                    document.getElementById('preview-container').classList.remove('hidden');
                    document.getElementById('preview-container').classList.add('flex');
                    // Langsung buka modal crop agar foto bisa disesuaikan
                    startCropping(e.target.result);
                };
                reader.readAsDataURL(file);
            }
        });

        // Buka modal cropper dengan gambar yang sedang aktif di preview
        function startCroppingCurrent() {
            const previewEl = document.getElementById('preview-image');
            const currentSrc = previewEl ? previewEl.src : null;
            if (currentSrc) {
                startCropping(currentSrc);
            } else {
                alert('Belum ada foto yang dipilih.');
            }
        }

        // Inisialisasi dan buka modal cropper
        function startCropping(imageSrc) {
            if (!imageSrc) {
                alert('Pilih foto terlebih dahulu.');
                return;
            }

            const modal = document.getElementById('cropper-modal');
            const targetImg = document.getElementById('cropper-target-img');

            modal.classList.remove('hidden');
            modal.classList.add('flex');

            if (cropperInstance) {
                cropperInstance.destroy();
                cropperInstance = null;
            }

            function setupCropper() {
                if (cropperInstance) {
                    cropperInstance.destroy();
                    cropperInstance = null;
                }

                if (typeof Cropper === 'undefined') {
                    console.error('Cropper.js library tidak terdeteksi.');
                    return;
                }

                cropperInstance = new Cropper(targetImg, {
                    aspectRatio: 3 / 4,
                    viewMode: 1,
                    autoCropArea: 0.95,
                    responsive: true,
                    background: false,
                    movable: true,
                    zoomable: true,
                    rotatable: true,
                    scalable: true
                });
            }

            targetImg.onload = setupCropper;
            targetImg.src = imageSrc;

            // Jika gambar sudah di-cache / complete di browser
            if (targetImg.complete && targetImg.naturalWidth > 0) {
                setupCropper();
            }
        }

        // Tombol aksi toolbar cropper (zoom, rotate, reset)
        function cropperAction(action, val = null) {
            if (!cropperInstance) return;
            if (action === 'zoom') {
                cropperInstance.zoom(val);
            } else if (action === 'rotate') {
                cropperInstance.rotate(val);
            } else if (action === 'reset') {
                cropperInstance.reset();
            }
        }

        // Terapkan hasil crop
        function applyCrop() {
            if (!cropperInstance) return;

            // Dapatkan canvas crop resolusi tajam
            const canvas = cropperInstance.getCroppedCanvas({
                width: 600,
                height: 800,
                imageSmoothingQuality: 'high'
            });

            if (canvas) {
                const croppedBase64 = canvas.toDataURL('image/png');
                document.getElementById('foto-cropped-base64').value = croppedBase64;
                document.getElementById('preview-image').src = croppedBase64;
                document.getElementById('preview-container').classList.remove('hidden');
                document.getElementById('preview-container').classList.add('flex');
            }

            closeCropperModal();
        }

        function closeCropperModal() {
            const modal = document.getElementById('cropper-modal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            if (cropperInstance) {
                cropperInstance.destroy();
                cropperInstance = null;
            }
        }

        @if ($calons->isNotEmpty())
            selectCandidate({{ $calons->first()->id }});
        @endif
    </script>
</x-app-layout>
