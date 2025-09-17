<?php

namespace App\Services;

use App\Models\User;
use App\Models\ProgressBaca;
use App\Models\Rating;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class SimpleKMeansService
{
     private $k = 3; // Tetap 3 cluster
     private $maxIterations = 100; // Tingkatkan iterasi untuk memastikan konvergensi

     /**
      * Jalankan K-Means clustering untuk semua user
      */
     public function clusterUsers()
     {
          $userData = $this->getUserFeatures();

          if (count($userData) < $this->k) {
               return false;
          }

          // Perubahan: Langsung panggil runKMeans tanpa inisialisasi acak di sini
          $clusters = $this->runKMeans($userData);

          Cache::put('user_clusters', $clusters, now()->addDays(7));
          Cache::put('clustering_last_updated', now()->toDateTimeString(), now()->addDays(7));

          $this->createClusterProfiles($clusters, $userData);

          return $clusters;
     }

     /**
      * Ambil fitur user untuk clustering
      */
     private function getUserFeatures()
     {
          $users = User::where('role', 'user')
               ->withCount(['progressBaca', 'rating'])
               ->having('progress_baca_count', '>', 0)
               ->get();

          $features = [];
          foreach ($users as $user) {
               $fiksiCount = $this->getFictionBooksRead($user->id);
               $nonFiksiCount = $this->getNonFictionBooksRead($user->id);
               $avgRating = $this->getAverageRating($user->id);
               $totalBooks = $fiksiCount + $nonFiksiCount;

               $features[$user->id] = [
                    'user_id' => $user->id,
                    'fiction_ratio' => $totalBooks > 0 ? $fiksiCount / $totalBooks : 0,
                    'total_books' => $totalBooks,
                    'avg_rating' => $avgRating ?: 3.0
               ];
          }
          return $features;
     }

     /**
      * Algoritma K-Means sederhana
      */
     private function runKMeans($data)
     {
          $dataPoints = array_values($data);

          // === PERUBAHAN UTAMA: Inisialisasi Centroid Tetap (Tidak Acak) ===
          $centroids = $this->getFixedInitialCentroids();
          // === AKHIR PERUBAHAN UTAMA ===

          $assignments = [];

          for ($iter = 0; $iter < $this->maxIterations; $iter++) {
               $newAssignments = [];
               foreach ($dataPoints as $point) {
                    $closestCluster = $this->findClosestCluster($point, $centroids);
                    $newAssignments[$point['user_id']] = $closestCluster;
               }

               $newCentroids = $this->updateCentroids($dataPoints, $newAssignments);

               // Cek konvergensi
               if ($this->hasConverged($centroids, $newCentroids)) {
                    break;
               }

               $assignments = $newAssignments;
               $centroids = $newCentroids;
          }

          return $assignments;
     }

     /**
      * PERUBAHAN BARU: Menentukan titik awal centroid secara manual.
      * Angka-angka ini dipilih untuk mewakili setiap profil pembaca secara ideal.
      */
     private function getFixedInitialCentroids()
     {
          return [
               // Centroid 0: Ideal untuk "Pembaca Fiksi"
               // Rasio fiksi sangat tinggi (0.8 = 80%), total buku rata-rata (10), rating tinggi (4.0)
               0 => ['fiction_ratio' => 0.8, 'total_books' => 10, 'avg_rating' => 4.0],

               // Centroid 1: Ideal untuk "Pembaca Non-Fiksi"
               // Rasio fiksi sangat rendah (0.1 = 10%), total buku rata-rata (10), rating tinggi (4.0)
               1 => ['fiction_ratio' => 0.1, 'total_books' => 10, 'avg_rating' => 4.0],

               // Centroid 2: Ideal untuk "Pembaca Seimbang"
               // Rasio fiksi seimbang (0.5 = 50%), total buku rata-rata (10), rating sedang (3.5)
               2 => ['fiction_ratio' => 0.5, 'total_books' => 10, 'avg_rating' => 3.5],
          ];
     }

     private function findClosestCluster($point, $centroids)
     {
          $minDistance = PHP_FLOAT_MAX;
          $closestCluster = 0;

          foreach ($centroids as $clusterId => $centroid) {
               $distance = $this->calculateDistance($point, $centroid);
               if ($distance < $minDistance) {
                    $minDistance = $distance;
                    $closestCluster = $clusterId;
               }
          }
          return $closestCluster;
     }

     private function calculateDistance($point1, $point2)
     {
          $sum = 0;
          $sum += pow($point1['fiction_ratio'] - $point2['fiction_ratio'], 2);
          $sum += pow(($point1['total_books'] - $point2['total_books']) / 100, 2); // Normalisasi
          $sum += pow($point1['avg_rating'] - $point2['avg_rating'], 2);
          return sqrt($sum);
     }

     private function updateCentroids($dataPoints, $assignments)
     {
          $newCentroids = [];
          for ($clusterId = 0; $clusterId < $this->k; $clusterId++) {
               $clusterPoints = [];
               foreach ($assignments as $userId => $assignedCluster) {
                    if ($assignedCluster === $clusterId) {
                         foreach ($dataPoints as $point) {
                              if ($point['user_id'] == $userId) {
                                   $clusterPoints[] = $point;
                                   break;
                              }
                         }
                    }
               }
               if (count($clusterPoints) > 0) {
                    $newCentroids[$clusterId] = [
                         'fiction_ratio' => array_sum(array_column($clusterPoints, 'fiction_ratio')) / count($clusterPoints),
                         'total_books' => array_sum(array_column($clusterPoints, 'total_books')) / count($clusterPoints),
                         'avg_rating' => array_sum(array_column($clusterPoints, 'avg_rating')) / count($clusterPoints)
                    ];
               } else {
                    // Jika cluster kosong, kembalikan ke posisi ideal awalnya
                    $newCentroids = $this->getFixedInitialCentroids();
               }
          }
          return $newCentroids;
     }

     // Fungsi baru untuk cek konvergensi yang lebih baik
     private function hasConverged($oldCentroids, $newCentroids)
     {
          $threshold = 0.0001;
          foreach ($oldCentroids as $i => $centroid) {
               $distance = $this->calculateDistance($centroid, $newCentroids[$i]);
               if ($distance > $threshold) {
                    return false;
               }
          }
          return true;
     }

     private function createClusterProfiles($clusters, $userData)
     {
          $profiles = [];
          $clusterData = [];
          foreach ($clusters as $userId => $clusterId) {
               if (isset($userData[$userId])) {
                    $clusterData[$clusterId][] = $userData[$userId];
               }
          }
          foreach ($clusterData as $clusterId => $users) {
               if (empty($users)) continue;
               $fictionRatios = array_column($users, 'fiction_ratio');
               $profiles[$clusterId] = [
                    'name' => $this->getClusterName($fictionRatios),
                    'user_count' => count($users),
                    'avg_fiction_ratio' => array_sum($fictionRatios) / count($fictionRatios),
                    'avg_total_books' => array_sum(array_column($users, 'total_books')) / count($users),
                    'avg_rating' => array_sum(array_column($users, 'avg_rating')) / count($users),
                    'description' => $this->getClusterDescription($fictionRatios)
               ];
          }
          Cache::put('cluster_profiles', $profiles, now()->addDays(7));
     }

     private function getClusterName($fictionRatios)
     {
          $avgFiction = array_sum($fictionRatios) / count($fictionRatios);
          if ($avgFiction > 0.65) return 'Pembaca Fiksi'; // Sedikit melonggarkan ambang batas
          if ($avgFiction < 0.35) return 'Pembaca Non-Fiksi'; // Sedikit melonggarkan ambang batas
          return 'Pembaca Seimbang';
     }

     private function getClusterDescription($fictionRatios)
     {
          $avgFiction = array_sum($fictionRatios) / count($fictionRatios);
          if ($avgFiction > 0.65) return 'Cenderung menikmati cerita imajinatif, novel, dan karya sastra.';
          if ($avgFiction < 0.35) return 'Lebih fokus pada pembelajaran, pengembangan diri, dan buku berdasarkan fakta.';
          return 'Menikmati berbagai jenis bacaan, baik fiksi maupun non-fiksi secara seimbang.';
     }

     private function getFictionBooksRead($userId)
     {
          return ProgressBaca::where('user_id', $userId)
               ->join('buku', 'progress_baca.buku_id', '=', 'buku.id')
               ->join('kategori_utama', 'buku.kategori_utama_id', '=', 'kategori_utama.id')
               ->where('kategori_utama.nama', 'Fiksi')
               ->count();
     }

     private function getNonFictionBooksRead($userId)
     {
          return ProgressBaca::where('user_id', $userId)
               ->join('buku', 'progress_baca.buku_id', '=', 'buku.id')
               ->join('kategori_utama', 'buku.kategori_utama_id', '=', 'kategori_utama.id')
               ->where('kategori_utama.nama', '!=', 'Fiksi')
               ->count();
     }

     private function getAverageRating($userId)
     {
          return Rating::where('user_id', $userId)->avg('rating');
     }

     public function getUserCluster($userId)
     {
          $clusters = Cache::get('user_clusters', []);
          return $clusters[$userId] ?? null;
     }

     public function getClusterProfiles()
     {
          return Cache::get('cluster_profiles', []);
     }
}
