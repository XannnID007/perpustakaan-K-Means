@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Book Info -->
            <div class="lg:col-span-2">
                <div class="card">
                    <div class="card-body">
                        <div class="flex flex-col md:flex-row space-y-6 md:space-y-0 md:space-x-6">
                            <!-- Book Cover -->
                            <div class="flex-shrink-0">
                                <div
                                    class="w-48 h-64 bg-primary-100 rounded-lg mx-auto md:mx-0 flex items-center justify-center">
                                    @if ($buku->gambar_sampul)
                                        <img src="{{ $buku->gambar_sampul_url }}" alt="{{ $buku->judul }}"
                                            class="w-48 h-64 object-cover rounded-lg">
                                    @else
                                        <svg class="w-16 h-16 text-primary-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                                            </path>
                                        </svg>
                                    @endif
                                </div>
                            </div>

                            <!-- Book Details -->
                            <div class="flex-1">
                                <div class="mb-4">
                                    <h1 class="text-2xl font-bold text-gray-900 mb-2">{{ $buku->judul }}</h1>
                                    <p class="text-lg text-gray-600 mb-1">oleh {{ $buku->penulis }}</p>
                                    @if ($buku->tahun_terbit)
                                        <p class="text-sm text-gray-500">Terbit {{ $buku->tahun_terbit }}</p>
                                    @endif
                                </div>

                                <!-- Categories -->
                                <div class="mb-4">
                                    <div class="flex flex-wrap gap-2">
                                        <span
                                            class="inline-block px-3 py-1 bg-primary-100 text-primary-800 text-sm rounded-full">
                                            {{ $buku->kategoriUtama->nama }}
                                        </span>
                                        <span
                                            class="inline-block px-3 py-1 bg-cream-100 text-cream-800 text-sm rounded-full">
                                            {{ $buku->subKategori->nama }}
                                        </span>
                                    </div>
                                </div>

                                <!-- Stats -->
                                <div class="mb-6">
                                    <div class="grid grid-cols-3 gap-4 text-center">
                                        <div>
                                            <div class="text-2xl font-bold text-gray-900">
                                                {{ number_format($buku->rating_rata_rata, 1) }}</div>
                                            <div class="text-sm text-gray-500">Rating</div>
                                            <div class="flex justify-center mt-1">
                                                @for ($i = 1; $i <= 5; $i++)
                                                    <svg class="w-4 h-4 {{ $i <= $buku->rating_rata_rata ? 'text-yellow-400' : 'text-gray-300' }}"
                                                        fill="currentColor" viewBox="0 0 20 20">
                                                        <path
                                                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                                        </path>
                                                    </svg>
                                                @endfor
                                            </div>
                                        </div>
                                        <div>
                                            <div class="text-2xl font-bold text-gray-900">
                                                {{ number_format($buku->total_pembaca) }}</div>
                                            <div class="text-sm text-gray-500">Pembaca</div>
                                        </div>
                                        <div>
                                            <div class="text-2xl font-bold text-gray-900">
                                                {{ number_format($buku->total_halaman) }}</div>
                                            <div class="text-sm text-gray-500">Halaman</div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="space-y-3">
                                    @auth
                                        @if ($userProgress)
                                            <a href="{{ route('buku.read', $buku) }}"
                                                class="btn btn-primary w-full flex items-center justify-center space-x-2">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                                                    </path>
                                                </svg>
                                                <span>Lanjutkan Membaca (Hal. {{ $userProgress->halaman_sekarang }})</span>
                                            </a>
                                            <div class="w-full bg-gray-200 rounded-full h-2">
                                                <div class="bg-primary-600 h-2 rounded-full"
                                                    style="width: {{ $userProgress->persentase_baca }}%"></div>
                                            </div>
                                            <p class="text-sm text-gray-600 text-center">
                                                {{ number_format($userProgress->persentase_baca, 1) }}% selesai</p>
                                        @else
                                            <a href="{{ route('buku.read', $buku) }}"
                                                class="btn btn-primary w-full flex items-center justify-center space-x-2">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                                                    </path>
                                                </svg>
                                                <span>Mulai Membaca</span>
                                            </a>
                                        @endif
                                    @else
                                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 text-center">
                                            <p class="text-blue-800 mb-3">🔒 Silakan login untuk membaca buku ini</p>
                                            <div class="space-x-2">
                                                <a href="{{ route('login') }}" class="btn btn-primary">Login</a>
                                                <a href="{{ route('register') }}" class="btn btn-outline">Daftar</a>
                                            </div>
                                        </div>
                                    @endauth
                                </div>
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="mt-8 pt-6 border-t border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900 mb-3">Deskripsi</h3>
                            <p class="text-gray-700 leading-relaxed">{{ $buku->deskripsi }}</p>
                        </div>
                    </div>
                </div>

                <!-- Reviews Section -->
                @if ($buku->rating->count() > 0)
                    <div class="card mt-6">
                        <div class="card-body">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Ulasan Pembaca</h3>
                            <div class="space-y-4">
                                @foreach ($buku->rating->take(5) as $rating)
                                    <div class="border-b border-gray-200 pb-4 last:border-b-0">
                                        <div class="flex items-start space-x-3">
                                            <div
                                                class="w-8 h-8 bg-primary-100 rounded-full flex items-center justify-center">
                                                <span
                                                    class="text-primary-700 font-medium text-sm">{{ substr($rating->user->name, 0, 1) }}</span>
                                            </div>
                                            <div class="flex-1">
                                                <div class="flex items-center space-x-2 mb-1">
                                                    <span
                                                        class="font-medium text-gray-900">{{ $rating->user->name }}</span>
                                                    <div class="flex">
                                                        @for ($i = 1; $i <= 5; $i++)
                                                            <svg class="w-4 h-4 {{ $i <= $rating->rating ? 'text-yellow-400' : 'text-gray-300' }}"
                                                                fill="currentColor" viewBox="0 0 20 20">
                                                                <path
                                                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                                                </path>
                                                            </svg>
                                                        @endfor
                                                    </div>
                                                </div>
                                                @if ($rating->ulasan)
                                                    <p class="text-gray-700 text-sm">{{ $rating->ulasan }}</p>
                                                @endif
                                                <p class="text-xs text-gray-500 mt-1">
                                                    {{ $rating->created_at->diffForHumans() }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Similar Books -->
                @if ($bukuSerupa->count() > 0)
                    <div class="card">
                        <div class="card-body">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Buku Serupa</h3>
                            <div class="space-y-3">
                                @foreach ($bukuSerupa as $serupa)
                                    <div class="flex space-x-3">
                                        <div
                                            class="w-12 h-16 bg-primary-100 rounded flex-shrink-0 flex items-center justify-center">
                                            @if ($serupa->gambar_sampul)
                                                <img src="{{ $serupa->gambar_sampul_url }}" alt="{{ $serupa->judul }}"
                                                    class="w-12 h-16 object-cover rounded">
                                            @else
                                                <svg class="w-4 h-4 text-primary-600" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                                                    </path>
                                                </svg>
                                            @endif
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <h4 class="font-medium text-sm text-gray-900 line-clamp-2 mb-1">
                                                {{ $serupa->judul }}</h4>
                                            <p class="text-xs text-gray-600">{{ $serupa->penulis }}</p>
                                            <div class="flex items-center justify-between mt-1">
                                                <span class="text-xs text-gray-500">★
                                                    {{ number_format($serupa->rating_rata_rata, 1) }}</span>
                                                <a href="{{ route('buku.show', $serupa) }}"
                                                    class="text-xs text-primary-600">Lihat</a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Quick Stats -->
                <div class="card">
                    <div class="card-body">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Buku</h3>
                        <div class="space-y-3 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Kategori:</span>
                                <span class="font-medium">{{ $buku->kategoriUtama->nama }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Sub Kategori:</span>
                                <span class="font-medium">{{ $buku->subKategori->nama }}</span>
                            </div>
                            @if ($buku->tahun_terbit)
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Tahun Terbit:</span>
                                    <span class="font-medium">{{ $buku->tahun_terbit }}</span>
                                </div>
                            @endif
                            <div class="flex justify-between">
                                <span class="text-gray-600">Total Halaman:</span>
                                <span class="font-medium">{{ number_format($buku->total_halaman) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Ukuran File:</span>
                                <span class="font-medium">{{ number_format($buku->ukuran_file / 1024 / 1024, 1) }}
                                    MB</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
