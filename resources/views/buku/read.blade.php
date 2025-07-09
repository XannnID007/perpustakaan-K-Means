<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $buku->judul }} - Perpustakaan Digital</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- PDF.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js"></script>
</head>

<body class="bg-gradient-to-br from-slate-50 to-stone-100 text-gray-800">
    <div class="h-screen flex flex-col">
        <!-- Modern Header -->
        <header class="bg-white/90 backdrop-blur-md border-b border-gray-200/50 shadow-sm">
            <div class="px-6 py-4">
                <div class="flex items-center justify-between">
                    <!-- Left Section - Book Info -->
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('buku.show', $buku) }}"
                            class="p-2.5 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-xl transition-all duration-200">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                        </a>

                        <div class="hidden md:block w-px h-8 bg-gray-200"></div>

                        <div>
                            <h1 class="text-lg font-semibold text-gray-900 line-clamp-1">{{ $buku->judul }}</h1>
                            <p class="text-sm text-gray-500">{{ $buku->penulis }}</p>
                        </div>
                    </div>

                    <!-- Right Section - Progress & User -->
                    <div class="flex items-center space-x-6">
                        <!-- Progress Info -->
                        <div class="hidden sm:flex items-center space-x-4">
                            <div class="text-right">
                                <div class="text-sm font-medium text-gray-900">
                                    <span id="currentPage">{{ $progress->halaman_sekarang }}</span> dari
                                    <span id="totalPages">{{ $progress->total_halaman }}</span>
                                </div>
                                <div class="text-xs text-gray-500">
                                    <span
                                        id="progressPercent">{{ number_format($progress->persentase_baca, 1) }}</span>%
                                    selesai
                                </div>
                            </div>

                            <!-- Modern Progress Bar -->
                            <div class="w-32 h-2 bg-gray-200 rounded-full overflow-hidden">
                                <div id="progressBar"
                                    class="h-full bg-gradient-to-r from-primary-500 to-primary-600 rounded-full transition-all duration-500 ease-out"
                                    style="width: {{ $progress->persentase_baca }}%"></div>
                            </div>
                        </div>

                        <!-- User Avatar -->
                        <div class="w-8 h-8 bg-primary-100 rounded-full flex items-center justify-center">
                            <span
                                class="text-primary-700 font-medium text-sm">{{ substr(auth()->user()->name, 0, 1) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Modern Control Bar -->
        <div class="bg-white/80 backdrop-blur-sm border-b border-gray-200/50">
            <div class="px-6 py-3">
                <div class="flex items-center justify-between">
                    <!-- Left Controls - Navigation -->
                    <div class="flex items-center space-x-2">
                        <button id="prevPage" class="control-btn group" title="Halaman Sebelumnya">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 19l-7-7 7-7"></path>
                            </svg>
                        </button>

                        <div class="flex items-center space-x-2 px-3 py-2 bg-gray-50 rounded-lg">
                            <input type="number" id="pageInput"
                                class="w-12 text-center text-sm font-medium bg-transparent border-none focus:outline-none"
                                value="{{ $progress->halaman_sekarang }}" min="1"
                                max="{{ $progress->total_halaman }}">
                            <span class="text-sm text-gray-500">dari {{ $progress->total_halaman }}</span>
                        </div>

                        <button id="nextPage" class="control-btn group" title="Halaman Selanjutnya">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                                </path>
                            </svg>
                        </button>

                        <div class="hidden sm:block w-px h-6 bg-gray-200 mx-2"></div>

                        <!-- Mobile Progress -->
                        <div class="sm:hidden flex items-center space-x-2">
                            <div class="w-16 h-1.5 bg-gray-200 rounded-full">
                                <div id="progressBarMobile"
                                    class="h-full bg-primary-500 rounded-full transition-all duration-300"
                                    style="width: {{ $progress->persentase_baca }}%"></div>
                            </div>
                            <span class="text-xs text-gray-500">
                                <span
                                    id="progressPercentMobile">{{ number_format($progress->persentase_baca, 1) }}</span>%
                            </span>
                        </div>
                    </div>

                    <!-- Right Controls - Actions -->
                    <div class="flex items-center space-x-2">
                        <!-- Zoom Controls -->
                        <div class="hidden md:flex items-center space-x-1 bg-gray-50 rounded-lg p-1">
                            <button id="zoomOut" class="control-btn-sm" title="Perkecil">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4">
                                    </path>
                                </svg>
                            </button>

                            <span id="zoomLevel" class="text-xs font-medium text-gray-600 w-12 text-center">100%</span>

                            <button id="zoomIn" class="control-btn-sm" title="Perbesar">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                            </button>
                        </div>

                        <!-- Action Buttons -->
                        <button id="addBookmark" class="action-btn" title="Tambah Bookmark">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path>
                            </svg>
                        </button>

                        <button id="fullscreen" class="action-btn hidden md:flex" title="Layar Penuh">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4">
                                </path>
                            </svg>
                        </button>

                        <!-- Settings Dropdown -->
                        <div class="relative">
                            <button id="settingsToggle" class="action-btn" title="Pengaturan">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z">
                                    </path>
                                </svg>
                            </button>

                            <!-- Settings Dropdown Menu -->
                            <div id="settingsMenu"
                                class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-gray-200 py-2 hidden z-10">
                                <button
                                    class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 flex items-center space-x-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                    </svg>
                                    <span>Download PDF</span>
                                </button>
                                <button
                                    class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 flex items-center space-x-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z">
                                        </path>
                                    </svg>
                                    <span>Bagikan</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- PDF Viewer Container -->
        <div class="flex-1 overflow-hidden relative">
            <div id="pdfContainer"
                class="h-full flex items-center justify-center bg-gradient-to-br from-gray-50 to-gray-100">
                <!-- Loading State -->
                <div id="loadingIndicator" class="text-center">
                    <div class="relative">
                        <div class="w-16 h-16 border-4 border-primary-200 rounded-full animate-spin mx-auto mb-6">
                        </div>
                        <div
                            class="w-10 h-10 bg-primary-500 rounded-full absolute top-3 left-1/2 transform -translate-x-1/2 animate-pulse">
                        </div>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Memuat buku...</h3>
                    <p class="text-gray-500">Mohon tunggu sebentar</p>
                    <p id="errorMessage" class="text-red-500 text-sm mt-4 hidden"></p>
                </div>

                <!-- PDF Canvas -->
                <div id="canvasContainer" class="relative hidden">
                    <canvas id="pdfCanvas"
                        class="max-w-full max-h-full shadow-2xl rounded-lg border border-white/20"></canvas>

                    <!-- Reading Progress Indicator -->
                    <div
                        class="absolute top-4 right-4 bg-black/70 text-white px-3 py-1 rounded-full text-sm backdrop-blur-sm">
                        <span id="pageIndicator">{{ $progress->halaman_sekarang }} /
                            {{ $progress->total_halaman }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modern Bookmark Modal -->
    <div id="bookmarkModal"
        class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden items-center justify-center z-50 p-4">
        <div class="bg-white rounded-2xl p-6 w-full max-w-md shadow-2xl transform transition-all duration-300 scale-95 opacity-0"
            id="bookmarkModalContent">
            <div class="flex items-center space-x-3 mb-6">
                <div class="w-10 h-10 bg-primary-100 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Tambah Bookmark</h3>
                    <p class="text-sm text-gray-500">Halaman <span
                            id="bookmarkPageNumber">{{ $progress->halaman_sekarang }}</span></p>
                </div>
            </div>

            <form id="bookmarkForm" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Catatan (opsional)</label>
                    <textarea id="bookmarkNote" rows="4"
                        class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent resize-none"
                        placeholder="Tambahkan catatan untuk bookmark ini..."></textarea>
                </div>

                <div class="flex space-x-3 pt-2">
                    <button type="button" id="cancelBookmark"
                        class="flex-1 px-4 py-3 text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-xl font-medium transition-colors">
                        Batal
                    </button>
                    <button type="submit"
                        class="flex-1 px-4 py-3 bg-primary-600 hover:bg-primary-700 text-white rounded-xl font-medium transition-colors">
                        Simpan Bookmark
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modern Notification -->
    <div id="notification"
        class="fixed top-4 right-4 transform translate-x-full transition-transform duration-300 z-50">
        <div
            class="bg-white rounded-xl shadow-lg border border-gray-200 p-4 flex items-center space-x-3 min-w-[300px]">
            <div id="notificationIcon" class="flex-shrink-0"></div>
            <div>
                <p id="notificationMessage" class="font-medium text-gray-900"></p>
                <p id="notificationSubtext" class="text-sm text-gray-500"></p>
            </div>
        </div>
    </div>

    <style>
        .control-btn {
            @apply p-2.5 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-xl transition-all duration-200 flex items-center justify-center;
        }

        .control-btn:disabled {
            @apply text-gray-300 hover:text-gray-300 hover:bg-transparent cursor-not-allowed;
        }

        .control-btn-sm {
            @apply p-2 text-gray-600 hover:text-gray-900 hover:bg-white rounded-lg transition-all duration-200;
        }

        .action-btn {
            @apply p-2.5 text-gray-600 hover:text-primary-600 hover:bg-primary-50 rounded-xl transition-all duration-200 flex items-center justify-center;
        }

        .line-clamp-1 {
            display: -webkit-box;
            -webkit-line-clamp: 1;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        @keyframes slideInRight {
            from {
                transform: translateX(100%);
            }

            to {
                transform: translateX(0);
            }
        }

        @keyframes slideOutRight {
            from {
                transform: translateX(0);
            }

            to {
                transform: translateX(100%);
            }
        }

        .slide-in {
            animation: slideInRight 0.3s ease-out forwards;
        }

        .slide-out {
            animation: slideOutRight 0.3s ease-in forwards;
        }
    </style>

    <script>
        // Enhanced PDF Reader with modern interactions
        class ModernPDFReader {
            constructor() {
                this.pdfDoc = null;
                this.currentPage = {{ $progress->halaman_sekarang }};
                this.totalPages = {{ $progress->total_halaman }};
                this.scale = 1.0;
                this.canvas = document.getElementById('pdfCanvas');
                this.ctx = this.canvas.getContext('2d');
                this.container = document.getElementById('pdfContainer');
                this.bookId = {{ $buku->id }};
                this.progressSaveTimer = null;
                this.pdfUrl = '{{ route('pdf.stream', $buku) }}';

                this.init();
            }

            async init() {
                try {
                    console.log('Loading PDF from:', this.pdfUrl);

                    pdfjsLib.GlobalWorkerOptions.workerSrc =
                        'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';

                    const loadingTask = pdfjsLib.getDocument({
                        url: this.pdfUrl,
                        httpHeaders: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                'content')
                        }
                    });

                    this.pdfDoc = await loadingTask.promise;
                    this.totalPages = this.pdfDoc.numPages;

                    console.log('PDF loaded successfully. Pages:', this.totalPages);

                    // Update UI
                    document.getElementById('totalPages').textContent = this.totalPages;
                    document.getElementById('loadingIndicator').style.display = 'none';
                    document.getElementById('canvasContainer').classList.remove('hidden');

                    // Render first page
                    await this.renderPage(this.currentPage);

                    // Setup event listeners
                    this.setupEventListeners();

                    // Start auto-save timer
                    this.startAutoSave();

                    // Show welcome notification
                    this.showNotification('Buku berhasil dimuat', 'Selamat membaca!', 'success');

                } catch (error) {
                    console.error('Error loading PDF:', error);
                    this.showError('Gagal memuat PDF: ' + error.message);
                }
            }

            showError(message) {
                document.getElementById('loadingIndicator').innerHTML =
                    `<div class="text-center">
                        <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Terjadi Kesalahan</h3>
                        <p class="text-red-600 mb-4">${message}</p>
                        <button onclick="location.reload()" class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">
                            Coba Lagi
                        </button>
                    </div>`;
            }

            async renderPage(pageNum) {
                try {
                    const page = await this.pdfDoc.getPage(pageNum);
                    const viewport = page.getViewport({
                        scale: this.scale
                    });

                    this.canvas.height = viewport.height;
                    this.canvas.width = viewport.width;

                    const renderContext = {
                        canvasContext: this.ctx,
                        viewport: viewport
                    };

                    await page.render(renderContext).promise;
                    this.updateUI();

                } catch (error) {
                    console.error('Error rendering page:', error);
                    this.showError('Gagal memuat halaman: ' + error.message);
                }
            }

            updateUI() {
                // Update all page indicators
                document.getElementById('currentPage').textContent = this.currentPage;
                document.getElementById('pageInput').value = this.currentPage;
                document.getElementById('pageIndicator').textContent = `${this.currentPage} / ${this.totalPages}`;

                // Update progress
                const progress = (this.currentPage / this.totalPages) * 100;
                document.getElementById('progressPercent').textContent = progress.toFixed(1);
                document.getElementById('progressBar').style.width = progress + '%';

                // Mobile progress
                const mobileProgress = document.getElementById('progressBarMobile');
                const mobilePercent = document.getElementById('progressPercentMobile');
                if (mobileProgress) mobileProgress.style.width = progress + '%';
                if (mobilePercent) mobilePercent.textContent = progress.toFixed(1);

                document.getElementById('zoomLevel').textContent = Math.round(this.scale * 100) + '%';

                // Update navigation buttons
                document.getElementById('prevPage').disabled = this.currentPage <= 1;
                document.getElementById('nextPage').disabled = this.currentPage >= this.totalPages;
            }

            setupEventListeners() {
                // Navigation
                document.getElementById('prevPage').addEventListener('click', () => this.prevPage());
                document.getElementById('nextPage').addEventListener('click', () => this.nextPage());

                // Page input
                document.getElementById('pageInput').addEventListener('change', (e) => {
                    const pageNum = parseInt(e.target.value);
                    if (pageNum >= 1 && pageNum <= this.totalPages) {
                        this.goToPage(pageNum);
                    } else {
                        e.target.value = this.currentPage;
                    }
                });

                // Zoom
                document.getElementById('zoomIn').addEventListener('click', () => this.zoomIn());
                document.getElementById('zoomOut').addEventListener('click', () => this.zoomOut());

                // Bookmark
                document.getElementById('addBookmark').addEventListener('click', () => this.showBookmarkModal());
                document.getElementById('cancelBookmark').addEventListener('click', () => this.hideBookmarkModal());
                document.getElementById('bookmarkForm').addEventListener('submit', (e) => this.saveBookmark(e));

                // Fullscreen
                document.getElementById('fullscreen').addEventListener('click', () => this.toggleFullscreen());

                // Settings menu
                document.getElementById('settingsToggle').addEventListener('click', () => this.toggleSettingsMenu());

                // Keyboard shortcuts
                document.addEventListener('keydown', (e) => {
                    switch (e.key) {
                        case 'ArrowLeft':
                            e.preventDefault();
                            this.prevPage();
                            break;
                        case 'ArrowRight':
                            e.preventDefault();
                            this.nextPage();
                            break;
                        case 'Escape':
                            this.hideBookmarkModal();
                            break;
                    }
                });

                // Close settings menu when clicking outside
                document.addEventListener('click', (e) => {
                    const settingsMenu = document.getElementById('settingsMenu');
                    const settingsToggle = document.getElementById('settingsToggle');
                    if (!settingsMenu.contains(e.target) && !settingsToggle.contains(e.target)) {
                        settingsMenu.classList.add('hidden');
                    }
                });
            }

            async prevPage() {
                if (this.currentPage > 1) {
                    this.currentPage--;
                    await this.renderPage(this.currentPage);
                    this.saveProgress();
                }
            }

            async nextPage() {
                if (this.currentPage < this.totalPages) {
                    this.currentPage++;
                    await this.renderPage(this.currentPage);
                    this.saveProgress();
                }
            }

            async goToPage(pageNum) {
                if (pageNum >= 1 && pageNum <= this.totalPages) {
                    this.currentPage = pageNum;
                    await this.renderPage(this.currentPage);
                    this.saveProgress();
                }
            }

            async zoomIn() {
                this.scale = Math.min(this.scale + 0.25, 3.0);
                await this.renderPage(this.currentPage);
            }

            async zoomOut() {
                this.scale = Math.max(this.scale - 0.25, 0.5);
                await this.renderPage(this.currentPage);
            }

            toggleFullscreen() {
                if (!document.fullscreenElement) {
                    document.documentElement.requestFullscreen();
                } else {
                    document.exitFullscreen();
                }
            }

            toggleSettingsMenu() {
                const menu = document.getElementById('settingsMenu');
                menu.classList.toggle('hidden');
            }

            showBookmarkModal() {
                document.getElementById('bookmarkPageNumber').textContent = this.currentPage;
                const modal = document.getElementById('bookmarkModal');
                const content = document.getElementById('bookmarkModalContent');

                modal.classList.remove('hidden');
                modal.classList.add('flex');

                setTimeout(() => {
                    content.classList.remove('scale-95', 'opacity-0');
                    content.classList.add('scale-100', 'opacity-100');
                }, 10);

                document.getElementById('bookmarkNote').focus();
            }

            hideBookmarkModal() {
                const modal = document.getElementById('bookmarkModal');
                const content = document.getElementById('bookmarkModalContent');

                content.classList.remove('scale-100', 'opacity-100');
                content.classList.add('scale-95', 'opacity-0');

                setTimeout(() => {
                    modal.classList.add('hidden');
                    modal.classList.remove('flex');
                    document.getElementById('bookmarkNote').value = '';
                }, 300);
            }

            async saveBookmark(e) {
                e.preventDefault();

                const note = document.getElementById('bookmarkNote').value;

                try {
                    const response = await fetch('/api/bookmark', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                'content')
                        },
                        body: JSON.stringify({
                            buku_id: this.bookId,
                            nomor_halaman: this.currentPage,
                            catatan: note,
                            tipe: 'bookmark'
                        })
                    });

                    if (response.ok) {
                        this.hideBookmarkModal();
                        this.showNotification('Bookmark tersimpan!', `Halaman ${this.currentPage} berhasil di-bookmark`,
                            'success');
                    } else {
                        throw new Error('Failed to save bookmark');
                    }
                } catch (error) {
                    console.error('Error saving bookmark:', error);
                    this.showNotification('Gagal menyimpan bookmark', 'Silakan coba lagi', 'error');
                }
            }

            saveProgress() {
                clearTimeout(this.progressSaveTimer);
                this.progressSaveTimer = setTimeout(() => {
                    this.doSaveProgress();
                }, 2000);
            }

            async doSaveProgress() {
                try {
                    await fetch('/api/progress', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                'content')
                        },
                        body: JSON.stringify({
                            buku_id: this.bookId,
                            halaman_sekarang: this.currentPage,
                            waktu_baca_tambahan: 30
                        })
                    });
                } catch (error) {
                    console.error('Error saving progress:', error);
                }
            }

            startAutoSave() {
                setInterval(() => {
                    this.doSaveProgress();
                }, 30000);
            }

            showNotification(title, message, type = 'success') {
                const notification = document.getElementById('notification');
                const icon = document.getElementById('notificationIcon');
                const titleEl = document.getElementById('notificationMessage');
                const messageEl = document.getElementById('notificationSubtext');

                // Set content
                titleEl.textContent = title;
                messageEl.textContent = message;

                // Set icon based on type
                if (type === 'success') {
                    icon.innerHTML = `
                        <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                    `;
                } else if (type === 'error') {
                    icon.innerHTML = `
                        <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </div>
                    `;
                } else {
                    icon.innerHTML = `
                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    `;
                }

                // Show notification
                notification.classList.remove('translate-x-full');
                notification.classList.add('slide-in');

                // Auto hide after 4 seconds
                setTimeout(() => {
                    notification.classList.remove('slide-in');
                    notification.classList.add('slide-out');

                    setTimeout(() => {
                        notification.classList.remove('slide-out');
                        notification.classList.add('translate-x-full');
                    }, 300);
                }, 4000);
            }
        }

        // Initialize PDF Reader when page loads
        document.addEventListener('DOMContentLoaded', () => {
            new ModernPDFReader();
        });

        // Prevent context menu on PDF canvas
        document.addEventListener('contextmenu', (e) => {
            if (e.target.id === 'pdfCanvas') {
                e.preventDefault();
            }
        });

        // Handle mobile swipe gestures
        let touchStartX = 0;
        let touchEndX = 0;

        document.getElementById('pdfCanvas').addEventListener('touchstart', (e) => {
            touchStartX = e.changedTouches[0].screenX;
        });

        document.getElementById('pdfCanvas').addEventListener('touchend', (e) => {
            touchEndX = e.changedTouches[0].screenX;
            handleSwipe();
        });

        function handleSwipe() {
            const swipeThreshold = 50;
            const diff = touchStartX - touchEndX;

            if (Math.abs(diff) > swipeThreshold) {
                if (diff > 0) {
                    // Swipe left - next page
                    document.getElementById('nextPage').click();
                } else {
                    // Swipe right - previous page
                    document.getElementById('prevPage').click();
                }
            }
        }

        // Smooth scroll to top on page change
        function smoothScrollToTop() {
            const container = document.getElementById('pdfContainer');
            container.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        }
    </script>
</body>

</html>
