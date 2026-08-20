<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pilketos | Pemilihan Ketua OSIS</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/35d8865ade.js" crossorigin="anonymous"></script>
    <link rel="icon" type="image/x-icon" href="{{ asset('img/logo.png') }}" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .font-heading { font-family: 'Outfit', sans-serif; }
        [x-cloak] { display: none !important; }

        /* Prevent SweetAlert2 scroll lock jump */
        body.swal2-shown { padding-right: 0 !important; }

        .swal2-popup {
            font-family: 'Plus Jakarta Sans', sans-serif !important;
            background: #111827 !important;
            color: #f3f4f6 !important;
            border-radius: 1.5rem !important;
            padding: 2.25rem !important;
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
            box-shadow: 0 25px 60px -15px rgba(0, 0, 0, 0.7) !important;
        }
        .swal2-title { color: #ffffff !important; font-weight: 700 !important; font-size: 1.75rem !important; font-family: 'Outfit', sans-serif !important; margin-bottom: 0.75rem !important; }
        .swal2-html-container { color: #9ca3af !important; font-size: 1rem !important; line-height: 1.6 !important; margin-bottom: 1.5rem !important; }
        .swal2-input {
            background-color: #1f2937 !important;
            color: #f9fafb !important;
            border: 1px solid #374151 !important;
            border-radius: 0.875rem !important;
            padding: 0.875rem 1.25rem !important;
            font-size: 1rem !important;
            box-shadow: none !important;
        }
        .swal2-input:focus {
            border-color: #6366f1 !important;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.25) !important;
        }
        .swal2-input-label {
            color: #e5e7eb !important;
            font-size: 0.875rem !important;
            font-weight: 600 !important;
            text-align: left !important;
            margin-bottom: 0.5rem !important;
        }
        .swal2-confirm {
            background: linear-gradient(135deg, #4f46e5 0%, #3730a3 100%) !important;
            color: #ffffff !important;
            border: none !important;
            border-radius: 0.875rem !important;
            padding: 0.875rem 2.25rem !important;
            font-weight: 600 !important;
            font-size: 0.95rem !important;
            letter-spacing: 0.02em !important;
            transition: all 0.25s ease !important;
            box-shadow: 0 10px 25px -5px rgba(79, 70, 229, 0.4) !important;
        }
        .swal2-confirm:hover {
            transform: translateY(-2px) !important;
            box-shadow: 0 15px 30px -5px rgba(79, 70, 229, 0.6) !important;
        }
        .swal2-cancel {
            background: #374151 !important;
            color: #e5e7eb !important;
            border: none !important;
            border-radius: 0.875rem !important;
            padding: 0.875rem 2rem !important;
            font-weight: 600 !important;
            font-size: 0.95rem !important;
            margin-right: 0.75rem !important;
            transition: all 0.25s ease !important;
        }
        .swal2-cancel:hover {
            background: #4b5563 !important;
        }
        .swal2-icon.swal2-success { border-color: #10B981 !important; color: #10B981 !important; }
        .swal2-icon.swal2-error { border-color: #EF4444 !important; color: #EF4444 !important; }
        .swal2-icon.swal2-warning { border-color: #F59E0B !important; color: #F59E0B !important; }
        .swal2-icon.swal2-question { border-color: #6366f1 !important; color: #6366f1 !important; }
        
        body.swal2-shown.token-popup-open>[aria-hidden="true"] { transition: 0.2s filter; filter: blur(6px); }
    </style>
</head>
<body class="ambient-mesh-voting text-slate-100 min-h-screen selection:bg-indigo-500 selection:text-white antialiased">
    {{ $slot }}
</body>
</html>
