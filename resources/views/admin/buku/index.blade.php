@extends('layouts.admin')

@section('title', 'Kelola Buku')
@section('subtitle', 'Daftar semua buku dalam perpustakaan')

@section('content')
    <div class="space-y-8"> <!-- INCREASED SPACING -->
        <!-- Header Actions -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-4 sm:space-y-0">
            <!-- INCREASED SPACING -->
            <div class="flex-1 max-w-lg">
                <form action="{{ route('admin.buku.index') }}" method="GET" class="relative">
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Cari judul atau penulis..." class="input-field pl-10">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center">
                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                </form>
            </div>

            <a href="{{ route('admin.buku.create') }}" class="btn btn-primary flex items-center space-x-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6">
                    </path>
                </svg>
                <span>Tambah Buku</span>
            </a>
        </div>

        <!-- Books Table -->
        <div class="card">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Buku
                            </th> <!-- INCREASED PADDING -->
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Kategori</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Statistik</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($buku as $item)
                            <tr>
                                <td class="px-6 py-5 whitespace-nowrap"> <!-- INCREASED PADDING -->
                                    <div class="flex items-center">
                                        <div class="w-12 h-16 bg-primary-100 rounded flex items-center justify-center mr-4">
                                            <!-- LARGER IMAGE -->
                                            @if ($item->gambar_sampul)
                                                <img src="{{ $item->gambar_sampul_url }}" alt="{{ $item->judul }}"
                                                    class="w-12 h-16 object-cover rounded">
                                            @else
                                                <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                                                    </path>
                                                </svg>
                                            @endif
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">{{ $item->judul }}</div>
                                            <div class="text-sm text-gray-500">{{ $item->penulis }}</div>
                                            @if ($item->tahun_terbit)
                                                <div class="text-xs text-gray-400">{{ $item->tahun_terbit }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-5 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $item->kategoriUtama->nama }}</div>
                                    <div class="text-sm text-gray-500">{{ $item->subKategori->nama }}</div>
                                </td>
                                <td class="px-6 py-5 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ number_format($item->total_pembaca) }} pembaca
                                    </div>
                                    <div class="text-sm text-gray-500">Rating:
                                        {{ number_format($item->rating_rata_rata, 1) }}/5</div>
                                </td>
                                <td class="px-6 py-5 whitespace-nowrap">
                                    @if ($item->aktif)
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Aktif
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            Nonaktif
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-5 whitespace-nowrap">
                                    <div class="flex items-center space-x-2"> <!-- ICON ACTIONS -->
                                        <!-- View -->
                                        <a href="{{ route('admin.buku.show', $item) }}"
                                            class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors"
                                            title="Lihat Detail">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                </path>
                                            </svg>
                                        </a>

                                        <!-- Edit -->
                                        <a href="{{ route('admin.buku.edit', $item) }}"
                                            class="p-2 text-green-600 hover:bg-green-50 rounded-lg transition-colors"
                                            title="Edit Buku">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                </path>
                                            </svg>
                                        </a>

                                        <!-- Delete -->
                                        <form action="{{ route('admin.buku.destroy', $item) }}" method="POST"
                                            class="inline" onsubmit="return confirm('Yakin ingin menghapus buku ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                                                title="Hapus Buku">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                    </path>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-gray-500"> <!-- INCREASED PADDING -->
                                    <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                                        </path>
                                    </svg>
                                    <p class="text-lg font-medium text-gray-900 mb-2">Tidak ada buku ditemukan</p>
                                    <p class="text-gray-600">Mulai dengan menambahkan buku pertama Anda</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($buku->hasPages())
                <div class="pagination-wrapper">
                    {{ $buku->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
