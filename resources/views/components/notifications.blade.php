<div x-data="{ notifications: [] }"
     x-init="
        @if(session('success'))
            notifications.push({ type: 'success', message: '{{ session('success') }}' });
        @endif
        @if(session('error'))
            notifications.push({ type: 'error', message: '{{ session('error') }}' });
        @endif

        setTimeout(() => notifications = [], 5000);
     "
     class="fixed top-4 right-4 z-50 space-y-2">
    <template x-for="(notification, index) in notifications" :key="index">
        <div x-show="true"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-x-8"
             x-transition:enter-end="opacity-100 translate-x-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-x-0"
             x-transition:leave-end="opacity-0 translate-x-8"
             :class="notification.type === 'success' ? 'bg-green-500' : 'bg-red-500'"
             class="text-white px-6 py-4 rounded-xl shadow-lg flex items-center gap-3 min-w-[300px]">
            <template x-if="notification.type === 'success'">
                <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </template>
            <template x-if="notification.type === 'error'">
                <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </template>
            <span x-text="notification.message" class="font-medium text-sm"></span>
        </div>
    </template>
</div>
