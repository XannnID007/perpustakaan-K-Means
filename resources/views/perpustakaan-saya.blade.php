@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Perpustakaan Saya</h1>
            <p class="text-gray-600">Kelola koleksi dan progress membaca Anda</p>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
            <div class="card">
                <div class="card-body text-center">
                    <div class="text-2xl font-bold text-primary-600 mb-1">{{ $stats['total_dibaca'] }}</div>
                    <div class="text-sm text-gray-600">Total Buku</div>
                </div>
            </div>

            <div class="card">
                <div class="card-body text-center">
                    <div class="text-2xl font-bold text-green-600 mb-1">{{ $stats['selesai_dibaca'] }}</div>
                    <div class="text-sm text-gray-600">Selesai Dibaca</div>
                </div>
            </div>

            <div class="card">
                <div class="card-body text-center">
                    <div class="text-2xl font-bold text-blue-600 mb-1">{{ $stats['total_bookmark'] }}</div>
                    <div class="text-sm text-gray-600">Bookmark</div>
                </div>
            </div>

            <div class="card">
                <div class="card-body text-center">
                    <div class="text-2xl font-bold text-purple-600 mb-1">
                        {{ number_format($stats['waktu_baca_total'] / 3600, 1) }}</div>
                    <div class="text-sm text-gray-600">Jam Membaca</div>
                </div>
            </div>
        </div>

        <!-- Navigation Tabs -->
        <div class="mb-6">
            <nav class="flex space-x-8">
                <button onclick="showTab('sedang-dibaca')" id="tab-sedang-dibaca" class="tab-button active">
                    Sedang Dibaca ({{ $sedangDibaca->count() }})
                </button>
                <button onclick="showTab('selesai-dibaca')" id="tab-selesai-dibaca" class="tab-button">
                    Selesai Dibaca ({{ $selesaiDibaca->count() }})
                </button>
                <button onclick="showTab('bookmark')" id="tab-bookmark" class="tab-button">
                    Bookmark ({{ $bookmark->count() }})
                </button>
            </nav>
        </div>

        <!-- Sedang Dibaca Tab -->
        <div id="content-sedang-dibaca" class="tab-content">
            @if ($sedangDibaca->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($sedangDibaca as $progress)
                        <div class="card">
                            <div class="card-body">
                                <div class="flex space-x-4">
                                    <div
                                        class="w-16 h-20 bg-primary-100 rounded flex-shrink-0 flex items-center justify-center overflow-hidden">
                                        @if ($progress->buku->gambar_sampul)
                                            <img src="{{ $progress->buku->gambar_sampul_url }}"
                                                alt="{{ $progress->buku->judul }}" class="w-full h-full object-cover">
                                        @else
                                            <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                                                </path>
                                            </svg>
                                        @endif
                                    </div>

                                    <div class="flex-1">
                                        <h3 class="font-semibold text-gray-900 mb-1 line-clamp-2">
                                            {{ $progress->buku->judul }}</h3>
                                        <p class="text-sm text-gray-600 mb-2">{{ $progress->buku->penulis }}</p>

                                        <div class="mb-3">
                                            <div class="flex justify-between text-sm text-gray-600 mb-1">
                                                <span>Halaman {{ $progress->halaman_sekarang }} dari
                                                    {{ $progress->total_halaman }}</span>
                                                <span>{{ number_format($progress->persentase_baca, 1) }}%</span>
                                            </div>
                                            <div class="w-full bg-gray-200 rounded-full h-2">
                                                <div class="bg-primary-600 h-2 rounded-full"
                                                    style="width: {{ $progress->persentase_baca }}%"></div>
                                            </div>
                                        </div>

                                        <div class="text-xs text-gray-500 mb-3">
                                            Terakhir dibaca: {{ $progress->terakhir_baca->diffForHumans() }}
                                        </div>

                                        <a href="{{ route('buku.read', $progress->buku) }}"
                                            class="btn btn-primary w-full text-sm">
                                            Lanjutkan Membaca
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                        </path>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada buku yang sedang dibaca</h3>
                    <p class="text-gray-600 mb-4">Mulai membaca buku pertama Anda dari koleksi kami</p>
                    <a href="{{ route('buku.index') }}" class="btn btn-primary">Jelajahi Koleksi</a>
                </div>
            @endif
        </div>

        <!-- Selesai Dibaca Tab -->
        <div id="content-selesai-dibaca" class="tab-content hidden">
            @if ($selesaiDibaca->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    @foreach ($selesaiDibaca as $progress)
                        <div class="card">
                            <div class="p-3">
                                <div
                                    class="aspect-[3/4] bg-primary-100 rounded mb-3 flex items-center justify-center overflow-hidden">
                                    @if ($progress->buku->gambar_sampul)
                                        <img src="{{ $progress->buku->gambar_sampul_url }}"
                                            alt="{{ $progress->buku->judul }}" class="w-full h-full object-cover">
                                    @else
                                        <svg class="w-8 h-8 text-primary-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                                            </path>
                                        </svg>
                                    @endif
                                </div>

                                <h3 class="font-medium text-sm text-gray-900 mb-1 line-clamp-2">
                                    {{ $progress->buku->judul }}</h3>
                                <p class="text-xs text-gray-600 mb-2">{{ $progress->buku->penulis }}</p>

                                <div class="text-xs text-gray-500 mb-2">
                                    <div class="flex items-center text-green-600 mb-1">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                        Selesai
                                    </div>
                                    Selesai: {{ $progress->updated_at->format('d M Y') }}
                                </div>

                                <a href="{{ route('buku.show', $progress->buku) }}"
                                    class="block btn btn-outline w-full text-xs py-1">
                                    Lihat Detail
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada buku yang selesai dibaca</h3>
                    <p class="text-gray-600 mb-4">Selesaikan membaca buku pertama Anda untuk melihatnya di sini</p>
                    <a href="{{ route('buku.index') }}" class="btn btn-primary">Jelajahi Koleksi</a>
                </div>
            @endif
        </div>

        <!-- Bookmark Tab -->
        <div id="content-bookmark" class="tab-content hidden">
            @if ($bookmark->count() > 0)
                <div class="space-y-4">
                    @foreach ($bookmark as $item)
                        <div class="card">
                            <div class="card-body">
                                <div class="flex items-start space-x-4">
                                    <div
                                        class="w-12 h-16 bg-primary-100 rounded flex-shrink-0 flex items-center justify-center overflow-hidden">
                                        @if ($item->buku->gambar_sampul)
                                            <img src="{{ $item->buku->gambar_sampul_url }}"
                                                alt="{{ $item->buku->judul }}" class="w-full h-full object-cover">
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
                                        <div class="flex items-start justify-between">
                                            <div>
                                                <h3 class="font-semibold text-gray-900 mb-1">{{ $item->buku->judul }}</h3>
                                                <p class="text-sm text-gray-600 mb-2">{{ $item->buku->penulis }}</p>

                                                <div class="flex items-center space-x-4 text-sm text-gray-500 mb-2">
                                                    <span>Halaman {{ $item->nomor_halaman }}</span>
                                                    <span>{{ $item->created_at->format('d M Y H:i') }}</span>
                                                    <span
                                                        class="inline-flex items-center px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs">
                                                        {{ ucfirst($item->tipe) }}
                                                    </span>
                                                </div>

                                                @if ($item->catatan)
                                                    <div class="bg-gray-50 rounded p-3 text-sm text-gray-700">
                                                        <strong>Catatan:</strong> {{ $item->catatan }}
                                                    </div>
                                                @endif
                                            </div>

                                            <div class="flex space-x-2">
                                                <a href="{{ route('buku.read', $item->buku) }}?page={{ $item->nomor_halaman }}"
                                                    class="btn btn-primary text-xs">
                                                    Buka
                                                </a>
                                                <button onclick="deleteBookmark({{ $item->id }})"
                                                    class="btn btn-outline text-xs text-red-600 border-red-300">
                                                    Hapus
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada bookmark</h3>
                    <p class="text-gray-600 mb-4">Tambahkan bookmark saat membaca untuk menyimpan halaman penting</p>
                    <a href="{{ route('buku.index') }}" class="btn btn-primary">Mulai Membaca</a>
                </div>
            @endif
        </div>
    </div>

    <script>
        // Tab functionality
        function showTab(tabName) {
            // Hide all tab contents
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.add('hidden');
            });

            // Remove active class from all tabs
            document.querySelectorAll('.tab-button').forEach(button => {
                button.classList.remove('active');
            });

            // Show selected tab content
            document.getElementById('content-' + tabName).classList.remove('hidden');

            // Add active class to selected tab
            document.getElementById('tab-' + tabName).classList.add('active');
        }

        // Delete bookmark function
        async function deleteBookmark(bookmarkId) {
            if (!confirm('Yakin ingin menghapus bookmark ini?')) {
                return;
            }

            try {
                const response = await fetch(`/api/bookmark/${bookmarkId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content'),
                        'Content-Type': 'application/json'
                    }
                });

                if (response.ok) {
                    location.reload();
                } else {
                    alert('Gagal menghapus bookmark');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Terjadi kesalahan');
            }
        }
    </script>

    <style>
        .tab-button {
            @apply pb-2 border-b-2 border-transparent text-gray-500 font-medium text-sm;
            transition: all 0.2s ease-in-out;
        }

        .tab-button:hover {
            @apply text-gray-700 border-gray-300;
        }

        .tab-button.active {
            @apply text-primary-600 border-primary-600;
        }

        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>
@endsection
