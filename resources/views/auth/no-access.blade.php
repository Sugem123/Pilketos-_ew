@php $page_title = 'Akses Dibatasi'; @endphp
<x-auth-layout>
    <div class="min-h-screen flex items-center justify-center px-4 py-12">
        <div class="max-w-md w-full text-center">
            <div class="glass-panel-dark rounded-3xl p-8 shadow-2xl border border-white/10 text-slate-200">
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-2xl bg-amber-500/10 border border-amber-500/30 text-amber-400 mb-6 text-2xl">
                    <i class="fa-solid fa-desktop"></i>
                </div>
                <h2 class="font-heading font-extrabold text-2xl text-white mb-2">Akses Desktop Diperlukan</h2>
                <p class="text-xs text-slate-400 leading-relaxed mb-6">
                    Panel kontrol dan dashboard administrator Pilketos dirancang untuk penggunaan pada layar desktop/laptop dengan lebar minimum <strong class="text-white font-mono">1024px</strong> demi optimalisasi pengelolaan data.
                </p>
                <div class="p-3 bg-slate-900/80 border border-slate-800 rounded-xl">
                    <p class="text-[11px] text-slate-500 font-medium">Silakan buka tautan ini melalui perangkat komputer Anda.</p>
                </div>
            </div>
        </div>
    </div>
</x-auth-layout>
