@php
    $page_title = 'Konfigurasi Admin';
    $page_description = 'Kelola akun administrator sistem';
@endphp
<x-app-layout :page_title="$page_title" :page_description="$page_description">
    <x-slot name="actions">
        <x-admin-button icon="fas fa-plus" onclick="openSidebar('add')">
            Tambah Admin
        </x-admin-button>
    </x-slot>

    <div class="space-y-6">

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-100">
                <h2 class="text-lg font-semibold text-accent">Daftar Admin</h2>
            </div>

            <div class="divide-y divide-gray-100">
                @forelse($users as $user)
                    <div class="p-6 flex items-center gap-4 hover:bg-gray-50 transition-colors">
                        <div class="w-12 h-12 bg-accent rounded-full flex items-center justify-center flex-shrink-0">
                            <span class="text-secondary font-semibold text-sm">{{ substr($user->nama_lengkap, 0, 1) }}</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="font-semibold text-accent">{{ $user->nama_lengkap }}</h3>
                            <p class="text-sm text-gray-500">{{ $user->email }}</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <x-admin-button variant="ghost" icon="fas fa-pen-to-square"
                                onclick="openSidebar('edit', adminData[{{ $user->id }}])"
                                class="text-gray-400 hover:text-accent hover:bg-gray-100"
                                title="Edit">
                            </x-admin-button>
                            @if($user->id !== auth()->id())
                                <x-admin-button variant="ghost" icon="fas fa-trash-can"
                                    onclick="confirmDelete('{{ route('admin-config.destroy', $user) }}', 'Hapus Admin', 'Apakah Anda yakin ingin menghapus admin {{ $user->nama_lengkap }}?')"
                                    class="text-gray-400 hover:text-red-600 hover:bg-red-50"
                                    title="Hapus">
                                </x-admin-button>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="p-12 text-center">
                        <i class="fas fa-users text-neutral-300 text-5xl mb-4"></i>
                        <p class="text-gray-500">Belum ada admin terdaftar.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <div id="secondary-sidebar" class="fixed inset-y-0 right-0 w-full sm:w-[480px] bg-white shadow-2xl z-50 transform translate-x-full transition-transform duration-300 ease-in-out flex flex-col pointer-events-none">
        <div class="flex items-center justify-between p-6 border-b border-gray-100">
            <h2 id="sidebar-title" class="text-lg font-bold text-accent">Tambah Admin</h2>
            <x-admin-button variant="ghost" icon="fas fa-times" onclick="closeSidebar()"
                class="text-gray-400 hover:text-accent hover:bg-gray-100 text-lg">
            </x-admin-button>
        </div>
        <div class="flex-1 overflow-y-auto p-6">
            <form id="admin-form" action="{{ route('admin-config.store') }}" method="POST" class="space-y-5">
                @csrf
                <input type="hidden" id="form-method" name="_method" value="POST">

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Nama Lengkap</label>
                    <input type="text" id="input-nama" name="nama_lengkap" required
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-accent focus:border-transparent outline-none">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Email</label>
                    <input type="email" id="input-email" name="email" required
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-accent focus:border-transparent outline-none">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Password</label>
                    <input type="password" id="input-password" name="password"
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-accent focus:border-transparent outline-none">
                    <p class="text-xs text-gray-400 mt-1" id="password-hint">Minimal 6 karakter. Kosongkan jika tidak ingin mengubah password.</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Konfirmasi Password</label>
                    <input type="password" id="input-password-confirm" name="password_confirmation"
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-accent focus:border-transparent outline-none">
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
        const adminData = @json($users->keyBy('id'));

        function openSidebar(mode, user = null) {
            const sidebar = document.getElementById('secondary-sidebar');
            const backdrop = document.getElementById('sidebar-backdrop');
            const title = document.getElementById('sidebar-title');
            const form = document.getElementById('admin-form');
            const methodInput = document.getElementById('form-method');
            const passwordHint = document.getElementById('password-hint');

            if (mode === 'edit' && user) {
                title.textContent = 'Edit Admin';
                form.action = '{{ url('/admin/admin-config') }}/' + user.id;
                methodInput.value = 'PUT';
                document.getElementById('input-nama').value = user.nama_lengkap;
                document.getElementById('input-email').value = user.email;
                document.getElementById('input-password').value = '';
                document.getElementById('input-password-confirm').value = '';
                passwordHint.style.display = 'block';
                document.getElementById('input-password').removeAttribute('required');
            } else {
                title.textContent = 'Tambah Admin Baru';
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
    </script>
</x-app-layout>
