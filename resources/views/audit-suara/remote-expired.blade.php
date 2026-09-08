<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-950">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sesi Pairing Berakhir | PILKETOS</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/35d8865ade.js" crossorigin="anonymous"></script>
    @vite(['resources/css/app.css'])
</head>
<body class="bg-slate-950 text-slate-100 min-h-full flex items-center justify-center p-6 text-center">
    <div class="max-w-sm w-full bg-slate-900/90 border border-white/10 rounded-3xl p-8 shadow-2xl">
        <div class="w-16 h-16 rounded-2xl bg-amber-500/10 text-amber-400 border border-amber-500/20 flex items-center justify-center mx-auto mb-4 text-2xl">
            <i class="fas fa-qrcode"></i>
        </div>
        <h2 class="text-xl font-bold text-white mb-2">Sesi Pairing Berakhir</h2>
        <p class="text-xs text-slate-400 leading-relaxed mb-6">
            Sesi remote scanner ini sudah kadaluarsa atau laptop telah membuat sesi baru. Silakan scan ulang QR Code Pairing di layar laptop Anda.
        </p>
        <a href="{{ url('/') }}" class="inline-block px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold">
            Kembali ke Beranda
        </a>
    </div>
</body>
</html>
