@php $page_title = 'Akses Dibatasi'; @endphp
<x-auth-layout>
    <div class="min-h-screen flex items-center justify-center px-4">
        <div class="max-w-md w-full text-center">
            <div class="bg-white rounded-2xl shadow-lg p-8">
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-yellow-100 mb-6">
                    <svg class="h-8 w-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
                </div>
                <h2 class="text-xl font-bold text-accent mb-3">Akses Dibatasi</h2>
                <p class="text-gray-500 mb-6">Halaman ini hanya dapat diakses dari desktop/laptop dengan lebar layar minimal 1024px.</p>
                <p class="text-sm text-gray-400">Silakan buka di perangkat yang lebih besar untuk mengakses halaman admin.</p>
            </div>
        </div>
    </div>
</x-auth-layout>
