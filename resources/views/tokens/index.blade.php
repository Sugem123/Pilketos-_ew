@php
    $page_title = 'Display Token';
    $page_description = 'Kelola token otorisasi bilik suara untuk akses pemilih';
@endphp
<x-app-layout :page_title="$page_title" :page_description="$page_description">
    <x-slot name="actions">
        <x-admin-button icon="fas fa-plus" onclick="openSidebar('add')">
            Tambah Token
        </x-admin-button>
    </x-slot>

    <div class="space-y-8">

        {{-- Metric Stats --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            <div class="luxury-card luxury-card-hover rounded-3xl p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 font-mono block mb-1">Total Token</span>
                        <h3 class="font-heading font-black text-3xl sm:text-4xl text-white font-mono leading-none">{{ $totalTokens }}</h3>
                    </div>
                    <div class="w-14 h-14 rounded-2xl bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 flex items-center justify-center text-xl shadow-lg">
                        <i class="fas fa-key"></i>
                    </div>
                </div>
            </div>

            <div class="luxury-card luxury-card-hover rounded-3xl p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 font-mono block mb-1">Token Aktif</span>
                        <h3 class="font-heading font-black text-3xl sm:text-4xl text-emerald-400 font-mono leading-none">{{ $activeTokens }}</h3>
                    </div>
                    <div class="w-14 h-14 rounded-2xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 flex items-center justify-center text-xl shadow-lg">
                        <i class="fa-solid fa-circle-check"></i>
                    </div>
                </div>
            </div>

            <div class="luxury-card luxury-card-hover rounded-3xl p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 font-mono block mb-1">Token Nonaktif</span>
                        <h3 class="font-heading font-black text-3xl sm:text-4xl text-slate-500 font-mono leading-none">{{ $totalTokens - $activeTokens }}</h3>
                    </div>
                    <div class="w-14 h-14 rounded-2xl bg-slate-900 border border-white/5 text-slate-500 flex items-center justify-center text-xl shadow-lg">
                        <i class="fas fa-ban"></i>
                    </div>
                </div>
            </div>

            <div class="luxury-card luxury-card-hover rounded-3xl p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 font-mono block mb-1">Rasio Aktif</span>
                        <h3 class="font-heading font-black text-3xl sm:text-4xl text-indigo-400 font-mono leading-none">{{ $totalTokens > 0 ? number_format(($activeTokens / $totalTokens) * 100, 1) : 0 }}%</h3>
                    </div>
                    <div class="w-14 h-14 rounded-2xl bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 flex items-center justify-center text-xl shadow-lg">
                        <i class="fas fa-chart-pie"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- Table Card --}}
        <div class="luxury-card rounded-3xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-slate-950/60 border-b border-white/5">
                        <tr>
                            <th class="px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-wider font-mono">No</th>
                            <th class="px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-wider font-mono">Kode Token</th>
                            <th class="px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-wider font-mono">Status Akses</th>
                            <th class="text-right px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-wider font-mono">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @forelse($tokens as $index => $token)
                            <tr class="hover:bg-slate-900/50 transition-colors">
                                <td class="px-6 py-4 text-xs font-mono text-slate-500">{{ $index + 1 }}</td>
                                <td class="px-6 py-4">
                                    <code class="px-3.5 py-1.5 bg-slate-950 border border-slate-800 rounded-xl text-xs font-mono font-black text-amber-400 tracking-widest select-all">
                                        {{ $token->token }}
                                    </code>
                                </td>
                                <td class="px-6 py-4">
                                    <form action="{{ route('tokens.update', $token) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="active" value="{{ $token->active ? '0' : '1' }}">
                                        <button type="submit"
                                            class="inline-flex items-center gap-1.5 px-3 py-1 text-[11px] font-bold rounded-full transition-all cursor-pointer {{ $token->active ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 hover:bg-emerald-500/20' : 'bg-slate-900 text-slate-400 border border-slate-800 hover:bg-slate-800' }}">
                                            <span class="w-1.5 h-1.5 rounded-full {{ $token->active ? 'bg-emerald-400 animate-pulse' : 'bg-slate-500' }}"></span>
                                            {{ $token->active ? 'Aktif' : 'Nonaktif' }}
                                        </button>
                                    </form>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <x-admin-button variant="ghost" icon="fas fa-trash-can"
                                        onclick="confirmDelete('{{ route('tokens.destroy', $token) }}', 'Hapus Token', 'Apakah Anda yakin ingin menghapus token {{ $token->token }}?')"
                                        class="text-slate-400 hover:text-rose-400 hover:bg-rose-500/10"
                                        title="Hapus">
                                    </x-admin-button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-14 text-center">
                                    <div class="w-12 h-12 rounded-2xl bg-slate-900 border border-white/5 text-slate-600 flex items-center justify-center mx-auto mb-3 text-lg">
                                        <i class="fas fa-key"></i>
                                    </div>
                                    <p class="text-xs font-semibold text-slate-400">Belum ada token yang dibuat.</p>
                                    <p class="text-[11px] text-slate-500 mt-0.5">Buat token baru untuk otorisasi bilik suara.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Slide-in Sidebar Form --}}
    <div id="secondary-sidebar"
        class="fixed inset-y-0 right-0 w-full sm:w-[420px] bg-slate-900 border-l border-white/10 shadow-2xl z-50 transform translate-x-full transition-transform duration-300 ease-in-out flex flex-col pointer-events-none text-slate-100">
        <div class="flex items-center justify-between p-6 border-b border-white/5 bg-slate-950/60">
            <h2 class="font-heading font-black text-lg text-white">Tambah Display Token</h2>
            <button onclick="closeSidebar()" class="w-8 h-8 rounded-xl text-slate-400 hover:text-white hover:bg-slate-800 flex items-center justify-center transition-colors">
                <i class="fas fa-times text-base"></i>
            </button>
        </div>
        <div class="flex-1 overflow-y-auto p-6 sm:p-8">
            <form action="{{ route('tokens.store') }}" method="POST" class="space-y-5">
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
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5 font-mono">Kode Token</label>
                    <div class="flex gap-2.5">
                        <input type="text" id="token-input" name="token" required maxlength="10"
                            value="{{ old('token') }}"
                            class="flex-1 px-4 py-3 luxury-input rounded-2xl outline-none font-mono uppercase text-sm font-black tracking-widest"
                            placeholder="ABC123">
                        <x-admin-button type="button" onclick="generateToken()" variant="secondary" size="sm" icon="fas fa-shuffle">
                            Acak
                        </x-admin-button>
                    </div>
                    <p class="text-[11px] text-slate-500 mt-1.5">Maksimal 10 karakter alfanumerik.</p>
                </div>
                <div class="flex gap-3 pt-4 border-t border-white/5">
                    <x-admin-button type="submit" class="flex-1" icon="fas fa-check">
                        Simpan Token
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
