<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use App\Models\KategoriUtama;
use App\Models\SubKategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BukuController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    public function index()
    {
        $buku = Buku::with(['kategoriUtama', 'subKategori'])
            ->when(request('search'), function ($query) {
                $query->where('judul', 'like', '%' . request('search') . '%')
                    ->orWhere('penulis', 'like', '%' . request('search') . '%');
            })
            ->paginate(10);

        return view('admin.buku.index', compact('buku'));
    }

    public function create()
    {
        $kategoriUtama = KategoriUtama::with('subKategori')->get();
        return view('admin.buku.create', compact('kategoriUtama'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'penulis' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'kategori_utama_id' => 'required|exists:kategori_utama,id',
            'sub_kategori_id' => 'required|exists:sub_kategori,id',
            'tahun_terbit' => 'nullable|numeric|min:1900|max:' . date('Y'),
            'gambar_sampul' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'file_pdf' => 'required|mimes:pdf|max:50000',
        ]);

        $data = $request->only(['judul', 'penulis', 'deskripsi', 'kategori_utama_id', 'sub_kategori_id', 'tahun_terbit']);

        // Upload cover image
        if ($request->hasFile('gambar_sampul')) {
            $fileName = time() . '_cover.' . $request->gambar_sampul->extension();
            $request->gambar_sampul->storeAs('public/covers', $fileName);
            $data['gambar_sampul'] = $fileName;
        }

        // Upload PDF file
        if ($request->hasFile('file_pdf')) {
            $pdfFileName = time() . '_' . Str::slug($request->judul) . '.pdf';
            $request->file_pdf->storeAs('books', $pdfFileName);
            $data['file_pdf'] = $pdfFileName;
            $data['ukuran_file'] = $request->file_pdf->getSize();
        }

        Buku::create($data);

        return redirect()->route('admin.buku.index')->with('success', 'Buku berhasil ditambahkan!');
    }

    public function show(Buku $buku)
    {
        $buku->load(['kategoriUtama', 'subKategori', 'progressBaca', 'rating']);
        return view('admin.buku.show', compact('buku'));
    }

    public function edit(Buku $buku)
    {
        $kategoriUtama = KategoriUtama::with('subKategori')->get();
        return view('admin.buku.edit', compact('buku', 'kategoriUtama'));
    }

    public function update(Request $request, Buku $buku)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'penulis' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'kategori_utama_id' => 'required|exists:kategori_utama,id',
            'sub_kategori_id' => 'required|exists:sub_kategori,id',
            'tahun_terbit' => 'nullable|numeric|min:1900|max:' . date('Y'),
            'gambar_sampul' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'file_pdf' => 'nullable|mimes:pdf|max:50000',
        ]);

        $data = $request->only(['judul', 'penulis', 'deskripsi', 'kategori_utama_id', 'sub_kategori_id', 'tahun_terbit']);

        // Update cover image
        if ($request->hasFile('gambar_sampul')) {
            // Delete old image
            if ($buku->gambar_sampul) {
                Storage::delete('public/covers/' . $buku->gambar_sampul);
            }

            $fileName = time() . '_cover.' . $request->gambar_sampul->extension();
            $request->gambar_sampul->storeAs('public/covers', $fileName);
            $data['gambar_sampul'] = $fileName;
        }

        // Update PDF file
        if ($request->hasFile('file_pdf')) {
            // Delete old PDF
            if ($buku->file_pdf) {
                Storage::delete('books/' . $buku->file_pdf);
            }

            $pdfFileName = time() . '_' . Str::slug($request->judul) . '.pdf';
            $request->file_pdf->storeAs('books', $pdfFileName);
            $data['file_pdf'] = $pdfFileName;
            $data['ukuran_file'] = $request->file_pdf->getSize();
        }

        $buku->update($data);

        return redirect()->route('admin.buku.index')->with('success', 'Buku berhasil diperbarui!');
    }

    public function destroy(Buku $buku)
    {
        // Delete files
        if ($buku->gambar_sampul) {
            Storage::delete('public/covers/' . $buku->gambar_sampul);
        }
        if ($buku->file_pdf) {
            Storage::delete('books/' . $buku->file_pdf);
        }

        $buku->delete();

        return redirect()->route('admin.buku.index')->with('success', 'Buku berhasil dihapus!');
    }

    public function getSubKategori(Request $request)
    {
        // Validasi request
        $request->validate([
            'kategori_utama_id' => 'required|exists:kategori_utama,id'
        ]);

        try {
            $subKategori = SubKategori::where('kategori_utama_id', $request->kategori_utama_id)
                ->select('id', 'nama')
                ->get();

            return response()->json($subKategori);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal mengambil sub kategori'], 500);
        }
    }
}
