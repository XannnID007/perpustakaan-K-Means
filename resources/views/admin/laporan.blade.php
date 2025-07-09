@extends('layouts.admin')

@section('title', 'Laporan')
@section('subtitle', 'Analisis dan statistik perpustakaan')

@section('content')
    <div class="space-y-6">
        <!-- Overview Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="card">
                <div class="card-body text-center">
                    <div class="text-3xl font-bold text-blue-600 mb-2">{{ \App\Models\User::where('role', 'user')->count() }}
                    </div>
                    <div class="text-sm text-gray-600">Total Pengguna</div>
                </div>
            </div>

            <div class="card">
                <div class="card-body text-center">
                    <div class="text-3xl font-bold text-green-600 mb-2">{{ \App\Models\Buku::count() }}</div>
                    <div class="text-sm text-gray-600">Total Buku</div>
                </div>
            </div>

            <div class="card">
                <div class="card-body text-center">
                    <div class="text-3xl font-bold text-purple-600 mb-2">{{ \App\Models\ProgressBaca::count() }}</div>
                    <div class="text-sm text-gray-600">Total Pembacaan</div>
                </div>
            </div>

            <div class="card">
                <div class="card-body text-center">
                    <div class="text-3xl font-bold text-red-600 mb-2">{{ \App\Models\Rating::count() }}</div>
                    <div class="text-sm text-gray-600">Total Rating</div>
                </div>
            </div>
        </div>

        <!-- Buku Populer -->
        <div class="card">
            <div class="card-body">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Buku Paling Populer</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead>
                            <tr class="border-b border-gray-200">
                                <th class="text-left py-2">Judul</th>
                                <th class="text-left py-2">Penulis</th>
                                <th class="text-left py-2">Kategori</th>
                                <th class="text-left py-2">Pembaca</th>
                                <th class="text-left py-2">Rating</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach (\App\Models\Buku::orderBy('total_pembaca', 'desc')->limit(10)->get() as $buku)
                                <tr class="border-b border-gray-100">
                                    <td class="py-2 font-medium">{{ $buku->judul }}</td>
                                    <td class="py-2 text-gray-600">{{ $buku->penulis }}</td>
                                    <td class="py-2 text-gray-600">{{ $buku->subKategori->nama ?? 'N/A' }}</td>
                                    <td class="py-2">{{ number_format($buku->total_pembaca) }}</td>
                                    <td class="py-2">{{ number_format($buku->rating_rata_rata, 1) }}/5</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Kategori Populer -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="card">
                <div class="card-body">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Kategori Populer</h3>
                    <div class="space-y-3">
                        @foreach (\App\Models\KategoriUtama::withCount('buku')->orderBy('buku_count', 'desc')->get() as $kategori)
                            <div class="flex justify-between items-center">
                                <span class="font-medium">{{ $kategori->nama }}</span>
                                <span class="text-gray-600">{{ $kategori->buku_count }} buku</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Pengguna Aktif</h3>
                    <div class="space-y-3">
                        @foreach (\App\Models\User::where('role', 'user')->withCount('progressBaca')->orderBy('progress_baca_count', 'desc')->limit(10)->get() as $user)
                            <div class="flex justify-between items-center">
                                <span class="font-medium">{{ $user->name }}</span>
                                <span class="text-gray-600">{{ $user->progress_baca_count }} buku</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
