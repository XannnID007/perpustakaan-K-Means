<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\KategoriUtama;
use App\Models\ProgressBaca;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $bukuTerbaru = Buku::with(['kategoriUtama', 'subKategori'])
            ->where('aktif', true)
            ->latest()
            ->limit(8)
            ->get();

        $bukuPopuler = Buku::with(['kategoriUtama', 'subKategori'])
            ->where('aktif', true)
            ->orderBy('total_pembaca', 'desc')
            ->limit(8)
            ->get();

        $kategori = KategoriUtama::with(['subKategori' => function ($query) {
            $query->limit(5);
        }])->get();

        // Rekomendasi untuk user yang login
        $rekomendasi = [];
        if (auth()->check()) {
            $rekomendasi = $this->getPersonalizedRecommendations();
        }

        return view('home', compact('bukuTerbaru', 'bukuPopuler', 'kategori', 'rekomendasi'));
    }

    private function getPersonalizedRecommendations()
    {
        $user = auth()->user();

        // Ambil kategori yang sering dibaca user
        $kategoriDibaca = ProgressBaca::where('user_id', $user->id)
            ->join('buku', 'progress_baca.buku_id', '=', 'buku.id')
            ->select('buku.kategori_utama_id', 'buku.sub_kategori_id')
            ->groupBy('buku.kategori_utama_id', 'buku.sub_kategori_id')
            ->limit(3)
            ->get();

        if ($kategoriDibaca->isEmpty()) {
            // Jika belum ada history, tampilkan buku populer
            return Buku::where('aktif', true)
                ->orderBy('total_pembaca', 'desc')
                ->limit(6)
                ->get();
        }

        // Ambil buku dari kategori yang sama
        $bukuIds = ProgressBaca::where('user_id', $user->id)->pluck('buku_id');

        return Buku::where('aktif', true)
            ->whereNotIn('id', $bukuIds) // Exclude buku yang sudah dibaca
            ->where(function ($query) use ($kategoriDibaca) {
                foreach ($kategoriDibaca as $kategori) {
                    $query->orWhere(function ($q) use ($kategori) {
                        $q->where('kategori_utama_id', $kategori->kategori_utama_id)
                            ->where('sub_kategori_id', $kategori->sub_kategori_id);
                    });
                }
            })
            ->orderBy('rating_rata_rata', 'desc')
            ->limit(6)
            ->get();
    }
}
