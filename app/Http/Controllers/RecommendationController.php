<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\ProgressBaca;
use Illuminate\Http\Request;

class RecommendationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = auth()->user();

        // Get user's reading categories
        $userCategories = ProgressBaca::where('user_id', $user->id)
            ->join('buku', 'progress_baca.buku_id', '=', 'buku.id')
            ->select('buku.kategori_utama_id', 'buku.sub_kategori_id')
            ->groupBy('buku.kategori_utama_id', 'buku.sub_kategori_id')
            ->get();

        // Books already read
        $readBookIds = ProgressBaca::where('user_id', $user->id)->pluck('buku_id');

        // Category-based recommendations
        $categoryRecommendations = collect();
        if ($userCategories->isNotEmpty()) {
            $categoryRecommendations = Buku::where('aktif', true)
                ->whereNotIn('id', $readBookIds)
                ->where(function ($query) use ($userCategories) {
                    foreach ($userCategories as $category) {
                        $query->orWhere(function ($q) use ($category) {
                            $q->where('kategori_utama_id', $category->kategori_utama_id)
                                ->where('sub_kategori_id', $category->sub_kategori_id);
                        });
                    }
                })
                ->orderBy('rating_rata_rata', 'desc')
                ->limit(8)
                ->get();
        }

        // Popular books
        $popularBooks = Buku::where('aktif', true)
            ->whereNotIn('id', $readBookIds)
            ->orderBy('total_pembaca', 'desc')
            ->limit(8)
            ->get();

        // High-rated books
        $highRatedBooks = Buku::where('aktif', true)
            ->whereNotIn('id', $readBookIds)
            ->where('rating_rata_rata', '>=', 4.0)
            ->orderBy('rating_rata_rata', 'desc')
            ->limit(8)
            ->get();

        return view('rekomendasi', compact(
            'categoryRecommendations',
            'popularBooks',
            'highRatedBooks'
        ));
    }
}
