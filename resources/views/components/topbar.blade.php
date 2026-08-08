@props(['page_title' => 'Dashboard', 'page_description' => null])

<header class="bg-white shadow-sm border-b border-gray-200 h-16 flex items-center justify-between px-6">
    <div class="flex items-center gap-4">
        <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden text-accent">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
        </button>
        <div>
            <h1 class="text-xl font-bold text-accent">{{ $page_title }}</h1>
            @if($page_description)
                <p class="text-xs text-gray-500">{{ $page_description }}</p>
            @endif
        </div>
    </div>
    <div class="flex items-center gap-3">
        {{ $slot }}
    </div>
</header>
