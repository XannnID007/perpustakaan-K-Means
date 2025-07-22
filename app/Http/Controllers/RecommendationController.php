<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\ProgressBaca;
use App\Services\SimpleKMeansService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class RecommendationController extends Controller
{
    private $kmeansService;

    public function __construct()
    {
        $this->middleware('auth');
        $this->kmeansService = new SimpleKMeansService();
    }

    public function index()
    {
        $user = auth()->user();

        // Cek apakah clustering sudah pernah dijalankan
        $userCluster = $this->kmeansService->getUserCluster($user->id);

        if ($userCluster === null) {
            // Jika belum ada cluster, gunakan rekomendasi lama
            return $this->fallbackRecommendation();
        }

        // Dapatkan rekomendasi berdasarkan cluster
        $categoryRecommendations = $this->getClusterBasedRecommendations($user->id, $userCluster);
        $popularBooks = $this->getPopularBooksForCluster($userCluster);
        $highRatedBooks = $this->getHighRatedBooksForCluster($userCluster);

        // Informasi cluster user
        $clusterProfiles = $this->kmeansService->getClusterProfiles();
        $userClusterInfo = $clusterProfiles[$userCluster] ?? null;

        return view('rekomendasi', compact(
            'categoryRecommendations',
            'popularBooks',
            'highRatedBooks',
            'userClusterInfo'
        ));
    }

    /**
     * Rekomendasi berdasarkan cluster K-Means
     */
    private function getClusterBasedRecommendations($userId, $clusterId)
    {
        // Ambil user lain di cluster yang sama
        $clusters = Cache::get('user_clusters', []);
        $clusterMates = array_keys(array_filter($clusters, fn($c) => $c === $clusterId));

        // Hapus user sendiri dari daftar
        $clusterMates = array_filter($clusterMates, fn($id) => $id != $userId);

        if (empty($clusterMates)) {
            return collect();
        }

        // Ambil buku yang sudah dibaca user
        $readBookIds = ProgressBaca::where('user_id', $userId)->pluck('buku_id')->toArray();

        // Cari buku yang dibaca cluster mates tapi belum dibaca user
        $recommendations = Buku::where('aktif', true)
            ->whereNotIn('id', $readBookIds)
            ->whereHas('progressBaca', function ($query) use ($clusterMates) {
                $query->whereIn('user_id', $clusterMates);
            })
            ->withCount(['progressBaca' => function ($query) use ($clusterMates) {
                $query->whereIn('user_id', $clusterMates);
            }])
            ->orderBy('progress_baca_count', 'desc') // Buku yang paling banyak dibaca cluster mates
            ->orderBy('rating_rata_rata', 'desc')
            ->limit(8)
            ->get();

        return $recommendations;
    }

    /**
     * Buku populer untuk cluster tertentu
     */
    private function getPopularBooksForCluster($clusterId)
    {
        $clusters = Cache::get('user_clusters', []);
        $clusterUsers = array_keys(array_filter($clusters, fn($c) => $c === $clusterId));

        if (empty($clusterUsers)) {
            // Fallback ke buku populer umum
            return Buku::where('aktif', true)
                ->orderBy('total_pembaca', 'desc')
                ->limit(8)
                ->get();
        }

        return Buku::where('aktif', true)
            ->whereHas('progressBaca', function ($query) use ($clusterUsers) {
                $query->whereIn('user_id', $clusterUsers);
            })
            ->withCount(['progressBaca' => function ($query) use ($clusterUsers) {
                $query->whereIn('user_id', $clusterUsers);
            }])
            ->orderBy('progress_baca_count', 'desc')
            ->limit(8)
            ->get();
    }

    /**
     * Buku rating tinggi untuk cluster
     */
    private function getHighRatedBooksForCluster($clusterId)
    {
        $clusters = Cache::get('user_clusters', []);
        $clusterUsers = array_keys(array_filter($clusters, fn($c) => $c === $clusterId));

        if (empty($clusterUsers)) {
            return Buku::where('aktif', true)
                ->where('rating_rata_rata', '>=', 4.0)
                ->orderBy('rating_rata_rata', 'desc')
                ->limit(8)
                ->get();
        }

        return Buku::where('aktif', true)
            ->where('rating_rata_rata', '>=', 4.0)
            ->whereHas('rating', function ($query) use ($clusterUsers) {
                $query->whereIn('user_id', $clusterUsers)
                    ->where('rating', '>=', 4);
            })
            ->orderBy('rating_rata_rata', 'desc')
            ->limit(8)
            ->get();
    }

    /**
     * Fallback ke rekomendasi lama jika clustering belum dijalankan
     */
    private function fallbackRecommendation()
    {
        $user = auth()->user();

        // Menggunakan logika lama dari HomeController
        $kategoriDibaca = ProgressBaca::where('user_id', $user->id)
            ->join('buku', 'progress_baca.buku_id', '=', 'buku.id')
            ->select('buku.kategori_utama_id', 'buku.sub_kategori_id')
            ->groupBy('buku.kategori_utama_id', 'buku.sub_kategori_id')
            ->limit(3)
            ->get();

        $readBookIds = ProgressBaca::where('user_id', $user->id)->pluck('buku_id');

        $categoryRecommendations = collect();
        if ($kategoriDibaca->isNotEmpty()) {
            $categoryRecommendations = Buku::where('aktif', true)
                ->whereNotIn('id', $readBookIds)
                ->where(function ($query) use ($kategoriDibaca) {
                    foreach ($kategoriDibaca as $kategori) {
                        $query->orWhere(function ($q) use ($kategori) {
                            $q->where('kategori_utama_id', $kategori->kategori_utama_id)
                                ->where('sub_kategori_id', $kategori->sub_kategori_id);
                        });
                    }
                })
                ->orderBy('rating_rata_rata', 'desc')
                ->limit(8)
                ->get();
        }

        $popularBooks = Buku::where('aktif', true)
            ->whereNotIn('id', $readBookIds)
            ->orderBy('total_pembaca', 'desc')
            ->limit(8)
            ->get();

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

    /**
     * Jalankan clustering (untuk admin atau cron job)
     */
    public function runClustering()
    {
        try {
            $clusters = $this->kmeansService->clusterUsers();

            if ($clusters) {
                $profiles = $this->kmeansService->getClusterProfiles();

                return response()->json([
                    'success' => true,
                    'message' => 'Clustering berhasil dijalankan',
                    'clusters_count' => count(array_unique($clusters)),
                    'users_clustered' => count($clusters),
                    'profiles' => $profiles
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Data tidak cukup untuk clustering (minimal 3 user dengan aktivitas membaca)'
                ], 400);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Lihat status clustering (untuk admin)
     */
    public function clusteringStatus()
    {
        $clusters = Cache::get('user_clusters', []);
        $profiles = Cache::get('cluster_profiles', []);

        return response()->json([
            'clusters_exist' => !empty($clusters),
            'total_clustered_users' => count($clusters),
            'cluster_distribution' => array_count_values($clusters),
            'profiles' => $profiles,
            'last_updated' => Cache::get('clustering_last_updated', 'Never')
        ]);
    }
}
