<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $page_title ?? 'Dashboard' }} | Pilketos v2.0</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <script src="https://kit.fontawesome.com/35d8865ade.js" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
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
            @include('components.topbar')

            <main class="flex-1 overflow-y-auto p-6 bg-gray-50">
                <x-notifications />
                {{ $slot }}
            </main>
        </div>
    </div>

    <x-confirm-modal />
</body>
</html>
