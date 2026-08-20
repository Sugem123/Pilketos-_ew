@props(['page_title' => 'Dashboard', 'page_description' => null])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-950">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $page_title }} | Pilketos Admin Portal</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800;900&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/35d8865ade.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css">
    <link rel="icon" type="image/x-icon" href="{{ asset('img/logo.png') }}" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .font-heading { font-family: 'Outfit', sans-serif; }
        [x-cloak] { display: none !important; }

        /* Custom Modern Scrollbars */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #334155; border-radius: 9999px; }
        ::-webkit-scrollbar-thumb:hover { background: #475569; }
    </style>
</head>
<body class="h-full luxury-ambient text-slate-100 font-sans antialiased selection:bg-indigo-500 selection:text-white" x-data="{ sidebarOpen: false }">
    <div class="flex h-screen overflow-hidden">
        @include('components.sidebar')

        <div class="flex-1 flex flex-col overflow-hidden lg:ml-64 relative">
            <x-topbar :page_title="$page_title" :page_description="$page_description">
                {{ $actions ?? '' }}
            </x-topbar>

            <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8 bg-transparent">
                <x-notifications />
                <div class="max-w-7xl mx-auto space-y-8 pb-12">
                    {{ $slot }}
                </div>
            </main>
        </div>
    </div>

    <x-confirm-modal />

    <style>
        .notyf__toast {
            border-radius: 1.25rem !important;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.7) !important;
            padding: 0.875rem 1.5rem !important;
            min-width: 320px !important;
            background: rgba(15, 23, 42, 0.95) !important;
            backdrop-filter: blur(16px) !important;
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
        }
        .notyf__message {
            font-family: 'Plus Jakarta Sans', sans-serif !important;
            font-size: 0.875rem !important;
            font-weight: 600 !important;
            color: #ffffff !important;
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const notyf = new Notyf({
                duration: 4000,
                position: { x: 'right', y: 'top' },
                dismissible: true,
                types: [
                    {
                        type: 'success',
                        background: '#0f172a',
                        icon: {
                            className: 'fas fa-check-circle',
                            tagName: 'i',
                            color: '#10b981'
                        }
                    },
                    {
                        type: 'error',
                        background: '#0f172a',
                        icon: {
                            className: 'fas fa-triangle-exclamation',
                            tagName: 'i',
                            color: '#ef4444'
                        }
                    }
                ]
            });

            @if(session('success'))
                notyf.success('{{ session('success') }}');
            @endif
            @if(session('error'))
                notyf.error('{{ session('error') }}');
            @endif
        });
    </script>
</body>
</html>
