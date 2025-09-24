<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\SimpleKMeansService;
use Illuminate\Support\Facades\Cache;

class ClusteringTestController extends Controller
{
    public function index()
    {
        // 1. Jalankan clustering menggunakan Service Anda apa adanya.
        // Ini akan menjalankan proses dan menyimpan hasilnya di Cache.
        $kmeansService = new SimpleKMeansService();
        $userAssignments = $kmeansService->clusterUsers();

        // Jika data tidak cukup, service akan mengembalikan false.
        if ($userAssignments === false) {
            return back()->with('error', 'Data tidak cukup untuk evaluasi. Butuh minimal 3 pengguna dengan riwayat membaca.');
        }

        // 2. Ambil kembali data fitur pengguna yang dibuat oleh service.
        // Kita akan menggunakan ReflectionClass untuk mengakses metode private `getUserFeatures`.
        // Ini adalah cara "bersih" untuk mendapatkan data yang sama persis seperti yang digunakan service.
        $reflection = new \ReflectionClass($kmeansService);
        $getUserFeaturesMethod = $reflection->getMethod('getUserFeatures');
        $getUserFeaturesMethod->setAccessible(true);
        $userData = $getUserFeaturesMethod->invoke($kmeansService);

        $getCentroidsMethod = $reflection->getMethod('getFixedInitialCentroids');
        $getCentroidsMethod->setAccessible(true);
        $initialCentroids = $getCentroidsMethod->invoke($kmeansService);

        // 3. Rekonstruksi (susun ulang) data cluster dan hitung centroids akhir.
        $clusters = [];
        $totalData = count($userData);
        for ($i = 0; $i < 3; $i++) {
            $clusters[$i] = [];
        }

        foreach ($userAssignments as $userId => $clusterId) {
            if (isset($userData[$userId])) {
                // Kumpulkan semua titik data yang termasuk dalam setiap cluster
                $clusters[$clusterId][] = $userData[$userId];
            }
        }

        $finalCentroids = $this->recalculateCentroids($clusters, $initialCentroids);

        // 4. Hitung Davies-Bouldin Index dari data yang sudah disusun ulang.
        $dbi = $this->calculateDaviesBouldinIndex($clusters, $finalCentroids);

        // 5. Tampilkan hasil.
        return view('admin.clustering_test', [
            'daviesBouldinIndex' => $dbi,
            'totalData' => $totalData,
            'nClusters' => 3
        ]);
    }

    /**
     * Menghitung ulang posisi centroid berdasarkan rata-rata titik di dalam sebuah cluster.
     */
    private function recalculateCentroids(array $clusters, array $initialCentroids): array
    {
        $centroids = [];
        foreach ($clusters as $clusterId => $clusterPoints) {
            if (empty($clusterPoints)) {
                // Jika ada cluster yang kosong, gunakan posisi centroid awal sebagai fallback
                $centroids[$clusterId] = $initialCentroids[$clusterId];
                continue;
            }
            $numPoints = count($clusterPoints);
            $centroids[$clusterId] = [
                'fiction_ratio' => array_sum(array_column($clusterPoints, 'fiction_ratio')) / $numPoints,
                'total_books' => array_sum(array_column($clusterPoints, 'total_books')) / $numPoints,
                'avg_rating' => array_sum(array_column($clusterPoints, 'avg_rating')) / $numPoints,
            ];
        }
        return $centroids;
    }

    /**
     * Menghitung jarak Euclidean antara dua titik (data pengguna dan centroid).
     */
    private function euclideanDistance(array $point1, array $point2): float
    {
        $sum = pow($point1['fiction_ratio'] - $point2['fiction_ratio'], 2) +
            pow(($point1['total_books'] - $point2['total_books']) / 100, 2) + // Gunakan normalisasi yang sama
            pow($point1['avg_rating'] - $point2['avg_rating'], 2);
        return sqrt($sum);
    }

    /**
     * Menghitung Davies-Bouldin Index.
     */
    private function calculateDaviesBouldinIndex(array $clusters, array $centroids): float
    {
        $numClusters = count($centroids);
        if ($numClusters <= 1) return 0.0;

        // Hitung S_i (rata-rata jarak intra-cluster)
        $avgIntraClusterDistances = [];
        foreach ($clusters as $i => $cluster) {
            $sumDistance = 0;
            if (empty($cluster)) {
                $avgIntraClusterDistances[$i] = 0;
                continue;
            }
            foreach ($cluster as $point) {
                $sumDistance += $this->euclideanDistance($point, $centroids[$i]);
            }
            $avgIntraClusterDistances[$i] = $sumDistance / count($cluster);
        }

        $totalDBIScore = 0;
        for ($i = 0; $i < $numClusters; $i++) {
            $maxRatio = 0;
            for ($j = 0; $j < $numClusters; $j++) {
                if ($i === $j) continue;

                $centroidDist = $this->euclideanDistance($centroids[$i], $centroids[$j]);
                if ($centroidDist == 0) continue;

                $ratio = ($avgIntraClusterDistances[$i] + $avgIntraClusterDistances[$j]) / $centroidDist;
                if ($ratio > $maxRatio) $maxRatio = $ratio;
            }
            $totalDBIScore += $maxRatio;
        }

        return $totalDBIScore / $numClusters;
    }
}
