@php $page_title = 'Pilih Calon'; @endphp
<x-voting-layout>
    <div class="flex flex-col min-h-screen">
        {{-- <div class="bg-secondary shadow-sm border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16">
                    <div class="flex gap-3 items-center">
                        <img src="{{ asset('img/logo.png') }}" alt="" class="size-7 lg:size-9">
                        <div>
                            <h1 class="text-sm lg:text-xl font-bold text-accent">PILKETOS</h1>
                            <p class="text-xs lg:text-sm text-gray-600">v2.0</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-medium text-accent">Sistem Voting</p>
                        <p class="text-xs lg:text-sm text-gray-600">Suara Anda sangat berharga untuk masa depan sekolah
                        </p>
                    </div>
                </div>
            </div>
        </div> --}}

        <main class="flex-grow flex items-center justify-center">
            <div class="mx-auto w-full">
                <div class="text-center px-6 lg:p-0 mb-6 lg:mb-12">
                    <h1 class="text-2xl lg:text-4xl font-bold text-accent mb-2 lg:mb-2">Pemilihan Ketua OSIS</h1>
                    @if ($totalHakSuara - $totalVote > 0)
                        <p class="text-lg lg:text-xl text-gray-600 mb-2">Pilih satu calon ketua OSIS favorit Anda</p>
                    @else
                        <p class="text-xl text-red-600 mb-2">Pemilihan suara ditutup! hak suara sudah mencapai batas</p>
                    @endif
                </div>

                <form id="votingForm" method="POST" action="{{ route('voting.vote') }}" class="space-y-8">
                    @csrf

                    @if ($calons->isNotEmpty())
                        <div class="flex flex-nowrap gap-2 lg:gap-8 items-center justify-center">
                            @php $no = 1; @endphp
                            @foreach ($calons as $calon)
                                @php
                                    $words = explode(' ', $calon->nama);
                                    $first = $words[0];
                                    $second = $words[1] ?? '';
                                    $third = $words[2] ?? '';
                                @endphp
                                <div id="caketos-container-{{ $no }}" class="caketos-item">
                                    <div class="cursor-pointer flex w-[10rem] lg:w-[22rem] group items-center relative">
                                        <div class="bg-white z-10 card w-full border-2 border-gray-200 rounded-xl shadow-lg hover:shadow-xl {{ $totalHakSuara - $totalVote > 0 ? 'hover:border-birupesat' : '' }} transition-all duration-300 overflow-hidden max-w-sm group relative"
                                            data-calon-id="{{ $calon->id }}" data-visi="{{ $calon->visi }}"
                                            data-misi="{{ $calon->misi }}" data-nama="{{ $calon->nama }}"
                                            data-kelas="{{ $calon->kelas->name }}">
                                            <i
                                                class="selection-indicator opacity-0 text-birupesat absolute top-2.5 right-2.5 text-lg lg:text-2xl fa-solid fa-circle-check z-20 transition-opacity duration-150 ease-in-out"></i>

                                            <input type="radio" name="id_calon"
                                                {{ $config['haksuara'] - $totalVote <= 0 ? 'disabled' : '' }}
                                                value="{{ $calon->id }}" id="calon_{{ $calon->id }}"
                                                class="hidden candidate-radio">

                                            <label for="calon_{{ $calon->id }}"
                                                class="{{ $config['haksuara'] - $totalVote <= 0 ? 'saturate-0 cursor-not-allowed' : '' }} block">
                                                <div class="flex gap-3 p-3 lg:p-6 border-b border-gray-100">
                                                    <h3 class="font-bold text-lg lg:text-2xl leading-5 lg:leading-6">
                                                        {{ $first }}<br>
                                                        <span
                                                            class="text-gray-500 text-sm lg:text-xl font-medium">{{ $second }}
                                                            {{ $third }}</span>
                                                    </h3>
                                                </div>
                                                <div
                                                    class="h-[10rem] lg:h-[22rem] bg-gradient-to-br from-gray-50 to-gray-200 flex items-center justify-center overflow-hidden relative">
                                                    <h1
                                                        class="absolute duration-200 ease-in-out top-3 m-0 left-4 font-bold opacity-20 text-6xl lg:text-9xl">
                                                        {{ '0' . $calon->nomor }}
                                                    </h1>
                                                    @if ($calon->url_foto)
                                                        <img class="size-[140%] object-cover absolute -top-3 -right-9"
                                                            src="{{ asset($calon->url_foto) }}"
                                                            alt="{{ $calon->nama }}" />
                                                    @else
                                                        <svg class="absolute bottom-0 right-0 w-32 h-32 text-gray-300"
                                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                                            </path>
                                                        </svg>
                                                    @endif
                                                </div>
                                                <div class="p-3 lg:p-6 space-y-3">
                                                    <div class="flex justify-between text-sm lg:text-xl">
                                                        <span class="text-gray-500 font-medium">KELAS</span>
                                                        <span
                                                            class="text-accent font-semibold">{{ $calon->kelas->name }}</span>
                                                    </div>
                                                </div>
                                            </label>
                                        </div>

                                        {{-- Detail Panel (preloaded behind card, 10% shorter, vertically centered) --}}
                                        <div class="detail-panel absolute top-[5%] left-0 w-[10rem] lg:w-[22rem] h-[90%] bg-white border-2 border-birupesat rounded-xl shadow-xl overflow-hidden pointer-events-none z-0"
                                            style="transform: translateX(0);">
                                            <div class="p-4 pl-7 lg:p-6 lg:pl-9 h-full overflow-y-auto">
                                                <div class="mb-4">
                                                    <h3
                                                        class="font-bold text-lg lg:text-2xl text-accent mb-1 detail-nama">
                                                    </h3>
                                                    <p class="text-sm lg:text-base text-gray-600 detail-kelas"></p>
                                                </div>

                                                <div class="mb-4">
                                                    <div class="flex items-center gap-2 mb-2">
                                                        <div class="w-1 h-4 rounded-full bg-birupesat"></div>
                                                        <h4
                                                            class="text-xs lg:text-sm font-semibold text-gray-700 uppercase tracking-wide">
                                                            Visi</h4>
                                                    </div>
                                                    <p
                                                        class="text-xs lg:text-sm text-gray-600 leading-relaxed bg-gray-50 rounded-lg p-3 detail-visi">
                                                    </p>
                                                </div>

                                                <div>
                                                    <div class="flex items-center gap-2 mb-2">
                                                        <div class="w-1 h-4 rounded-full bg-accent"></div>
                                                        <h4
                                                            class="text-xs lg:text-sm font-semibold text-gray-700 uppercase tracking-wide">
                                                            Misi</h4>
                                                    </div>
                                                    <p
                                                        class="text-xs lg:text-sm text-gray-600 leading-relaxed bg-gray-50 rounded-lg p-3 whitespace-pre-line detail-misi">
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @php $no++; @endphp
                            @endforeach
                        </div>

                        <div class="text-center mt-12">
                            <button type="submit" id="voteButton" disabled
                                class="bg-gray-400 text-white py-4 px-12 rounded-2xl font-bold text-lg transition-all duration-300 cursor-not-allowed">
                                Pilih Calon Favorit
                            </button>
                            <p class="text-sm text-gray-500 mt-3">Silakan pilih salah satu calon terlebih dahulu</p>
                        </div>
                    @else
                        <div class="text-center py-16">
                            <svg class="w-24 h-24 text-gray-400 mx-auto mb-6" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z">
                                </path>
                            </svg>
                            <h3 class="text-2xl font-bold text-gray-900 mb-4">Belum Ada Calon</h3>
                            <p class="text-gray-600">Saat ini belum ada calon ketua OSIS yang terdaftar.</p>
                        </div>
                    @endif
                </form>
            </div>
        </main>

        <footer class="bg-secondary border-t border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3">
                <div class="text-center">
                    <p class="text-sm text-gray-600">Pilketos v2.0 FOSS - Sistem Pemilihan Ketua OSIS
                    </p>
                    <p class="text-xs text-gray-500 mt-1">Made with $20 Claude subscription by Sattar</p>
                </div>
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
            return t < 0.5 ?
                2 * t * t :
                1 - Math.pow(-2 * t + 2, 2) / 2;
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
            const overlap = 10;
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
                    parseFloat(currentTransform.match(/translateX\((.+)px\)/)?.[1] || 0) :
                    0;
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
                parseFloat(currentTransform.match(/translateX\((.+)px\)/)?.[1] || 0) :
                0;

            const siblingsAfter = getItemsAfter(container);

            animateValue(detailPanel, currentVal, 0, 400, (val) => {
                detailPanel.style.transform = 'translateX(' + val + 'px)';
            }, () => {
                detailPanel.style.pointerEvents = 'none';
            });

            siblingsAfter.forEach(sib => {
                const sibTransform = sib.style.transform;
                const sibCurrent = sibTransform ?
                    parseFloat(sibTransform.match(/translateX\((.+)px\)/)?.[1] || 0) :
                    0;
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
                candidateCards.forEach(card => card.classList.remove('selected'));
                if (this.checked) {
                    this.closest('.card').classList.add('selected');
                    voteButton.disabled = false;
                    voteButton.classList.remove('bg-gray-400', 'cursor-not-allowed');
                    voteButton.classList.add('bg-birupesat', 'hover:bg-blue-700', 'cursor-pointer');
                    voteButton.textContent = 'VOTE SEKARANG!';
                    voteButton.nextElementSibling.textContent = 'Klik untuk memberikan suara Anda';
                }
            });
        });

        document.getElementById('votingForm').addEventListener('submit', function(e) {
            e.preventDefault();

            let token = getCookie('display_token');
            if (!token) {
                Swal.fire({
                    icon: 'error',
                    title: 'Token Hilang',
                    text: 'Silakan masukkan token terlebih dahulu.'
                }).then(() => showTokenPopup());
                return;
            }

            const tokenInput = document.createElement('input');
            tokenInput.type = 'hidden';
            tokenInput.name = 'display_token';
            tokenInput.value = token;
            this.appendChild(tokenInput);

            const selectedCandidate = document.querySelector('input[name="id_calon"]:checked');
            if (!selectedCandidate) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Pilihan Belum Dipilih',
                    text: 'Silakan pilih salah satu calon terlebih dahulu!',
                    confirmButtonText: 'OK, Mengerti'
                });
                return;
            }

            const candidateCard = selectedCandidate.closest('.card');
            const candidateName = candidateCard.querySelector('h3').textContent.trim().replace(/\s+/g, ' ');

            Swal.fire({
                icon: 'question',
                title: 'Konfirmasi Pilihan',
                html: `Apakah Anda yakin ingin memilih <strong>${candidateName}</strong> sebagai calon ketua OSIS?`,
                input: 'text',
                inputLabel: 'Masukkan Nama Anda',
                inputPlaceholder: 'Contoh: Shabira Syahla',
                inputAttributes: {
                    maxlength: 255,
                    autocapitalize: 'off',
                    autocorrect: 'off'
                },
                showCancelButton: true,
                confirmButtonText: 'Ya, Pilih Calon Ini',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                preConfirm: (nisn) => {
                    if (!nisn) {
                        Swal.showValidationMessage('Nama tidak boleh kosong!');
                    }
                    return nisn;
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const nisn = result.value;
                    const form = document.getElementById('votingForm');
                    const hiddenInput = document.createElement('input');
                    hiddenInput.type = 'hidden';
                    hiddenInput.name = 'nisn';
                    hiddenInput.value = nisn;
                    form.appendChild(hiddenInput);

                    Swal.fire({
                        title: 'Memproses Vote...',
                        text: 'Mohon tunggu sebentar',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        showConfirmButton: false,
                        didOpen: () => Swal.showLoading()
                    });

                    setTimeout(() => form.submit(), 1000);
                }
            });
        });

        @if (session('success'))
            window.addEventListener('load', function() {
                Swal.fire({
                    icon: 'success',
                    title: 'Vote Berhasil!',
                    html: '{{ session('success') }}',
                    confirmButtonText: 'Close',
                    timer: 3000,
                    timerProgressBar: true
                }).then(() => resetForm());
            });
        @endif

        @if (session('error'))
            window.addEventListener('load', function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Terjadi Kesalahan',
                    text: '{{ session('error') }}',
                    confirmButtonText: 'Coba Lagi'
                });
            });
        @endif

        function resetForm() {
            document.getElementById('votingForm').reset();
            candidateCards.forEach(card => card.classList.remove('selected'));
            voteButton.disabled = true;
            voteButton.classList.remove('bg-birupesat', 'hover:bg-blue-700', 'cursor-pointer');
            voteButton.classList.add('bg-gray-400', 'cursor-not-allowed');
            voteButton.textContent = 'Pilih Calon Favorit';
            voteButton.nextElementSibling.textContent = 'Silakan pilih salah satu calon terlebih dahulu';
            // Cancel all animations and reset transforms
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
        }

        function getCookie(name) {
            let match = document.cookie.match(new RegExp('(^| )' + name + '=([^;]+)'));
            return match ? decodeURIComponent(match[2]) : null;
        }

        function deleteCookie(name) {
            document.cookie = name + '=; Max-Age=0; path=/';
        }

        function showTokenPopup() {
            Swal.fire({
                title: 'Masukkan Display Token',
                input: 'text',
                inputPlaceholder: 'Masukkan token di sini...',
                allowOutsideClick: false,
                allowEscapeKey: false,
                customClass: {
                    popup: 'popup-token'
                },
                backdrop: true,
                preConfirm: (token) => {
                    if (!token) {
                        Swal.showValidationMessage('Token tidak boleh kosong');
                        return false;
                    }
                    return fetch('{{ route('check-token') }}', {
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
                                Swal.showValidationMessage(data.message || 'Token tidak valid');
                                return false;
                            }
                            document.cookie = "display_token=" + encodeURIComponent(token) + "; max-age=" +
                                (24 * 60 * 60) + "; path=/";
                            return true;
                        })
                        .catch(() => {
                            Swal.showValidationMessage('Terjadi kesalahan koneksi');
                            return false;
                        });
                }
            });
        }

        window.addEventListener('DOMContentLoaded', () => {
            let token = getCookie('display_token');

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
                            deleteCookie('display_token');
                            showTokenPopup();
                        }
                    })
                    .catch(() => {
                        Swal.fire({
                            icon: 'error',
                            title: 'Koneksi Error',
                            text: 'Tidak bisa memvalidasi token.'
                        });
                    });
            }
        });
    </script>
</x-voting-layout>
