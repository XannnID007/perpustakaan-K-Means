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

                <!-- Rating Section -->
                <div class="card mt-6">
                    <div class="card-body">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-900">Rating & Ulasan</h3>
                            <div class="text-sm text-gray-500">
                                {{ $buku->rating->count() }} ulasan
                            </div>
                        </div>

                        <!-- Rating Form/Display -->
                        @auth
                            @if ($userRating)
                                {{-- Edit existing rating --}}
                                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                                    <h4 class="font-semibold text-blue-900 mb-2">Rating Anda</h4>
                                    <div class="flex items-center space-x-3 mb-2">
                                        <div class="flex" id="currentUserRating">
                                            @for ($i = 1; $i <= 5; $i++)
                                                <svg class="w-5 h-5 {{ $i <= $userRating->rating ? 'text-yellow-400' : 'text-gray-300' }}"
                                                    fill="currentColor" viewBox="0 0 20 20">
                                                    <path
                                                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                                    </path>
                                                </svg>
                                            @endfor
                                            <span
                                                class="ml-2 text-sm font-medium text-blue-700">{{ $userRating->rating }}/5</span>
                                        </div>
                                        <button onclick="editRating()"
                                            class="text-sm text-blue-600 hover:text-blue-800 font-medium">Edit</button>
                                        <button onclick="deleteRating({{ $userRating->id }})"
                                            class="text-sm text-red-600 hover:text-red-800 font-medium">Hapus</button>
                                    </div>
                                    @if ($userRating->ulasan)
                                        <div class="bg-white rounded p-3 mt-2">
                                            <p class="text-sm text-gray-700 italic">"{{ $userRating->ulasan }}"</p>
                                        </div>
                                    @endif
                                </div>
                            @else
                                {{-- Add new rating --}}
                                <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 mb-6">
                                    <h4 class="font-semibold text-gray-900 mb-3">Berikan Rating Anda</h4>
                                    <button onclick="showRatingModal()" class="btn btn-primary">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z">
                                            </path>
                                        </svg>
                                        Beri Rating & Ulasan
                                    </button>
                                </div>
                            @endif
                        @else
                            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                                <p class="text-yellow-800">
                                    <a href="{{ route('login') }}" class="font-medium text-yellow-900 underline">Login</a>
                                    untuk memberikan rating dan ulasan
                                </p>
                            </div>
                        @endauth

                        <!-- Reviews List -->
                        @if ($buku->rating->count() > 0)
                            <div class="space-y-4">
                                <h4 class="font-medium text-gray-900 border-b border-gray-200 pb-2">Semua Ulasan</h4>
                                @foreach ($buku->rating->take(10) as $rating)
                                    <div class="border-b border-gray-200 pb-4 last:border-b-0">
                                        <div class="flex items-start space-x-3">
                                            <div
                                                class="w-8 h-8 bg-primary-100 rounded-full flex items-center justify-center flex-shrink-0">
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
                                                    <span
                                                        class="text-sm text-gray-500">{{ $rating->created_at->diffForHumans() }}</span>
                                                </div>
                                                @if ($rating->ulasan)
                                                    <p class="text-sm text-gray-700 mt-2">{{ $rating->ulasan }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach

                                @if ($buku->rating->count() > 10)
                                    <div class="text-center pt-4">
                                        <button class="text-blue-600 hover:text-blue-800 font-medium text-sm">
                                            Lihat semua {{ $buku->rating->count() }} ulasan
                                        </button>
                                    </div>
                                @endif
                            </div>
                        @else
                            <div class="text-center py-8">
                                <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 8h10m0 0V6a2 2 0 00-2-2H9a2 2 0 00-2 2v2m10 0v10a2 2 0 01-2 2H9a2 2 0 01-2-2V8m10 0H7">
                                    </path>
                                </svg>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada ulasan</h3>
                                <p class="text-gray-600">Jadilah yang pertama memberikan ulasan untuk buku ini</p>
                            </div>
                        @endif
                    </div>
                </div>
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
                                                <svg class="w-4 h-4 text-primary-600" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
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
                            <div class="flex justify-between">
                                <span class="text-gray-600">Total Rating:</span>
                                <span class="font-medium">{{ $buku->rating->count() }} ulasan</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Rating Modal -->
    <div id="ratingModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
        <div class="bg-white rounded-lg p-6 w-full max-w-md">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Berikan Rating</h3>
                <button onclick="closeRatingModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>

            <form id="ratingForm" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Rating</label>
                    <div class="flex items-center space-x-1" id="starRating">
                        @for ($i = 1; $i <= 5; $i++)
                            <svg class="w-8 h-8 text-gray-300 cursor-pointer hover:text-yellow-400 transition-colors star-input"
                                data-rating="{{ $i }}" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                </path>
                            </svg>
                        @endfor
                    </div>
                    <p class="text-sm text-gray-500 mt-1">Klik bintang untuk memberikan rating</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Ulasan (opsional)</label>
                    <textarea id="reviewText" rows="4"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="Bagikan pengalaman Anda membaca buku ini..."></textarea>
                </div>

                <div class="flex space-x-3 pt-2">
                    <button type="button" onclick="closeRatingModal()"
                        class="flex-1 px-4 py-2 text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-md transition-colors">
                        Batal
                    </button>
                    <button type="submit"
                        class="flex-1 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md transition-colors">
                        Simpan Rating
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        let selectedRating = 0;
        let isEditMode = false;
        let editingRatingId = null;

        // Show rating modal
        function showRatingModal() {
            document.getElementById('ratingModal').classList.remove('hidden');
            document.getElementById('ratingModal').classList.add('flex');

            // Reset form
            selectedRating = 0;
            updateStarDisplay();
            document.getElementById('reviewText').value = '';
        }

        // Edit existing rating
        function editRating() {
            isEditMode = true;
            editingRatingId = {{ $userRating->id ?? 'null' }};
            selectedRating = {{ $userRating->rating ?? 0 }};

            showRatingModal();

            // Pre-fill form
            updateStarDisplay();
            document.getElementById('reviewText').value = `{{ $userRating->ulasan ?? '' }}`;
        }

        // Close rating modal
        function closeRatingModal() {
            document.getElementById('ratingModal').classList.add('hidden');
            document.getElementById('ratingModal').classList.remove('flex');
            isEditMode = false;
            editingRatingId = null;
        }

        // Handle star clicks
        document.addEventListener('DOMContentLoaded', function() {
            const stars = document.querySelectorAll('.star-input');

            stars.forEach(star => {
                star.addEventListener('click', function() {
                    selectedRating = parseInt(this.dataset.rating);
                    updateStarDisplay();
                });

                star.addEventListener('mouseenter', function() {
                    const rating = parseInt(this.dataset.rating);
                    highlightStars(rating);
                });
            });

            document.getElementById('starRating').addEventListener('mouseleave', function() {
                updateStarDisplay();
            });
        });

        function highlightStars(rating) {
            const stars = document.querySelectorAll('.star-input');
            stars.forEach((star, index) => {
                if (index < rating) {
                    star.classList.remove('text-gray-300');
                    star.classList.add('text-yellow-400');
                } else {
                    star.classList.remove('text-yellow-400');
                    star.classList.add('text-gray-300');
                }
            });
        }

        function updateStarDisplay() {
            highlightStars(selectedRating);
        }

        // Submit rating
        document.getElementById('ratingForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            if (selectedRating === 0) {
                alert('Silakan pilih rating terlebih dahulu');
                return;
            }

            const reviewText = document.getElementById('reviewText').value;

            try {
                const url = isEditMode ? `/api/rating/${editingRatingId}` : '/api/rating';
                const method = isEditMode ? 'PUT' : 'POST';

                const response = await fetch(url, {
                    method: method,
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content')
                    },
                    body: JSON.stringify({
                        buku_id: {{ $buku->id }},
                        rating: selectedRating,
                        ulasan: reviewText
                    })
                });

                const result = await response.json();

                if (result.success) {
                    closeRatingModal();
                    showNotification('Rating berhasil disimpan!', 'Terima kasih atas ulasan Anda', 'success');

                    // Reload page to show updated rating
                    setTimeout(() => {
                        location.reload();
                    }, 1500);
                } else {
                    alert(result.message || 'Gagal menyimpan rating');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat menyimpan rating');
            }
        });

        // Delete rating
        async function deleteRating(ratingId) {
            if (!confirm('Yakin ingin menghapus rating Anda?')) {
                return;
            }

            try {
                const response = await fetch(`/api/rating/${ratingId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content')
                    }
                });

                const result = await response.json();

                if (result.success) {
                    showNotification('Rating berhasil dihapus', '', 'success');

                    // Reload page
                    setTimeout(() => {
                        location.reload();
                    }, 1500);
                } else {
                    alert(result.message || 'Gagal menghapus rating');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat menghapus rating');
            }
        }

        // Notification function
        function showNotification(title, message, type = 'success') {
            // Create notification element if it doesn't exist
            let notification = document.getElementById('notification');
            if (!notification) {
                notification = document.createElement('div');
                notification.id = 'notification';
                notification.className =
                    'fixed top-4 right-4 transform translate-x-full transition-transform duration-300 z-50';
                notification.innerHTML = `
                    <div class="bg-white rounded-lg shadow-lg border border-gray-200 p-4 flex items-center space-x-3 min-w-[280px]">
                        <div id="notificationIcon"></div>
                        <div>
                            <p id="notificationMessage" class="font-medium text-gray-900"></p>
                            <p id="notificationSubtext" class="text-sm text-gray-600"></p>
                        </div>
                    </div>
                `;
                document.body.appendChild(notification);
            }

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
    </script>
@endsection
