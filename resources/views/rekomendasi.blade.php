@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Enhanced Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Rekomendasi Personal untuk Anda</h1>
            <p class="text-gray-600">Buku-buku yang dipersonalisasi berdasarkan analisis mendalam pola membaca Anda</p>
        </div>

        {{-- Enhanced User Cluster Info --}}
        @if (isset($userClusterInfo))
            <div
                class="card mb-8 bg-gradient-to-r from-indigo-50 via-blue-50 to-cyan-50 border-indigo-200 overflow-hidden relative">
                <!-- Background Pattern -->
                <div class="absolute inset-0 opacity-10">
                    <svg class="w-full h-full" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                        <defs>
                            <pattern id="books" patternUnits="userSpaceOnUse" width="20" height="20">
                                <path d="M2 2h16v16H2z" fill="none" stroke="currentColor" stroke-width="0.5" />
                                <path d="M6 6h8v8H6z" fill="currentColor" opacity="0.3" />
                            </pattern>
                        </defs>
                        <rect width="100" height="100" fill="url(#books)" />
                    </svg>
                </div>

                <div class="card-body relative z-10">
                    <div class="flex flex-col lg:flex-row items-start lg:items-center space-y-6 lg:space-y-0 lg:space-x-8">
                        <!-- Profile Icon -->
                        <div class="flex-shrink-0">
                            <div
                                class="w-20 h-20 bg-gradient-to-br from-indigo-500 to-blue-600 rounded-full flex items-center justify-center shadow-lg">
                                <span class="text-3xl">{{ $userClusterInfo['icon'] ?? '📚' }}</span>
                            </div>
                        </div>

                        <!-- Profile Info -->
                        <div class="flex-1">
                            <div class="mb-4">
                                <h3 class="text-2xl font-bold text-indigo-900 mb-2">
                                    {{ $userClusterInfo['name'] }}
                                </h3>
                                <p class="text-indigo-700 text-lg mb-3">{{ $userClusterInfo['description'] }}</p>

                                <!-- Characteristics Tags -->
                                @if (isset($userClusterInfo['characteristics']))
                                    <div class="flex flex-wrap gap-2 mb-4">
                                        @foreach ($userClusterInfo['characteristics'] as $characteristic)
                                            <span
                                                class="inline-flex items-center px-3 py-1 bg-white bg-opacity-80 text-indigo-800 text-sm font-medium rounded-full border border-indigo-200">
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                                {{ $characteristic }}
                                            </span>
                                        @endforeach
                                    </div>
                                @endif
                            </div>

                            <!-- Enhanced Statistics -->
                            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                                <div
                                    class="bg-white bg-opacity-60 backdrop-blur-sm rounded-lg p-4 text-center border border-indigo-200">
                                    <div class="text-2xl font-bold text-indigo-600 mb-1">
                                        {{ round(($userClusterInfo['avg_fiction_preference'] ?? 0.5) * 100) }}%
                                    </div>
                                    <div class="text-sm font-medium text-indigo-900">Preferensi Fiksi</div>
                                    <div class="text-xs text-indigo-600 mt-1">
                                        {{ round(($userClusterInfo['avg_fiction_preference'] ?? 0.5) * 100) > 50 ? 'Lebih suka cerita imajinatif' : 'Lebih suka fakta & pembelajaran' }}
                                    </div>
                                </div>

                                <div
                                    class="bg-white bg-opacity-60 backdrop-blur-sm rounded-lg p-4 text-center border border-indigo-200">
                                    <div class="text-2xl font-bold text-blue-600 mb-1">
                                        {{ round($userClusterInfo['avg_total_books'] ?? 0) }}
                                    </div>
                                    <div class="text-sm font-medium text-indigo-900">Rata-rata Buku</div>
                                    <div class="text-xs text-blue-600 mt-1">
                                        {{ round($userClusterInfo['avg_total_books'] ?? 0) > 10 ? 'Pembaca aktif' : 'Pembaca kasual' }}
                                    </div>
                                </div>

                                <div
                                    class="bg-white bg-opacity-60 backdrop-blur-sm rounded-lg p-4 text-center border border-indigo-200">
                                    <div class="text-2xl font-bold text-cyan-600 mb-1">
                                        {{ number_format($userClusterInfo['avg_rating'] ?? 3.5, 1) }}/5
                                    </div>
                                    <div class="text-sm font-medium text-indigo-900">Standar Rating</div>
                                    <div class="text-xs text-cyan-600 mt-1">
                                        {{ ($userClusterInfo['avg_rating'] ?? 3.5) > 4 ? 'Standar tinggi' : (($userClusterInfo['avg_rating'] ?? 3.5) > 3.5 ? 'Standar sedang' : 'Mudah puas') }}
                                    </div>
                                </div>

                                <div
                                    class="bg-white bg-opacity-60 backdrop-blur-sm rounded-lg p-4 text-center border border-indigo-200">
                                    <div class="text-2xl font-bold text-purple-600 mb-1">
                                        {{ round(($userClusterInfo['avg_completion_rate'] ?? 0.75) * 100) }}%
                                    </div>
                                    <div class="text-sm font-medium text-indigo-900">Tingkat Selesai</div>
                                    <div class="text-xs text-purple-600 mt-1">
                                        {{ round(($userClusterInfo['avg_completion_rate'] ?? 0.75) * 100) > 80 ? 'Selalu selesai' : 'Kadang berhenti' }}
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4 flex items-center space-x-2 text-sm text-indigo-700">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3z" />
                                </svg>
                                <span>Berbagi preferensi dengan {{ $userClusterInfo['user_count'] }} pembaca serupa</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- Cluster-based Recommendations --}}
        @if (isset($categoryRecommendations) && $categoryRecommendations->count() > 0)
            <section class="mb-12">
                <div class="mb-6">
                    <h2 class="text-2xl font-semibold text-gray-900 mb-2 flex items-center">
                        <span class="mr-3">👥</span>
                        {{ isset($userClusterInfo) ? 'Rekomendasi dari Pembaca Serupa' : 'Berdasarkan Preferensi Anda' }}
                    </h2>
                    <p class="text-gray-600">
                        {{ isset($userClusterInfo)
                            ? 'Buku yang disukai oleh pembaca dengan profil dan preferensi serupa dengan Anda'
                            : 'Rekomendasi berdasarkan buku yang pernah Anda baca' }}
                    </p>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-8 gap-4">
                    @foreach ($categoryRecommendations as $buku)
                        <div class="card hover:shadow-lg transition-all duration-300 group h-full flex flex-col">
                            <div class="p-3 flex-1 flex flex-col">
                                <a href="{{ route('buku.show', $buku) }}" class="block">
                                    <div
                                        class="aspect-[3/4] bg-primary-100 rounded mb-3 flex items-center justify-center overflow-hidden relative">
                                        @if ($buku->gambar_sampul)
                                            <img src="{{ $buku->gambar_sampul_url }}" alt="{{ $buku->judul }}"
                                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                        @else
                                            <svg class="w-8 h-8 text-primary-600 group-hover:scale-110 transition-transform"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                                                </path>
                                            </svg>
                                        @endif

                                        @if (isset($userClusterInfo))
                                            <div class="absolute top-1 right-1">
                                                <span
                                                    class="bg-blue-500 text-white text-xs px-2 py-1 rounded-full shadow-sm">
                                                    AI Match
                                                </span>
                                            </div>
                                        @endif
                                    </div>
                                </a>

                                <!-- Content dengan flex untuk push button ke bawah -->
                                <div class="flex-1 flex flex-col">
                                    <h3
                                        class="font-medium text-sm text-gray-900 mb-1 line-clamp-2 group-hover:text-primary-600 transition-colors flex-1">
                                        <a href="{{ route('buku.show', $buku) }}">{{ $buku->judul }}</a>
                                    </h3>
                                    <p class="text-xs text-gray-600 mb-2">{{ $buku->penulis }}</p>

                                    <div class="flex items-center justify-between text-xs mb-2">
                                        <span
                                            class="text-gray-500 truncate">{{ $buku->subKategori->nama ?? 'Umum' }}</span>
                                        <div class="flex items-center space-x-1 ml-2">
                                            <svg class="w-3 h-3 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                                <path
                                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                            </svg>
                                            <span
                                                class="text-gray-600 font-medium">{{ number_format($buku->rating_rata_rata, 1) }}</span>
                                        </div>
                                    </div>

                                    <!-- Button di bagian paling bawah dengan mt-auto -->
                                    <a href="{{ route('buku.show', $buku) }}"
                                        class="btn btn-primary w-full text-xs py-2 group-hover:shadow-md transition-all mt-auto">
                                        Lihat Detail
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
        @endif

        <!-- Genre Specific Sections Based on User Cluster -->
        @if (isset($userClusterInfo))
            @php
                $fictionPref = $userClusterInfo['avg_fiction_preference'] ?? 0.5;
                $showFiction = $fictionPref > 0.3; // Show fiction if preference > 30%
                $showNonFiction = $fictionPref < 0.7; // Show non-fiction if preference < 70%
            @endphp

            @if ($showFiction)
                <!-- Fiction Recommendations -->
                <section class="mb-12">
                    <div class="mb-6">
                        <h2 class="text-2xl font-semibold text-gray-900 mb-2 flex items-center">
                            <span class="mr-3">📖</span>
                            {{ $fictionPref > 0.7 ? 'Novel & Cerita Pilihan' : 'Jelajahi Dunia Fiksi' }}
                        </h2>
                        <p class="text-gray-600">
                            {{ $fictionPref > 0.7
                                ? 'Karya fiksi terbaik yang sesuai dengan selera Anda'
                                : 'Temukan keajaiban cerita dan dunia imajinatif' }}
                        </p>
                    </div>

                    @php
                        // Get fiction books
                        $fictionBooks = \App\Models\Buku::where('aktif', true)
                            ->whereHas('kategoriUtama', function ($q) {
                                $q->where('nama', 'LIKE', '%fiksi%');
                            })
                            ->whereNotIn(
                                'id',
                                \App\Models\ProgressBaca::where('user_id', auth()->id())->pluck('buku_id'),
                            )
                            ->orderBy('rating_rata_rata', 'desc')
                            ->limit(8)
                            ->get();
                    @endphp

                    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-8 gap-4">
                        @foreach ($fictionBooks as $buku)
                            <div class="card hover:shadow-lg transition-all duration-300 group h-full flex flex-col">
                                <div class="p-3 flex-1 flex flex-col">
                                    <a href="{{ route('buku.show', $buku) }}" class="block">
                                        <div
                                            class="aspect-[3/4] bg-purple-100 rounded mb-3 flex items-center justify-center overflow-hidden relative">
                                            @if ($buku->gambar_sampul)
                                                <img src="{{ $buku->gambar_sampul_url }}" alt="{{ $buku->judul }}"
                                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                            @else
                                                <svg class="w-8 h-8 text-purple-600 group-hover:scale-110 transition-transform"
                                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                                                    </path>
                                                </svg>
                                            @endif

                                            <div class="absolute top-1 right-1">
                                                <span
                                                    class="bg-purple-500 text-white text-xs px-2 py-1 rounded-full shadow-sm">
                                                    📚 Fiksi
                                                </span>
                                            </div>
                                        </div>
                                    </a>

                                    <!-- Content dengan flex untuk push button ke bawah -->
                                    <div class="flex-1 flex flex-col">
                                        <h3
                                            class="font-medium text-sm text-gray-900 mb-1 line-clamp-2 group-hover:text-purple-600 transition-colors flex-1">
                                            <a href="{{ route('buku.show', $buku) }}">{{ $buku->judul }}</a>
                                        </h3>
                                        <p class="text-xs text-gray-600 mb-2">{{ $buku->penulis }}</p>

                                        <div class="flex items-center justify-between text-xs mb-2">
                                            <span
                                                class="text-gray-500 truncate">{{ $buku->subKategori->nama ?? 'Umum' }}</span>
                                            <div class="flex items-center space-x-1 ml-2">
                                                <svg class="w-3 h-3 text-yellow-400" fill="currentColor"
                                                    viewBox="0 0 20 20">
                                                    <path
                                                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                </svg>
                                                <span
                                                    class="text-gray-600 font-medium">{{ number_format($buku->rating_rata_rata, 1) }}</span>
                                            </div>
                                        </div>

                                        <!-- Button di bagian paling bawah dengan mt-auto -->
                                        <a href="{{ route('buku.show', $buku) }}"
                                            class="btn btn-primary w-full text-xs py-2 group-hover:shadow-md transition-all bg-purple-600 hover:bg-purple-700 mt-auto">
                                            Lihat Detail
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>
            @endif

            @if ($showNonFiction)
                <!-- Non-Fiction Recommendations -->
                <section class="mb-12">
                    <div class="mb-6">
                        <h2 class="text-2xl font-semibold text-gray-900 mb-2 flex items-center">
                            <span class="mr-3">🎓</span>
                            {{ $fictionPref < 0.3 ? 'Pengetahuan & Pembelajaran' : 'Wawasan & Fakta Menarik' }}
                        </h2>
                        <p class="text-gray-600">
                            {{ $fictionPref < 0.3
                                ? 'Buku-buku yang akan memperkaya pengetahuan dan mengembangkan diri'
                                : 'Tambah wawasan dengan buku-buku berisi fakta dan pengetahuan' }}
                        </p>
                    </div>

                    @php
                        // Get non-fiction books
                        $nonFictionBooks = \App\Models\Buku::where('aktif', true)
                            ->whereHas('kategoriUtama', function ($q) {
                                $q->where('nama', 'NOT LIKE', '%fiksi%');
                            })
                            ->whereNotIn(
                                'id',
                                \App\Models\ProgressBaca::where('user_id', auth()->id())->pluck('buku_id'),
                            )
                            ->orderBy('rating_rata_rata', 'desc')
                            ->limit(8)
                            ->get();
                    @endphp

                    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-8 gap-4">
                        @foreach ($nonFictionBooks as $buku)
                            <div class="card hover:shadow-lg transition-all duration-300 group h-full flex flex-col">
                                <div class="p-3 flex-1 flex flex-col">
                                    <a href="{{ route('buku.show', $buku) }}" class="block">
                                        <div
                                            class="aspect-[3/4] bg-blue-100 rounded mb-3 flex items-center justify-center overflow-hidden relative">
                                            @if ($buku->gambar_sampul)
                                                <img src="{{ $buku->gambar_sampul_url }}" alt="{{ $buku->judul }}"
                                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                            @else
                                                <svg class="w-8 h-8 text-blue-600 group-hover:scale-110 transition-transform"
                                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                                                    </path>
                                                </svg>
                                            @endif

                                            <div class="absolute top-1 right-1">
                                                <span
                                                    class="bg-blue-500 text-white text-xs px-2 py-1 rounded-full shadow-sm">
                                                    🎓 Fakta
                                                </span>
                                            </div>
                                        </div>
                                    </a>

                                    <!-- Content dengan flex untuk push button ke bawah -->
                                    <div class="flex-1 flex flex-col">
                                        <h3
                                            class="font-medium text-sm text-gray-900 mb-1 line-clamp-2 group-hover:text-blue-600 transition-colors flex-1">
                                            <a href="{{ route('buku.show', $buku) }}">{{ $buku->judul }}</a>
                                        </h3>
                                        <p class="text-xs text-gray-600 mb-2">{{ $buku->penulis }}</p>

                                        <div class="flex items-center justify-between text-xs mb-2">
                                            <span
                                                class="text-gray-500 truncate">{{ $buku->subKategori->nama ?? 'Umum' }}</span>
                                            <div class="flex items-center space-x-1 ml-2">
                                                <svg class="w-3 h-3 text-yellow-400" fill="currentColor"
                                                    viewBox="0 0 20 20">
                                                    <path
                                                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                </svg>
                                                <span
                                                    class="text-gray-600 font-medium">{{ number_format($buku->rating_rata_rata, 1) }}</span>
                                            </div>
                                        </div>

                                        <!-- Button di bagian paling bawah dengan mt-auto -->
                                        <a href="{{ route('buku.show', $buku) }}"
                                            class="btn btn-primary w-full text-xs py-2 group-hover:shadow-md transition-all bg-blue-600 hover:bg-blue-700 mt-auto">
                                            Lihat Detail
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>
            @endif
        @endif

        <!-- Popular Books in User's Cluster -->
        @if (isset($popularBooks) && $popularBooks->count() > 0)
            <section class="mb-12">
                <div class="mb-6">
                    <h2 class="text-2xl font-semibold text-gray-900 mb-2 flex items-center">
                        <span class="mr-3">🔥</span>
                        {{ isset($userClusterInfo) ? 'Trending dalam Kelompok Anda' : 'Sedang Trending' }}
                    </h2>
                    <p class="text-gray-600">
                        {{ isset($userClusterInfo)
                            ? 'Buku yang sedang populer di kalangan pembaca dengan profil serupa'
                            : 'Buku yang paling banyak dibaca pembaca lain' }}
                    </p>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-8 gap-4">
                    @foreach ($popularBooks as $buku)
                        <div class="card hover:shadow-lg transition-all duration-300 group relative h-full flex flex-col">
                            <!-- Trending Badge -->
                            <div class="absolute -top-2 -right-2 z-10">
                                <div
                                    class="bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs font-bold shadow-lg">
                                    {{ $loop->iteration }}
                                </div>
                            </div>

                            <div class="p-3 flex-1 flex flex-col">
                                <a href="{{ route('buku.show', $buku) }}" class="block">
                                    <div
                                        class="aspect-[3/4] bg-red-100 rounded mb-3 flex items-center justify-center overflow-hidden relative">
                                        @if ($buku->gambar_sampul)
                                            <img src="{{ $buku->gambar_sampul_url }}" alt="{{ $buku->judul }}"
                                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                        @else
                                            <svg class="w-8 h-8 text-red-600 group-hover:scale-110 transition-transform"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                                                </path>
                                            </svg>
                                        @endif

                                        <div class="absolute bottom-1 left-1">
                                            <span
                                                class="bg-red-500 bg-opacity-90 text-white text-xs px-2 py-1 rounded shadow-sm">
                                                🔥 {{ number_format($buku->total_pembaca) }}
                                            </span>
                                        </div>
                                    </div>
                                </a>

                                <!-- Content dengan flex untuk push button ke bawah -->
                                <div class="flex-1 flex flex-col">
                                    <h3
                                        class="font-medium text-sm text-gray-900 mb-1 line-clamp-2 group-hover:text-red-600 transition-colors flex-1">
                                        <a href="{{ route('buku.show', $buku) }}">{{ $buku->judul }}</a>
                                    </h3>
                                    <p class="text-xs text-gray-600 mb-2">{{ $buku->penulis }}</p>

                                    <div class="flex items-center justify-between text-xs mb-2">
                                        <span
                                            class="text-gray-500 truncate">{{ $buku->subKategori->nama ?? 'Umum' }}</span>
                                        <div class="flex items-center space-x-1 ml-2">
                                            <svg class="w-3 h-3 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                                <path
                                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                            </svg>
                                            <span
                                                class="text-gray-600 font-medium">{{ number_format($buku->rating_rata_rata, 1) }}</span>
                                        </div>
                                    </div>

                                    <!-- Button di bagian paling bawah dengan mt-auto -->
                                    <a href="{{ route('buku.show', $buku) }}"
                                        class="btn btn-primary w-full text-xs py-2 group-hover:shadow-md transition-all bg-red-600 hover:bg-red-700 mt-auto">
                                        Lihat Detail
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
        @endif

        <!-- High Rated Books -->
        @if (isset($highRatedBooks) && $highRatedBooks->count() > 0)
            <section class="mb-12">
                <div class="mb-6">
                    <h2 class="text-2xl font-semibold text-gray-900 mb-2 flex items-center">
                        <span class="mr-3">⭐</span>
                        Buku Rating Tinggi
                    </h2>
                    <p class="text-gray-600">Buku-buku terbaik dengan rating 4+ bintang dari pembaca</p>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-8 gap-4">
                    @foreach ($highRatedBooks as $buku)
                        <div class="card hover:shadow-lg transition-all duration-300 group h-full flex flex-col">
                            <div class="p-3 flex-1 flex flex-col">
                                <a href="{{ route('buku.show', $buku) }}" class="block">
                                    <div
                                        class="aspect-[3/4] bg-yellow-100 rounded mb-3 flex items-center justify-center overflow-hidden relative">
                                        @if ($buku->gambar_sampul)
                                            <img src="{{ $buku->gambar_sampul_url }}" alt="{{ $buku->judul }}"
                                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                        @else
                                            <svg class="w-8 h-8 text-yellow-600 group-hover:scale-110 transition-transform"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                                                </path>
                                            </svg>
                                        @endif

                                        <div class="absolute top-1 right-1">
                                            <span
                                                class="bg-yellow-500 text-white text-xs px-2 py-1 rounded-full shadow-sm font-bold">
                                                ⭐ {{ number_format($buku->rating_rata_rata, 1) }}
                                            </span>
                                        </div>
                                    </div>
                                </a>

                                <!-- Content dengan flex untuk push button ke bawah -->
                                <div class="flex-1 flex flex-col">
                                    <h3
                                        class="font-medium text-sm text-gray-900 mb-1 line-clamp-2 group-hover:text-yellow-600 transition-colors flex-1">
                                        <a href="{{ route('buku.show', $buku) }}">{{ $buku->judul }}</a>
                                    </h3>
                                    <p class="text-xs text-gray-600 mb-2">{{ $buku->penulis }}</p>

                                    <div class="flex items-center justify-between text-xs mb-2">
                                        <span
                                            class="text-gray-500 truncate">{{ $buku->subKategori->nama ?? 'Umum' }}</span>
                                        <div class="flex items-center space-x-1 ml-2">
                                            <svg class="w-3 h-3 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                                <path
                                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                            </svg>
                                            <span
                                                class="text-gray-600 font-medium">{{ number_format($buku->rating_rata_rata, 1) }}</span>
                                        </div>
                                    </div>

                                    <!-- Button di bagian paling bawah dengan mt-auto -->
                                    <a href="{{ route('buku.show', $buku) }}"
                                        class="btn btn-primary w-full text-xs py-2 group-hover:shadow-md transition-all mt-auto">
                                        Lihat Detail
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
        @endif
    </div>

    <style>
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        /* Ensure consistent card heights dan alignment button */
        .card {
            transition: all 0.2s ease-in-out;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        /* Force equal height cards dengan flex */
        .grid .card.h-full {
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .grid .card.h-full>div {
            display: flex;
            flex-direction: column;
            flex: 1;
        }

        /* Pastikan button selalu di bawah */
        .grid .card.h-full .flex-1:last-child {
            display: flex;
            flex-direction: column;
        }

        .grid .card.h-full .mt-auto {
            margin-top: auto;
        }

        /* Custom hover animations */
        .group:hover .group-hover\:scale-105 {
            transform: scale(1.05);
        }

        .group:hover .group-hover\:scale-110 {
            transform: scale(1.1);
        }

        .group:hover .group-hover\:translate-x-1 {
            transform: translateX(0.25rem);
        }

        /* Custom gradient animations */
        @keyframes shimmer {
            0% {
                background-position: -200% 0;
            }

            100% {
                background-position: 200% 0;
            }
        }

        .card:hover::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(45deg, transparent 30%, rgba(255, 255, 255, 0.1) 50%, transparent 70%);
            background-size: 200% 200%;
            animation: shimmer 2s infinite;
            pointer-events: none;
            border-radius: inherit;
        }
    </style>
@endsection
