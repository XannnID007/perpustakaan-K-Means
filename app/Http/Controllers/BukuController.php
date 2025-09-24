<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Rating;
use App\Models\SubKategori;
use App\Models\ProgressBaca;
use Illuminate\Http\Request;
use App\Models\KategoriUtama;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class BukuController extends Controller
{
    public function index(Request $request)
    {
        $query = Buku::with(['kategoriUtama', 'subKategori'])
            ->where('aktif', true);

        // Filter pencarian
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('judul', 'like', '%' . $request->search . '%')
                    ->orWhere('penulis', 'like', '%' . $request->search . '%');
            });
        }

        // Filter kategori utama
        if ($request->kategori_utama) {
            $query->where('kategori_utama_id', $request->kategori_utama);
        }

        // Filter sub kategori
        if ($request->sub_kategori) {
            $query->where('sub_kategori_id', $request->sub_kategori);
        }

        // Sorting
        switch ($request->sort) {
            case 'terbaru':
                $query->latest();
                break;
            case 'terpopuler':
                $query->orderBy('total_pembaca', 'desc');
                break;
            case 'rating':
                $query->orderBy('rating_rata_rata', 'desc');
                break;
            case 'judul':
                $query->orderBy('judul');
                break;
            default:
                $query->latest();
        }

        $buku = $query->paginate(12);
        $kategoriUtama = KategoriUtama::with('subKategori')->get();

        return view('buku.index', compact('buku', 'kategoriUtama'));
    }

    public function show(Buku $buku)
    {
        $buku->load(['kategoriUtama', 'subKategori', 'rating.user']);

        $userProgress = null;
        $userRating = null;

        if (auth()->check()) {
            $userProgress = ProgressBaca::where('user_id', auth()->id())
                ->where('buku_id', $buku->id)
                ->first();

            $userRating = Rating::where('user_id', auth()->id())
                ->where('buku_id', $buku->id)
                ->first();
        }

        // Buku serupa
        $bukuSerupa = Buku::where('aktif', true)
            ->where('id', '!=', $buku->id)
            ->where(function ($query) use ($buku) {
                $query->where('sub_kategori_id', $buku->sub_kategori_id)
                    ->orWhere('kategori_utama_id', $buku->kategori_utama_id);
            })
            ->limit(6)
            ->get();

        return view('buku.show', compact('buku', 'userProgress', 'userRating', 'bukuSerupa'));
    }

    public function read(Buku $buku)
    {
        if (!auth()->check()) {
            return redirect()->route('login')
                ->with('message', 'Silakan login untuk membaca buku');
        }

        if (!$buku->aktif) {
            abort(404);
        }

        // Cek apakah file PDF ada
        $pdfPath = storage_path('app/books/' . $buku->file_pdf);
        if (!File::exists($pdfPath)) {
            return back()->with('error', 'File PDF tidak ditemukan. Silakan hubungi administrator.');
        }

        // Update atau create progress
        $progress = ProgressBaca::updateOrCreate(
            ['user_id' => auth()->id(), 'buku_id' => $buku->id],
            [
                'total_halaman' => $buku->total_halaman ?: 100,
                'terakhir_baca' => now()
            ]
        );

        // Update total pembaca jika pertama kali baca
        if ($progress->wasRecentlyCreated) {
            $buku->increment('total_pembaca');
        }

        return view('buku.read', compact('buku', 'progress'));
    }

    public function streamPdf(Buku $buku)
    {
        if (!auth()->check()) {
            abort(403, 'Unauthorized');
        }

        if (!$buku->aktif) {
            abort(404, 'Book not found');
        }

        $path = storage_path('app/books/' . $buku->file_pdf);

        // DEBUGGING: Log file info
        Log::info('PDF Stream Debug:', [
            'buku_id' => $buku->id,
            'file_pdf' => $buku->file_pdf,
            'path' => $path,
            'exists' => File::exists($path),
            'size' => File::exists($path) ? File::size($path) : 'N/A'
        ]);

        if (!File::exists($path)) {
            abort(404, 'PDF file not found');
        }

        // Validasi file adalah PDF
        $mimeType = File::mimeType($path);
        if ($mimeType !== 'application/pdf') {
            abort(415, 'Invalid file type');
        }

        return response()->file($path, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $buku->judul . '.pdf"',
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
            'Pragma' => 'no-cache',
            'Expires' => '0'
        ]);
    }

    public function getSubKategoriByKategori(Request $request)
    {
        $kategori_utama_id = $request->query('kategori_utama_id');

        if (!$kategori_utama_id) {
            return response()->json([]);
        }

        $subKategori = SubKategori::where('kategori_utama_id', $kategori_utama_id)->get();

        return response()->json($subKategori);
    }
}
