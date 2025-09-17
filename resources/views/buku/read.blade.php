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
</head>

<body class="bg-gray-50">
    <div class="h-screen flex flex-col">
        <!-- Compact Header -->
        <header class="bg-white shadow-sm border-b border-gray-200 relative z-10">
            <div class="px-4 py-2">
                <div class="flex items-center justify-between">
                    <!-- Left: Compact back and title -->
                    <div class="flex items-center space-x-3">
                        <a href="{{ route('buku.show', $buku) }}"
                            class="inline-flex items-center px-2 py-1 text-sm text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded transition-colors">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 19l-7-7 7-7"></path>
                            </svg>
                            <span class="hidden sm:inline">Kembali</span>
                        </a>

                        <div class="hidden md:block">
                            <h1 class="text-sm font-semibold text-gray-900 truncate max-w-xs">{{ $buku->judul }}</h1>
                        </div>
                    </div>

                    <!-- Right: Compact controls -->
                    <div class="flex items-center space-x-3">
                        <!-- Navigation -->
                        <div class="flex items-center space-x-2">
                            <button id="prevPage"
                                class="p-1 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded transition-colors"
                                title="Halaman Sebelumnya">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 19l-7-7 7-7"></path>
                                </svg>
                            </button>

                            <div class="flex items-center space-x-1 bg-gray-50 rounded px-2 py-1">
                                <input type="number" id="pageInput"
                                    class="w-8 text-center text-xs bg-transparent border-none focus:outline-none"
                                    value="{{ $progress->halaman_sekarang }}" min="1">
                                <span class="text-xs text-gray-500">/<span id="totalPages">0</span></span>
                            </div>

                            <button id="nextPage"
                                class="p-1 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded transition-colors"
                                title="Halaman Selanjutnya">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5l7 7-7 7"></path>
                                </svg>
                            </button>
                        </div>

                        <!-- Progress -->
                        <div class="hidden sm:flex items-center space-x-2 text-xs">
                            <div class="w-16 h-1.5 bg-gray-200 rounded-full">
                                <div id="progressBar" class="h-1.5 bg-blue-500 rounded-full transition-all duration-300"
                                    style="width: 0%"></div>
                            </div>
                            <span class="text-gray-600 min-w-max">
                                <span id="progressPercent">0</span>%
                            </span>
                        </div>

                        <!-- Actions -->
                        <div class="flex items-center space-x-1">
                            <!-- Zoom -->
                            <div class="hidden md:flex items-center space-x-1">
                                <button id="zoomOut"
                                    class="p-1 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded transition-colors"
                                    title="Perkecil">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M20 12H4"></path>
                                    </svg>
                                </button>
                                <span id="zoomLevel" class="text-xs text-gray-600 min-w-max">100%</span>
                                <button id="zoomIn"
                                    class="p-1 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded transition-colors"
                                    title="Perbesar">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                </button>
                            </div>

                            <button id="addBookmark"
                                class="p-1 text-gray-600 hover:text-blue-600 hover:bg-blue-50 rounded transition-colors"
                                title="Bookmark">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path>
                                </svg>
                            </button>

                            <button id="fullscreen"
                                class="p-1 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded transition-colors hidden md:block"
                                title="Fullscreen">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4">
                                    </path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Mobile progress -->
                <div class="sm:hidden mt-2 pb-1">
                    <div class="flex items-center justify-between text-xs text-gray-600 mb-1">
                        <span>Hal. <span id="currentPageMobile">{{ $progress->halaman_sekarang }}</span> dari
                            <span id="totalPagesMobile">0</span></span>
                        <span><span id="progressPercentMobile">0</span>%</span>
                    </div>
                    <div class="w-full h-1 bg-gray-200 rounded-full">
                        <div id="progressBarMobile" class="h-1 bg-blue-500 rounded-full transition-all duration-300"
                            style="width: 0%"></div>
                    </div>
                </div>
            </div>
        </header>

        <!-- PDF Viewer - Full height minus header -->
        <div class="flex-1 bg-gray-100 overflow-hidden">
            <div id="pdfContainer" class="h-full flex items-center justify-center p-4">
                <!-- Loading -->
                <div id="loadingIndicator" class="text-center">
                    <div class="w-12 h-12 border-4 border-blue-200 border-t-blue-500 rounded-full animate-spin mb-4">
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Memuat buku...</h3>
                    <p class="text-gray-600">Mohon tunggu sebentar</p>
                    <div class="mt-4">
                        <div id="loadingProgress" class="w-64 h-2 bg-gray-200 rounded-full mx-auto">
                            <div id="loadingProgressBar"
                                class="h-2 bg-blue-500 rounded-full transition-all duration-300" style="width: 0%">
                            </div>
                        </div>
                        <p id="loadingStatus" class="text-sm text-gray-500 mt-2">Menghubungkan ke server...</p>
                    </div>
                </div>

                <!-- PDF Canvas -->
                <div id="canvasContainer" class="relative hidden h-full w-full flex items-center justify-center">
                    <canvas id="pdfCanvas" class="max-w-full max-h-full shadow-lg bg-white"></canvas>

                    <!-- Minimal page indicator -->
                    <div class="absolute top-2 right-2 bg-black bg-opacity-60 text-white px-2 py-1 rounded text-xs">
                        <span id="pageIndicator">0 / 0</span>
                    </div>
                </div>

                <!-- Error State -->
                <div id="errorContainer" class="text-center hidden">
                    <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Terjadi Kesalahan</h3>
                    <p id="errorMessage" class="text-red-600 mb-4">Gagal memuat PDF</p>
                    <div class="space-x-2">
                        <button onclick="location.reload()"
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                            Coba Lagi
                        </button>
                        <a href="{{ route('buku.show', $buku) }}"
                            class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                            Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Simple Bookmark Modal -->
    <div id="bookmarkModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
        <div class="bg-white rounded-lg p-6 w-full max-w-md">
            <div class="flex items-center space-x-3 mb-4">
                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-900">Tambah Bookmark</h3>
                    <p class="text-sm text-gray-600">Halaman <span id="bookmarkPageNumber">0</span></p>
                </div>
            </div>

            <form id="bookmarkForm" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Catatan (opsional)</label>
                    <textarea id="bookmarkNote" rows="3"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="Tambahkan catatan..."></textarea>
                </div>

                <div class="flex space-x-3">
                    <button type="button" id="cancelBookmark"
                        class="flex-1 px-4 py-2 text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-md transition-colors">
                        Batal
                    </button>
                    <button type="submit"
                        class="flex-1 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md transition-colors">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Simple Notification -->
    <div id="notification"
        class="fixed top-4 right-4 transform translate-x-full transition-transform duration-300 z-50">
        <div
            class="bg-white rounded-lg shadow-lg border border-gray-200 p-4 flex items-center space-x-3 min-w-[280px]">
            <div id="notificationIcon"></div>
            <div>
                <p id="notificationMessage" class="font-medium text-gray-900"></p>
                <p id="notificationSubtext" class="text-sm text-gray-600"></p>
            </div>
        </div>
    </div>

    <script>
        class SimplePDFReader {
            constructor() {
                this.pdfDoc = null;
                this.currentPage = {{ $progress->halaman_sekarang }};
                this.totalPages = 0;
                this.scale = 1.0;
                this.canvas = document.getElementById('pdfCanvas');
                this.ctx = this.canvas.getContext('2d');
                this.bookId = {{ $buku->id }};
                this.pdfUrl = '{{ route('pdf.stream', $buku) }}';
                this.isLoading = false;
                this.isInitialized = false;

                // Bind methods to preserve context
                this.init = this.init.bind(this);
                this.renderPage = this.renderPage.bind(this);

                console.log('PDF Reader initialized with URL:', this.pdfUrl);
                this.init();
            }

            async init() {
                if (this.isLoading || this.isInitialized) {
                    console.log('Already loading or initialized, skipping...');
                    return;
                }

                this.isLoading = true;
                this.updateLoadingStatus('Menghubungkan ke server...', 10);

                try {
                    // Set PDF.js worker
                    pdfjsLib.GlobalWorkerOptions.workerSrc =
                        'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';

                    this.updateLoadingStatus('Memuat PDF...', 30);
                    console.log('Starting PDF load...');

                    // Load PDF with timeout and better error handling
                    const loadingTask = pdfjsLib.getDocument({
                        url: this.pdfUrl,
                        httpHeaders: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                'content')
                        },
                        withCredentials: true
                    });

                    // Track loading progress
                    loadingTask.onProgress = (progress) => {
                        if (progress.total > 0) {
                            const percent = Math.round((progress.loaded / progress.total) * 50) + 30; // 30-80%
                            this.updateLoadingStatus('Mengunduh PDF...', percent);
                        }
                    };

                    this.pdfDoc = await loadingTask.promise;
                    this.totalPages = this.pdfDoc.numPages;

                    console.log('PDF loaded successfully. Total pages:', this.totalPages);
                    this.updateLoadingStatus('Mempersiapkan tampilan...', 90);

                    // Validate current page
                    if (this.currentPage > this.totalPages) {
                        this.currentPage = 1;
                    }

                    // Update UI elements
                    this.updateTotalPagesUI();

                    // Hide loading and show canvas
                    document.getElementById('loadingIndicator').style.display = 'none';
                    document.getElementById('canvasContainer').classList.remove('hidden');

                    // Render first page
                    await this.renderPage(this.currentPage);

                    // Setup event listeners
                    this.setupEventListeners();

                    // Start auto save
                    this.startAutoSave();

                    this.isInitialized = true;
                    this.isLoading = false;

                    console.log('PDF Reader fully initialized');

                    // Show success notification
                    this.showNotification('Buku berhasil dimuat!', `${this.totalPages} halaman siap dibaca`, 'success');

                } catch (error) {
                    this.isLoading = false;
                    console.error('Error loading PDF:', error);
                    this.showError('Gagal memuat PDF: ' + error.message);
                }
            }

            updateLoadingStatus(message, percent) {
                const statusEl = document.getElementById('loadingStatus');
                const progressBar = document.getElementById('loadingProgressBar');

                if (statusEl) statusEl.textContent = message;
                if (progressBar) progressBar.style.width = percent + '%';
            }

            updateTotalPagesUI() {
                // Update all total pages displays
                document.getElementById('totalPages').textContent = this.totalPages;
                document.getElementById('totalPagesMobile').textContent = this.totalPages;
                document.getElementById('pageInput').setAttribute('max', this.totalPages);
            }

            async renderPage(pageNum) {
                if (!this.pdfDoc || this.isLoading) {
                    console.log('PDF not loaded or still loading, skipping render');
                    return;
                }

                try {
                    console.log('Rendering page:', pageNum);
                    const page = await this.pdfDoc.getPage(pageNum);

                    // Calculate scale based on container size
                    const container = document.getElementById('canvasContainer');
                    const containerWidth = container.clientWidth - 40; // padding
                    const containerHeight = container.clientHeight - 40; // padding

                    const viewport = page.getViewport({
                        scale: 1.0
                    });
                    const scaleX = containerWidth / viewport.width;
                    const scaleY = containerHeight / viewport.height;
                    const autoScale = Math.min(scaleX, scaleY, 2.0); // max scale 2.0

                    const finalScale = this.scale * autoScale;
                    const scaledViewport = page.getViewport({
                        scale: finalScale
                    });

                    this.canvas.height = scaledViewport.height;
                    this.canvas.width = scaledViewport.width;

                    const renderContext = {
                        canvasContext: this.ctx,
                        viewport: scaledViewport
                    };

                    await page.render(renderContext).promise;
                    console.log('Page rendered successfully:', pageNum);

                    this.updateUI();

                } catch (error) {
                    console.error('Error rendering page:', error);
                    this.showNotification('Gagal memuat halaman', 'Silakan coba lagi', 'error');
                }
            }

            updateUI() {
                if (this.totalPages === 0) return;

                const progress = (this.currentPage / this.totalPages) * 100;

                // Update page numbers
                document.getElementById('pageInput').value = this.currentPage;
                document.getElementById('pageIndicator').textContent = `${this.currentPage} / ${this.totalPages}`;

                // Update progress bars
                document.getElementById('progressPercent').textContent = progress.toFixed(1);
                document.getElementById('progressBar').style.width = progress + '%';

                // Mobile updates
                const mobileProgress = document.getElementById('progressBarMobile');
                const mobilePercent = document.getElementById('progressPercentMobile');
                const mobilePage = document.getElementById('currentPageMobile');

                if (mobileProgress) mobileProgress.style.width = progress + '%';
                if (mobilePercent) mobilePercent.textContent = progress.toFixed(1);
                if (mobilePage) mobilePage.textContent = this.currentPage;

                // Update zoom level
                document.getElementById('zoomLevel').textContent = Math.round(this.scale * 100) + '%';

                // Update navigation buttons
                document.getElementById('prevPage').disabled = this.currentPage <= 1;
                document.getElementById('nextPage').disabled = this.currentPage >= this.totalPages;

                // Update bookmark page number
                document.getElementById('bookmarkPageNumber').textContent = this.currentPage;
            }

            setupEventListeners() {
                console.log('Setting up event listeners...');

                // Navigation
                document.getElementById('prevPage').onclick = () => this.prevPage();
                document.getElementById('nextPage').onclick = () => this.nextPage();

                // Page input
                document.getElementById('pageInput').addEventListener('keypress', (e) => {
                    if (e.key === 'Enter') {
                        const pageNum = parseInt(e.target.value);
                        if (pageNum >= 1 && pageNum <= this.totalPages) {
                            this.goToPage(pageNum);
                        } else {
                            e.target.value = this.currentPage;
                        }
                    }
                });

                document.getElementById('pageInput').addEventListener('blur', (e) => {
                    const pageNum = parseInt(e.target.value);
                    if (pageNum >= 1 && pageNum <= this.totalPages) {
                        this.goToPage(pageNum);
                    } else {
                        e.target.value = this.currentPage;
                    }
                });

                // Zoom
                document.getElementById('zoomIn').onclick = () => this.zoomIn();
                document.getElementById('zoomOut').onclick = () => this.zoomOut();

                // Bookmark
                document.getElementById('addBookmark').onclick = () => this.showBookmarkModal();
                document.getElementById('cancelBookmark').onclick = () => this.hideBookmarkModal();
                document.getElementById('bookmarkForm').onsubmit = (e) => this.saveBookmark(e);

                // Fullscreen
                document.getElementById('fullscreen').onclick = () => this.toggleFullscreen();

                // Keyboard shortcuts
                document.addEventListener('keydown', (e) => {
                    if (e.key === 'ArrowLeft' && !e.target.matches('input')) {
                        e.preventDefault();
                        this.prevPage();
                    }
                    if (e.key === 'ArrowRight' && !e.target.matches('input')) {
                        e.preventDefault();
                        this.nextPage();
                    }
                    if (e.key === 'Escape') this.hideBookmarkModal();
                });

                // Mobile swipe support
                this.setupTouchEvents();

                console.log('Event listeners setup complete');
            }

            setupTouchEvents() {
                let touchStartX = 0;
                let touchStartY = 0;

                this.canvas.addEventListener('touchstart', (e) => {
                    touchStartX = e.changedTouches[0].screenX;
                    touchStartY = e.changedTouches[0].screenY;
                });

                this.canvas.addEventListener('touchend', (e) => {
                    const touchEndX = e.changedTouches[0].screenX;
                    const touchEndY = e.changedTouches[0].screenY;

                    const diffX = touchStartX - touchEndX;
                    const diffY = Math.abs(touchStartY - touchEndY);

                    // Only horizontal swipes
                    if (Math.abs(diffX) > 50 && diffY < 100) {
                        if (diffX > 0) {
                            this.nextPage();
                        } else {
                            this.prevPage();
                        }
                    }
                });
            }

            async prevPage() {
                if (this.currentPage > 1 && !this.isLoading) {
                    this.currentPage--;
                    await this.renderPage(this.currentPage);
                    this.saveProgress();
                }
            }

            async nextPage() {
                if (this.currentPage < this.totalPages && !this.isLoading) {
                    this.currentPage++;
                    await this.renderPage(this.currentPage);
                    this.saveProgress();
                }
            }

            async goToPage(pageNum) {
                if (pageNum >= 1 && pageNum <= this.totalPages && !this.isLoading) {
                    this.currentPage = pageNum;
                    await this.renderPage(this.currentPage);
                    this.saveProgress();
                }
            }

            async zoomIn() {
                if (this.scale < 3.0 && !this.isLoading) {
                    this.scale = Math.min(this.scale + 0.25, 3.0);
                    await this.renderPage(this.currentPage);
                }
            }

            async zoomOut() {
                if (this.scale > 0.5 && !this.isLoading) {
                    this.scale = Math.max(this.scale - 0.25, 0.5);
                    await this.renderPage(this.currentPage);
                }
            }

            toggleFullscreen() {
                if (!document.fullscreenElement) {
                    document.documentElement.requestFullscreen();
                } else {
                    document.exitFullscreen();
                }
            }

            showBookmarkModal() {
                document.getElementById('bookmarkPageNumber').textContent = this.currentPage;
                document.getElementById('bookmarkModal').classList.remove('hidden');
                document.getElementById('bookmarkModal').classList.add('flex');
                document.getElementById('bookmarkNote').focus();
            }

            hideBookmarkModal() {
                document.getElementById('bookmarkModal').classList.add('hidden');
                document.getElementById('bookmarkModal').classList.remove('flex');
                document.getElementById('bookmarkNote').value = '';
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
                        throw new Error('Server error');
                    }
                } catch (error) {
                    console.error('Error saving bookmark:', error);
                    this.showNotification('Gagal menyimpan bookmark', 'Silakan coba lagi', 'error');
                }
            }

            async saveProgress() {
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
                    if (this.isInitialized) {
                        this.saveProgress();
                    }
                }, 30000);
            }

            showNotification(title, message, type = 'success') {
                const notification = document.getElementById('notification');
                const icon = document.getElementById('notificationIcon');
                const titleEl = document.getElementById('notificationMessage');
                const messageEl = document.getElementById('notificationSubtext');

                titleEl.textContent = title;
                messageEl.textContent = message;

                if (type === 'success') {
                    icon.innerHTML =
                        '<div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center"><svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg></div>';
                } else {
                    icon.innerHTML =
                        '<div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center"><svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></div>';
                }

                notification.classList.remove('translate-x-full');

                setTimeout(() => {
                    notification.classList.add('translate-x-full');
                }, 3000);
            }

            showError(message) {
                document.getElementById('loadingIndicator').style.display = 'none';
                document.getElementById('canvasContainer').classList.add('hidden');
                document.getElementById('errorContainer').classList.remove('hidden');
                document.getElementById('errorMessage').textContent = message;

                this.showNotification('Error', message, 'error');
            }
        }

        // Initialize when page loads
        document.addEventListener('DOMContentLoaded', () => {
            console.log('DOM loaded, initializing PDF Reader...');
            new SimplePDFReader();
        });
    </script>
</body>

</html>
