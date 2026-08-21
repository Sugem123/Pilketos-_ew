@php $page_title = 'Pilih Calon'; @endphp
<x-voting-layout>
    {{-- Preload candidate photos so they load before SweetAlert overlay --}}
    @push('head')
        @foreach ($calons as $calon)
            @if ($calon->url_foto)
                <link rel="preload" as="image" href="{{ asset($calon->url_foto) }}">
            @endif
        @endforeach
    @endpush

    <div class="flex flex-col min-h-screen relative overflow-hidden">
        {{-- Floating Top Bar --}}
        <header class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6 pb-2 z-20">
            <div class="glass-panel-dark rounded-2xl px-6 py-4 flex items-center justify-between shadow-2xl border border-white/10">
                <div class="flex items-center gap-3.5">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-indigo-600 to-indigo-400 flex items-center justify-center p-2 shadow-lg shadow-indigo-500/30">
                        <img src="{{ !empty($config['url_logo']) ? asset($config['url_logo']) : asset('img/logo.png') }}" alt="Logo" class="w-full h-full object-contain">
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="font-heading font-extrabold text-base lg:text-lg tracking-tight text-white">{{ $config['nama_sekolah'] ?? 'PILKETOS' }}</span>
                            <span class="px-2 py-0.5 text-[10px] font-semibold bg-indigo-500/20 text-indigo-300 border border-indigo-500/30 rounded-full">E-VOTING</span>
                        </div>
                        <p class="text-xs text-slate-400 font-medium">{{ $config['nama_kegiatan'] ?? 'Pemilihan Ketua OSIS' }} &bull; TP {{ $config['tahun_ajaran'] ?? date('Y') }}</p>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <div class="hidden sm:flex flex-col text-right">
                        <span class="text-xs text-slate-400">Hak Suara Terpakai</span>
                        <span class="text-sm font-semibold text-indigo-300 font-mono">{{ $totalVote }} / {{ $config['haksuara'] }}</span>
                    </div>
                    <div class="w-2.5 h-2.5 rounded-full {{ ($config['haksuara'] ?? 150) - $totalVote > 0 ? 'bg-emerald-400 animate-pulse ring-4 ring-emerald-400/20' : 'bg-rose-500' }}"></div>
                </div>
            </div>
        </header>

        {{-- Main Voting Stage --}}
        <main class="flex-grow flex items-center justify-center px-4 py-8 lg:py-12 z-10">
            <div class="mx-auto w-full max-w-7xl">
                <div class="text-center max-w-2xl mx-auto mb-8 lg:mb-12">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-indigo-500/10 text-indigo-400 border border-indigo-500/20 mb-3">
                        <i class="fa-solid fa-check-to-slot text-[11px]"></i> E-Voting Bilik Suara
                    </span>
                    <h1 class="text-3xl lg:text-5xl font-extrabold font-heading text-white tracking-tight leading-tight mb-3">
                        Pilih Pemimpin Masa Depan
                    </h1>
                    @if (($config['haksuara'] ?? 150) - $totalVote > 0)
                        <p class="text-sm lg:text-base text-slate-400">
                            Gunakan hak suaramu secara bijak, jujur, dan adil. Klik kartu untuk melihat visi & misi kandidat.
                        </p>
                    @else
                        <div class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-rose-500/10 border border-rose-500/30 text-rose-400 text-sm font-medium">
                            <i class="fa-solid fa-triangle-exclamation"></i>
                            Pemilihan suara ditutup! Kuota hak suara telah mencapai batas maksimum.
                        </div>
                    @endif
                </div>

                <form id="votingForm" method="POST" action="{{ route('voting.vote') }}" class="space-y-10">
                    @csrf

                    @if ($calons->isNotEmpty())
                        <div class="flex flex-wrap lg:flex-nowrap gap-6 lg:gap-8 items-center justify-center">
                            @php $no = 1; @endphp
                            @foreach ($calons as $calon)
                                @php
                                    $words = explode(' ', $calon->nama);
                                    $first = $words[0];
                                    $second = $words[1] ?? '';
                                    $third = $words[2] ?? '';
                                @endphp
                                <div id="caketos-container-{{ $no }}" class="caketos-item relative transition-all duration-300">
                                    <div class="cursor-pointer flex w-[18rem] lg:w-[22rem] group items-center relative">
                                        
                                        {{-- Candidate Card --}}
                                        <div class="bg-slate-900/90 backdrop-blur-xl z-10 card w-full border-2 border-slate-800 hover:border-indigo-500/60 rounded-3xl shadow-2xl transition-all duration-300 overflow-hidden group relative hover:shadow-indigo-500/10 hover:-translate-y-1.5"
                                            data-calon-id="{{ $calon->id }}" data-visi="{{ $calon->visi }}"
                                            data-misi="{{ $calon->misi }}" data-nama="{{ $calon->nama }}"
                                            data-kelas="{{ $calon->kelas->name }}">
                                            
                                            {{-- Selection Indicator Badge --}}
                                            <div class="selection-indicator opacity-0 absolute top-4 right-4 z-20 transition-all duration-300 transform scale-75 group-[.selected]:opacity-100 group-[.selected]:scale-100">
                                                <div class="w-9 h-9 rounded-full bg-gradient-to-tr from-indigo-600 to-indigo-400 flex items-center justify-center text-white shadow-lg shadow-indigo-500/50">
                                                    <i class="fa-solid fa-check text-sm font-bold"></i>
                                                </div>
                                            </div>

                                            <input type="radio" name="id_calon"
                                                {{ $config['haksuara'] - $totalVote <= 0 ? 'disabled' : '' }}
                                                value="{{ $calon->id }}" id="calon_{{ $calon->id }}"
                                                class="hidden candidate-radio">

                                            <label for="calon_{{ $calon->id }}"
                                                class="{{ $config['haksuara'] - $totalVote <= 0 ? 'opacity-40 cursor-not-allowed' : 'cursor-pointer' }} block select-none">
                                                
                                                {{-- Card Top Header --}}
                                                <div class="p-5 lg:p-6 border-b border-slate-800/80 bg-slate-950/40 flex items-center justify-between">
                                                    <div>
                                                        <span class="text-[11px] font-bold uppercase tracking-wider text-indigo-400 font-mono">KANDIDAT KETUA</span>
                                                        <h3 class="font-heading font-extrabold text-xl lg:text-2xl text-white leading-snug mt-0.5">
                                                            {{ $first }}
                                                            <span class="block text-slate-400 font-semibold text-sm lg:text-base font-sans mt-0.5">{{ $second }} {{ $third }}</span>
                                                        </h3>
                                                    </div>
                                                    <div class="w-10 h-10 rounded-2xl bg-indigo-500/10 border border-indigo-500/20 flex items-center justify-center font-heading font-black text-indigo-300 text-lg">
                                                        {{ $calon->nomor }}
                                                    </div>
                                                </div>

                                                {{-- Card Photo Area (Full Contain Photo) --}}
                                                <div class="h-[15rem] lg:h-[22rem] bg-gradient-to-b from-slate-900/90 via-slate-900/60 to-slate-950 flex items-center justify-center overflow-hidden relative group-hover:brightness-105 transition-all p-3">
                                                    <h1 class="absolute bottom-2 left-3 font-heading font-black text-slate-800/25 text-8xl lg:text-9xl pointer-events-none select-none z-0">
                                                        {{ sprintf('%02d', $calon->nomor) }}
                                                    </h1>
                                                    
                                                    @if ($calon->url_foto)
                                                        <img class="w-full h-full object-contain object-center relative z-10 transition-transform duration-500 group-hover:scale-105 drop-shadow-2xl"
                                                            src="{{ asset($calon->url_foto) }}"
                                                            alt="{{ $calon->nama }}"
                                                            loading="eager"
                                                            fetchpriority="high"
                                                            decoding="async" />
                                                    @else
                                                        <div class="w-24 h-24 rounded-full bg-slate-800 flex items-center justify-center text-slate-600 relative z-10">
                                                            <i class="fa-solid fa-user text-4xl"></i>
                                                        </div>
                                                    @endif
                                                </div>

                                                {{-- Card Info Bottom --}}
                                                <div class="p-5 lg:p-6 bg-slate-900/90 border-t border-slate-800/80 flex items-center justify-between">
                                                    <div class="flex items-center gap-2">
                                                        <span class="w-2 h-2 rounded-full bg-indigo-500"></span>
                                                        <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Kelas</span>
                                                    </div>
                                                    <span class="text-sm font-bold text-white px-3 py-1 bg-slate-800 border border-slate-700 rounded-xl font-mono">
                                                        {{ $calon->kelas->name }}
                                                    </span>
                                                </div>
                                            </label>
                                        </div>

                                        {{-- Detail Panel (Slides smoothly behind card) --}}
                                        <div class="detail-panel absolute top-[3%] left-0 w-[18rem] lg:w-[22rem] h-[94%] bg-slate-900/95 backdrop-blur-2xl border-2 border-indigo-500/50 rounded-3xl shadow-2xl overflow-hidden pointer-events-none z-0 text-slate-200"
                                            style="transform: translateX(0);">
                                            <div class="p-5 pl-8 lg:p-6 lg:pl-10 h-full overflow-y-auto space-y-4">
                                                <div class="border-b border-slate-800 pb-3">
                                                    <span class="text-[10px] font-bold uppercase tracking-widest text-indigo-400 font-mono">Profil Kandidat</span>
                                                    <h3 class="font-heading font-extrabold text-lg lg:text-xl text-white detail-nama mt-0.5"></h3>
                                                    <p class="text-xs font-medium text-slate-400 detail-kelas font-mono"></p>
                                                </div>

                                                <div>
                                                    <div class="flex items-center gap-2 mb-1.5">
                                                        <div class="w-1.5 h-3.5 rounded-full bg-gradient-to-b from-indigo-400 to-indigo-600"></div>
                                                        <h4 class="text-xs font-bold text-indigo-300 uppercase tracking-wider">Visi</h4>
                                                    </div>
                                                    <p class="text-xs text-slate-300 leading-relaxed bg-slate-950/60 border border-slate-800 rounded-2xl p-3.5 detail-visi shadow-inner"></p>
                                                </div>

                                                <div>
                                                    <div class="flex items-center gap-2 mb-1.5">
                                                        <div class="w-1.5 h-3.5 rounded-full bg-gradient-to-b from-amber-400 to-amber-600"></div>
                                                        <h4 class="text-xs font-bold text-amber-300 uppercase tracking-wider">Misi</h4>
                                                    </div>
                                                    <p class="text-xs text-slate-300 leading-relaxed bg-slate-950/60 border border-slate-800 rounded-2xl p-3.5 whitespace-pre-line detail-misi shadow-inner"></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @php $no++; @endphp
                            @endforeach
                        </div>

                        {{-- Action Vote Button --}}
                        <div class="text-center pt-4">
                            <button type="submit" id="voteButton" disabled
                                class="inline-flex items-center justify-center gap-3 bg-slate-800 text-slate-500 py-4 px-12 rounded-2xl font-heading font-extrabold text-base lg:text-lg transition-all duration-300 cursor-not-allowed border border-slate-700 shadow-lg">
                                <i class="fa-solid fa-lock text-sm"></i>
                                <span>PILIH KANDIDAT</span>
                            </button>
                            <p class="text-xs text-slate-500 mt-3 font-medium">Klik pada salah satu kartu kandidat untuk menentukan pilihan</p>
                        </div>
                    @else
                        <div class="text-center py-20 bg-slate-900/60 rounded-3xl border border-slate-800 max-w-lg mx-auto p-8">
                            <div class="w-16 h-16 rounded-2xl bg-indigo-500/10 text-indigo-400 flex items-center justify-center mx-auto mb-4 text-2xl">
                                <i class="fa-solid fa-users-slash"></i>
                            </div>
                            <h3 class="text-xl font-heading font-bold text-white mb-2">Belum Ada Kandidat</h3>
                            <p class="text-sm text-slate-400">Saat ini data kandidat ketua OSIS belum ditambahkan oleh panitia.</p>
                        </div>
                    @endif
                </form>
            </div>
        </main>

        {{-- Minimalist Footer --}}
        <footer class="w-full border-t border-white/5 py-4 z-10">
            <div class="max-w-7xl mx-auto px-4 text-center">
                <p class="text-xs text-slate-500">
                    &copy; {{ date('Y') }} PILKETOS Official E-Voting System &bull; Realtime & Terpercaya
                </p>
            </div>
        </footer>
    </div>

    <script>
        const candidateRadios = document.querySelectorAll('.candidate-radio');
        const candidateCards = document.querySelectorAll('.card');
        const voteButton = document.getElementById('voteButton');
        const allItems = document.querySelectorAll('.caketos-item');
        let currentlyExpanded = null;
        const activeAnimations = new Map();

        function ease(t) {
            return t < 0.5 ? 2 * t * t : 1 - Math.pow(-2 * t + 2, 2) / 2;
        }

        function cancelAnimation(el) {
            const existing = activeAnimations.get(el);
            if (existing) {
                cancelAnimationFrame(existing);
                activeAnimations.delete(el);
            }
        }

        function animateValue(el, from, to, duration, callback, onDone) {
            cancelAnimation(el);
            const start = performance.now();

            function step(now) {
                const elapsed = now - start;
                const progress = Math.min(elapsed / duration, 1);
                const eased = ease(progress);
                callback(from + (to - from) * eased);
                if (progress < 1) {
                    activeAnimations.set(el, requestAnimationFrame(step));
                } else {
                    activeAnimations.delete(el);
                    if (onDone) onDone();
                }
            }
            activeAnimations.set(el, requestAnimationFrame(step));
        }

        function getItemsAfter(item) {
            const after = [];
            allItems.forEach(other => {
                if (other !== item && other.compareDocumentPosition(item) & Node.DOCUMENT_POSITION_PRECEDING) {
                    after.push(other);
                }
            });
            return after;
        }

        function expandPanel(card, container) {
            const detailPanel = container.querySelector('.detail-panel');
            const cardWidth = card.offsetWidth;
            const overlap = 12;
            const slideDistance = cardWidth - overlap;

            detailPanel.querySelector('.detail-nama').textContent = card.dataset.nama;
            detailPanel.querySelector('.detail-kelas').textContent = 'Kelas ' + card.dataset.kelas;
            detailPanel.querySelector('.detail-visi').textContent = card.dataset.visi;
            detailPanel.querySelector('.detail-misi').textContent = card.dataset.misi;

            const siblingsAfter = getItemsAfter(container);

            animateValue(detailPanel, 0, slideDistance, 400, (val) => {
                detailPanel.style.transform = 'translateX(' + val + 'px)';
            }, () => {
                detailPanel.style.pointerEvents = 'auto';
            });

            siblingsAfter.forEach(sib => {
                const currentTransform = sib.style.transform;
                const currentVal = currentTransform ?
                    parseFloat(currentTransform.match(/translateX\((.+)px\)/)?.[1] || 0) : 0;
                animateValue(sib, currentVal, slideDistance, 400, (val) => {
                    sib.style.transform = 'translateX(' + val + 'px)';
                });
            });

            detailPanel.style.pointerEvents = 'none';
            currentlyExpanded = container;
        }

        function collapsePanel(container) {
            const detailPanel = container.querySelector('.detail-panel');
            const currentTransform = detailPanel.style.transform;
            const currentVal = currentTransform ?
                parseFloat(currentTransform.match(/translateX\((.+)px\)/)?.[1] || 0) : 0;

            const siblingsAfter = getItemsAfter(container);

            animateValue(detailPanel, currentVal, 0, 400, (val) => {
                detailPanel.style.transform = 'translateX(' + val + 'px)';
            }, () => {
                detailPanel.style.pointerEvents = 'none';
            });

            siblingsAfter.forEach(sib => {
                const sibTransform = sib.style.transform;
                const sibCurrent = sibTransform ?
                    parseFloat(sibTransform.match(/translateX\((.+)px\)/)?.[1] || 0) : 0;
                animateValue(sib, sibCurrent, 0, 400, (val) => {
                    sib.style.transform = 'translateX(' + val + 'px)';
                });
            });

            currentlyExpanded = null;
        }

        candidateCards.forEach((card) => {
            card.addEventListener('click', function(e) {
                if (e.target.tagName === 'LABEL' || e.target.closest('label')) {
                    return;
                }

                const container = this.closest('.caketos-item');

                if (currentlyExpanded === container) {
                    collapsePanel(container);
                    return;
                }

                if (currentlyExpanded) {
                    const prevContainer = currentlyExpanded;
                    collapsePanel(prevContainer);
                    setTimeout(() => expandPanel(this, container), 50);
                } else {
                    expandPanel(this, container);
                }
            });
        });

        candidateRadios.forEach((radio) => {
            radio.addEventListener('change', function() {
                candidateCards.forEach(card => {
                    card.classList.remove('selected', 'border-indigo-500', 'ring-4', 'ring-indigo-500/30');
                    card.classList.add('border-slate-800');
                });
                if (this.checked) {
                    const activeCard = this.closest('.card');
                    activeCard.classList.add('selected', 'border-indigo-500', 'ring-4', 'ring-indigo-500/30');
                    activeCard.classList.remove('border-slate-800');
                    
                    voteButton.disabled = false;
                    voteButton.classList.remove('bg-slate-800', 'text-slate-500', 'cursor-not-allowed', 'border-slate-700');
                    voteButton.classList.add('bg-gradient-to-r', 'from-indigo-600', 'to-indigo-500', 'text-white', 'hover:shadow-indigo-500/50', 'hover:scale-[1.02]', 'cursor-pointer', 'border-indigo-400');
                    voteButton.innerHTML = '<i class="fa-solid fa-circle-check text-base"></i> <span>KIRIM SUARA SEKARANG</span>';
                    voteButton.nextElementSibling.textContent = 'Klik tombol di atas untuk konfirmasi dan kirimkan suara Anda';
                }
            });
        });

        document.getElementById('votingForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            let token = sessionStorage.getItem('display_token');
            if (!token) {
                Swal.fire({
                    icon: 'error',
                    title: 'Token Hilang',
                    text: 'Silakan masukkan display token terlebih dahulu.'
                }).then(() => showTokenPopup());
                return;
            }

            const selectedCandidate = document.querySelector('input[name="id_calon"]:checked');
            if (!selectedCandidate) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Kandidat Belum Dipilih',
                    text: 'Silakan pilih salah satu kandidat terlebih dahulu!',
                    confirmButtonText: 'Mengerti'
                });
                return;
            }

            const candidateCard = selectedCandidate.closest('.card');
            const candidateName = candidateCard.dataset.nama;
            const candidateId = selectedCandidate.value;
            const prefillName = sessionStorage.getItem('voter_name') || '';

            const confirmResult = await Swal.fire({
                icon: 'question',
                title: 'Konfirmasi Pilihan',
                html: `Apakah Anda yakin ingin memberikan suara kepada <strong class="text-indigo-400">${candidateName}</strong>?`,
                input: 'text',
                inputValue: prefillName,
                inputLabel: 'Nama Lengkap Anda Sesuai DPT / Kartu Pemilih',
                inputPlaceholder: 'Contoh: Shabira Syahla',
                inputAttributes: {
                    maxlength: 255,
                    autocapitalize: 'off',
                    autocorrect: 'off'
                },
                showCancelButton: true,
                confirmButtonText: 'Ya, Kirim Suara',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                preConfirm: (nisn) => {
                    if (!nisn || !nisn.trim()) {
                        Swal.showValidationMessage('Nama pemilih tidak boleh kosong!');
                        return false;
                    }
                    return nisn.trim();
                }
            });

            if (!confirmResult.isConfirmed || !confirmResult.value) {
                return;
            }

            const nisn = confirmResult.value;

            // Tampilkan loading modal
            Swal.fire({
                title: 'Menyimpan Suara...',
                text: 'Mencatat suara Anda ke sistem...',
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false,
                didOpen: () => Swal.showLoading()
            });

            try {
                const response = await fetch('{{ route('voting.vote') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        id_calon: candidateId,
                        nisn: nisn,
                        display_token: token
                    })
                });

                const data = await response.json();

                if (!response.ok || !data.success) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal Menyimpan Suara',
                        text: data.message || (data.errors ? Object.values(data.errors).flat().join('\n') : 'Terjadi kesalahan sistem.'),
                        confirmButtonText: 'Coba Lagi'
                    });
                    return;
                }

                // Sukses instan tanpa freeze
                await Swal.fire({
                    icon: 'success',
                    title: 'Suara Berhasil Dicatat!',
                    html: `Terima kasih <strong class="text-indigo-400">${data.voter_name || nisn}</strong>, suara Anda telah resmi masuk.`,
                    confirmButtonText: 'Selesai',
                    timer: 3000,
                    timerProgressBar: true
                });

                resetForm();
            } catch (error) {
                console.error(error);
                Swal.fire({
                    icon: 'error',
                    title: 'Koneksi Error',
                    text: 'Gagal menghubungi server saat menyimpan suara.',
                    confirmButtonText: 'Coba Lagi'
                });
            }
        });

        @if (session('success'))
            window.addEventListener('load', function() {
                Swal.fire({
                    icon: 'success',
                    title: 'Suara Berhasil Dicatat!',
                    html: '{{ session('success') }}',
                    confirmButtonText: 'Selesai',
                    timer: 3500,
                    timerProgressBar: true
                }).then(() => resetForm());
            });
        @endif

        @if (session('error'))
            window.addEventListener('load', function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal Memproses',
                    text: '{{ session('error') }}',
                    confirmButtonText: 'Coba Lagi'
                });
            });
        @endif

        function resetForm() {
            document.getElementById('votingForm').reset();
            candidateCards.forEach(card => {
                card.classList.remove('selected', 'border-indigo-500', 'ring-4', 'ring-indigo-500/30');
                card.classList.add('border-slate-800');
            });
            voteButton.disabled = true;
            voteButton.classList.remove('bg-gradient-to-r', 'from-indigo-600', 'to-indigo-500', 'text-white', 'hover:shadow-indigo-500/50', 'hover:scale-[1.02]', 'cursor-pointer', 'border-indigo-400');
            voteButton.classList.add('bg-slate-800', 'text-slate-500', 'cursor-not-allowed', 'border-slate-700');
            voteButton.innerHTML = '<i class="fa-solid fa-lock text-sm"></i> <span>PILIH KANDIDAT</span>';
            voteButton.nextElementSibling.textContent = 'Klik pada salah satu kartu kandidat untuk menentukan pilihan';
            
            sessionStorage.removeItem('display_token');
            sessionStorage.removeItem('voter_name');

            activeAnimations.forEach((id, el) => cancelAnimationFrame(id));
            activeAnimations.clear();
            allItems.forEach(item => {
                item.style.transform = '';
                const panel = item.querySelector('.detail-panel');
                if (panel) {
                    panel.style.transform = 'translateX(0)';
                    panel.style.pointerEvents = 'none';
                }
            });
            currentlyExpanded = null;
            setTimeout(() => showTokenPopup(), 1200);
        }

        function showTokenPopup() {
            Swal.fire({
                title: 'Aktivasi Bilik Suara',
                text: 'Masukkan Token dari Kartu Pemilih atau Display Token panitia.',
                input: 'text',
                inputPlaceholder: 'Contoh token: ABC123',
                allowOutsideClick: false,
                allowEscapeKey: false,
                backdrop: 'rgba(11, 15, 25, 0.85)',
                preConfirm: (token) => {
                    if (!token) {
                        Swal.showValidationMessage('Token wajib diisi');
                        return false;
                    }
                    return fetch('{{ route('check-token') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: 'token=' + encodeURIComponent(token.trim().toUpperCase())
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (!data.success) {
                                Swal.showValidationMessage(data.message || 'Token tidak valid atau telah hangus');
                                return false;
                            }
                            sessionStorage.setItem('display_token', token.trim().toUpperCase());
                            if (data.voter_name) {
                                sessionStorage.setItem('voter_name', data.voter_name);
                            }
                            return true;
                        })
                        .catch(() => {
                            Swal.showValidationMessage('Gagal menghubungi server');
                            return false;
                        });
                }
            });
        }

        // Wait for all candidate images to finish loading before showing token popup
        function waitForCandidateImages() {
            const imgs = document.querySelectorAll('.caketos-item img');
            if (imgs.length === 0) return Promise.resolve();
            return Promise.all(Array.from(imgs).map(img => {
                if (img.complete && img.naturalHeight > 0) return Promise.resolve();
                return new Promise(resolve => {
                    img.addEventListener('load', resolve, { once: true });
                    img.addEventListener('error', resolve, { once: true });
                });
            }));
        }

        window.addEventListener('DOMContentLoaded', () => {
            waitForCandidateImages().then(() => {
                let token = sessionStorage.getItem('display_token');

                if (!token) {
                    showTokenPopup();
                } else {
                    fetch('{{ route('check-token') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: 'token=' + encodeURIComponent(token)
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (!data.success) {
                                sessionStorage.removeItem('display_token');
                                showTokenPopup();
                            }
                        })
                        .catch(() => {
                            Swal.fire({
                                icon: 'error',
                                title: 'Koneksi Error',
                                text: 'Gagal memverifikasi status token.'
                            });
                        });
                }
            });
        });
    </script>
</x-voting-layout>
