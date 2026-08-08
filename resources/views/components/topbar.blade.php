<header class="bg-white shadow-sm border-b border-gray-200 h-16 flex items-center justify-between px-6">
    <div class="flex items-center gap-4">
        <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden text-accent">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
        </button>
        <h1 class="text-xl font-bold text-accent">{{ $page_title ?? 'Dashboard' }}</h1>
    </div>
    <div class="flex items-center gap-4">
        <span class="text-sm text-gray-500">{{ now()->format('d M Y, H:i') }}</span>
    </div>
</header>
