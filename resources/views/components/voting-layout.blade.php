<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pilketos | Pilih Calon Ketua OSIS</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <script src="https://kit.fontawesome.com/35d8865ade.js" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="{{ asset('img/logo.png') }}" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Montserrat', sans-serif; }
        [x-cloak] { display: none !important; }

        .swal2-popup {
            font-family: 'Montserrat', sans-serif !important;
            border-radius: 1.5rem !important;
            padding: 2rem !important;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25) !important;
        }
        .swal2-title { color: #1A1A1B !important; font-weight: 700 !important; font-size: 1.875rem !important; margin-bottom: 1rem !important; }
        .swal2-html-container { color: #6B7280 !important; font-size: 1rem !important; line-height: 1.5 !important; margin-bottom: 1.5rem !important; }
        .swal2-confirm { background-color: #1A1A1B !important; color: #FFFFFF !important; border: none !important; border-radius: 0.75rem !important; padding: 0.75rem 2rem !important; font-weight: 600 !important; font-size: 1rem !important; transition: all 0.3s ease !important; box-shadow: none !important; }
        .swal2-confirm:hover { background-color: #374151 !important; transform: translateY(-1px) !important; }
        .swal2-cancel { background-color: #6B7280 !important; color: #FFFFFF !important; border: none !important; border-radius: 0.75rem !important; padding: 0.75rem 2rem !important; font-weight: 600 !important; font-size: 1rem !important; margin-right: 1rem !important; box-shadow: none !important; }
        .swal2-cancel:hover { background-color: #4B5563 !important; }
        .swal2-icon.swal2-success { border-color: #10B981 !important; color: #10B981 !important; }
        .swal2-icon.swal2-error { border-color: #EF4444 !important; color: #EF4444 !important; }
        .swal2-icon.swal2-warning { border-color: #F59E0B !important; color: #F59E0B !important; }
        .swal2-icon.swal2-question { border-color: #3B82F6 !important; color: #3B82F6 !important; }
        body.swal2-shown.token-popup-open>[aria-hidden="true"] { transition: 0.1s filter; filter: blur(3px); }
        .card.selected { border-color: #2f2575 !important; box-shadow: 0 10px 25px -5px rgba(59, 130, 246, 0.4) !important; }
        .card.selected .selection-indicator { opacity: 100; }
    </style>
</head>
<body class="bg-primary font-montserrat">
    {{ $slot }}
</body>
</html>
