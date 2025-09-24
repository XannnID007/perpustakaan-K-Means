@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Koleksi Buku</h1>
            <p class="text-gray-600">Jelajahi ribuan buku dari berbagai kategori</p>
        </div>

        <!-- Filters -->
        <div class="mb-8">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('buku.index') }}" method="GET" class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <!-- Search -->
                            <div class="md:col-span-2">
                                <input type="text" name="search" value="{{ request('search') }}"
                                    placeholder="Cari judul buku atau penulis..." class="input-field">
                            </div>

                            <!-- Category Filter -->
                            <div>
                                <select name="kategori_utama" class="input-field" onchange="loadSubKategori(this.value)">
                                    <option value="">Semua Kategori</option>
                                    @foreach ($kategoriUtama as $kategori)
                                        <option value="{{ $kategori->id }}"
                                            {{ request('kategori_utama') == $kategori->id ? 'selected' : '' }}>
                                            {{ $kategori->nama }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Sub Category Filter -->
                            <div>
                                <select name="sub_kategori" id="subKategoriSelect" class="input-field">
                                    <option value="">Semua Sub Kategori</option>
                                    @if (request('kategori_utama'))
                                        @foreach ($kategoriUtama->find(request('kategori_utama'))->subKategori ?? [] as $sub)
                                            <option value="{{ $sub->id }}"
                                                {{ request('sub_kategori') == $sub->id ? 'selected' : '' }}>
                                                {{ $sub->nama }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>

                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-3 sm:space-y-0">
                            <!-- Sort -->
                            <div class="flex items-center space-x-4">
                                <label class="text-sm font-medium text-gray-700">Urutkan:</label>
                                <select name="sort" class="input-field w-auto">
                                    <option value="terbaru" {{ request('sort') == 'terbaru' ? 'selected' : '' }}>Terbaru
                                    </option>
                                    <option value="terpopuler" {{ request('sort') == 'terpopuler' ? 'selected' : '' }}>
                                        Terpopuler</option>
                                    <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>Rating
                                        Tertinggi</option>
                                    <option value="judul" {{ request('sort') == 'judul' ? 'selected' : '' }}>Judul A-Z
                                    </option>
                                </select>
                            </div>

                            <!-- Filter Actions -->
                            <div class="flex space-x-2">
                                <button type="submit" class="btn btn-primary">
                                    Filter
                                </button>
                                <a href="{{ route('buku.index') }}" class="btn btn-outline">Reset</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Results Info -->
        <div class="flex items-center justify-between mb-6">
            <p class="text-gray-600">
                Menampilkan {{ $buku->count() }} dari {{ $buku->total() }} buku
                @if (request('search'))
                    untuk "<strong>{{ request('search') }}</strong>"
                @endif
            </p>

            <!-- View Toggle (Grid/List) -->
            <div class="flex bg-gray-100 rounded-lg p-1">
                <button id="gridView" class="px-3 py-1 rounded text-sm font-medium bg-white shadow-sm text-gray-900">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z">
                        </path>
                    </svg>
                </button>
                <button id="listView" class="px-3 py-1 rounded text-sm font-medium text-gray-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Books Grid - FIXED STRUCTURE -->
        <div id="booksGrid" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-4 mb-8">
            @forelse($buku as $item)
                <div class="card h-full flex flex-col">
                    <div class="p-3 flex-1 flex flex-col">
                        <a href="{{ route('buku.show', $item) }}" class="block">
                            <div
                                class="aspect-[3/4] bg-primary-100 rounded mb-3 flex items-center justify-center overflow-hidden">
                                @if ($item->gambar_sampul)
                                    <img src="{{ $item->gambar_sampul_url }}" alt="{{ $item->judul }}"
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

                        <!-- Content dengan flex untuk push button ke bawah -->
                        <div class="flex-1 flex flex-col">
                            <h3 class="font-medium text-sm text-gray-900 mb-1 line-clamp-2 flex-1">
                                <a href="{{ route('buku.show', $item) }}">{{ $item->judul }}</a>
                            </h3>
                            <p class="text-xs text-gray-600 mb-2">{{ $item->penulis }}</p>

                            <div class="flex items-center justify-between text-xs text-gray-500 mb-3">
                                <span class="truncate">{{ $item->subKategori->nama }}</span>
                                <div class="flex items-center space-x-1">
                                    <svg class="w-3 h-3 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                        </path>
                                    </svg>
                                    <span class="text-gray-600">{{ number_format($item->rating_rata_rata, 1) }}</span>
                                </div>
                            </div>

                            <div class="text-xs text-gray-500 mb-3">
                                {{ number_format($item->total_pembaca) }} pembaca
                            </div>

                            <!-- Button di bagian paling bawah dengan mt-auto -->
                            @auth
                                <a href="{{ route('buku.show', $item) }}" class="btn btn-primary w-full text-xs py-2 mt-auto">
                                    Lihat Detail
                                </a>
                            @else
                                <div class="text-center mt-auto">
                                    <a href="{{ route('login') }}" class="text-xs text-primary-600">Login untuk membaca</a>
                                </div>
                            @endauth
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-12">
                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9.172 16.172a4 4 0 015.656 0M9 12h6m-6-4h6m2 5.291A7.962 7.962 0 0118 12c0-4.418-3.582-8-8-8s-8 3.582-8 8c0 2.152.851 4.103 2.233 5.291">
                        </path>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak ada buku ditemukan</h3>
                    <p class="text-gray-600 mb-4">Coba ubah kata kunci pencarian atau filter yang Anda gunakan</p>
                    <a href="{{ route('buku.index') }}" class="btn btn-primary">Lihat Semua Buku</a>
                </div>
            @endforelse
        </div>

        <!-- Books List View (Hidden by default) -->
        <div id="booksList" class="space-y-4 mb-8 hidden">
            @foreach ($buku as $item)
                <div class="card">
                    <div class="card-body">
                        <div class="flex space-x-4">
                            <div
                                class="w-16 h-20 bg-primary-100 rounded flex-shrink-0 flex items-center justify-center overflow-hidden">
                                @if ($item->gambar_sampul)
                                    <img src="{{ $item->gambar_sampul_url }}" alt="{{ $item->judul }}"
                                        class="w-full h-full object-cover">
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
                                <div class="flex items-start justify-between">
                                    <div class="flex-1 pr-4">
                                        <h3 class="text-lg font-semibold text-gray-900 mb-1">
                                            <a href="{{ route('buku.show', $item) }}"
                                                class="hover:text-primary-600">{{ $item->judul }}</a>
                                        </h3>
                                        <p class="text-gray-600 mb-2">oleh {{ $item->penulis }}</p>
                                        <p class="text-sm text-gray-700 line-clamp-2 mb-3">
                                            {{ Str::limit($item->deskripsi, 150) }}</p>

                                        <div class="flex items-center space-x-4 text-sm text-gray-500">
                                            <span
                                                class="inline-flex items-center px-2 py-1 bg-primary-100 text-primary-800 rounded-full text-xs">
                                                {{ $item->subKategori->nama }}
                                            </span>
                                            <span class="flex items-center">
                                                <svg class="w-4 h-4 text-yellow-400 mr-1" fill="currentColor"
                                                    viewBox="0 0 20 20">
                                                    <path
                                                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                                    </path>
                                                </svg>
                                                {{ number_format($item->rating_rata_rata, 1) }}
                                            </span>
                                            <span>{{ number_format($item->total_pembaca) }} pembaca</span>
                                            @if ($item->tahun_terbit)
                                                <span>{{ $item->tahun_terbit }}</span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="flex-shrink-0">
                                        @auth
                                            <a href="{{ route('buku.show', $item) }}" class="btn btn-primary">
                                                Lihat Detail
                                            </a>
                                        @else
                                            <a href="{{ route('login') }}" class="btn btn-outline">
                                                Login untuk Membaca
                                            </a>
                                        @endauth
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if ($buku->hasPages())
            <div class="pagination-wrapper">
                {{ $buku->links() }}
            </div>
        @endif
    </div>

    <script>
        // View Toggle
        document.getElementById('gridView').addEventListener('click', function() {
            document.getElementById('booksGrid').classList.remove('hidden');
            document.getElementById('booksList').classList.add('hidden');
            this.classList.add('bg-white', 'shadow-sm', 'text-gray-900');
            this.classList.remove('text-gray-600');
            document.getElementById('listView').classList.remove('bg-white', 'shadow-sm', 'text-gray-900');
            document.getElementById('listView').classList.add('text-gray-600');
        });

        document.getElementById('listView').addEventListener('click', function() {
            document.getElementById('booksList').classList.remove('hidden');
            document.getElementById('booksGrid').classList.add('hidden');
            this.classList.add('bg-white', 'shadow-sm', 'text-gray-900');
            this.classList.remove('text-gray-600');
            document.getElementById('gridView').classList.remove('bg-white', 'shadow-sm', 'text-gray-900');
            document.getElementById('gridView').classList.add('text-gray-600');
        });

        // Load Sub Kategori
        function loadSubKategori(kategoriUtamaId) {
            const subKategoriSelect = document.getElementById('subKategoriSelect');

            // Reset sub kategori
            subKategoriSelect.innerHTML = '<option value="">Semua Sub Kategori</option>';

            if (kategoriUtamaId) {
                fetch(`/admin/get-sub-kategori?kategori_utama_id=${kategoriUtamaId}`)
                    .then(response => response.json())
                    .then(data => {
                        data.forEach(subKategori => {
                            const option = document.createElement('option');
                            option.value = subKategori.id;
                            option.textContent = subKategori.nama;
                            if ('{{ request('sub_kategori') }}' == subKategori.id) {
                                option.selected = true;
                            }
                            subKategoriSelect.appendChild(option);
                        });
                    });
            }
        }

        // Load sub kategori on page load if kategori utama is selected
        document.addEventListener('DOMContentLoaded', function() {
            const kategoriUtamaSelect = document.querySelector('select[name="kategori_utama"]');
            if (kategoriUtamaSelect.value) {
                loadSubKategori(kategoriUtamaSelect.value);
            }
        });
    </script>

    <style>
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        /* Ensure consistent card heights */
        .card {
            transition: all 0.2s ease-in-out;
            height: 100%;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        /* Force equal height cards dengan flex */
        #booksGrid .card {
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        #booksGrid .card>div {
            display: flex;
            flex-direction: column;
            flex: 1;
        }

        /* Pastikan button selalu di bawah */
        #booksGrid .card .flex-1:last-child {
            display: flex;
            flex-direction: column;
        }

        #booksGrid .card .mt-auto {
            margin-top: auto;
        }
    </style>
@endsection
