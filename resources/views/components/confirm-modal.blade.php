<div x-data="{
    show: false,
    title: '',
    message: '',
    confirmText: 'Ya, Hapus',
    cancelText: 'Batal',
    onConfirm: null,
    open(config) {
        this.title = config.title || 'Konfirmasi';
        this.message = config.message || 'Apakah Anda yakin?';
        this.confirmText = config.confirmText || 'Ya, Hapus';
        this.cancelText = config.cancelText || 'Batal';
        this.onConfirm = config.onConfirm;
        this.show = true;
    },
    close() {
        this.show = false;
    },
    confirm() {
        if (this.onConfirm) this.onConfirm();
        this.close();
    }
}" class="relative">
    <template x-teleport="body">
        <div x-show="show" x-cloak
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-[100] flex items-center justify-center p-4">
            <div class="absolute inset-0 bg-black/50" @click="close()"></div>
            <div class="relative bg-white rounded-2xl shadow-2xl max-w-md w-full p-6"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95">
                <div class="text-center">
                    <div class="mx-auto flex items-center justify-center h-14 w-14 rounded-full bg-red-100 mb-4">
                        <svg class="h-7 w-7 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2" x-text="title"></h3>
                    <p class="text-sm text-gray-500 mb-6" x-text="message"></p>
                    <div class="flex gap-3 justify-center">
                        <button @click="close()" class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 rounded-xl hover:bg-gray-200 transition-colors" x-text="cancelText"></button>
                        <button @click="confirm()" class="px-5 py-2.5 text-sm font-medium text-white bg-red-600 rounded-xl hover:bg-red-700 transition-colors" x-text="confirmText"></button>
                    </div>
                </div>
            </div>
        </div>
    </template>
</div>
