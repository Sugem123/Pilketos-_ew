@props(['page_title' => 'Dashboard', 'page_description' => null])

@php
    $configPath = base_path('config.json');
    $topConfig = file_exists($configPath) ? json_decode(file_get_contents($configPath), true) : [];
    $schoolTitle = $topConfig['nama_sekolah'] ?? 'PILKETOS';
@endphp

<header class="luxury-glass border-b border-white/5 sticky top-0 z-20 h-20 flex items-center justify-between px-6 lg:px-8">
    <div class="flex items-center gap-4">
        <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden w-10 h-10 rounded-2xl bg-slate-900 border border-white/10 flex items-center justify-center text-slate-300 hover:text-white transition-colors">
            <i class="fas fa-bars text-base"></i>
        </button>
        <div>
            <div class="flex items-center gap-2.5">
                <h1 class="font-heading font-black text-lg sm:text-xl lg:text-2xl text-white tracking-tight leading-tight">{{ $page_title }}</h1>
                <span class="hidden md:inline-block px-3 py-0.5 rounded-full text-[10px] font-bold bg-indigo-500/10 text-indigo-300 font-mono border border-indigo-500/20">
                    {{ $schoolTitle }}
                </span>
            </div>
            @if ($page_description)
                <p class="text-xs text-slate-400 font-medium mt-0.5">{{ $page_description }}</p>
            @endif
        </div>
    </div>
    <div class="flex items-center gap-3">
        {{ $slot }}
    </div>
</header>
