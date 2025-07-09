<!-- resources/views/home.blade.php -->
@extends('layouts.app')

@section('content')
    <!-- Hero Section -->
    <div class="bg-gradient-to-br from-cream-50 to-primary-50 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                    Selamat Datang di Perpustakaan Digital
                </h1>
                <p class="text-lg text-gray-600 mb-8 max-w-2xl mx-auto">
                    Temukan ribuan buku dari berbagai kategori. Baca kapan saja, di mana saja dengan pengalaman membaca yang
                    nyaman.
                </p>

                @guest
                    <div class="space-x-4">
                        <a href="{{ route('register') }}" class="btn btn-primary px-6 py-3">Mulai Membaca</a>
                        <a href="{{ route('buku.index') }}" class="btn btn-outline px-6 py-3">Jelajahi Koleksi</a>
                    </div>
                @else
                    <div class="space-x-4">
                        <a href="{{ route('perpustakaan-saya') }}" class="btn btn-primary px-6 py-3">Perpustakaan Saya</a>
                        <a href="{{ route('buku.index') }}" class="btn btn-outline px-6 py-3">Jelajahi Koleksi</a>
                    </div>
                @endguest
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 space-y-16">

        @auth
            @if ($rekomendasi->count() > 0)
                <!-- Rekomendasi untuk User -->
                <section>
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-2xl font-semibold text-gray-900">Rekomendasi untuk Anda</h2>
                        <a href="{{ route('rekomendasi') }}" class="text-primary-600 text-sm font-medium">Lihat Semua</a>
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
                        @foreach ($rekomendasi as $buku)
                            <div class="card">
                                <div class="p-3">
                                    <div class="aspect-[3/4] bg-primary-100 rounded mb-3 flex items-center justify-center">
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
                                    <h3 class="font-medium text-sm text-gray-900 mb-1 line-clamp-2">{{ $buku->judul }}</h3>
                                    <p class="text-xs text-gray-600 mb-2">{{ $buku->penulis }}</p>
                                    <div class="flex items-center justify-between text-xs text-gray-500">
                                        <span>{{ $buku->subKategori->nama }}</span>
                                        <span>★ {{ number_format($buku->rating_rata_rata, 1) }}</span>
                                    </div>
                                    <a href="{{ route('buku.show', $buku) }}"
                                        class="block mt-2 btn btn-primary w-full text-xs py-1">
                                        Lihat Detail
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>
            @endif
        @endauth

        <!-- Buku Terbaru -->
        <section>
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-semibold text-gray-900">Buku Terbaru</h2>
                <a href="{{ route('buku.index', ['sort' => 'terbaru']) }}"
                    class="text-primary-600 text-sm font-medium">Lihat Semua</a>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-8 gap-4">
                @foreach ($bukuTerbaru as $buku)
                    <div class="card">
                        <div class="p-3">
                            <div class="aspect-[3/4] bg-primary-100 rounded mb-3 flex items-center justify-center">
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
                            <h3 class="font-medium text-sm text-gray-900 mb-1 line-clamp-2">{{ $buku->judul }}</h3>
                            <p class="text-xs text-gray-600 mb-2">{{ $buku->penulis }}</p>
                            <div class="flex items-center justify-between text-xs text-gray-500 mb-2">
                                <span>{{ $buku->subKategori->nama }}</span>
                                <span>★ {{ number_format($buku->rating_rata_rata, 1) }}</span>
                            </div>
                            <a href="{{ route('buku.show', $buku) }}" class="block btn btn-primary w-full text-xs py-1">
                                Lihat Detail
                            </a>
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
                    class="text-primary-600 text-sm font-medium">Lihat Semua</a>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-8 gap-4">
                @foreach ($bukuPopuler as $buku)
                    <div class="card">
                        <div class="p-3">
                            <div class="aspect-[3/4] bg-primary-100 rounded mb-3 flex items-center justify-center">
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
                            <h3 class="font-medium text-sm text-gray-900 mb-1 line-clamp-2">{{ $buku->judul }}</h3>
                            <p class="text-xs text-gray-600 mb-2">{{ $buku->penulis }}</p>
                            <div class="flex items-center justify-between text-xs text-gray-500 mb-2">
                                <span>{{ $buku->total_pembaca }} pembaca</span>
                                <span>★ {{ number_format($buku->rating_rata_rata, 1) }}</span>
                            </div>
                            <a href="{{ route('buku.show', $buku) }}" class="block btn btn-primary w-full text-xs py-1">
                                Lihat Detail
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>

        <!-- Kategori -->
        <section>
            <div class="mb-6">
                <h2 class="text-2xl font-semibold text-gray-900 mb-2">Jelajahi Kategori</h2>
                <p class="text-gray-600">Temukan buku berdasarkan kategori yang Anda sukai</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach ($kategori as $kat)
                    <div class="card">
                        <div class="card-body">
                            <div class="flex items-start space-x-4">
                                <div class="flex-shrink-0">
                                    <div class="w-12 h-12 bg-primary-100 rounded-lg flex items-center justify-center">
                                        <span class="text-primary-700 font-semibold">{{ substr($kat->nama, 0, 1) }}</span>
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $kat->nama }}</h3>
                                    <p class="text-sm text-gray-600 mb-3">{{ $kat->deskripsi }}</p>

                                    <div class="flex flex-wrap gap-2 mb-4">
                                        @foreach ($kat->subKategori as $sub)
                                            <a href="{{ route('buku.index', ['sub_kategori' => $sub->id]) }}"
                                                class="inline-block px-3 py-1 bg-cream-100 text-cream-800 text-xs rounded-full">
                                                {{ $sub->nama }}
                                            </a>
                                        @endforeach
                                    </div>

                                    <a href="{{ route('buku.index', ['kategori_utama' => $kat->id]) }}"
                                        class="btn btn-outline text-sm">
                                        Lihat Semua Buku {{ $kat->nama }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>

        <!-- Call to Action -->
        @guest
            <section class="bg-gradient-to-r from-primary-500 to-primary-600 rounded-lg p-8 text-center text-white">
                <h2 class="text-2xl font-bold mb-4">Mulai Petualangan Membaca Anda</h2>
                <p class="text-primary-100 mb-6 max-w-2xl mx-auto">
                    Bergabunglah dengan ribuan pembaca lainnya dan nikmati akses unlimited ke koleksi buku digital kami.
                </p>
                <div class="space-x-4">
                    <a href="{{ route('register') }}" class="btn bg-white text-primary-600 font-medium px-6 py-3">
                        Daftar Gratis
                    </a>
                    <a href="{{ route('buku.index') }}" class="btn border-white text-white px-6 py-3">
                        Lihat Koleksi
                    </a>
                </div>
            </section>
        @endguest
    </div>

    <style>
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>
@endsection
