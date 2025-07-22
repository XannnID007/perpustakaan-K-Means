@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Rekomendasi untuk Anda</h1>
            <p class="text-gray-600">Buku-buku yang dipersonalisasi berdasarkan pola membaca Anda</p>
        </div>

        {{-- Info Cluster User (Jika Ada) --}}
        @if (isset($userClusterInfo))
            <div class="card mb-8 bg-gradient-to-r from-blue-50 to-indigo-50 border-blue-200">
                <div class="card-body">
                    <div class="flex items-start space-x-4">
                        <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                                </path>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold text-blue-900 mb-1">
                                🎯 Profil Anda: {{ $userClusterInfo['name'] }}
                            </h3>
                            <p class="text-blue-700 mb-3">{{ $userClusterInfo['description'] }}</p>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                                <div class="bg-white bg-opacity-50 rounded p-3">
                                    <div class="font-medium text-blue-900">Preferensi Fiksi</div>
                                    <div class="text-2xl font-bold text-blue-600">
                                        {{ round($userClusterInfo['avg_fiction_ratio'] * 100) }}%
                                    </div>
                                </div>
                                <div class="bg-white bg-opacity-50 rounded p-3">
                                    <div class="font-medium text-blue-900">Rata-rata Buku</div>
                                    <div class="text-2xl font-bold text-blue-600">
                                        {{ round($userClusterInfo['avg_total_books']) }}
                                    </div>
                                </div>
                                <div class="bg-white bg-opacity-50 rounded p-3">
                                    <div class="font-medium text-blue-900">Rata-rata Rating</div>
                                    <div class="text-2xl font-bold text-blue-600">
                                        {{ number_format($userClusterInfo['avg_rating'], 1) }}/5
                                    </div>
                                </div>
                            </div>

                            <div class="mt-3 text-xs text-blue-600">
                                💡 Rekomendasi berikut dibuat berdasarkan {{ $userClusterInfo['user_count'] }} pembaca
                                dengan profil serupa
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- Berdasarkan Pembaca Serupa (Jika menggunakan K-Means) --}}
        @if (isset($categoryRecommendations) && $categoryRecommendations->count() > 0)
            <section class="mb-12">
                <div class="mb-6">
                    <h2 class="text-2xl font-semibold text-gray-900 mb-2">
                        {{ isset($userClusterInfo) ? '👥 Disukai Pembaca Serupa' : 'Berdasarkan Preferensi Anda' }}
                    </h2>
                    <p class="text-gray-600">
                        {{ isset($userClusterInfo)
                            ? 'Buku yang disukai oleh pembaca dengan profil serupa dengan Anda'
                            : 'Rekomendasi berdasarkan buku yang pernah Anda baca' }}
                    </p>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-8 gap-4">
                    @foreach ($categoryRecommendations as $buku)
                        <div class="card hover:shadow-lg transition-shadow">
                            <div class="p-3">
                                <a href="{{ route('buku.show', $buku) }}" class="block">
                                    <div
                                        class="aspect-[3/4] bg-primary-100 rounded mb-3 flex items-center justify-center overflow-hidden">
                                        @if ($buku->gambar_sampul)
                                            <img src="{{ $buku->gambar_sampul_url }}" alt="{{ $buku->judul }}"
                                                class="w-full h-full object-cover">
                                        @else
                                            <svg class="w-8 h-8 text-primary-600" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                                                </path>
                                            </svg>
                                        @endif
                                    </div>
                                </a>

                                <h3 class="font-medium text-sm text-gray-900 mb-1 line-clamp-2">
                                    <a href="{{ route('buku.show', $buku) }}">{{ $buku->judul }}</a>
                                </h3>
                                <p class="text-xs text-gray-600 mb-2">{{ $buku->penulis }}</p>

                                <div class="flex items-center justify-between text-xs mb-2">
                                    <span class="text-gray-500">{{ $buku->subKategori->nama ?? 'Umum' }}</span>
                                    <div class="flex items-center space-x-1">
                                        <svg class="w-3 h-3 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path
                                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                            </path>
                                        </svg>
                                        <span class="text-gray-600">{{ number_format($buku->rating_rata_rata, 1) }}</span>
                                    </div>
                                </div>

                                {{-- Badge khusus jika dari clustering --}}
                                @if (isset($userClusterInfo))
                                    <div class="text-xs mb-2">
                                        <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full">
                                            🤝 Cluster Match
                                        </span>
                                    </div>
                                @endif

                                <a href="{{ route('buku.show', $buku) }}"
                                    class="block btn btn-primary w-full text-xs py-1.5">
                                    Lihat Detail
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
        @endif

        @php
            $user = auth()->user();

            // Ambil kategori yang sering dibaca user
            $kategoriDibaca = \App\Models\ProgressBaca::where('user_id', $user->id)
                ->join('buku', 'progress_baca.buku_id', '=', 'buku.id')
                ->select('buku.kategori_utama_id', 'buku.sub_kategori_id')
                ->groupBy('buku.kategori_utama_id', 'buku.sub_kategori_id')
                ->limit(3)
                ->get();

            // Buku yang sudah dibaca
            $bukuIds = \App\Models\ProgressBaca::where('user_id', $user->id)->pluck('buku_id');

            // Rekomendasi berdasarkan kategori
            $rekomendasiKategori = collect();
            if ($kategoriDibaca->isNotEmpty()) {
                $rekomendasiKategori = \App\Models\Buku::where('aktif', true)
                    ->whereNotIn('id', $bukuIds)
                    ->where(function ($query) use ($kategoriDibaca) {
                        foreach ($kategoriDibaca as $kategori) {
                            $query->orWhere(function ($q) use ($kategori) {
                                $q->where('kategori_utama_id', $kategori->kategori_utama_id)->where(
                                    'sub_kategori_id',
                                    $kategori->sub_kategori_id,
                                );
                            });
                        }
                    })
                    ->orderBy('rating_rata_rata', 'desc')
                    ->limit(8)
                    ->get();
            }

            // Buku populer (fallback)
            $bukuPopuler = \App\Models\Buku::where('aktif', true)
                ->whereNotIn('id', $bukuIds)
                ->orderBy('total_pembaca', 'desc')
                ->limit(8)
                ->get();

            // Buku dengan rating tinggi
            $bukuRatingTinggi = \App\Models\Buku::where('aktif', true)
                ->whereNotIn('id', $bukuIds)
                ->where('rating_rata_rata', '>=', 4.0)
                ->orderBy('rating_rata_rata', 'desc')
                ->limit(8)
                ->get();
        @endphp

        @if ($rekomendasiKategori->count() > 0)
            <!-- Berdasarkan Preferensi -->
            <section class="mb-12">
                <div class="mb-6">
                    <h2 class="text-2xl font-semibold text-gray-900 mb-2">Berdasarkan Preferensi Anda</h2>
                    <p class="text-gray-600">Rekomendasi berdasarkan buku yang pernah Anda baca</p>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-8 gap-4">
                    @foreach ($rekomendasiKategori as $buku)
                        <div class="card">
                            <div class="p-3">
                                <a href="{{ route('buku.show', $buku) }}" class="block">
                                    <div
                                        class="aspect-[3/4] bg-primary-100 rounded mb-3 flex items-center justify-center overflow-hidden">
                                        @if ($buku->gambar_sampul)
                                            <img src="{{ $buku->gambar_sampul_url }}" alt="{{ $buku->judul }}"
                                                class="w-full h-full object-cover">
                                        @else
                                            <svg class="w-8 h-8 text-primary-600" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                                                </path>
                                            </svg>
                                        @endif
                                    </div>
                                </a>

                                <h3 class="font-medium text-sm text-gray-900 mb-1 line-clamp-2">
                                    <a href="{{ route('buku.show', $buku) }}">{{ $buku->judul }}</a>
                                </h3>
                                <p class="text-xs text-gray-600 mb-2">{{ $buku->penulis }}</p>

                                <div class="flex items-center justify-between text-xs mb-2">
                                    <span class="text-gray-500">{{ $buku->subKategori->nama }}</span>
                                    <div class="flex items-center space-x-1">
                                        <svg class="w-3 h-3 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path
                                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                            </path>
                                        </svg>
                                        <span class="text-gray-600">{{ number_format($buku->rating_rata_rata, 1) }}</span>
                                    </div>
                                </div>

                                <a href="{{ route('buku.show', $buku) }}"
                                    class="block btn btn-primary w-full text-xs py-1.5">
                                    Lihat Detail
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
        @endif

        <!-- Buku dengan Rating Tinggi -->
        @if ($bukuRatingTinggi->count() > 0)
            <section class="mb-12">
                <div class="mb-6">
                    <h2 class="text-2xl font-semibold text-gray-900 mb-2">Buku dengan Rating Tinggi</h2>
                    <p class="text-gray-600">Buku-buku terbaik dengan rating 4+ bintang</p>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-8 gap-4">
                    @foreach ($bukuRatingTinggi as $buku)
                        <div class="card">
                            <div class="p-3">
                                <a href="{{ route('buku.show', $buku) }}" class="block">
                                    <div
                                        class="aspect-[3/4] bg-primary-100 rounded mb-3 flex items-center justify-center overflow-hidden">
                                        @if ($buku->gambar_sampul)
                                            <img src="{{ $buku->gambar_sampul_url }}" alt="{{ $buku->judul }}"
                                                class="w-full h-full object-cover">
                                        @else
                                            <svg class="w-8 h-8 text-primary-600" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                                                </path>
                                            </svg>
                                        @endif
                                    </div>
                                </a>

                                <h3 class="font-medium text-sm text-gray-900 mb-1 line-clamp-2">
                                    <a href="{{ route('buku.show', $buku) }}">{{ $buku->judul }}</a>
                                </h3>
                                <p class="text-xs text-gray-600 mb-2">{{ $buku->penulis }}</p>

                                <div class="flex items-center justify-between text-xs mb-2">
                                    <span class="text-gray-500">{{ $buku->subKategori->nama }}</span>
                                    <div class="flex items-center space-x-1">
                                        <svg class="w-3 h-3 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path
                                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                            </path>
                                        </svg>
                                        <span class="text-gray-600">{{ number_format($buku->rating_rata_rata, 1) }}</span>
                                    </div>
                                </div>

                                <a href="{{ route('buku.show', $buku) }}"
                                    class="block btn btn-primary w-full text-xs py-1.5">
                                    Lihat Detail
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
        @endif

        <!-- Buku Populer -->
        <section class="mb-12">
            <div class="mb-6">
                <h2 class="text-2xl font-semibold text-gray-900 mb-2">Sedang Trending</h2>
                <p class="text-gray-600">Buku yang paling banyak dibaca pembaca lain</p>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-8 gap-4">
                @foreach ($bukuPopuler as $buku)
                    <div class="card">
                        <div class="p-3">
                            <a href="{{ route('buku.show', $buku) }}" class="block">
                                <div
                                    class="aspect-[3/4] bg-primary-100 rounded mb-3 flex items-center justify-center overflow-hidden">
                                    @if ($buku->gambar_sampul)
                                        <img src="{{ $buku->gambar_sampul_url }}" alt="{{ $buku->judul }}"
                                            class="w-full h-full object-cover">
                                    @else
                                        <svg class="w-8 h-8 text-primary-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                                            </path>
                                        </svg>
                                    @endif
                                </div>
                            </a>

                            <h3 class="font-medium text-sm text-gray-900 mb-1 line-clamp-2">
                                <a href="{{ route('buku.show', $buku) }}">{{ $buku->judul }}</a>
                            </h3>
                            <p class="text-xs text-gray-600 mb-2">{{ $buku->penulis }}</p>

                            <div class="flex items-center justify-between text-xs mb-2">
                                <span class="text-gray-500">{{ number_format($buku->total_pembaca) }} pembaca</span>
                                <div class="flex items-center space-x-1">
                                    <svg class="w-3 h-3 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                        </path>
                                    </svg>
                                    <span class="text-gray-600">{{ number_format($buku->rating_rata_rata, 1) }}</span>
                                </div>
                            </div>

                            <a href="{{ route('buku.show', $buku) }}"
                                class="block btn btn-primary w-full text-xs py-1.5">
                                Lihat Detail
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>

        <!-- Jelajahi Kategori -->
        <section>
            <div class="mb-6">
                <h2 class="text-2xl font-semibold text-gray-900 mb-2">Jelajahi Kategori Lain</h2>
                <p class="text-gray-600">Temukan buku menarik dari kategori yang belum pernah Anda coba</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach (\App\Models\KategoriUtama::with('subKategori')->get() as $kategori)
                    <div class="card">
                        <div class="card-body">
                            <div class="flex items-center space-x-4">
                                <div class="w-12 h-12 bg-primary-100 rounded-lg flex items-center justify-center">
                                    <span
                                        class="text-primary-700 font-semibold">{{ substr($kategori->nama, 0, 1) }}</span>
                                </div>
                                <div class="flex-1">
                                    <h3 class="font-semibold text-gray-900 mb-1">{{ $kategori->nama }}</h3>
                                    <p class="text-sm text-gray-600 mb-2">{{ $kategori->deskripsi }}</p>
                                    <div class="flex flex-wrap gap-1">
                                        @foreach ($kategori->subKategori->take(3) as $sub)
                                            <span
                                                class="inline-block px-2 py-1 bg-cream-100 text-cream-800 text-xs rounded">
                                                {{ $sub->nama }}
                                            </span>
                                        @endforeach
                                        @if ($kategori->subKategori->count() > 3)
                                            <span class="text-xs text-gray-500">+{{ $kategori->subKategori->count() - 3 }}
                                                lainnya</span>
                                        @endif
                                    </div>
                                </div>
                                <div>
                                    <a href="{{ route('buku.index', ['kategori_utama' => $kategori->id]) }}"
                                        class="btn btn-outline text-sm">
                                        Jelajahi
                                    </a>
                                </div>
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
    </style>
@endsection
