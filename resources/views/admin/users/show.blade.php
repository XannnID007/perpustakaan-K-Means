@extends('layouts.admin')

@section('title', 'Detail Pengguna')
@section('subtitle', $user->name)

@section('content')
    <div class="max-w-6xl">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- User Info -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Profile Card -->
                <div class="card">
                    <div class="card-body">
                        <div class="flex items-start space-x-4">
                            <div class="w-16 h-16 bg-primary-100 rounded-full flex items-center justify-center">
                                <span class="text-primary-700 font-bold text-xl">{{ substr($user->name, 0, 1) }}</span>
                            </div>
                            <div class="flex-1">
                                <h2 class="text-xl font-semibold text-gray-900 mb-1">{{ $user->name }}</h2>
                                <p class="text-gray-600 mb-2">{{ $user->email }}</p>
                                <div class="flex items-center space-x-4 text-sm text-gray-500">
                                    <span>Bergabung {{ $user->created_at->format('d M Y') }}</span>
                                    @if ($user->terakhir_aktif)
                                        <span>Aktif {{ $user->terakhir_aktif->diffForHumans() }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Reading Progress -->
                @if ($user->progressBaca->count() > 0)
                    <div class="card">
                        <div class="card-body">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Progress Membaca</h3>
                            <div class="space-y-4">
                                @foreach ($user->progressBaca->take(10) as $progress)
                                    <div class="flex items-center space-x-4">
                                        <div
                                            class="w-12 h-16 bg-primary-100 rounded flex-shrink-0 flex items-center justify-center">
                                            @if ($progress->buku->gambar_sampul)
                                                <img src="{{ $progress->buku->gambar_sampul_url }}"
                                                    alt="{{ $progress->buku->judul }}"
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
                                        <div class="flex-1">
                                            <h4 class="font-medium text-gray-900">{{ $progress->buku->judul }}</h4>
                                            <p class="text-sm text-gray-600">{{ $progress->buku->penulis }}</p>
                                            <div class="flex items-center justify-between mt-2">
                                                <div class="flex-1 mr-4">
                                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                                        <div class="bg-primary-600 h-2 rounded-full"
                                                            style="width: {{ $progress->persentase_baca }}%"></div>
                                                    </div>
                                                </div>
                                                <span
                                                    class="text-sm text-gray-500">{{ number_format($progress->persentase_baca, 1) }}%</span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                <!-- User Ratings -->
                @if ($user->rating->count() > 0)
                    <div class="card">
                        <div class="card-body">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Rating yang Diberikan</h3>
                            <div class="space-y-4">
                                @foreach ($user->rating->take(10) as $rating)
                                    <div class="border-b border-gray-200 pb-4 last:border-b-0">
                                        <div class="flex items-start space-x-3">
                                            <div
                                                class="w-10 h-14 bg-primary-100 rounded flex-shrink-0 flex items-center justify-center">
                                                @if ($rating->buku->gambar_sampul)
                                                    <img src="{{ $rating->buku->gambar_sampul_url }}"
                                                        alt="{{ $rating->buku->judul }}"
                                                        class="w-10 h-14 object-cover rounded">
                                                @else
                                                    <svg class="w-4 h-4 text-primary-600" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                                                        </path>
                                                    </svg>
                                                @endif
                                            </div>
                                            <div class="flex-1">
                                                <h4 class="font-medium text-gray-900">{{ $rating->buku->judul }}</h4>
                                                <div class="flex items-center space-x-2 mt-1">
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
                                                    <span
                                                        class="text-sm text-gray-500">{{ $rating->created_at->format('d M Y') }}</span>
                                                </div>
                                                @if ($rating->ulasan)
                                                    <p class="text-sm text-gray-700 mt-2">{{ $rating->ulasan }}</p>
                                                @endif
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
                <!-- Statistics -->
                <div class="card">
                    <div class="card-body">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Statistik</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Total Buku Dibaca:</span>
                                <span class="font-medium">{{ $user->progressBaca->count() }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Selesai Dibaca:</span>
                                <span class="font-medium">{{ $user->progressBaca->where('selesai', true)->count() }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Total Bookmark:</span>
                                <span class="font-medium">{{ $user->bookmark->count() }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Rating Diberikan:</span>
                                <span class="font-medium">{{ $user->rating->count() }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Rata-rata Rating:</span>
                                <span
                                    class="font-medium">{{ $user->rating->count() > 0 ? number_format($user->rating->avg('rating'), 1) : 'N/A' }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Bookmarks -->
                @if ($user->bookmark->count() > 0)
                    <div class="card">
                        <div class="card-body">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Bookmark Terbaru</h3>
                            <div class="space-y-3">
                                @foreach ($user->bookmark->take(5) as $bookmark)
                                    <div class="text-sm">
                                        <p class="font-medium text-gray-900">{{ $bookmark->buku->judul }}</p>
                                        <p class="text-gray-600">Halaman {{ $bookmark->nomor_halaman }}</p>
                                        <p class="text-gray-500 text-xs">{{ $bookmark->created_at->diffForHumans() }}</p>
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
