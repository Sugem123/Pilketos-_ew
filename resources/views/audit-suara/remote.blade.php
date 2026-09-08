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
            <div class="w-10 h-10 rounded-2xl flex items-center justify-center text-white shadow-lg"
                 :class="deviceStatus === 'approved' ? 'bg-gradient-to-tr from-emerald-500 to-teal-700 shadow-emerald-500/20' : 'bg-slate-800 border border-white/10'">
                <i class="fas fa-qrcode text-lg"></i>
            </div>
            <div>
                <h1 class="font-heading font-black text-sm text-white" x-text="deviceName || 'HP SCANNER REMOTE'"></h1>
                <p class="text-[11px] font-mono flex items-center gap-1.5"
                   :class="deviceStatus === 'approved' ? 'text-emerald-400' : deviceStatus === 'pending' ? 'text-amber-400' : 'text-slate-400'">
                    <span class="w-2 h-2 rounded-full" :class="deviceStatus === 'approved' ? 'bg-emerald-400 animate-ping' : deviceStatus === 'pending' ? 'bg-amber-400 animate-pulse' : 'bg-slate-600'"></span>
                    <span x-text="deviceStatus === 'approved' ? 'Terkoneksi (Disetujui)' : deviceStatus === 'pending' ? 'Menunggu Approval' : 'Belum Terhubung'"></span>
                </p>
            </div>
        </div>

        <div class="flex items-center gap-2">
            <template x-if="deviceStatus === 'approved'">
                <button type="button" @click="disconnect()" class="px-3 py-1.5 rounded-xl bg-rose-500/10 text-rose-400 border border-rose-500/20 text-xs font-bold hover:bg-rose-500 hover:text-white transition-all">
                    <i class="fas fa-power-off mr-1"></i> Putus
                </button>
            </template>
            <div class="text-right pl-2 border-l border-white/10" x-show="deviceStatus === 'approved'">
                <span class="text-[9px] text-slate-400 block font-mono">Terkirim</span>
                <span class="font-heading font-black text-sm text-emerald-400 font-mono" x-text="scannedCount">0</span>
            </div>
        </div>
    </header>

    <main class="flex-1 p-4 flex flex-col items-center justify-start gap-4 max-w-md mx-auto w-full">

        {{-- TAHAP 1: INPUT IDENTITAS PERANGKAT HP --}}
        <div x-show="deviceStatus === 'unregistered'" class="w-full bg-slate-900/90 border border-white/10 rounded-3xl p-6 shadow-2xl my-auto">
            <div class="w-14 h-14 rounded-2xl bg-indigo-500/15 border border-indigo-500/30 text-indigo-400 flex items-center justify-center mx-auto mb-4 text-2xl">
                <i class="fas fa-id-badge"></i>
            </div>
            <h2 class="font-heading font-black text-xl text-white text-center mb-1">Identitas HP Scanner</h2>
            <p class="text-xs text-slate-400 text-center mb-5 leading-relaxed">
                Masukkan nama atau identitas perangkat ini untuk dikenali pada layar laptop Admin.
            </p>

            <form @submit.prevent="submitJoin()" class="space-y-4">
                <div>
                    <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1.5 font-mono">Nama Perangkat</label>
                    <input type="text" x-model="inputName" required maxlength="30"
                           placeholder="Contoh: Panitia 1 (Kotak A)"
                           class="w-full px-4 py-3.5 bg-slate-950 border border-white/15 rounded-2xl text-sm text-white font-bold outline-none focus:border-indigo-500">
                </div>
                <button type="submit" :disabled="submitting"
                        class="w-full py-4 rounded-2xl bg-gradient-to-r from-indigo-600 to-indigo-500 hover:from-indigo-500 hover:to-indigo-600 text-white font-heading font-black text-sm tracking-wider shadow-xl shadow-indigo-500/30 flex items-center justify-center gap-2">
                    <i class="fas fa-paper-plane" x-show="!submitting"></i>
                    <i class="fas fa-spinner fa-spin" x-show="submitting" x-cloak></i>
                    <span>SAMBUNGKAN KE LAPTOP</span>
                </button>
            </form>
        </div>

        {{-- TAHAP 2: MENUNGGU PERSETUJUAN (PENDING APPROVAL) --}}
        <div x-show="deviceStatus === 'pending'" x-cloak class="w-full bg-slate-900/90 border border-amber-500/30 rounded-3xl p-8 shadow-2xl my-auto text-center">
            <div class="w-16 h-16 rounded-full bg-amber-500/15 border border-amber-500/30 text-amber-400 flex items-center justify-center mx-auto mb-4 text-2xl animate-pulse">
                <i class="fas fa-hourglass-half"></i>
            </div>
            <h2 class="font-heading font-black text-xl text-white mb-2">Menunggu Persetujuan</h2>
            <p class="text-xs text-slate-300 mb-2 leading-relaxed">
                Permintaan koneksi atas nama <strong class="text-amber-300 font-mono" x-text="deviceName"></strong> telah dikirim ke laptop.
            </p>
            <p class="text-[11px] text-slate-500 mb-6">
                Admin di layar laptop akan menekan tombol <strong>[Setujui / Approve]</strong> untuk mengizinkan HP ini memindai kartu suara.
            </p>
            <button type="button" @click="cancelJoin()" class="px-5 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-xs text-slate-300 font-bold border border-white/10">
                Batal / Ganti Nama
            </button>
        </div>

        {{-- TAHAP 3: DITOLAK (REJECTED) --}}
        <div x-show="deviceStatus === 'rejected'" x-cloak class="w-full bg-slate-900/90 border border-rose-500/30 rounded-3xl p-8 shadow-2xl my-auto text-center">
            <div class="w-16 h-16 rounded-full bg-rose-500/15 border border-rose-500/30 text-rose-400 flex items-center justify-center mx-auto mb-4 text-2xl">
                <i class="fas fa-ban"></i>
            </div>
            <h2 class="font-heading font-black text-xl text-white mb-2">Koneksi Ditolak</h2>
            <p class="text-xs text-slate-400 mb-6">
                Admin laptop menolak atau memutus izin perangkat ini.
            </p>
            <button type="button" @click="cancelJoin()" class="px-6 py-3 rounded-2xl bg-indigo-600 text-white font-bold text-xs shadow-lg">
                Coba Sambungkan Ulang
            </button>
        </div>

        {{-- TAHAP 4: KAMERA SCANNER AKTIF (APPROVED) --}}
        <div x-show="deviceStatus === 'approved'" x-cloak class="w-full flex flex-col gap-4">
            {{-- Camera Frame --}}
            <div class="w-full relative rounded-3xl overflow-hidden bg-black border-2 border-emerald-500/40 shadow-2xl shadow-emerald-500/10 aspect-square flex items-center justify-center">
                <div id="reader" class="w-full h-full"></div>

                {{-- Scanning Target Guide --}}
                <div class="absolute inset-0 pointer-events-none flex flex-col items-center justify-center p-8">
                    <div class="w-48 h-48 border-2 border-emerald-400 rounded-2xl relative">
                        <div class="absolute -top-1 -left-1 w-5 h-5 border-t-4 border-l-4 border-emerald-400 rounded-tl-md"></div>
                        <div class="absolute -top-1 -right-1 w-5 h-5 border-t-4 border-r-4 border-emerald-400 rounded-tr-md"></div>
                        <div class="absolute -bottom-1 -left-1 w-5 h-5 border-b-4 border-l-4 border-emerald-400 rounded-bl-md"></div>
                        <div class="absolute -bottom-1 -right-1 w-5 h-5 border-b-4 border-r-4 border-emerald-400 rounded-br-md"></div>
                        <div class="absolute top-1/2 left-0 right-0 h-0.5 bg-gradient-to-r from-transparent via-emerald-400 to-transparent animate-pulse"></div>
                    </div>
                </div>

                {{-- Camera Paused/Off --}}
                <div x-show="!cameraActive" class="absolute inset-0 bg-slate-950/95 flex flex-col items-center justify-center p-6 text-center z-20">
                    <i class="fas fa-camera text-4xl text-slate-600 mb-3"></i>
                    <h3 class="font-heading font-bold text-white text-base mb-1">Kamera Nonaktif</h3>
                    <button type="button" @click="startScanner()" class="mt-2 px-6 py-3 rounded-2xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs shadow-lg shadow-emerald-600/30 flex items-center gap-2">
                        <i class="fas fa-play"></i> Nyalakan Kamera
                    </button>
                </div>
            </div>

            {{-- Status Pill --}}
            <div class="w-full">
                <div class="p-3.5 rounded-2xl text-xs font-mono flex items-center justify-between transition-all"
                     :class="lastStatus === 'success' ? 'bg-emerald-500/15 border border-emerald-500/30 text-emerald-300' : lastStatus === 'error' ? 'bg-rose-500/15 border border-rose-500/30 text-rose-300' : 'bg-slate-900 border border-white/10 text-slate-400'">
                    <div class="flex items-center gap-2 truncate">
                        <i :class="lastStatus === 'success' ? 'fas fa-circle-check text-emerald-400' : lastStatus === 'error' ? 'fas fa-circle-xmark text-rose-400' : 'fas fa-info-circle text-slate-500'"></i>
                        <span class="truncate" x-text="statusMessage">Arahkan ke QR Code kartu pemilih...</span>
                    </div>
                    <template x-if="lastToken">
                        <code class="px-2 py-0.5 bg-slate-950 text-amber-400 rounded font-black tracking-widest text-[11px]" x-text="lastToken"></code>
                    </template>
                </div>
            </div>

            {{-- Controls --}}
            <div class="flex items-center justify-between w-full gap-2">
                <button type="button" @click="toggleCamera()" class="flex-1 py-3 px-4 rounded-2xl bg-slate-900 hover:bg-slate-800 border border-white/10 text-xs font-bold text-slate-300 flex items-center justify-center gap-2">
                    <i :class="cameraActive ? 'fas fa-stop text-rose-400' : 'fas fa-play text-emerald-400'"></i>
                    <span x-text="cameraActive ? 'Jeda Kamera' : 'Mulai Kamera'"></span>
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
        </div>

    </main>

    {{-- Footer --}}
    <footer class="p-3 text-center text-[10px] text-slate-500 border-t border-white/5 bg-slate-950">
        PILKETOS Mobile Scanner &bull; Multi-Device Remote TPS
    </footer>

    <script>
        function remoteScanner(sessionId) {
            return {
                session: sessionId,
                deviceId: localStorage.getItem('pilketos_remote_dev_id') || '',
                deviceName: localStorage.getItem('pilketos_remote_dev_name') || '',
                deviceStatus: 'unregistered', // unregistered, pending, approved, rejected
                inputName: localStorage.getItem('pilketos_remote_dev_name') || 'Panitia 1',
                submitting: false,
                statusPollTimer: null,

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
                    if (this.deviceId && this.deviceName) {
                        this.deviceStatus = 'pending';
                        this.startStatusPolling();
                    }
                },

                async submitJoin() {
                    if (!this.inputName.trim() || this.submitting) return;
                    this.submitting = true;

                    try {
                        const res = await fetch('{{ route("audit-suara.remote-join") }}', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json' },
                            body: JSON.stringify({
                                session_id: this.session,
                                device_name: this.inputName.trim()
                            })
                        });

                        const data = await res.json();
                        if (data.success) {
                            this.deviceId = data.device_id;
                            this.deviceName = data.device_name;
                            localStorage.setItem('pilketos_remote_dev_id', this.deviceId);
                            localStorage.setItem('pilketos_remote_dev_name', this.deviceName);
                            this.deviceStatus = 'pending';
                            this.startStatusPolling();
                        } else {
                            alert(data.message || 'Gagal mengajukan koneksi.');
                        }
                    } catch (e) {
                        alert('Kesalahan jaringan saat menyambungkan ke server.');
                    } finally {
                        this.submitting = false;
                    }
                },

                startStatusPolling() {
                    if (this.statusPollTimer) clearInterval(this.statusPollTimer);

                    this.statusPollTimer = setInterval(async () => {
                        if (!this.deviceId) return;

                        try {
                            const res = await fetch(`{{ url('audit-suara/remote/status') }}/${this.session}/${this.deviceId}`);
                            const data = await res.json();

                            if (data.status === 'approved') {
                                if (this.deviceStatus !== 'approved') {
                                    this.deviceStatus = 'approved';
                                    this.startScanner();
                                }
                            } else if (data.status === 'rejected') {
                                this.deviceStatus = 'rejected';
                                this.stopScanner();
                            } else if (data.status === 'disconnected') {
                                this.cancelJoin();
                            }
                        } catch (e) { }
                    }, 1000);
                },

                cancelJoin() {
                    if (this.statusPollTimer) clearInterval(this.statusPollTimer);
                    this.stopScanner();
                    localStorage.removeItem('pilketos_remote_dev_id');
                    this.deviceId = '';
                    this.deviceStatus = 'unregistered';
                },

                async disconnect() {
                    if (!confirm('Putus koneksi scanner dengan laptop?')) return;
                    try {
                        await fetch('{{ route("audit-suara.remote-disconnect") }}', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json' },
                            body: JSON.stringify({
                                session_id: this.session,
                                device_id: this.deviceId
                            })
                        });
                    } catch (e) { }
                    this.cancelJoin();
                },

                async startScanner() {
                    try {
                        if (!this.html5QrCode) {
                            this.html5QrCode = new Html5Qrcode("reader");
                        }

                        if (this.cameras.length === 0) {
                            this.cameras = await Html5Qrcode.getCameras();
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
                    const rawToken = decodedText.trim();
                    const now = Date.now();

                    // 1. Validasi Keamanan Isi QR: HANYA BOLEH FORMAT TOKEN PILKETOS (6 karakter alfanumerik)
                    const cleanToken = rawToken.toUpperCase();
                    if (!/^[A-Z0-9]{6}$/.test(cleanToken)) {
                        if (now - this.lastScanTime > 1500) {
                            this.lastScanTime = now;
                            this.statusMessage = 'QR Ditolak: Bukan token kartu suara resmi!';
                            this.lastStatus = 'error';
                            if (navigator.vibrate) navigator.vibrate([100, 50, 100]);
                        }
                        return;
                    }

                    // Cooldown 2.5 detik jika token yang sama dihadapkan ke lensa
                    if (cleanToken === this.lastToken && (now - this.lastScanTime) < 2500) {
                        return;
                    }

                    this.lastToken = cleanToken;
                    this.lastScanTime = now;

                    // Haptic getar tanda sukses baca QR
                    if (navigator.vibrate) {
                        navigator.vibrate(80);
                    }

                    this.statusMessage = `Mengirim token ${cleanToken} ke laptop...`;
                    this.lastStatus = 'ready';

                    try {
                        const res = await fetch('{{ route("audit-suara.remote-push") }}', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json' },
                            body: JSON.stringify({
                                session_id: this.session,
                                device_id: this.deviceId,
                                token: cleanToken
                            })
                        });

                        const data = await res.json();

                        if (data.success) {
                            this.scannedCount++;
                            this.statusMessage = `Berhasil dikirim: ${cleanToken}`;
                            this.lastStatus = 'success';
                            this.recentSent.unshift({
                                token: cleanToken,
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
