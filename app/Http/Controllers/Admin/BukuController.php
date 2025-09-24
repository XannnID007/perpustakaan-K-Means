<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use App\Models\Rating;
use App\Models\SubKategori;
use App\Models\ProgressBaca;
use Illuminate\Http\Request;
use App\Models\KategoriUtama;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

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
            abort(404, 'Buku tidak tersedia');
        }

        // Cek apakah file PDF ada
        $pdfPath = storage_path('app/books/' . $buku->file_pdf);
        if (!File::exists($pdfPath)) {
            Log::error('PDF file not found', [
                'book_id' => $buku->id,
                'file_pdf' => $buku->file_pdf,
                'path' => $pdfPath
            ]);

            return back()->with('error', 'File PDF tidak ditemukan. Silakan hubungi administrator.');
        }

        // Pastikan total halaman tidak kosong
        if (!$buku->total_halaman || $buku->total_halaman < 1) {
            // Coba hitung halaman dari file PDF jika memungkinkan
            $defaultPages = 100;
            $buku->update(['total_halaman' => $defaultPages]);
            Log::info('Set default page count', [
                'book_id' => $buku->id,
                'total_pages' => $defaultPages
            ]);
        }

        // Update atau create progress
        $progress = ProgressBaca::updateOrCreate(
            ['user_id' => auth()->id(), 'buku_id' => $buku->id],
            [
                'total_halaman' => $buku->total_halaman,
                'halaman_sekarang' => 1, // Set default jika belum pernah baca
                'terakhir_baca' => now()
            ]
        );

        // Pastikan halaman sekarang tidak melebihi total halaman
        if ($progress->halaman_sekarang > $progress->total_halaman) {
            $progress->update(['halaman_sekarang' => 1]);
        }

        // Update total pembaca jika pertama kali baca
        if ($progress->wasRecentlyCreated) {
            $buku->increment('total_pembaca');
            Log::info('New reader added', [
                'book_id' => $buku->id,
                'user_id' => auth()->id(),
                'total_readers' => $buku->fresh()->total_pembaca
            ]);
        }

        return view('buku.read', compact('buku', 'progress'));
    }

    public function streamPdf(Buku $buku)
    {
        // Log request untuk debugging
        Log::info('PDF stream requested', [
            'book_id' => $buku->id,
            'user_id' => auth()->id() ?? 'guest',
            'file_pdf' => $buku->file_pdf
        ]);

        // Cek autentikasi
        if (!auth()->check()) {
            Log::warning('Unauthorized PDF access attempt', ['book_id' => $buku->id]);
            abort(403, 'Unauthorized - Please login first');
        }

        // Cek status buku
        if (!$buku->aktif) {
            Log::warning('Inactive book access attempt', ['book_id' => $buku->id]);
            abort(404, 'Book not available');
        }

        // Cek file PDF ada
        if (!$buku->file_pdf) {
            Log::error('PDF filename is empty', ['book_id' => $buku->id]);
            abort(404, 'PDF file not configured');
        }

        $path = storage_path('app/books/' . $buku->file_pdf);

        // Validasi file exists
        if (!File::exists($path)) {
            Log::error('PDF file not found on disk', [
                'book_id' => $buku->id,
                'file_pdf' => $buku->file_pdf,
                'path' => $path,
                'storage_path' => storage_path('app/books/'),
                'files_in_directory' => File::exists(storage_path('app/books/')) ? File::files(storage_path('app/books/')) : 'Directory not exists'
            ]);
            abort(404, 'PDF file not found on server');
        }

        // Validasi ukuran file
        $fileSize = File::size($path);
        if ($fileSize === 0) {
            Log::error('PDF file is empty', [
                'book_id' => $buku->id,
                'file_pdf' => $buku->file_pdf,
                'path' => $path
            ]);
            abort(404, 'PDF file is corrupted or empty');
        }

        // Validasi MIME type
        $mimeType = File::mimeType($path);
        if ($mimeType !== 'application/pdf') {
            Log::error('Invalid file type', [
                'book_id' => $buku->id,
                'file_pdf' => $buku->file_pdf,
                'mime_type' => $mimeType
            ]);
            abort(415, 'Invalid file type. Expected PDF.');
        }

        Log::info('PDF stream successful', [
            'book_id' => $buku->id,
            'file_size' => $fileSize,
            'mime_type' => $mimeType
        ]);

        // Return file response dengan header yang tepat
        return response()->file($path, [
            'Content-Type' => 'application/pdf',
            'Content-Length' => $fileSize,
            'Content-Disposition' => 'inline; filename="' . str_replace('"', '', $buku->judul) . '.pdf"',
            'Accept-Ranges' => 'bytes',
            'Cache-Control' => 'public, max-age=3600', // Cache 1 jam untuk performa
            'X-Content-Type-Options' => 'nosniff',
            'X-Frame-Options' => 'SAMEORIGIN'
        ]);
    }
}
