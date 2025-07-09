@extends('layouts.admin')

@section('title', 'Edit Buku')
@section('subtitle', 'Perbarui informasi buku')

@section('content')
    <div class="max-w-4xl">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.buku.update', $buku) }}" method="POST" enctype="multipart/form-data"
                    class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Judul -->
                        <div class="md:col-span-2">
                            <label for="judul" class="block text-sm font-medium text-gray-700 mb-1">Judul Buku</label>
                            <input type="text" id="judul" name="judul" value="{{ old('judul', $buku->judul) }}"
                                class="input-field @error('judul') border-red-300 @enderror"
                                placeholder="Masukkan judul buku">
                            @error('judul')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Penulis -->
                        <div>
                            <label for="penulis" class="block text-sm font-medium text-gray-700 mb-1">Penulis</label>
                            <input type="text" id="penulis" name="penulis" value="{{ old('penulis', $buku->penulis) }}"
                                class="input-field @error('penulis') border-red-300 @enderror" placeholder="Nama penulis">
                            @error('penulis')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Tahun Terbit -->
                        <div>
                            <label for="tahun_terbit" class="block text-sm font-medium text-gray-700 mb-1">Tahun
                                Terbit</label>
                            <input type="number" id="tahun_terbit" name="tahun_terbit"
                                value="{{ old('tahun_terbit', $buku->tahun_terbit) }}" min="1900"
                                max="{{ date('Y') }}"
                                class="input-field @error('tahun_terbit') border-red-300 @enderror" placeholder="2024">
                            @error('tahun_terbit')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Kategori Utama -->
                        <div>
                            <label for="kategori_utama_id" class="block text-sm font-medium text-gray-700 mb-1">Kategori
                                Utama</label>
                            <select id="kategori_utama_id" name="kategori_utama_id"
                                class="input-field @error('kategori_utama_id') border-red-300 @enderror"
                                onchange="loadSubKategori()">
                                <option value="">Pilih Kategori Utama</option>
                                @foreach ($kategoriUtama as $kategori)
                                    <option value="{{ $kategori->id }}"
                                        {{ old('kategori_utama_id', $buku->kategori_utama_id) == $kategori->id ? 'selected' : '' }}>
                                        {{ $kategori->nama }}
                                    </option>
                                @endforeach
                            </select>
                            @error('kategori_utama_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Sub Kategori -->
                        <div>
                            <label for="sub_kategori_id" class="block text-sm font-medium text-gray-700 mb-1">Sub
                                Kategori</label>
                            <select id="sub_kategori_id" name="sub_kategori_id"
                                class="input-field @error('sub_kategori_id') border-red-300 @enderror">
                                <option value="">Pilih Sub Kategori</option>
                            </select>
                            @error('sub_kategori_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Deskripsi -->
                    <div>
                        <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                        <textarea id="deskripsi" name="deskripsi" rows="4"
                            class="input-field @error('deskripsi') border-red-300 @enderror" placeholder="Masukkan deskripsi buku">{{ old('deskripsi', $buku->deskripsi) }}</textarea>
                        @error('deskripsi')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Cover Image -->
                        <div>
                            <label for="gambar_sampul" class="block text-sm font-medium text-gray-700 mb-1">Gambar
                                Sampul</label>
                            @if ($buku->gambar_sampul)
                                <div class="mb-2">
                                    <img src="{{ $buku->gambar_sampul_url }}" alt="Current cover"
                                        class="w-20 h-28 object-cover rounded">
                                    <p class="text-xs text-gray-500 mt-1">Gambar saat ini</p>
                                </div>
                            @endif
                            <input type="file" id="gambar_sampul" name="gambar_sampul" accept="image/*"
                                class="input-field @error('gambar_sampul') border-red-300 @enderror">
                            <p class="mt-1 text-xs text-gray-500">Format: JPG, PNG. Maksimal 2MB. Kosongkan jika tidak ingin
                                mengubah.</p>
                            @error('gambar_sampul')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- PDF File -->
                        <div>
                            <label for="file_pdf" class="block text-sm font-medium text-gray-700 mb-1">File PDF</label>
                            <div class="mb-2">
                                <p class="text-sm text-gray-600">File saat ini: {{ $buku->file_pdf }}</p>
                                <p class="text-xs text-gray-500">Ukuran:
                                    {{ number_format($buku->ukuran_file / 1024 / 1024, 1) }} MB</p>
                            </div>
                            <input type="file" id="file_pdf" name="file_pdf" accept=".pdf"
                                class="input-field @error('file_pdf') border-red-300 @enderror">
                            <p class="mt-1 text-xs text-gray-500">Format: PDF. Maksimal 50MB. Kosongkan jika tidak ingin
                                mengubah.</p>
                            @error('file_pdf')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Status -->
                    <div>
                        <label class="flex items-center">
                            <input type="checkbox" name="aktif" value="1"
                                {{ old('aktif', $buku->aktif) ? 'checked' : '' }}
                                class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded">
                            <span class="ml-2 text-sm text-gray-700">Aktif (buku dapat dibaca oleh pengguna)</span>
                        </label>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="flex items-center justify-end space-x-3 pt-6">
                        <a href="{{ route('admin.buku.index') }}" class="btn btn-outline">Batal</a>
                        <button type="submit" class="btn btn-primary">Perbarui Buku</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function loadSubKategori() {
            const kategoriUtamaId = document.getElementById('kategori_utama_id').value;
            const subKategoriSelect = document.getElementById('sub_kategori_id');

            // Reset sub kategori
            subKategoriSelect.innerHTML = '<option value="">Pilih Sub Kategori</option>';

            if (kategoriUtamaId) {
                fetch(`/admin/get-sub-kategori?kategori_utama_id=${kategoriUtamaId}`)
                    .then(response => response.json())
                    .then(data => {
                        data.forEach(subKategori => {
                            const option = document.createElement('option');
                            option.value = subKategori.id;
                            option.textContent = subKategori.nama;
                            // Set selected if this was the original sub category
                            if (subKategori.id == {{ $buku->sub_kategori_id }}) {
                                option.selected = true;
                            }
                            subKategoriSelect.appendChild(option);
                        });
                    });
            }
        }

        // Load sub kategori on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadSubKategori();
        });
    </script>
@endsection
