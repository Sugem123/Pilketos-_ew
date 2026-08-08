@php $page_title = 'Hak Suara'; @endphp
<x-app-layout>
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-accent">Kelola Hak Suara</h1>
                <p class="text-gray-500 mt-1">Daftar pemilih yang berhak memberikan suara</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('hak-suara.download-sample') }}" class="bg-gray-100 text-accent px-4 py-2.5 rounded-xl font-medium hover:bg-gray-200 transition-colors flex items-center gap-2 text-sm">
                    <i class="fas fa-download"></i> Download Sample
                </a>
                <button onclick="openImportModal()" class="bg-green-600 text-white px-5 py-2.5 rounded-xl font-medium hover:bg-green-700 transition-colors flex items-center gap-2 text-sm">
                    <i class="fas fa-file-import"></i> Import Excel
                </button>
                <button onclick="openSidebar('add')" class="bg-accent text-secondary px-5 py-2.5 rounded-xl font-medium hover:bg-gray-800 transition-colors flex items-center gap-2">
                    <i class="fas fa-plus"></i> Tambah
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-users text-blue-600 text-lg"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Total Hak Suara</p>
                        <h3 class="text-2xl font-bold text-gray-800">{{ $totalHakSuara }}</h3>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-check-circle text-green-600 text-lg"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Sudah Memilih</p>
                        <h3 class="text-2xl font-bold text-gray-800">{{ $hakSuaras->where('votes_count', '>', 0)->count() }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
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
                                    <button onclick="confirmDelete('{{ route('hak-suara.destroy', $hs) }}', 'Hapus Pemilih', 'Apakah Anda yakin ingin menghapus {{ $hs->nisn }} dari daftar pemilih?')"
                                            class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Hapus">
                                        <i class="fas fa-trash-can"></i>
                                    </button>
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
            <button onclick="closeSidebar()" class="p-2 text-gray-400 hover:text-accent hover:bg-gray-100 rounded-lg transition-colors">
                <i class="fas fa-times text-lg"></i>
            </button>
        </div>
        <div class="flex-1 overflow-y-auto p-6">
            <form action="{{ route('hak-suara.store') }}" method="POST" class="space-y-5">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Nama Lengkap</label>
                    <input type="text" name="nisn" required
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-accent focus:border-transparent outline-none"
                           placeholder="Masukkan nama lengkap pemilih">
                    <p class="text-xs text-gray-400 mt-1">Nama harus sama dengan yang akan dipilih di halaman voting.</p>
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

    <div id="import-modal" class="fixed inset-0 z-[60] hidden items-center justify-center p-4">
        <div class="absolute inset-0 bg-black/50" onclick="closeImportModal()"></div>
        <div class="relative bg-white rounded-2xl shadow-2xl max-w-md w-full p-6">
            <div class="text-center">
                <div class="mx-auto flex items-center justify-center h-14 w-14 rounded-full bg-green-100 mb-4">
                    <i class="fas fa-file-excel text-green-600 text-xl"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-2">Import dari Excel</h3>
                <p class="text-sm text-gray-500 mb-6">Upload file Excel (.xlsx/.xlsx) dengan format: kolom A = No, kolom B = Nama</p>
                <form action="{{ route('hak-suara.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <label class="flex flex-col items-center justify-center w-full py-8 border-2 border-dashed border-gray-300 rounded-xl text-sm text-gray-400 hover:border-green-500 hover:text-green-600 cursor-pointer transition-all mb-4">
                        <span>Pilih file Excel</span>
                        <input type="file" name="file_excel" required accept=".xls,.xlsx" class="hidden" onchange="this.parentElement.querySelector('span').textContent = this.files[0].name">
                    </label>
                    <div class="flex gap-3 justify-center">
                        <button type="button" onclick="closeImportModal()" class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 rounded-xl hover:bg-gray-200 transition-colors">Batal</button>
                        <button type="submit" class="px-5 py-2.5 text-sm font-medium text-white bg-green-600 rounded-xl hover:bg-green-700 transition-colors">Import</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openSidebar() {
            document.getElementById('secondary-sidebar').classList.remove('translate-x-full');
            document.getElementById('sidebar-backdrop').classList.remove('hidden');
        }
        function closeSidebar() {
            document.getElementById('secondary-sidebar').classList.add('translate-x-full');
            document.getElementById('sidebar-backdrop').classList.add('hidden');
        }
        function openImportModal() {
            document.getElementById('import-modal').classList.remove('hidden');
            document.getElementById('import-modal').classList.add('flex');
        }
        function closeImportModal() {
            document.getElementById('import-modal').classList.add('hidden');
            document.getElementById('import-modal').classList.remove('flex');
        }
    </script>
</x-app-layout>
