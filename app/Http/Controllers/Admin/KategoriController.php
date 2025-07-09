<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KategoriUtama;
use App\Models\SubKategori;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class KategoriController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    public function index()
    {
        $kategoriUtama = KategoriUtama::with(['subKategori' => function ($query) {
            $query->withCount('buku');
        }])->withCount('buku')->get();

        return view('admin.kategori.index', compact('kategoriUtama'));
    }

    public function store(Request $request)
    {
        if ($request->tipe === 'utama') {
            $request->validate([
                'nama' => 'required|string|max:50|unique:kategori_utama,nama',
                'deskripsi' => 'nullable|string',
            ]);

            KategoriUtama::create([
                'nama' => $request->nama,
                'slug' => Str::slug($request->nama),
                'deskripsi' => $request->deskripsi,
            ]);

            return redirect()->back()->with('success', 'Kategori utama berhasil ditambahkan!');
        } else {
            $request->validate([
                'nama' => 'required|string|max:100',
                'kategori_utama_id' => 'required|exists:kategori_utama,id',
                'deskripsi' => 'nullable|string',
            ]);

            SubKategori::create([
                'kategori_utama_id' => $request->kategori_utama_id,
                'nama' => $request->nama,
                'slug' => Str::slug($request->nama),
                'deskripsi' => $request->deskripsi,
            ]);

            return redirect()->back()->with('success', 'Sub kategori berhasil ditambahkan!');
        }
    }

    public function update(Request $request, $id)
    {
        if ($request->tipe === 'utama') {
            $kategori = KategoriUtama::findOrFail($id);
            $request->validate([
                'nama' => 'required|string|max:50|unique:kategori_utama,nama,' . $id,
                'deskripsi' => 'nullable|string',
            ]);

            $kategori->update([
                'nama' => $request->nama,
                'slug' => Str::slug($request->nama),
                'deskripsi' => $request->deskripsi,
            ]);
        } else {
            $subKategori = SubKategori::findOrFail($id);
            $request->validate([
                'nama' => 'required|string|max:100',
                'deskripsi' => 'nullable|string',
            ]);

            $subKategori->update([
                'nama' => $request->nama,
                'slug' => Str::slug($request->nama),
                'deskripsi' => $request->deskripsi,
            ]);
        }

        return redirect()->back()->with('success', 'Kategori berhasil diperbarui!');
    }

    public function destroy($tipe, $id)
    {
        if ($tipe === 'utama') {
            $kategori = KategoriUtama::findOrFail($id);
            if ($kategori->buku()->count() > 0) {
                return redirect()->back()->with('error', 'Tidak dapat menghapus kategori yang masih memiliki buku!');
            }
            $kategori->delete();
        } else {
            $subKategori = SubKategori::findOrFail($id);
            if ($subKategori->buku()->count() > 0) {
                return redirect()->back()->with('error', 'Tidak dapat menghapus sub kategori yang masih memiliki buku!');
            }
            $subKategori->delete();
        }

        return redirect()->back()->with('success', 'Kategori berhasil dihapus!');
    }
}
