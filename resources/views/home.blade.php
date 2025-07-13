<!-- resources/views/home.blade.php -->
@extends('layouts.app')

@section('content')
    <!-- Hero Carousel Section -->
    <div class="relative overflow-hidden">
        <div class="carousel-container relative h-96 md:h-[500px]" id="heroCarousel">
            <!-- Slide 1 -->
            <div class="carousel-slide active h-full flex items-center justify-center bg-cover bg-center bg-no-repeat relative"
                style="background-image: url('https://images.unsplash.com/photo-1524995997946-a1c2e315a42f?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80');">
                <!-- Dark overlay -->
                <div class="absolute inset-0 bg-black/40"></div>
                <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                    <h1 class="text-3xl md:text-5xl font-bold text-white mb-4">
                        Selamat Datang di Perpustakaan Digital
                    </h1>
                    <p class="text-lg md:text-xl text-gray-200 mb-8 max-w-3xl mx-auto">
                        Temukan ribuan buku dari berbagai kategori. Baca kapan saja, di mana saja dengan pengalaman membaca
                        yang nyaman.
                    </p>
                </div>
            </div>

            <!-- Slide 2 -->
            <div class="carousel-slide h-full flex items-center justify-center bg-cover bg-center bg-no-repeat relative"
                style="background-image: url('https://images.unsplash.com/photo-1481627834876-b7833e8f5570?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2128&q=80');">
                <!-- Dark overlay -->
                <div class="absolute inset-0 bg-black/40"></div>
                <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                    <h1 class="text-3xl md:text-5xl font-bold text-white mb-4">
                        📚 Koleksi Lengkap
                    </h1>
                    <p class="text-lg md:text-xl text-gray-200 mb-8 max-w-3xl mx-auto">
                        Ribuan buku dari berbagai genre dan kategori menanti Anda. Dari fiksi hingga non-fiksi, temukan
                        bacaan favorit Anda.
                    </p>
                </div>
            </div>

            <!-- Slide 3 -->
            <div class="carousel-slide h-full flex items-center justify-center bg-cover bg-center bg-no-repeat relative"
                style="background-image: url('https://images.unsplash.com/photo-1524995997946-a1c2e315a42f?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80');">
                <!-- Dark overlay -->
                <div class="absolute inset-0 bg-black/40"></div>
                <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                    <h1 class="text-3xl md:text-5xl font-bold text-white mb-4">
                        🎯 Rekomendasi Personal
                    </h1>
                    <p class="text-lg md:text-xl text-gray-200 mb-8 max-w-3xl mx-auto">
                        Dapatkan rekomendasi buku yang disesuaikan dengan preferensi dan riwayat membaca Anda.
                    </p>
                </div>
            </div>
        </div>

        <!-- Carousel Indicators -->
        <div class="absolute bottom-6 left-1/2 transform -translate-x-1/2 flex space-x-3 z-10">
            <button
                class="carousel-indicator w-4 h-4 rounded-full bg-white/60 hover:bg-white transition-all duration-200 active"
                data-slide="0"></button>
            <button class="carousel-indicator w-4 h-4 rounded-full bg-white/60 hover:bg-white transition-all duration-200"
                data-slide="1"></button>
            <button class="carousel-indicator w-4 h-4 rounded-full bg-white/60 hover:bg-white transition-all duration-200"
                data-slide="2"></button>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 space-y-16">

        @auth
            @if ($rekomendasi->count() > 0)
                <!-- Rekomendasi untuk User -->
                <section>
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-2xl font-semibold text-gray-900">Rekomendasi untuk Anda</h2>
                        <a href="{{ route('rekomendasi') }}"
                            class="text-primary-600 text-sm font-medium hover:text-primary-700 transition-colors">Lihat
                            Semua</a>
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
                        @foreach ($rekomendasi as $buku)
                            <div class="card h-full flex flex-col">
                                <div class="p-3 flex-1 flex flex-col">
                                    <div
                                        class="aspect-[3/4] bg-primary-100 rounded mb-3 flex items-center justify-center overflow-hidden">
                                        @if ($buku->gambar_sampul)
                                            <img src="{{ $buku->gambar_sampul_url }}" alt="{{ $buku->judul }}"
                                                class="w-full h-full object-cover rounded">
                                        @else
                                            <svg class="w-8 h-8 text-primary-600" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                                                </path>
                                            </svg>
                                        @endif
                                    </div>

                                    <div class="flex-1 flex flex-col">
                                        <h3 class="font-medium text-sm text-gray-900 mb-1 line-clamp-2 flex-1">
                                            {{ $buku->judul }}</h3>
                                        <p class="text-xs text-gray-600 mb-2">{{ $buku->penulis }}</p>
                                        <div class="flex items-center justify-between text-xs text-gray-500 mb-3">
                                            <span class="truncate">{{ $buku->subKategori->nama }}</span>
                                            <span class="flex items-center ml-1">★
                                                {{ number_format($buku->rating_rata_rata, 1) }}</span>
                                        </div>

                                        <a href="{{ route('buku.show', $buku) }}"
                                            class="btn btn-primary w-full text-xs py-2 mt-auto">
                                            Lihat Detail
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>
            @endif
        @endauth

        <!-- Kategori -->
        <section class="mb-16">
            <div class="text-center mb-10">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Pilih Kategori Favorit</h2>
                <p class="text-lg text-gray-600">Ribuan buku menanti untuk dieksplorasi</p>
            </div>

            <!-- Modern Cards -->
            <div class="space-y-6">
                @foreach ($kategori as $index => $kat)
                    <div
                        class="group relative bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden hover:shadow-xl transition-all duration-300">
                        <div class="flex flex-col md:flex-row">
                            <!-- Left: Category Info -->
                            <div class="flex-1 p-8">
                                <div class="flex items-start space-x-6">
                                    <!-- Large Icon -->
                                    <div class="flex-shrink-0">
                                        <div
                                            class="w-20 h-20 bg-gradient-to-br from-primary-100 via-primary-200 to-primary-300 rounded-2xl flex items-center justify-center shadow-lg group-hover:scale-105 transition-transform duration-300">
                                            <span
                                                class="text-3xl font-bold text-primary-700">{{ substr($kat->nama, 0, 1) }}</span>
                                        </div>
                                    </div>

                                    <div class="flex-1">
                                        <h3
                                            class="text-2xl font-bold text-gray-900 mb-3 group-hover:text-primary-700 transition-colors">
                                            {{ $kat->nama }}
                                        </h3>
                                        <p class="text-gray-600 mb-4 leading-relaxed">{{ $kat->deskripsi }}</p>

                                        <!-- Sub Categories Tags -->
                                        <div class="flex flex-wrap gap-2 mb-6">
                                            @foreach ($kat->subKategori->take(4) as $sub)
                                                <span
                                                    class="inline-block px-3 py-1 bg-gray-100 hover:bg-primary-100 text-gray-700 hover:text-primary-700 text-sm rounded-full transition-colors cursor-pointer">
                                                    {{ $sub->nama }}
                                                </span>
                                            @endforeach
                                            @if ($kat->subKategori->count() > 4)
                                                <span
                                                    class="inline-block px-3 py-1 bg-gray-200 text-gray-500 text-sm rounded-full">
                                                    +{{ $kat->subKategori->count() - 4 }} lainnya
                                                </span>
                                            @endif
                                        </div>

                                        <!-- Action Button -->
                                        <a href="{{ route('buku.index', ['kategori_utama' => $kat->id]) }}"
                                            class="inline-flex items-center px-6 py-3 bg-primary-600 hover:bg-primary-700 text-white font-medium rounded-xl transition-colors duration-200 shadow-sm group-hover:shadow-md">
                                            Eksplorasi {{ $kat->nama }}
                                            <svg class="w-4 h-4 ml-2 group-hover:translate-x-1 transition-transform"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!-- Right: Stats -->
                            <div class="md:w-48 bg-gray-50 p-8 flex flex-col justify-center">
                                <div class="text-center">
                                    <div class="text-3xl font-bold text-primary-600 mb-2">{{ $kat->buku_count ?? 0 }}</div>
                                    <div class="text-sm text-gray-600 mb-4">Total Buku</div>

                                    <div class="text-xl font-semibold text-gray-800 mb-1">{{ $kat->subKategori->count() }}
                                    </div>
                                    <div class="text-xs text-gray-500">Sub Kategori</div>
                                </div>
                            </div>
                        </div>

                        <!-- Decorative gradient -->
                        <div
                            class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-bl from-primary-100 to-transparent opacity-50 rounded-bl-3xl">
                        </div>
                    </div>
                @endforeach
            </div>
        </section>

        <!-- Buku Terbaru -->
        <section>
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-semibold text-gray-900">Buku Terbaru</h2>
                <a href="{{ route('buku.index', ['sort' => 'terbaru']) }}"
                    class="text-primary-600 text-sm font-medium hover:text-primary-700 transition-colors">Lihat Semua</a>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-8 gap-4">
                @foreach ($bukuTerbaru as $buku)
                    <div class="card h-full flex flex-col">
                        <div class="p-3 flex-1 flex flex-col">
                            <div
                                class="aspect-[3/4] bg-primary-100 rounded mb-3 flex items-center justify-center overflow-hidden">
                                @if ($buku->gambar_sampul)
                                    <img src="{{ $buku->gambar_sampul_url }}" alt="{{ $buku->judul }}"
                                        class="w-full h-full object-cover rounded">
                                @else
                                    <svg class="w-8 h-8 text-primary-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                                        </path>
                                    </svg>
                                @endif
                            </div>

                            <div class="flex-1 flex flex-col">
                                <h3 class="font-medium text-sm text-gray-900 mb-1 line-clamp-2 flex-1">{{ $buku->judul }}
                                </h3>
                                <p class="text-xs text-gray-600 mb-2">{{ $buku->penulis }}</p>
                                <div class="flex items-center justify-between text-xs text-gray-500 mb-3">
                                    <span class="truncate">{{ $buku->subKategori->nama }}</span>
                                    <span class="flex items-center ml-1">★
                                        {{ number_format($buku->rating_rata_rata, 1) }}</span>
                                </div>

                                <a href="{{ route('buku.show', $buku) }}"
                                    class="btn btn-primary w-full text-xs py-2 mt-auto">
                                    Lihat Detail
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>

        <!-- Buku Populer -->
        <section>
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-semibold text-gray-900">Buku Populer</h2>
                <a href="{{ route('buku.index', ['sort' => 'terpopuler']) }}"
                    class="text-primary-600 text-sm font-medium hover:text-primary-700 transition-colors">Lihat Semua</a>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-8 gap-4">
                @foreach ($bukuPopuler as $buku)
                    <div class="card h-full flex flex-col">
                        <div class="p-3 flex-1 flex flex-col">
                            <div
                                class="aspect-[3/4] bg-primary-100 rounded mb-3 flex items-center justify-center overflow-hidden">
                                @if ($buku->gambar_sampul)
                                    <img src="{{ $buku->gambar_sampul_url }}" alt="{{ $buku->judul }}"
                                        class="w-full h-full object-cover rounded">
                                @else
                                    <svg class="w-8 h-8 text-primary-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                                        </path>
                                    </svg>
                                @endif
                            </div>

                            <div class="flex-1 flex flex-col">
                                <h3 class="font-medium text-sm text-gray-900 mb-1 line-clamp-2 flex-1">{{ $buku->judul }}
                                </h3>
                                <p class="text-xs text-gray-600 mb-2">{{ $buku->penulis }}</p>
                                <div class="flex items-center justify-between text-xs text-gray-500 mb-3">
                                    <span class="truncate">{{ number_format($buku->total_pembaca) }} pembaca</span>
                                    <span class="flex items-center ml-1">★
                                        {{ number_format($buku->rating_rata_rata, 1) }}</span>
                                </div>

                                <a href="{{ route('buku.show', $buku) }}"
                                    class="btn btn-primary w-full text-xs py-2 mt-auto">
                                    Lihat Detail
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    </div>

    <style>
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .carousel-container {
            position: relative;
            overflow: hidden;
        }

        .carousel-slide {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            opacity: 0;
            transform: translateX(100%);
            transition: all 0.6s ease-in-out;
        }

        .carousel-slide.active {
            opacity: 1;
            transform: translateX(0);
        }

        .carousel-slide.prev {
            transform: translateX(-100%);
        }

        .carousel-indicator.active {
            background-color: white;
        }

        /* Card improvements untuk button alignment */
        .card {
            transition: all 0.2s ease-in-out;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        /* Ensure consistent card heights */
        .grid .card {
            height: 100%;
        }

        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        /* Custom hover animations */
        .group:hover .group-hover\:scale-110 {
            transform: scale(1.1);
        }

        .group:hover .group-hover\:translate-x-1 {
            transform: translateX(0.25rem);
        }

        /* Smooth transitions */
        .transition-all {
            transition-property: all;
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
            transition-duration: 300ms;
        }
    </style>

    <script>
        // Carousel functionality
        class Carousel {
            constructor() {
                this.currentSlide = 0;
                this.slides = document.querySelectorAll('.carousel-slide');
                this.indicators = document.querySelectorAll('.carousel-indicator');
                this.totalSlides = this.slides.length;
                this.autoPlayInterval = null;

                this.init();
            }

            init() {
                // Set up event listeners for indicators only
                this.indicators.forEach((indicator, index) => {
                    indicator.addEventListener('click', () => this.goToSlide(index));
                });

                // Start autoplay
                this.startAutoPlay();

                // Pause autoplay on hover
                const carousel = document.getElementById('heroCarousel');
                carousel.addEventListener('mouseenter', () => this.stopAutoPlay());
                carousel.addEventListener('mouseleave', () => this.startAutoPlay());
            }

            goToSlide(index) {
                // Remove active classes
                this.slides[this.currentSlide].classList.remove('active');
                this.indicators[this.currentSlide].classList.remove('active');

                // Update current slide
                this.currentSlide = index;

                // Add active classes
                this.slides[this.currentSlide].classList.add('active');
                this.indicators[this.currentSlide].classList.add('active');
            }

            nextSlide() {
                const nextIndex = (this.currentSlide + 1) % this.totalSlides;
                this.goToSlide(nextIndex);
            }

            startAutoPlay() {
                this.autoPlayInterval = setInterval(() => {
                    this.nextSlide();
                }, 5000); // Change slide every 5 seconds
            }

            stopAutoPlay() {
                if (this.autoPlayInterval) {
                    clearInterval(this.autoPlayInterval);
                    this.autoPlayInterval = null;
                }
            }
        }

        // Initialize carousel when DOM is loaded
        document.addEventListener('DOMContentLoaded', () => {
            new Carousel();
        });
    </script>
@endsection
