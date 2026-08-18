@php
    $page_title = 'Hak Suara';
    $page_description = 'Daftar pemilih yang berhak memberikan suara';
@endphp
<x-app-layout :page_title="$page_title" :page_description="$page_description">
    <x-slot name="actions">
        <x-admin-button variant="success" icon="fas fa-file-import" onclick="openImportModal()">
            Import Excel
        </x-admin-button>
        <x-admin-button icon="fas fa-plus" onclick="openSidebar('add')">
            Tambah
        </x-admin-button>
    </x-slot>

    <div class="space-y-6">

        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Total Pemilih</p>
                        <h3 class="text-2xl font-bold text-gray-800">{{ $totalHakSuara }}</h3>
                    </div>
                    <div class="w-10 h-10 bg-accent rounded-lg flex items-center justify-center">
                        <i class="fas fa-users text-primary text-sm"></i>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Sudah Memilih</p>
                        <h3 class="text-2xl font-bold text-gray-800">{{ $hakSuaras->where('votes_count', '>', 0)->count() }}</h3>
                    </div>
                    <div class="w-10 h-10 bg-accent rounded-lg flex items-center justify-center">
                        <i class="fa-regular fa-circle-check text-primary text-base"></i>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Belum Memilih</p>
                        <h3 class="text-2xl font-bold text-gray-800">{{ $hakSuaras->where('votes_count', 0)->count() }}</h3>
                    </div>
                    <div class="w-10 h-10 bg-accent rounded-lg flex items-center justify-center">
                        <i class="fas fa-clock text-primary text-sm"></i>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Partisipasi</p>
                        <h3 class="text-2xl font-bold text-gray-800">{{ $totalHakSuara > 0 ? number_format(($hakSuaras->where('votes_count', '>', 0)->count() / $totalHakSuara) * 100, 1) : 0 }}%</h3>
                    </div>
                    <div class="w-10 h-10 bg-accent rounded-lg flex items-center justify-center">
                        <i class="fas fa-chart-simple text-primary text-sm"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-4 border-b border-gray-100">
                <form method="GET" action="{{ route('hak-suara.index') }}" class="flex flex-col sm:flex-row gap-3">
                    <div class="relative flex-1">
                        <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                        <input type="text" name="search" value="{{ request('search') }}"
                               placeholder="Cari nama pemilih..."
                               class="w-full pl-9 pr-4 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-accent focus:border-transparent outline-none">
                    </div>
                    <select name="status" class="px-3 py-2 border border-gray-200 rounded-lg text-sm text-gray-600 focus:ring-2 focus:ring-accent focus:border-transparent outline-none">
                        <option value="">Semua Status</option>
                        <option value="sudah" {{ request('status') === 'sudah' ? 'selected' : '' }}>Sudah Memilih</option>
                        <option value="belum" {{ request('status') === 'belum' ? 'selected' : '' }}>Belum Memilih</option>
                    </select>
                    <button type="submit" class="px-4 py-2 bg-accent text-white text-sm font-medium rounded-lg hover:bg-accent/90 transition-colors">
                        Filter
                    </button>
                    @if(request('search') || request('status'))
                        <a href="{{ route('hak-suara.index') }}" class="px-4 py-2 border border-gray-200 text-gray-600 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors text-center">
                            Reset
                        </a>
                    @endif
                </form>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-100">
                        <tr>
                            <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase">No</th>
                            <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase">Nama</th>
                            <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase">Status</th>
                            <th class="text-right px-6 py-4 text-xs font-semibold text-gray-500 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($hakSuaras as $index => $hs)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 text-sm text-gray-500">{{ $index + 1 }}</td>
                                <td class="px-6 py-4 text-sm font-medium text-accent">{{ $hs->nisn }}</td>
                                <td class="px-6 py-4">
                                    @if($hs->votes_count > 0)
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-green-100 text-green-700 text-xs font-medium rounded-full">
                                            <i class="fas fa-check"></i> Sudah Memilih
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-yellow-100 text-yellow-700 text-xs font-medium rounded-full">
                                            <i class="fas fa-clock"></i> Belum Memilih
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <x-admin-button variant="ghost" icon="fas fa-trash-can"
                                        onclick="confirmDelete('{{ route('hak-suara.destroy', $hs) }}', 'Hapus Pemilih', 'Apakah Anda yakin ingin menghapus {{ $hs->nisn }} dari daftar pemilih?')"
                                        class="text-gray-400 hover:text-red-600 hover:bg-red-50"
                                        title="Hapus">
                                    </x-admin-button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center">
                                    <i class="fas fa-users text-neutral-300 text-4xl mb-3"></i>
                                    <p class="text-gray-500">Belum ada data hak suara.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div id="secondary-sidebar" class="fixed inset-y-0 right-0 w-full sm:w-[420px] bg-white shadow-2xl z-50 transform translate-x-full transition-transform duration-300 ease-in-out flex flex-col">
        <div class="flex items-center justify-between p-6 border-b border-gray-100">
            <h2 class="text-lg font-bold text-accent">Tambah Hak Suara</h2>
            <x-admin-button variant="ghost" icon="fas fa-times" onclick="closeSidebar()"
                class="text-gray-400 hover:text-accent hover:bg-gray-100 text-lg">
            </x-admin-button>
        </div>
        <div class="flex-1 overflow-y-auto p-6">
            <form action="{{ route('hak-suara.store') }}" method="POST" class="space-y-5">
                @csrf
                @if ($errors->any())
                    <div class="bg-red-50 border border-red-200 rounded-lg p-3">
                        <ul class="text-sm text-red-600 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Nama Lengkap</label>
                    <input type="text" name="nisn" required
                           value="{{ old('nisn') }}"
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-accent focus:border-transparent outline-none"
                           placeholder="Masukkan nama lengkap pemilih">
                    <p class="text-xs text-gray-400 mt-1">Hanya huruf, spasi, titik, dan tanda hubung. Nama harus sama dengan yang akan diinput di halaman voting.</p>
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
        function openSidebar() {
            document.getElementById('secondary-sidebar').classList.remove('translate-x-full');
            document.getElementById('sidebar-backdrop').classList.remove('hidden');
        }
        function closeSidebar() {
            document.getElementById('secondary-sidebar').classList.add('translate-x-full');
            document.getElementById('sidebar-backdrop').classList.add('hidden');
        }

        // Auto-buka sidebar jika ada error validasi
        @if ($errors->any())
            openSidebar();
        @endif

        function openImportModal() {
            Swal.fire({
                title: 'Import dari Excel',
                html: `
                    <p class="text-sm text-gray-500 mb-3">Upload file Excel (.xls/.xlsx) dengan format:<br>kolom A = No, kolom B = Nama</p>
                    <a href="{{ route('hak-suara.download-sample') }}" class="inline-flex items-center gap-1.5 text-sm text-gray-400 hover:text-green-600 mb-4 transition-colors">
                        <i class="fas fa-download text-xs"></i> Download Sample
                    </a>
                    <label id="import-label" class="flex flex-col items-center justify-center w-full py-8 border-2 border-dashed border-gray-300 rounded-xl text-sm text-gray-400 hover:border-green-500 hover:text-green-600 cursor-pointer transition-all">
                        <i class="fas fa-file-arrow-up text-2xl mb-2"></i>
                        <span id="import-filename">Pilih file Excel</span>
                        <input id="import-file-input" type="file" accept=".xls,.xlsx" class="hidden">
                    </label>
                `,
                iconHtml: '<i class="fas fa-file-excel" style="font-size:1.6rem;color:#16a34a;"></i>',
                showCancelButton: true,
                confirmButtonText: '<i class="fas fa-check mr-1.5"></i> Import',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                buttonsStyling: false,
                backdrop: 'rgba(17, 24, 39, 0.35)',
                customClass: {
                    popup: 'swal2-popup-custom',
                    icon: 'swal2-icon-import',
                    title: 'swal2-title-custom',
                    htmlContainer: 'swal2-html-custom',
                    actions: 'swal2-actions-custom',
                    confirmButton: 'swal2-confirm-import',
                    cancelButton: 'swal2-cancel-custom',
                },
                didOpen: () => {
                    const input = document.getElementById('import-file-input');
                    const label = document.getElementById('import-label');
                    input.addEventListener('change', function () {
                        if (this.files[0]) {
                            document.getElementById('import-filename').textContent = this.files[0].name;
                            label.classList.add('border-green-500', 'text-green-600');
                            label.classList.remove('border-gray-300', 'text-gray-400');
                        }
                    });
                },
                preConfirm: () => {
                    const file = document.getElementById('import-file-input').files[0];
                    if (!file) {
                        Swal.showValidationMessage('Pilih file Excel terlebih dahulu.');
                        return false;
                    }
                    return file;
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
                    csrf.value = document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}';

                    const fileInput = document.createElement('input');
                    fileInput.type = 'file';
                    fileInput.name = 'file_excel';

                    const dt = new DataTransfer();
                    dt.items.add(result.value);
                    fileInput.files = dt.files;

                    form.appendChild(csrf);
                    form.appendChild(fileInput);
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }
    </script>
</x-app-layout>
