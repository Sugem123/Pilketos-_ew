@php
    $currentRoute = request()->route()->getName();
    $configPath = base_path('config.json');
    $appConfig = file_exists($configPath) ? json_decode(file_get_contents($configPath), true) : [];
    $appLogo = !empty($appConfig['url_logo']) ? asset($appConfig['url_logo']) : asset('img/logo_white.png');
    $schoolName = $appConfig['nama_sekolah'] ?? 'PILKETOS';
@endphp

<aside class="hidden lg:flex lg:flex-col lg:w-64 lg:fixed lg:inset-y-0 bg-slate-900 border-r border-slate-800/80 shadow-2xl z-30">
    {{-- Brand Header --}}
    <div class="flex items-center gap-3 px-6 h-20 bg-slate-950/60 border-b border-slate-800/80">
        <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-indigo-600 to-indigo-400 flex items-center justify-center p-2 shadow-lg shadow-indigo-500/20">
            <img src="{{ $appLogo }}" alt="Logo" class="h-6 w-6 object-contain">
        </div>
        <div class="min-w-0 flex-1">
            <h1 class="text-white font-heading font-extrabold text-base tracking-tight leading-none truncate" title="{{ $schoolName }}">
                {{ $schoolName }}
            </h1>
            <p class="text-[10px] font-medium text-slate-400 mt-1 font-mono">Control Center</p>
        </div>
    </div>

    {{-- Nav Links --}}
    <nav class="flex-1 px-4 py-6 space-y-1.5 overflow-y-auto">
        <div class="px-3 pb-2 text-[10px] font-bold uppercase tracking-wider text-slate-400 font-mono">Menu Utama</div>

        <a href="{{ route('dashboard') }}"
            class="flex items-center gap-3 px-3.5 py-2.5 text-sm font-medium rounded-xl transition-all {{ $currentRoute === 'dashboard' ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/30' : 'text-slate-400 hover:text-white hover:bg-slate-800/60' }}">
            <i class="fas fa-chart-pie w-5 text-center text-sm {{ $currentRoute === 'dashboard' ? 'text-white' : 'text-indigo-400' }}"></i>
            Dashboard
        </a>

        <a href="{{ route('calon.index') }}"
            class="flex items-center gap-3 px-3.5 py-2.5 text-sm font-medium rounded-xl transition-all {{ str_starts_with($currentRoute, 'calon') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/30' : 'text-slate-400 hover:text-white hover:bg-slate-800/60' }}">
            <i class="fas fa-users-viewfinder w-5 text-center text-sm {{ str_starts_with($currentRoute, 'calon') ? 'text-white' : 'text-indigo-400' }}"></i>
            Data Calon
        </a>

        <a href="{{ route('hak-suara.index') }}"
            class="flex items-center gap-3 px-3.5 py-2.5 text-sm font-medium rounded-xl transition-all {{ str_starts_with($currentRoute, 'hak-suara') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/30' : 'text-slate-400 hover:text-white hover:bg-slate-800/60' }}">
            <i class="fas fa-id-card-clip w-5 text-center text-sm {{ str_starts_with($currentRoute, 'hak-suara') ? 'text-white' : 'text-indigo-400' }}"></i>
            Hak Suara (DPT)
        </a>

        <a href="{{ route('tokens.index') }}"
            class="flex items-center gap-3 px-3.5 py-2.5 text-sm font-medium rounded-xl transition-all {{ str_starts_with($currentRoute, 'tokens') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/30' : 'text-slate-400 hover:text-white hover:bg-slate-800/60' }}">
            <i class="fas fa-key w-5 text-center text-sm {{ str_starts_with($currentRoute, 'tokens') ? 'text-white' : 'text-indigo-400' }}"></i>
            Display Token
        </a>

        <a href="{{ route('audit-suara.index') }}"
            class="flex items-center gap-3 px-3.5 py-2.5 text-sm font-medium rounded-xl transition-all {{ str_starts_with($currentRoute, 'audit-suara') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/30' : 'text-slate-400 hover:text-white hover:bg-slate-800/60' }}">
            <i class="fas fa-check-double w-5 text-center text-sm {{ str_starts_with($currentRoute, 'audit-suara') ? 'text-white' : 'text-indigo-400' }}"></i>
            Audit Suara Manual
        </a>

        <div class="pt-5 px-3 pb-2 text-[10px] font-bold uppercase tracking-wider text-slate-400 font-mono">Sistem & Live</div>

        <a href="{{ route('live-count') }}" target="_blank"
            class="flex items-center justify-between px-3.5 py-2.5 text-sm font-medium rounded-xl text-slate-400 hover:text-white hover:bg-slate-800/60 transition-all group">
            <div class="flex items-center gap-3">
                <i class="fas fa-tower-broadcast w-5 text-center text-sm text-rose-400"></i>
                <span>Live Count (Proyektor)</span>
            </div>
            <span class="text-[10px] bg-rose-500/20 text-rose-300 group-hover:bg-rose-500 group-hover:text-white px-2 py-0.5 rounded font-mono font-bold">TPS</span>
        </a>

        <a href="{{ route('admin-config.index') }}"
            class="flex items-center gap-3 px-3.5 py-2.5 text-sm font-medium rounded-xl transition-all {{ str_starts_with($currentRoute, 'admin-config') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/30' : 'text-slate-400 hover:text-white hover:bg-slate-800/60' }}">
            <i class="fas fa-user-shield w-5 text-center text-sm {{ str_starts_with($currentRoute, 'admin-config') ? 'text-white' : 'text-indigo-400' }}"></i>
            Akun Admin
        </a>

        <a href="{{ route('voting.index') }}" target="_blank"
            class="flex items-center justify-between px-3.5 py-2.5 text-sm font-medium rounded-xl text-slate-400 hover:text-white hover:bg-slate-800/60 transition-all group">
            <div class="flex items-center gap-3">
                <i class="fas fa-arrow-up-right-from-square w-5 text-center text-sm text-amber-400"></i>
                <span>Bilik Suara</span>
            </div>
            <span class="text-[10px] bg-slate-800 group-hover:bg-slate-700 px-2 py-0.5 rounded text-slate-400 font-mono">Live</span>
        </a>
    </nav>

    {{-- User Footer --}}
    <div class="p-4 bg-slate-950/60 border-t border-slate-800/80">
        <div class="flex items-center gap-3 mb-3 p-2 rounded-xl bg-slate-900/80 border border-slate-800">
            <div class="w-9 h-9 bg-gradient-to-tr from-indigo-500 to-indigo-700 rounded-lg flex items-center justify-center font-bold text-white text-sm shadow-md">
                {{ substr(auth()->user()->nama_lengkap ?? 'A', 0, 1) }}
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-xs font-bold text-white truncate">{{ auth()->user()->nama_lengkap ?? 'Admin' }}</p>
                <p class="text-[11px] text-slate-400 truncate">{{ auth()->user()->email ?? 'admin@system.local' }}</p>
            </div>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full inline-flex items-center justify-center gap-2 px-3 py-2 text-xs font-semibold rounded-xl bg-rose-500/10 text-rose-400 hover:bg-rose-500 hover:text-white transition-all cursor-pointer border border-rose-500/20">
                <i class="fas fa-power-off text-xs"></i>
                Keluar Sesi
            </button>
        </form>
    </div>
</aside>
