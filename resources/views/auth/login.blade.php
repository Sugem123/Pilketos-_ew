@php
    $page_title = 'Login Administrator';
    $configPath = base_path('config.json');
    $loginConfig = file_exists($configPath) ? json_decode(file_get_contents($configPath), true) : [];
    $schoolLogo = !empty($loginConfig['url_logo']) ? asset($loginConfig['url_logo']) : asset('img/logo.png');
    $schoolName = $loginConfig['nama_sekolah'] ?? 'PILKETOS';
@endphp
<x-auth-layout>
    <div class="min-h-screen flex items-center justify-center px-4 py-12 relative overflow-hidden">
        
        {{-- Background Glow Orbs --}}
        <div class="absolute top-1/4 left-1/2 -translate-x-1/2 -translate-y-1/2 w-96 h-96 bg-indigo-600/15 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute bottom-1/4 right-1/4 w-72 h-72 bg-amber-500/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="max-w-md w-full relative z-10">
            {{-- Brand Presentation Header --}}
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-20 h-20 rounded-3xl bg-slate-900/90 border border-white/10 shadow-2xl p-4 mb-4 ring-4 ring-white/5 relative group">
                    <img src="{{ $schoolLogo }}" alt="Logo" class="w-full h-full object-contain filter drop-shadow-md">
                </div>
                <h1 class="font-heading font-black text-2xl lg:text-3xl text-white tracking-tight uppercase">
                    {{ $schoolName }}
                </h1>
                <div class="flex items-center justify-center gap-2 mt-1">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                    <p class="text-xs text-slate-400 font-mono font-bold tracking-widest uppercase">E-VOTING CONTROL PORTAL</p>
                </div>
            </div>

            {{-- Ultra-Luxury Glass Login Card --}}
            <div class="luxury-card rounded-3xl p-8 sm:p-10 shadow-2xl relative overflow-hidden">
                <div class="mb-6 text-center">
                    <h2 class="font-heading font-bold text-lg text-white">Autentikasi Administrator</h2>
                    <p class="text-xs text-slate-400 mt-0.5">Akses terbatas untuk panitia dan pengawas pemilihan</p>
                </div>

                <form method="POST" action="{{ route('login') }}" class="space-y-5">
                    @csrf

                    @if($errors->any())
                        <div class="bg-rose-500/10 border border-rose-500/30 text-rose-400 px-4 py-3 rounded-2xl text-xs font-semibold flex items-center gap-2.5">
                            <i class="fa-solid fa-triangle-exclamation text-sm flex-shrink-0"></i>
                            <span>{{ $errors->first() }}</span>
                        </div>
                    @endif

                    <div>
                        <label for="email" class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5 font-mono">Username / Email</label>
                        <div class="relative">
                            <i class="fas fa-user-shield absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 text-xs"></i>
                            <input type="text" id="email" name="email" value="{{ old('email') }}" required autofocus
                                   class="w-full pl-11 pr-4 py-3.5 luxury-input rounded-2xl outline-none text-sm font-semibold placeholder:text-slate-600 font-mono"
                                   placeholder="admin atau email">
                        </div>
                    </div>

                    <div>
                        <label for="password" class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5 font-mono">Password</label>
                        <div class="relative">
                            <i class="fas fa-lock-keyhole absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 text-xs"></i>
                            <input type="password" id="password" name="password" required
                                   class="w-full pl-11 pr-4 py-3.5 luxury-input rounded-2xl outline-none text-sm font-semibold placeholder:text-slate-600"
                                   placeholder="••••••••">
                        </div>
                    </div>

                    <button type="submit" class="w-full luxury-btn-primary text-white py-4 rounded-2xl font-heading font-black text-sm tracking-wider transition-all duration-300 active:scale-[0.98] cursor-pointer flex items-center justify-center gap-2">
                        <i class="fas fa-right-to-bracket text-xs"></i>
                        <span>MASUK CONTROL CENTER</span>
                    </button>
                </form>

                <div class="mt-8 pt-6 border-t border-white/5 text-center flex items-center justify-between text-[11px] text-slate-500">
                    <a href="{{ route('voting.index') }}" class="hover:text-indigo-400 transition-colors font-medium">
                        &larr; Bilik Suara
                    </a>
                    <span>&copy; {{ date('Y') }} PILKETOS</span>
                </div>
            </div>
        </div>
    </div>
</x-auth-layout>
