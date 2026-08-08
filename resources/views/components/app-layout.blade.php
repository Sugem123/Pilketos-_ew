@props(['page_title' => 'Dashboard', 'page_description' => null])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $page_title }} | Pilketos v2.0</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <script src="https://kit.fontawesome.com/35d8865ade.js" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css">
    <link rel="icon" type="image/x-icon" href="{{ asset('img/logo.png') }}" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Montserrat', sans-serif; }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-gray-50 font-sans" x-data="{ sidebarOpen: false }">
    <div class="flex h-screen overflow-hidden">
        @include('components.sidebar')

        <div class="flex-1 flex flex-col overflow-hidden lg:ml-64">
            <x-topbar :page_title="$page_title" :page_description="$page_description">
                {{ $actions ?? '' }}
            </x-topbar>

            <main class="flex-1 overflow-y-auto p-6 bg-gray-50">
                <x-notifications />
                {{ $slot }}
            </main>
        </div>
    </div>

    <x-confirm-modal />

    <style>
        .notyf__toast {
            border-radius: 0.75rem !important;
            box-shadow: 0 10px 25px -5px rgba(0,0,0,0.1), 0 4px 10px -5px rgba(0,0,0,0.05) !important;
            padding: 0.875rem 1.25rem !important;
            min-width: 280px !important;
        }
        .notyf__message {
            font-family: 'Montserrat', sans-serif !important;
            font-size: 0.875rem !important;
            font-weight: 500 !important;
            color: #111827 !important;
        }
        .notyf__icon {
            margin-right: 0.625rem !important;
        }
        .notyf__dismiss {
            background: #f3f4f6 !important;
            border-radius: 0.5rem !important;
        }
        .notyf__dismiss-btn::before,
        .notyf__dismiss-btn::after {
            background: #6b7280 !important;
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const notyf = new Notyf({
                duration: 4000,
                position: { x: 'center', y: 'top' },
                dismissible: true,
                types: [
                    {
                        type: 'success',
                        background: '#ffffff',
                        icon: {
                            className: 'fas fa-check-circle',
                            tagName: 'i',
                            color: '#9ca3af'
                        }
                    },
                    {
                        type: 'error',
                        background: '#ffffff',
                        icon: {
                            className: 'fas fa-times-circle',
                            tagName: 'i',
                            color: '#9ca3af'
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
