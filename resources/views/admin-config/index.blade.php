@php
    $page_title = 'Konfigurasi Sistem dan Template';
    $page_description = 'Kelola profil sekolah, logo resmi, format surat undangan pemilihan, serta akun administrator';
@endphp
<x-app-layout :page_title="$page_title" :page_description="$page_description">
    <x-slot name="actions">
        <a href="{{ route('cetak.undangan') }}" target="_blank"
           class="inline-flex items-center gap-1.5 px-4 py-2.5 bg-slate-900 border border-white/10 text-slate-200 hover:text-white hover:bg-slate-800 rounded-2xl text-xs font-bold transition-all shadow-md">
            <i class="fas fa-eye text-indigo-400"></i>
            <span>Preview Undangan</span>
        </a>
        <x-admin-button icon="fas fa-user-shield" onclick="openSidebar('add')">
            Tambah Admin
        </x-admin-button>
    </x-slot>

    <div class="space-y-8">

        {{-- Section 1: Pengaturan Profil Sekolah & Logo --}}
        <div class="luxury-card rounded-3xl overflow-hidden p-6 sm:p-8">
            <div class="border-b border-white/5 pb-5 mb-6 flex items-center justify-between">
                <div>
                    <h2 class="font-heading font-black text-lg sm:text-xl text-white flex items-center gap-2.5">
                        <i class="fas fa-school text-indigo-400"></i>
                        <span>Profil Lembaga Sekolah dan Logo</span>
                    </h2>
                    <p class="text-xs text-slate-400 mt-1">Pengaturan ini akan ditampilkan di bilik suara, live count proyektor, kartu pemilih, dan kop resmi.</p>
                </div>
                <span class="text-xs font-mono font-bold px-3 py-1 bg-indigo-500/10 text-indigo-300 border border-indigo-500/20 rounded-xl">
                    Branding Resmi
                </span>
            </div>

            <form action="{{ route('admin-config.school-profile') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    {{-- Logo Upload Card --}}
                    <div class="flex flex-col items-center justify-center p-6 bg-slate-900/90 border border-white/5 rounded-3xl text-center">
                        <span class="text-xs font-mono font-bold uppercase tracking-wider text-slate-400 mb-3 block">Logo Sekolah / Pemilu</span>
                        
                        <div class="w-32 h-32 rounded-3xl bg-slate-950 border border-white/10 shadow-2xl p-3 flex items-center justify-center overflow-hidden mb-4 relative">
                            <img id="logo-preview-img"
                                 src="{{ asset($config['url_logo'] ?? 'img/logo.png') }}"
                                 alt="Logo Sekolah"
                                 class="max-w-full max-h-full object-contain">
                        </div>

                        <label for="logo-input"
                               class="inline-flex items-center gap-2 px-4 py-2.5 bg-slate-900 hover:bg-slate-800 border border-white/10 hover:border-indigo-500 rounded-2xl text-xs font-bold text-slate-200 hover:text-white cursor-pointer transition-all shadow-md">
                            <i class="fas fa-camera text-indigo-400"></i>
                            <span>Ganti Logo Sekolah</span>
                            <input type="file" id="logo-input" name="logo" class="hidden" accept="image/*">
                        </label>
                        <p class="text-[10px] text-slate-500 mt-2">Maksimal 3MB (PNG, JPG, SVG, WEBP)</p>
                    </div>

                    {{-- School Meta Inputs --}}
                    <div class="lg:col-span-2 space-y-4">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5 font-mono">Nama Sekolah / Lembaga</label>
                                <input type="text" name="nama_sekolah" value="{{ old('nama_sekolah', $config['nama_sekolah'] ?? 'SMA NEGERI 1 PRAMBON') }}" required
                                       class="w-full px-4 py-3 luxury-input rounded-2xl outline-none text-sm font-bold">
                            </div>

                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5 font-mono">Nama Kegiatan Pemilihan</label>
                                <input type="text" name="nama_kegiatan" value="{{ old('nama_kegiatan', $config['nama_kegiatan'] ?? 'PEMILIHAN KETUA & WAKIL KETUA OSIS') }}" required
                                       class="w-full px-4 py-3 luxury-input rounded-2xl outline-none text-sm font-bold">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5 font-mono">Tahun Ajaran / Periode</label>
                                <input type="text" name="tahun_ajaran" value="{{ old('tahun_ajaran', $config['tahun_ajaran'] ?? '2026/2027') }}" required
                                       class="w-full px-4 py-3 luxury-input rounded-2xl outline-none text-sm font-mono font-bold">
                            </div>

                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5 font-mono">Batas Kuota Hak Suara</label>
                                <input type="number" name="haksuara" value="{{ old('haksuara', $config['haksuara'] ?? 1050) }}" required min="1"
                                       class="w-full px-4 py-3 luxury-input rounded-2xl outline-none text-sm font-mono font-bold">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5 font-mono">Alamat Sekolah / Lokasi TPS</label>
                            <input type="text" name="alamat_sekolah" value="{{ old('alamat_sekolah', $config['alamat_sekolah'] ?? '') }}"
                                   class="w-full px-4 py-3 luxury-input rounded-2xl outline-none text-sm"
                                   placeholder="Contoh: JL. A.YANI SUGIHWARAS PRAMBON">
                        </div>

                        <div class="pt-2 flex justify-end">
                            <x-admin-button type="submit" icon="fas fa-save" class="px-6 py-3">
                                Simpan Profil Sekolah
                            </x-admin-button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        {{-- Section 2: Pengaturan Format Isi Surat Undangan Pemilihan --}}
        <div class="luxury-card rounded-3xl overflow-hidden p-6 sm:p-8">
            <div class="border-b border-white/5 pb-5 mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div>
                    <h2 class="font-heading font-black text-lg sm:text-xl text-white flex items-center gap-2.5">
                        <i class="fas fa-envelope-open-text text-amber-400"></i>
                        <span>Pengaturan Isi Format Surat Undangan Panggilan</span>
                    </h2>
                    <p class="text-xs text-slate-400 mt-1">Ubah redaksi teks, jadwal, waktu, lokasi, dan catatan kaki surat undangan yang akan dicetak dan dibagikan ke pemilih DPT.</p>
                </div>
                <span class="text-xs font-mono font-bold px-3 py-1 bg-amber-500/10 text-amber-300 border border-amber-500/20 rounded-xl self-start sm:self-auto">
                    Editor Undangan
                </span>
            </div>

            <form action="{{ route('admin-config.undangan-template') }}" method="POST" class="space-y-5">
                @csrf

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5 font-mono">Judul Kop Surat</label>
                        <input type="text" name="undangan_judul_kop"
                               value="{{ old('undangan_judul_kop', $config['undangan_judul_kop'] ?? 'PANITIA PEMILIHAN KETUA & WAKIL KETUA OSIS') }}" required
                               class="w-full px-4 py-3 luxury-input rounded-2xl outline-none text-sm font-bold">
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5 font-mono">Sub Judul Kop Surat</label>
                        <input type="text" name="undangan_sub_kop"
                               value="{{ old('undangan_sub_kop', $config['undangan_sub_kop'] ?? 'Surat Pemberitahuan Pemungutan Suara di TPS Bilik Suara') }}" required
                               class="w-full px-4 py-3 luxury-input rounded-2xl outline-none text-sm font-medium">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5 font-mono">Paragraf Pembuka Undangan</label>
                    <textarea name="undangan_pembuka" rows="3" required
                              class="w-full px-4 py-3 luxury-input rounded-2xl outline-none text-xs leading-relaxed resize-none">{{ old('undangan_pembuka', $config['undangan_pembuka'] ?? 'Bersama ini Panitia Pemilihan Ketua OSIS mengundang Saudara/i untuk hadir dan menggunakan hak pilih pada pemilihan umum ketua OSIS dengan data identitas terdaftar sebagai berikut:') }}</textarea>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5 font-mono">Hari / Tanggal Pemilihan</label>
                        <input type="text" name="undangan_hari_tanggal"
                               value="{{ old('undangan_hari_tanggal', $config['undangan_hari_tanggal'] ?? 'Kamis, 20 Agustus 2026') }}" required
                               class="w-full px-4 py-3 luxury-input rounded-2xl outline-none text-xs font-semibold"
                               placeholder="Contoh: Kamis, 20 Agustus 2026">
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5 font-mono">Waktu Pelaksanaan</label>
                        <input type="text" name="undangan_waktu"
                               value="{{ old('undangan_waktu', $config['undangan_waktu'] ?? '08.00 - 13.00 WIB') }}" required
                               class="w-full px-4 py-3 luxury-input rounded-2xl outline-none text-xs font-semibold"
                               placeholder="Contoh: 08.00 - 13.00 WIB">
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5 font-mono">Tempat / TPS</label>
                        <input type="text" name="undangan_lokasi"
                               value="{{ old('undangan_lokasi', $config['undangan_lokasi'] ?? 'Bilik Suara TPS Pemilihan OSIS') }}" required
                               class="w-full px-4 py-3 luxury-input rounded-2xl outline-none text-xs font-semibold"
                               placeholder="Contoh: Aula Utama SMAN 1 Prambon">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5 font-mono">Catatan Kaki Petunjuk Pemilih</label>
                        <input type="text" name="undangan_catatan_kaki"
                               value="{{ old('undangan_catatan_kaki', $config['undangan_catatan_kaki'] ?? 'Harap membawa surat undangan ini atau mengingat Token Otorisasi saat dipanggil oleh panitia TPS menuju bilik suara e-voting. Satu token hanya berlaku untuk 1 (satu) kali penggunaan.') }}"
                               class="w-full px-4 py-3 luxury-input rounded-2xl outline-none text-xs text-slate-300">
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5 font-mono">Jabatan Penandatangan</label>
                        <input type="text" name="undangan_penandatangan"
                               value="{{ old('undangan_penandatangan', $config['undangan_penandatangan'] ?? 'Ketua Panitia Pelaksana') }}" required
                               class="w-full px-4 py-3 luxury-input rounded-2xl outline-none text-xs font-semibold">
                    </div>
                </div>

                <div class="pt-3 flex justify-end gap-3">
                    <a href="{{ route('cetak.undangan') }}" target="_blank"
                       class="px-5 py-3 bg-slate-900 hover:bg-slate-800 border border-white/10 text-slate-300 hover:text-white rounded-2xl text-xs font-bold transition-all">
                        <i class="fas fa-print mr-1.5"></i> Test Cetak Undangan
                    </a>
                    <x-admin-button type="submit" icon="fas fa-check" class="px-6 py-3">
                        Simpan Format Undangan
                    </x-admin-button>
                </div>
            </form>
        </div>

        {{-- Section 3: Daftar Administrator --}}
        <div class="luxury-card rounded-3xl overflow-hidden p-6 sm:p-8">
            <div class="border-b border-white/5 pb-5 mb-6 flex items-center justify-between">
                <div>
                    <h2 class="font-heading font-black text-lg sm:text-xl text-white flex items-center gap-2.5">
                        <i class="fas fa-user-shield text-indigo-400"></i>
                        <span>Daftar Akun Administrator</span>
                    </h2>
                    <p class="text-xs text-slate-400 mt-1">Pengguna yang memiliki otorisasi penuh pada sistem</p>
                </div>
                <span class="text-xs font-mono font-bold px-3 py-1 bg-indigo-500/10 text-indigo-300 border border-indigo-500/20 rounded-xl">
                    {{ $users->count() }} Admin Terdaftar
                </span>
            </div>

            <div class="divide-y divide-white/5">
                @forelse($users as $user)
                    <div class="py-4 sm:py-5 flex items-center gap-4 hover:bg-slate-900/40 transition-colors rounded-2xl px-2">
                        <div class="w-12 h-12 bg-gradient-to-tr from-indigo-600 to-indigo-400 rounded-2xl flex items-center justify-center flex-shrink-0 shadow-md shadow-indigo-500/20 text-white font-heading font-black text-base">
                            {{ substr($user->nama_lengkap, 0, 1) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2">
                                <h3 class="font-bold text-white text-sm truncate">{{ $user->nama_lengkap }}</h3>
                                @if($user->id === auth()->id())
                                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 font-mono">Akun Anda</span>
                                @endif
                            </div>
                            <p class="text-xs text-slate-400 font-mono mt-0.5">{{ $user->email }}</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <x-admin-button variant="ghost" icon="fas fa-pen-to-square"
                                onclick="openSidebar('edit', adminData[{{ $user->id }}])"
                                class="text-slate-400 hover:text-indigo-400 hover:bg-slate-800"
                                title="Edit">
                            </x-admin-button>
                            @if($user->id !== auth()->id())
                                <x-admin-button variant="ghost" icon="fas fa-trash-can"
                                    onclick="confirmDelete('{{ route('admin-config.destroy', $user) }}', 'Hapus Admin', 'Apakah Anda yakin ingin menghapus admin {{ $user->nama_lengkap }}?')"
                                    class="text-slate-400 hover:text-rose-400 hover:bg-rose-500/10"
                                    title="Hapus">
                                </x-admin-button>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="p-12 text-center">
                        <i class="fas fa-users text-slate-600 text-5xl mb-4"></i>
                        <p class="text-slate-400 text-xs">Belum ada admin terdaftar.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Slide-in Sidebar Form Admin --}}
    <div id="secondary-sidebar" class="fixed inset-y-0 right-0 w-full sm:w-[480px] bg-slate-900 border-l border-white/10 shadow-2xl z-50 transform translate-x-full transition-transform duration-300 ease-in-out flex flex-col pointer-events-none text-slate-100">
        <div class="flex items-center justify-between p-6 border-b border-white/5 bg-slate-950/60">
            <h2 id="sidebar-title" class="font-heading font-black text-lg text-white">Tambah Administrator</h2>
            <button onclick="closeSidebar()" class="w-8 h-8 rounded-xl text-slate-400 hover:text-white hover:bg-slate-800 flex items-center justify-center transition-colors">
                <i class="fas fa-times text-base"></i>
            </button>
        </div>
        <div class="flex-1 overflow-y-auto p-6 sm:p-8">
            <form id="admin-form" action="{{ route('admin-config.store') }}" method="POST" class="space-y-5">
                @csrf
                <input type="hidden" id="form-method" name="_method" value="POST">

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5 font-mono">Nama Lengkap</label>
                    <input type="text" id="input-nama" name="nama_lengkap" required
                           class="w-full px-4 py-3 luxury-input rounded-2xl outline-none text-sm font-semibold">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5 font-mono">Alamat Email</label>
                    <input type="email" id="input-email" name="email" required
                           class="w-full px-4 py-3 luxury-input rounded-2xl outline-none text-sm font-semibold font-mono">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5 font-mono">Password</label>
                    <input type="password" id="input-password" name="password"
                           class="w-full px-4 py-3 luxury-input rounded-2xl outline-none text-sm">
                    <p class="text-[11px] text-slate-500 mt-1.5" id="password-hint">Minimal 6 karakter. Kosongkan jika tidak ingin mengubah password.</p>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5 font-mono">Konfirmasi Password</label>
                    <input type="password" id="input-password-confirm" name="password_confirmation"
                           class="w-full px-4 py-3 luxury-input rounded-2xl outline-none text-sm">
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
        const adminData = @json($users->keyBy('id'));

        function openSidebar(mode, user = null) {
            const sidebar = document.getElementById('secondary-sidebar');
            const backdrop = document.getElementById('sidebar-backdrop');
            const title = document.getElementById('sidebar-title');
            const form = document.getElementById('admin-form');
            const methodInput = document.getElementById('form-method');
            const passwordHint = document.getElementById('password-hint');

            if (mode === 'edit' && user) {
                title.textContent = 'Edit Data Administrator';
                form.action = '{{ url('/admin/admin-config') }}/' + user.id;
                methodInput.value = 'PUT';
                document.getElementById('input-nama').value = user.nama_lengkap;
                document.getElementById('input-email').value = user.email;
                document.getElementById('input-password').value = '';
                document.getElementById('input-password-confirm').value = '';
                passwordHint.style.display = 'block';
                document.getElementById('input-password').removeAttribute('required');
            } else {
                title.textContent = 'Tambah Administrator Baru';
                form.action = '{{ route('admin-config.store') }}';
                methodInput.value = 'POST';
                form.reset();
                passwordHint.style.display = 'none';
                document.getElementById('input-password').setAttribute('required', 'required');
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

        // Logo image preview
        document.getElementById('logo-input').addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('logo-preview-img').src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        });
    </script>
</x-app-layout>


