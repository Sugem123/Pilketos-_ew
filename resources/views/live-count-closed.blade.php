<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-950">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Live Count Ditutup Sementara | {{ $config['nama_sekolah'] ?? 'PILKETOS' }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@600;700;800;900&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/35d8865ade.js" crossorigin="anonymous"></script>
    <link rel="icon" type="image/x-icon" href="{{ asset('img/logo.png') }}" />
    @vite(['resources/css/app.css'])
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .font-heading { font-family: 'Outfit', sans-serif; }
        .ambient-grid {
            background-size: 50px 50px;
            background-image: 
                linear-gradient(to right, rgba(255, 255, 255, 0.03) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(255, 255, 255, 0.03) 1px, transparent 1px);
        }
    </style>
</head>
<body class="ambient-mesh-voting text-slate-100 min-h-screen flex flex-col justify-between p-4 sm:p-8 antialiased overflow-x-hidden relative ambient-grid select-none">

    {{-- Header --}}
    <header class="w-full max-w-4xl mx-auto flex items-center justify-between glass-panel-dark rounded-3xl px-6 py-4 border border-white/10 shadow-2xl">
        <div class="flex items-center gap-3.5">
            <div class="w-10 h-10 rounded-2xl bg-gradient-to-tr from-indigo-600 to-indigo-400 flex items-center justify-center p-2 shadow-lg shadow-indigo-500/30">
                <img src="{{ !empty($config['url_logo']) ? asset($config['url_logo']) : asset('img/logo.png') }}" alt="Logo" class="w-full h-full object-contain">
            </div>
            <div>
                <h1 class="font-heading font-black text-sm sm:text-base text-white uppercase tracking-tight">
                    {{ $config['nama_sekolah'] ?? 'PILKETOS' }}
                </h1>
                <p class="text-[11px] text-slate-400 font-mono">Live Counting TPS</p>
            </div>
        </div>
        <a href="{{ url('/') }}" class="px-4 py-2 rounded-xl bg-slate-900 hover:bg-slate-800 border border-white/10 text-xs font-bold text-slate-300 hover:text-white transition-all">
            <i class="fa-solid fa-arrow-left mr-1"></i> Beranda
        </a>
    </header>

    {{-- Main State --}}
    <main class="w-full max-w-lg mx-auto my-auto text-center p-6">
        <div class="glass-panel-dark rounded-3xl p-8 sm:p-10 border border-amber-500/30 shadow-2xl shadow-amber-500/10 relative overflow-hidden">
            <div class="w-20 h-20 rounded-3xl bg-amber-500/15 border border-amber-500/30 text-amber-400 flex items-center justify-center mx-auto mb-5 text-3xl shadow-xl animate-pulse">
                <i class="fa-solid fa-lock"></i>
            </div>

            <span class="inline-block px-3 py-1 rounded-full bg-amber-500/20 text-amber-300 border border-amber-500/30 text-[10px] font-mono font-bold uppercase tracking-widest mb-3">
                Siaran Ditutup Sementara
            </span>

            <h2 class="font-heading font-black text-2xl sm:text-3xl text-white tracking-tight mb-3">
                Live Count Ditutup
            </h2>

            <p class="text-xs sm:text-sm text-slate-300 leading-relaxed bg-slate-950/60 border border-white/5 rounded-2xl p-5 mb-6 shadow-inner font-normal">
                {{ $config['livecount_closed_message'] ?? 'Perolehan suara langsung (Live Count) ditutup sementara oleh panitia dan akan dibuka kembali saat pleno pengumuman resmi.' }}
            </p>

            <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
                <a href="{{ url('/') }}"
                   class="w-full sm:w-auto px-6 py-3 rounded-2xl bg-slate-900 hover:bg-slate-800 border border-white/10 text-slate-300 hover:text-white font-heading font-bold text-xs transition-all">
                    <i class="fa-solid fa-arrow-left mr-1.5"></i> Beranda
                </a>
                <button type="button" onclick="openAdminModal()"
                        class="w-full sm:w-auto px-6 py-3 rounded-2xl bg-gradient-to-r from-indigo-600 to-indigo-500 hover:from-indigo-500 hover:to-indigo-600 text-white font-heading font-bold text-xs shadow-lg shadow-indigo-600/30 transition-all cursor-pointer">
                    <i class="fa-solid fa-user-shield mr-1.5"></i> Login Admin / Pengawas
                </button>
            </div>
        </div>
    </main>

    {{-- ====== MODAL LOGIN ADMIN / PENGAWAS DI HP ====== --}}
    <div id="admin-login-modal" class="fixed inset-0 z-50 bg-slate-950/90 backdrop-blur-md hidden items-center justify-center p-4">
        <div class="w-full max-w-sm bg-slate-900 border border-indigo-500/30 rounded-3xl p-6 sm:p-8 shadow-2xl relative text-left">
            <button type="button" onclick="closeAdminModal()" class="absolute top-5 right-5 text-slate-400 hover:text-white p-2 rounded-xl bg-slate-800">
                <i class="fas fa-times text-sm"></i>
            </button>

            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 rounded-2xl bg-indigo-500/20 text-indigo-400 border border-indigo-500/30 flex items-center justify-center text-lg shadow-inner">
                    <i class="fas fa-user-shield"></i>
                </div>
                <div>
                    <h3 class="font-heading font-black text-base text-white">Login Petugas / Admin</h3>
                    <p class="text-[11px] text-slate-400 font-mono">Buka preview Live Count di HP</p>
                </div>
            </div>

            <div id="login-error-alert" class="hidden mb-4 p-3 rounded-xl bg-rose-500/15 border border-rose-500/30 text-rose-300 text-xs font-semibold"></div>

            <form id="admin-login-form" onsubmit="submitAdminLogin(event)" class="space-y-4">
                @csrf
                <input type="hidden" name="redirect_to" value="/live-count">

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5 font-mono">Username / Email</label>
                    <input type="text" id="login-email" name="email" required autofocus
                           placeholder="admin@gmail.com"
                           class="w-full px-4 py-3 bg-slate-950 border border-white/10 rounded-2xl text-xs text-white outline-none focus:border-indigo-500 transition-colors">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5 font-mono">Password</label>
                    <input type="password" id="login-password" name="password" required
                           placeholder="&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;"
                           class="w-full px-4 py-3 bg-slate-950 border border-white/10 rounded-2xl text-xs text-white outline-none focus:border-indigo-500 transition-colors">
                </div>

                <button type="submit" id="btn-submit-login"
                        class="w-full py-3.5 rounded-2xl bg-gradient-to-r from-indigo-600 to-indigo-500 hover:from-indigo-500 hover:to-indigo-600 text-white font-heading font-bold text-xs tracking-wider shadow-lg shadow-indigo-600/30 flex items-center justify-center gap-2 cursor-pointer transition-all">
                    <i class="fas fa-unlock"></i>
                    <span>Masuk &amp; Buka Live Count</span>
                </button>
            </form>
        </div>
    </div>

    <script>
        function openAdminModal() {
            const m = document.getElementById('admin-login-modal');
            m.classList.remove('hidden');
            m.classList.add('flex');
            setTimeout(() => {
                document.getElementById('login-email').focus();
            }, 100);
        }

        function closeAdminModal() {
            const m = document.getElementById('admin-login-modal');
            m.classList.add('hidden');
            m.classList.remove('flex');
        }

        async function submitAdminLogin(e) {
            e.preventDefault();
            const btn = document.getElementById('btn-submit-login');
            const errAlert = document.getElementById('login-error-alert');
            const email = document.getElementById('login-email').value.trim();
            const password = document.getElementById('login-password').value;

            errAlert.classList.add('hidden');
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> <span>Memverifikasi...</span>';

            try {
                const res = await fetch('{{ route('login') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        email: email,
                        password: password,
                        redirect_to: '/live-count'
                    })
                });

                const data = await res.json();

                if (res.ok && data.success) {
                    window.location.href = data.redirect || '/live-count';
                } else {
                    btn.disabled = false;
                    btn.innerHTML = '<i class="fas fa-unlock"></i> <span>Masuk &amp; Buka Live Count</span>';
                    errAlert.textContent = data.message || 'Username/Email atau password salah.';
                    errAlert.classList.remove('hidden');
                }
            } catch (err) {
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-unlock"></i> <span>Masuk &amp; Buka Live Count</span>';
                errAlert.textContent = 'Gagal menghubungi server. Periksa koneksi internet.';
                errAlert.classList.remove('hidden');
            }
        }
    </script>

    {{-- Footer --}}
    <footer class="w-full max-w-4xl mx-auto text-center py-4 text-xs text-slate-500 border-t border-white/5">
        &copy; {{ date('Y') }} {{ $config['nama_sekolah'] ?? 'PILKETOS' }} &bull; Panitia Pelaksana Pemilihan Umum OSIS &amp; MPK
    </footer>

</body>
</html>
