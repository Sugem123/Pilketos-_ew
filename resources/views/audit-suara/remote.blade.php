<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-950">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>Wireless Scanner QR | {{ $config['nama_sekolah'] ?? 'PILKETOS' }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@600;700;800;900&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/35d8865ade.js" crossorigin="anonymous"></script>
    <script src="{{ asset('js/html5-qrcode.min.js') }}"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .font-heading { font-family: 'Outfit', sans-serif; }
    </style>
</head>
<body class="bg-slate-950 text-slate-100 min-h-full flex flex-col justify-between antialiased select-none"
      x-data="remoteScanner('{{ $session }}')">

    {{-- Top App Bar --}}
    <header class="p-4 bg-slate-900/90 border-b border-white/10 flex items-center justify-between sticky top-0 z-30 backdrop-blur-md">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-2xl bg-gradient-to-tr from-emerald-500 to-teal-700 flex items-center justify-center text-white shadow-lg shadow-emerald-500/20">
                <i class="fas fa-qrcode text-lg"></i>
            </div>
            <div>
                <h1 class="font-heading font-black text-sm text-white">HP SCANNER REMOTE</h1>
                <p class="text-[11px] text-emerald-400 font-mono flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span>
                    <span>Pairing: {{ $session }}</span>
                </p>
            </div>
        </div>
        <div class="text-right">
            <span class="text-[10px] text-slate-400 block font-mono">Terkirim</span>
            <span class="font-heading font-black text-base text-emerald-400 font-mono" x-text="scannedCount">0</span>
        </div>
    </header>

    {{-- Camera Viewport --}}
    <main class="flex-1 p-4 flex flex-col items-center justify-start gap-4 max-w-md mx-auto w-full">
        <div class="w-full relative rounded-3xl overflow-hidden bg-black border-2 border-emerald-500/40 shadow-2xl shadow-emerald-500/10 aspect-square flex items-center justify-center">
            <div id="reader" class="w-full h-full"></div>

            {{-- Scanning Frame overlay --}}
            <div class="absolute inset-0 pointer-events-none flex flex-col items-center justify-center p-8">
                <div class="w-48 h-48 border-2 border-emerald-400 rounded-2xl relative">
                    <div class="absolute -top-1 -left-1 w-5 h-5 border-t-4 border-l-4 border-emerald-400 rounded-tl-md"></div>
                    <div class="absolute -top-1 -right-1 w-5 h-5 border-t-4 border-r-4 border-emerald-400 rounded-tr-md"></div>
                    <div class="absolute -bottom-1 -left-1 w-5 h-5 border-b-4 border-l-4 border-emerald-400 rounded-bl-md"></div>
                    <div class="absolute -bottom-1 -right-1 w-5 h-5 border-b-4 border-r-4 border-emerald-400 rounded-br-md"></div>
                    <div class="absolute top-1/2 left-0 right-0 h-0.5 bg-gradient-to-r from-transparent via-emerald-400 to-transparent animate-pulse"></div>
                </div>
            </div>

            {{-- Camera Off State --}}
            <div x-show="!cameraActive" class="absolute inset-0 bg-slate-950/95 flex flex-col items-center justify-center p-6 text-center z-20">
                <i class="fas fa-camera text-4xl text-slate-600 mb-3"></i>
                <h3 class="font-heading font-bold text-white text-base mb-1">Kamera Siap Digunakan</h3>
                <p class="text-xs text-slate-400 mb-4">Tekan tombol di bawah untuk mengaktifkan pemindai kartu suara</p>
                <button type="button" @click="startScanner()" class="px-6 py-3 rounded-2xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs shadow-lg shadow-emerald-600/30 flex items-center gap-2">
                    <i class="fas fa-play"></i> Aktifkan Scanner
                </button>
            </div>
        </div>

        {{-- Status Notification Pill --}}
        <div class="w-full">
            <div class="p-3.5 rounded-2xl text-xs font-mono flex items-center justify-between transition-all"
                 :class="lastStatus === 'success' ? 'bg-emerald-500/15 border border-emerald-500/30 text-emerald-300' : lastStatus === 'error' ? 'bg-rose-500/15 border border-rose-500/30 text-rose-300' : 'bg-slate-900 border border-white/10 text-slate-400'">
                <div class="flex items-center gap-2 truncate">
                    <i :class="lastStatus === 'success' ? 'fas fa-circle-check text-emerald-400' : lastStatus === 'error' ? 'fas fa-circle-xmark text-rose-400' : 'fas fa-info-circle text-slate-500'"></i>
                    <span class="truncate" x-text="statusMessage">Arahkan kamera ke QR Code kartu pemilih...</span>
                </div>
                <template x-if="lastToken">
                    <code class="px-2 py-0.5 bg-slate-950 text-amber-400 rounded font-black tracking-widest text-[11px]" x-text="lastToken"></code>
                </template>
            </div>
        </div>

        {{-- Camera Controls --}}
        <div class="flex items-center justify-between w-full gap-2 pt-1">
            <button type="button" @click="toggleCamera()" class="flex-1 py-3 px-4 rounded-2xl bg-slate-900 hover:bg-slate-800 border border-white/10 text-xs font-bold text-slate-300 flex items-center justify-center gap-2">
                <i :class="cameraActive ? 'fas fa-stop text-rose-400' : 'fas fa-play text-emerald-400'"></i>
                <span x-text="cameraActive ? 'Matikan Kamera' : 'Nyalakan Kamera'"></span>
            </button>
            <button type="button" @click="switchCamera()" x-show="cameras.length > 1" class="py-3 px-4 rounded-2xl bg-slate-900 hover:bg-slate-800 border border-white/10 text-xs font-bold text-slate-300 flex items-center gap-2">
                <i class="fas fa-rotate"></i> Ganti Kamera
            </button>
        </div>

        {{-- Recent Sent Cards --}}
        <div class="w-full bg-slate-900/60 border border-white/5 rounded-2xl p-4">
            <h4 class="text-[11px] font-mono font-bold uppercase tracking-wider text-slate-400 mb-2 flex items-center justify-between">
                <span>Riwayat Terkirim</span>
                <span x-text="recentSent.length + ' kartu'"></span>
            </h4>
            <div class="space-y-1.5 max-h-36 overflow-y-auto">
                <template x-for="(item, idx) in recentSent" :key="idx">
                    <div class="flex items-center justify-between px-3 py-1.5 rounded-xl bg-slate-950/80 border border-white/5 text-xs font-mono">
                        <span class="text-slate-400" x-text="item.time"></span>
                        <code class="font-black text-amber-400 tracking-wider" x-text="item.token"></code>
                        <span class="text-emerald-400 font-bold text-[10px]"><i class="fas fa-paper-plane mr-1"></i>TERKIRIM</span>
                    </div>
                </template>
                <div x-show="recentSent.length === 0" class="text-center py-4 text-xs text-slate-600 font-mono">
                    Belum ada kartu suara yang dipindai.
                </div>
            </div>
        </div>
    </main>

    {{-- Footer --}}
    <footer class="p-3 text-center text-[10px] text-slate-500 border-t border-white/5 bg-slate-950">
        PILKETOS Mobile Scanner &bull; Terhubung ke Layar Laptop
    </footer>

    <script>
        function remoteScanner(sessionId) {
            return {
                session: sessionId,
                cameraActive: false,
                html5QrCode: null,
                cameras: [],
                currentCamIndex: 0,
                scannedCount: 0,
                statusMessage: 'Kamera scanner siap.',
                lastStatus: 'ready',
                lastToken: '',
                lastScanTime: 0,
                recentSent: [],

                init() {
                    this.$nextTick(() => {
                        this.startScanner();
                    });
                },

                async startScanner() {
                    try {
                        if (!this.html5QrCode) {
                            this.html5QrCode = new Html5Qrcode("reader");
                        }

                        if (this.cameras.length === 0) {
                            this.cameras = await Html5Qrcode.getCameras();
                            // Pilih kamera belakang jika ada
                            const backIdx = this.cameras.findIndex(c => /back|rear|environment/i.test(c.label));
                            this.currentCamIndex = backIdx >= 0 ? backIdx : 0;
                        }

                        const currentCam = this.cameras[this.currentCamIndex];
                        const camId = currentCam ? currentCam.id : { facingMode: "environment" };

                        await this.html5QrCode.start(
                            camId,
                            { fps: 12, qrbox: { width: 220, height: 220 } },
                            (decodedText) => this.onScanSuccess(decodedText),
                            (errorMessage) => { }
                        );

                        this.cameraActive = true;
                        this.statusMessage = 'Arahkan ke QR Code kartu pemilih...';
                        this.lastStatus = 'ready';
                    } catch (err) {
                        this.cameraActive = false;
                        this.statusMessage = 'Gagal membuka kamera: ' + (err.message || err);
                        this.lastStatus = 'error';
                    }
                },

                async stopScanner() {
                    if (this.html5QrCode && this.cameraActive) {
                        try {
                            await this.html5QrCode.stop();
                        } catch (e) { }
                        this.cameraActive = false;
                    }
                },

                async toggleCamera() {
                    if (this.cameraActive) {
                        await this.stopScanner();
                    } else {
                        await this.startScanner();
                    }
                },

                async switchCamera() {
                    if (this.cameras.length <= 1) return;
                    await this.stopScanner();
                    this.currentCamIndex = (this.currentCamIndex + 1) % this.cameras.length;
                    await this.startScanner();
                },

                async onScanSuccess(decodedText) {
                    const token = decodedText.trim().toUpperCase();
                    const now = Date.now();

                    // Cooldown 2.5 detik per token yang sama
                    if (token === this.lastToken && (now - this.lastScanTime) < 2500) {
                        return;
                    }

                    this.lastToken = token;
                    this.lastScanTime = now;

                    // Haptic feedback jika didukung smartphone
                    if (navigator.vibrate) {
                        navigator.vibrate([60, 40, 60]);
                    }

                    this.statusMessage = `Mengirim token ${token} ke laptop...`;
                    this.lastStatus = 'ready';

                    try {
                        const res = await fetch('{{ route("audit-suara.remote-push") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                session_id: this.session,
                                token: token
                            })
                        });

                        const data = await res.json();

                        if (data.success) {
                            this.scannedCount++;
                            this.statusMessage = `Berhasil dikirim ke laptop: ${token}`;
                            this.lastStatus = 'success';
                            this.recentSent.unshift({
                                token: token,
                                time: new Date().toLocaleTimeString()
                            });
                            if (this.recentSent.length > 20) this.recentSent.pop();
                        } else {
                            this.statusMessage = data.message || 'Gagal mengirim token.';
                            this.lastStatus = 'error';
                        }
                    } catch (e) {
                        this.statusMessage = 'Gagal menghubungi server.';
                        this.lastStatus = 'error';
                    }
                }
            };
        }
    </script>
</body>
</html>
