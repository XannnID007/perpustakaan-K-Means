@extends('layouts.admin')

@section('title', 'Dashboard')
@section('subtitle', 'Selamat datang di panel admin')

@section('content')
    <div class="space-y-8"> <!-- INCREASED SPACING -->
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6"> <!-- INCREASED GAP -->
            <div class="card">
                <div class="card-body">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-primary-100 rounded-lg flex items-center justify-center">
                                <!-- LARGER ICON CONTAINER -->
                                <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                                    </path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-5"> <!-- INCREASED MARGIN -->
                            <p class="text-sm font-medium text-gray-600">Total Buku</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ number_format($stats['total_buku']) }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-cream-100 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-cream-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z">
                                    </path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-5">
                            <p class="text-sm font-medium text-gray-600">Total Pengguna</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ number_format($stats['total_pengguna']) }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z">
                                    </path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-5">
                            <p class="text-sm font-medium text-gray-600">Total Kategori</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ number_format($stats['total_kategori']) }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                                    </path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-5">
                            <p class="text-sm font-medium text-gray-600">Dibaca Bulan Ini</p>
                            <p class="text-2xl font-semibold text-gray-900">
                                {{ number_format($stats['buku_dibaca_bulan_ini']) }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8"> <!-- INCREASED GAP -->
            <!-- Buku Populer -->
            <div class="card">
                <div class="card-body">
                    <h3 class="text-lg font-semibold text-gray-900 mb-6">Buku Paling Populer</h3> <!-- INCREASED MARGIN -->
                    <div class="space-y-4"> <!-- INCREASED SPACING -->
                        @forelse($bukuPopuler as $buku)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <!-- ADDED BACKGROUND -->
                                <div class="flex items-center space-x-4">
                                    <div class="w-10 h-12 bg-primary-100 rounded flex items-center justify-center">
                                        <span class="text-xs font-medium text-primary-700">{{ $loop->iteration }}</span>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 truncate">{{ $buku->judul }}</p>
                                        <p class="text-xs text-gray-500">{{ $buku->penulis }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-medium text-gray-900">{{ $buku->total_pembaca }}</p>
                                    <p class="text-xs text-gray-500">pembaca</p>
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500 text-center py-8">Belum ada data buku populer</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Pengguna Aktif -->
            <div class="card">
                <div class="card-body">
                    <h3 class="text-lg font-semibold text-gray-900 mb-6">Pengguna Aktif (7 hari terakhir)</h3>
                    <div class="space-y-4">
                        @forelse($penggunaAktif as $user)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <div class="flex items-center space-x-4">
                                    <div class="w-8 h-8 bg-cream-100 rounded-full flex items-center justify-center">
                                        <span
                                            class="text-sm font-medium text-cream-700">{{ substr($user->name, 0, 1) }}</span>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 truncate">{{ $user->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $user->email }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-xs text-gray-500">
                                        {{ $user->terakhir_aktif ? $user->terakhir_aktif->diffForHumans() : 'Belum pernah' }}
                                    </p>
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500 text-center py-8">Belum ada pengguna aktif</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="card">
            <div class="card-body">
                <h3 class="text-lg font-semibold text-gray-900 mb-6">Aksi Cepat</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6"> <!-- INCREASED GAP -->
                    <a href="{{ route('admin.buku.create') }}"
                        class="btn btn-primary flex items-center justify-center space-x-3 py-4"> <!-- INCREASED PADDING -->
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        <span>Tambah Buku</span>
                    </a>

                    <a href="{{ route('admin.kategori.index') }}"
                        class="btn btn-secondary flex items-center justify-center space-x-3 py-4">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z">
                            </path>
                        </svg>
                        <span>Kelola Kategori</span>
                    </a>

                    <a href="{{ route('admin.users.index') }}"
                        class="btn btn-outline flex items-center justify-center space-x-3 py-4">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z">
                            </path>
                        </svg>
                        <span>Lihat Pengguna</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
