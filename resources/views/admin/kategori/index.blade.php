@extends('layouts.admin')

@section('title', 'Kelola Kategori')
@section('subtitle', 'Manajemen kategori dan sub kategori buku')

@section('content')
    <div class="space-y-8"> <!-- INCREASED SPACING -->
        <!-- Add Category Buttons -->
        <div class="flex space-x-4"> <!-- INCREASED SPACING -->
            <button onclick="showAddModal('utama')" class="btn btn-primary flex items-center space-x-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6">
                    </path>
                </svg>
                <span>Tambah Kategori Utama</span>
            </button>
            <button onclick="showAddModal('sub')" class="btn btn-secondary flex items-center space-x-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6">
                    </path>
                </svg>
                <span>Tambah Sub Kategori</span>
            </button>
        </div>

        <!-- Categories List -->
        <div class="space-y-6"> <!-- INCREASED SPACING -->
            @foreach ($kategoriUtama as $kategori)
                <div class="card">
                    <div class="card-body">
                        <!-- Main Category -->
                        <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-200">
                            <!-- INCREASED MARGIN -->
                            <div class="flex items-center space-x-4">
                                <div class="w-12 h-12 bg-primary-100 rounded-lg flex items-center justify-center">
                                    <!-- LARGER ICON -->
                                    <span
                                        class="text-primary-700 font-semibold text-lg">{{ substr($kategori->nama, 0, 1) }}</span>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900">{{ $kategori->nama }}</h3>
                                    <p class="text-sm text-gray-600">{{ $kategori->deskripsi }}</p>
                                    <p class="text-xs text-gray-500">{{ $kategori->buku_count }} buku</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2"> <!-- ICON ACTIONS -->
                                <!-- Edit -->
                                <button
                                    onclick="editKategori('utama', {{ $kategori->id }}, '{{ $kategori->nama }}', '{{ $kategori->deskripsi }}')"
                                    class="p-2 text-green-600 hover:bg-green-50 rounded-lg transition-colors"
                                    title="Edit Kategori">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                        </path>
                                    </svg>
                                </button>

                                <!-- Delete -->
                                <form action="{{ route('admin.kategori.destroy', ['utama', $kategori->id]) }}"
                                    method="POST" class="inline"
                                    onsubmit="return confirm('Yakin ingin menghapus kategori ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                                        title="Hapus Kategori">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                            </path>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>

                        <!-- Sub Categories -->
                        @if ($kategori->subKategori->count() > 0)
                            <div class="space-y-3"> <!-- INCREASED SPACING -->
                                <h4 class="font-medium text-gray-900 mb-4">Sub Kategori:</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4"> <!-- INCREASED GAP -->
                                    @foreach ($kategori->subKategori as $sub)
                                        <div class="bg-gray-50 rounded-lg p-4"> <!-- INCREASED PADDING -->
                                            <div class="flex items-center justify-between">
                                                <div class="flex-1">
                                                    <h5 class="font-medium text-gray-900">{{ $sub->nama }}</h5>
                                                    <p class="text-xs text-gray-600">{{ $sub->buku_count }} buku</p>
                                                </div>
                                                <div class="flex items-center space-x-1 ml-2">
                                                    <!-- Edit Sub -->
                                                    <button
                                                        onclick="editKategori('sub', {{ $sub->id }}, '{{ $sub->nama }}', '{{ $sub->deskripsi }}')"
                                                        class="p-1.5 text-green-600 hover:bg-green-100 rounded transition-colors"
                                                        title="Edit Sub Kategori">
                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                            </path>
                                                        </svg>
                                                    </button>

                                                    <!-- Delete Sub -->
                                                    <form action="{{ route('admin.kategori.destroy', ['sub', $sub->id]) }}"
                                                        method="POST" class="inline"
                                                        onsubmit="return confirm('Yakin ingin menghapus sub kategori ini?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="p-1.5 text-red-600 hover:bg-red-100 rounded transition-colors"
                                                            title="Hapus Sub Kategori">
                                                            <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                                </path>
                                                            </svg>
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <p class="text-sm text-gray-500 italic">Belum ada sub kategori</p>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Modal tetap sama seperti sebelumnya -->
    <div id="kategoriModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 w-96 max-w-full mx-4">
            <h3 id="modalTitle" class="text-lg font-semibold text-gray-900 mb-4">Tambah Kategori</h3>
            <form id="kategoriForm" method="POST">
                @csrf
                <input type="hidden" id="formMethod" name="_method" value="">
                <input type="hidden" id="formTipe" name="tipe" value="">

                <div class="space-y-4">
                    <!-- Kategori Utama Selection (for sub category) -->
                    <div id="kategoriUtamaField" class="hidden">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kategori Utama</label>
                        <select name="kategori_utama_id" class="input-field">
                            <option value="">Pilih Kategori Utama</option>
                            @foreach ($kategoriUtama as $kategori)
                                <option value="{{ $kategori->id }}">{{ $kategori->nama }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Name -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama</label>
                        <input type="text" name="nama" id="kategoriNama" class="input-field" required>
                    </div>

                    <!-- Description -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                        <textarea name="deskripsi" id="kategoriDeskripsi" rows="3" class="input-field"></textarea>
                    </div>
                </div>

                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" onclick="closeModal()" class="btn btn-outline">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function showAddModal(tipe) {
            document.getElementById('modalTitle').textContent =
                tipe === 'utama' ? 'Tambah Kategori Utama' : 'Tambah Sub Kategori';
            document.getElementById('kategoriForm').action = '{{ route('admin.kategori.store') }}';
            document.getElementById('formMethod').value = '';
            document.getElementById('formTipe').value = tipe;

            // Show/hide kategori utama field
            if (tipe === 'sub') {
                document.getElementById('kategoriUtamaField').classList.remove('hidden');
            } else {
                document.getElementById('kategoriUtamaField').classList.add('hidden');
            }

            // Reset form
            document.getElementById('kategoriNama').value = '';
            document.getElementById('kategoriDeskripsi').value = '';

            document.getElementById('kategoriModal').classList.remove('hidden');
            document.getElementById('kategoriModal').classList.add('flex');
        }

        function editKategori(tipe, id, nama, deskripsi) {
            document.getElementById('modalTitle').textContent =
                tipe === 'utama' ? 'Edit Kategori Utama' : 'Edit Sub Kategori';
            document.getElementById('kategoriForm').action = `/admin/kategori/${id}`;
            document.getElementById('formMethod').value = 'PUT';
            document.getElementById('formTipe').value = tipe;

            // Hide kategori utama field for edit
            document.getElementById('kategoriUtamaField').classList.add('hidden');

            // Fill form
            document.getElementById('kategoriNama').value = nama;
            document.getElementById('kategoriDeskripsi').value = deskripsi;

            document.getElementById('kategoriModal').classList.remove('hidden');
            document.getElementById('kategoriModal').classList.add('flex');
        }

        function closeModal() {
            document.getElementById('kategoriModal').classList.add('hidden');
            document.getElementById('kategoriModal').classList.remove('flex');
        }
    </script>
@endsection
