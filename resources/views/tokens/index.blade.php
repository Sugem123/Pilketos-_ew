@php
    $page_title = 'Display Token';
    $page_description = 'Kelola token untuk akses halaman voting';
@endphp
<x-app-layout :page_title="$page_title" :page_description="$page_description">
    <x-slot name="actions">
        <x-admin-button icon="fas fa-plus" onclick="openSidebar('add')">
            Tambah Token
        </x-admin-button>
    </x-slot>

    <div class="space-y-6">

        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Total Token</p>
                        <h3 class="text-2xl font-bold text-gray-800">{{ $totalTokens }}</h3>
                    </div>
                    <div class="w-10 h-10 bg-accent rounded-lg flex items-center justify-center">
                        <i class="fas fa-key text-primary text-sm"></i>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Token Aktif</p>
                        <h3 class="text-2xl font-bold text-gray-800">{{ $activeTokens }}</h3>
                    </div>
                    <div class="w-10 h-10 bg-accent rounded-lg flex items-center justify-center">
                        <i class="fa-regular fa-circle-check text-primary text-base"></i>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Token Nonaktif</p>
                        <h3 class="text-2xl font-bold text-gray-800">{{ $totalTokens - $activeTokens }}</h3>
                    </div>
                    <div class="w-10 h-10 bg-accent rounded-lg flex items-center justify-center">
                        <i class="fas fa-ban text-primary text-sm"></i>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Rasio Aktif</p>
                        <h3 class="text-2xl font-bold text-gray-800">{{ $totalTokens > 0 ? number_format(($activeTokens / $totalTokens) * 100, 1) : 0 }}%</h3>
                    </div>
                    <div class="w-10 h-10 bg-accent rounded-lg flex items-center justify-center">
                        <i class="fas fa-chart-simple text-primary text-sm"></i>
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
                            <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase">Token</th>
                            <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase">Status</th>
                            <th class="text-right px-6 py-4 text-xs font-semibold text-gray-500 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($tokens as $index => $token)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 text-sm text-gray-500">{{ $index + 1 }}</td>
                                <td class="px-6 py-4">
                                    <code
                                        class="px-3 py-1 bg-gray-100 rounded-lg text-sm font-mono font-semibold text-accent">{{ $token->token }}</code>
                                </td>
                                <td class="px-6 py-4">
                                    <form action="{{ route('tokens.update', $token) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="active" value="{{ $token->active ? '0' : '1' }}">
                                        <button type="submit"
                                            class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-medium rounded-full transition-colors {{ $token->active ? 'bg-green-100 text-green-700 hover:bg-green-200' : 'bg-red-100 text-red-700 hover:bg-red-200' }}">
                                            <i class="fas fa-circle text-[8px]"></i>
                                            {{ $token->active ? 'Aktif' : 'Nonaktif' }}
                                        </button>
                                    </form>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <x-admin-button variant="ghost" icon="fas fa-trash-can"
                                        onclick="confirmDelete('{{ route('tokens.destroy', $token) }}', 'Hapus Token', 'Apakah Anda yakin ingin menghapus token {{ $token->token }}?')"
                                        class="text-gray-400 hover:text-red-600 hover:bg-red-50"
                                        title="Hapus">
                                    </x-admin-button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center">
                                    <i class="fas fa-key text-neutral-300 text-4xl mb-3"></i>
                                    <p class="text-gray-500">Belum ada token terdaftar.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div id="secondary-sidebar"
        class="fixed inset-y-0 right-0 w-full sm:w-[420px] bg-white shadow-2xl z-50 transform translate-x-full transition-transform duration-300 ease-in-out flex flex-col">
        <div class="flex items-center justify-between p-6 border-b border-gray-100">
            <h2 class="text-lg font-bold text-accent">Tambah Token</h2>
            <x-admin-button variant="ghost" icon="fas fa-times" onclick="closeSidebar()"
                class="text-gray-400 hover:text-accent hover:bg-gray-100 text-lg">
            </x-admin-button>
        </div>
        <div class="flex-1 overflow-y-auto p-6">
            <form action="{{ route('tokens.store') }}" method="POST" class="space-y-5">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Token</label>
                    <div class="flex gap-2">
                        <input type="text" id="token-input" name="token" required maxlength="10"
                            class="flex-1 px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-accent focus:border-transparent outline-none font-mono uppercase"
                            placeholder="ABC123">
                        <x-admin-button type="button" onclick="generateToken()" variant="secondary" size="sm">
                            Generate
                        </x-admin-button>
                    </div>
                    <p class="text-xs text-gray-400 mt-1">Maksimal 10 karakter. Huruf dan angka saja.</p>
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

    <div id="sidebar-backdrop" class="fixed inset-0 bg-black/50 z-40 hidden transition-opacity"
        onclick="closeSidebar()"></div>

    <script>
        function openSidebar() {
            document.getElementById('secondary-sidebar').classList.remove('translate-x-full');
            document.getElementById('sidebar-backdrop').classList.remove('hidden');
        }

        function closeSidebar() {
            document.getElementById('secondary-sidebar').classList.add('translate-x-full');
            document.getElementById('sidebar-backdrop').classList.add('hidden');
        }

        function generateToken() {
            const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
            let token = '';
            for (let i = 0; i < 6; i++) {
                token += chars.charAt(Math.floor(Math.random() * chars.length));
            }
            document.getElementById('token-input').value = token;
        }
    </script>
</x-app-layout>
