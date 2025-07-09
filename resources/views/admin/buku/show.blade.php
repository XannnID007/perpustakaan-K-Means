@extends('layouts.admin')

@section('title', 'Detail Buku')
@section('subtitle', $buku->judul)

@section('content')
    <div class="max-w-6xl">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Book Info -->
            <div class="lg:col-span-2">
                <div class="card">
                    <div class="card-body">
                        <div class="flex flex-col md:flex-row space-y-6 md:space-y-0 md:space-x-6">
                            <!-- Book Cover -->
                            <div class="flex-shrink-0">
                                <div class="w-48 h-64 bg-primary-100 rounded-lg flex items-center justify-center">
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
                                        @if ($buku->aktif)
                                            <span
                                                class="inline-block px-3 py-1 bg-green-100 text-green-800 text-sm rounded-full">
                                                Aktif
                                            </span>
                                        @else
                                            <span
                                                class="inline-block px-3 py-1 bg-red-100 text-red-800 text-sm rounded-full">
                                                Nonaktif
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <!-- Stats -->
                                <div class="mb-6">
                                    <div class="grid grid-cols-3 gap-4 text-center">
                                        <div>
                                            <div class="text-2xl font-bold text-gray-900">
                                                {{ number_format($buku->rating_rata_rata, 1) }}</div>
                                            <div class="text-sm text-gray-500">Rating</div>
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
                                <div class="flex space-x-3">
                                    <a href="{{ route('admin.buku.edit', $buku) }}" class="btn btn-primary">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                            </path>
                                        </svg>
                                        Edit Buku
                                    </a>
                                    <a href="{{ route('buku.show', $buku) }}" target="_blank" class="btn btn-outline">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-2M7 7l10 10M17 7v10a2 2 0 01-2 2H7">
                                            </path>
                                        </svg>
                                        Lihat di Frontend
                                    </a>
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
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Quick Info -->
                <div class="card">
                    <div class="card-body">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi File</h3>
                        <div class="space-y-3 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-600">File PDF:</span>
                                <span class="font-medium">{{ $buku->file_pdf }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Ukuran File:</span>
                                <span class="font-medium">{{ number_format($buku->ukuran_file / 1024 / 1024, 1) }}
                                    MB</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Ditambahkan:</span>
                                <span class="font-medium">{{ $buku->created_at->format('d M Y') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Terakhir Update:</span>
                                <span class="font-medium">{{ $buku->updated_at->format('d M Y') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Reading Statistics -->
                @if ($buku->progressBaca->count() > 0)
                    <div class="card">
                        <div class="card-body">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Statistik Pembaca</h3>
                            <div class="space-y-3">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Sedang Membaca:</span>
                                    <span class="font-medium">{{ $buku->progressBaca->where('selesai', false)->count() }}
                                        orang</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Selesai Membaca:</span>
                                    <span class="font-medium">{{ $buku->progressBaca->where('selesai', true)->count() }}
                                        orang</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Rata-rata Progress:</span>
                                    <span
                                        class="font-medium">{{ number_format($buku->progressBaca->avg('persentase_baca'), 1) }}%</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Recent Ratings -->
                @if ($buku->rating->count() > 0)
                    <div class="card">
                        <div class="card-body">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Rating Terbaru</h3>
                            <div class="space-y-3">
                                @foreach ($buku->rating->take(5) as $rating)
                                    <div class="border-b border-gray-200 pb-3 last:border-b-0">
                                        <div class="flex items-start space-x-2">
                                            <div
                                                class="w-6 h-6 bg-primary-100 rounded-full flex items-center justify-center">
                                                <span
                                                    class="text-primary-700 font-medium text-xs">{{ substr($rating->user->name, 0, 1) }}</span>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <div class="flex items-center space-x-1 mb-1">
                                                    <span
                                                        class="font-medium text-sm text-gray-900">{{ $rating->user->name }}</span>
                                                    <div class="flex">
                                                        @for ($i = 1; $i <= 5; $i++)
                                                            <svg class="w-3 h-3 {{ $i <= $rating->rating ? 'text-yellow-400' : 'text-gray-300' }}"
                                                                fill="currentColor" viewBox="0 0 20 20">
                                                                <path
                                                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                                                </path>
                                                            </svg>
                                                        @endfor
                                                    </div>
                                                </div>
                                                @if ($rating->ulasan)
                                                    <p class="text-xs text-gray-600 line-clamp-2">{{ $rating->ulasan }}
                                                    </p>
                                                @endif
                                                <p class="text-xs text-gray-400 mt-1">
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
        </div>
    </div>
@endsection
