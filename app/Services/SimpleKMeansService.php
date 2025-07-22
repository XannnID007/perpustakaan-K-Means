<?php

namespace App\Services;

use App\Models\User;
use App\Models\ProgressBaca;
use App\Models\Rating;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class SimpleKMeansService
{
     private $k = 3; // 3 cluster: Casual Reader, Serious Reader, Mixed Reader
     private $maxIterations = 50;

     /**
      * Jalankan K-Means clustering untuk semua user
      */
     public function clusterUsers()
     {
          // 1. Ambil data user untuk clustering
          $userData = $this->getUserFeatures();

          if (count($userData) < $this->k) {
               return false; // Tidak cukup data
          }

          // 2. Jalankan K-Means
          $clusters = $this->runKMeans($userData);

          // 3. Simpan hasil ke cache (simple storage)
          Cache::put('user_clusters', $clusters, now()->addDays(7));

          // 4. Buat profil setiap cluster
          $this->createClusterProfiles($clusters, $userData);

          return $clusters;
     }

     /**
      * Ambil fitur user untuk clustering (SIMPLE - hanya 3 fitur)
      */
     private function getUserFeatures()
     {
          $users = User::where('role', 'user')
               ->withCount(['progressBaca', 'rating'])
               ->having('progress_baca_count', '>', 0) // Minimal pernah baca
               ->get();

          $features = [];

          foreach ($users as $user) {
               // Hitung fitur sederhana
               $fiksiCount = $this->getFictionBooksRead($user->id);
               $nonFiksiCount = $this->getNonFictionBooksRead($user->id);
               $avgRating = $this->getAverageRating($user->id);

               $features[$user->id] = [
                    'user_id' => $user->id,
                    'fiction_ratio' => $fiksiCount / ($fiksiCount + $nonFiksiCount + 1), // 0-1
                    'total_books' => $fiksiCount + $nonFiksiCount, // Jumlah total
                    'avg_rating' => $avgRating ?: 3.0 // Default 3.0 jika belum rating
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
          $n = count($dataPoints);

          // 1. Inisialisasi centroids secara random
          $centroids = $this->initializeCentroids($dataPoints);

          $assignments = [];

          // 2. Iterasi K-Means
          for ($iter = 0; $iter < $this->maxIterations; $iter++) {
               $newAssignments = [];

               // Assign setiap data point ke cluster terdekat
               foreach ($dataPoints as $i => $point) {
                    $closestCluster = $this->findClosestCluster($point, $centroids);
                    $newAssignments[$point['user_id']] = $closestCluster;
               }

               // Update centroids
               $newCentroids = $this->updateCentroids($dataPoints, $newAssignments);

               // Cek konvergensi (simplified)
               if ($assignments === $newAssignments) {
                    break;
               }

               $assignments = $newAssignments;
               $centroids = $newCentroids;
          }

          return $assignments;
     }

     /**
      * Inisialisasi centroids secara random
      */
     private function initializeCentroids($data)
     {
          $centroids = [];

          // Ambil 3 data point random sebagai centroid awal
          $randomIndices = array_rand($data, $this->k);

          foreach ($randomIndices as $i => $index) {
               $centroids[$i] = [
                    'fiction_ratio' => $data[$index]['fiction_ratio'],
                    'total_books' => $data[$index]['total_books'],
                    'avg_rating' => $data[$index]['avg_rating']
               ];
          }

          return $centroids;
     }

     /**
      * Cari cluster terdekat untuk sebuah data point
      */
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

     /**
      * Hitung jarak Euclidean (disederhanakan)
      */
     private function calculateDistance($point1, $point2)
     {
          $sum = 0;
          $sum += pow($point1['fiction_ratio'] - $point2['fiction_ratio'], 2);
          $sum += pow(($point1['total_books'] - $point2['total_books']) / 100, 2); // Normalisasi
          $sum += pow($point1['avg_rating'] - $point2['avg_rating'], 2);

          return sqrt($sum);
     }

     /**
      * Update centroids berdasarkan assignment baru
      */
     private function updateCentroids($dataPoints, $assignments)
     {
          $newCentroids = [];

          // Untuk setiap cluster
          for ($clusterId = 0; $clusterId < $this->k; $clusterId++) {
               $clusterPoints = [];

               // Ambil semua point yang assigned ke cluster ini
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

               // Hitung rata-rata (centroid baru)
               if (count($clusterPoints) > 0) {
                    $newCentroids[$clusterId] = [
                         'fiction_ratio' => array_sum(array_column($clusterPoints, 'fiction_ratio')) / count($clusterPoints),
                         'total_books' => array_sum(array_column($clusterPoints, 'total_books')) / count($clusterPoints),
                         'avg_rating' => array_sum(array_column($clusterPoints, 'avg_rating')) / count($clusterPoints)
                    ];
               } else {
                    // Jika cluster kosong, gunakan centroid lama
                    $newCentroids[$clusterId] = [
                         'fiction_ratio' => 0.5,
                         'total_books' => 5,
                         'avg_rating' => 3.0
                    ];
               }
          }

          return $newCentroids;
     }

     /**
      * Buat profil untuk setiap cluster
      */
     private function createClusterProfiles($clusters, $userData)
     {
          $profiles = [];

          for ($clusterId = 0; $clusterId < $this->k; $clusterId++) {
               $clusterUsers = array_keys(array_filter($clusters, fn($c) => $c === $clusterId));

               if (count($clusterUsers) === 0) continue;

               // Hitung karakteristik cluster
               $fictionRatios = [];
               $totalBooks = [];
               $avgRatings = [];

               foreach ($clusterUsers as $userId) {
                    if (isset($userData[$userId])) {
                         $fictionRatios[] = $userData[$userId]['fiction_ratio'];
                         $totalBooks[] = $userData[$userId]['total_books'];
                         $avgRatings[] = $userData[$userId]['avg_rating'];
                    }
               }

               $profiles[$clusterId] = [
                    'name' => $this->getClusterName($clusterId, $fictionRatios, $totalBooks),
                    'user_count' => count($clusterUsers),
                    'avg_fiction_ratio' => array_sum($fictionRatios) / count($fictionRatios),
                    'avg_total_books' => array_sum($totalBooks) / count($totalBooks),
                    'avg_rating' => array_sum($avgRatings) / count($avgRatings),
                    'description' => $this->getClusterDescription($clusterId, $fictionRatios, $totalBooks)
               ];
          }

          Cache::put('cluster_profiles', $profiles, now()->addDays(7));

          return $profiles;
     }

     /**
      * Dapatkan nama cluster berdasarkan karakteristik
      */
     private function getClusterName($clusterId, $fictionRatios, $totalBooks)
     {
          $avgFiction = array_sum($fictionRatios) / count($fictionRatios);
          $avgTotal = array_sum($totalBooks) / count($totalBooks);

          if ($avgFiction > 0.7) {
               return $avgTotal > 10 ? 'Pecinta Fiksi Aktif' : 'Pembaca Fiksi Kasual';
          } elseif ($avgFiction < 0.3) {
               return 'Pencari Pengetahuan'; // Non-fiksi dominan
          } else {
               return 'Pembaca Seimbang'; // Mixed
          }
     }

     /**
      * Dapatkan deskripsi cluster
      */
     private function getClusterDescription($clusterId, $fictionRatios, $totalBooks)
     {
          $avgFiction = array_sum($fictionRatios) / count($fictionRatios);
          $avgTotal = array_sum($totalBooks) / count($totalBooks);

          if ($avgFiction > 0.7) {
               return 'Menyukai cerita imajinatif, novel, dan karya sastra';
          } elseif ($avgFiction < 0.3) {
               return 'Fokus pada pembelajaran, pengembangan diri, dan fakta';
          } else {
               return 'Menikmati berbagai jenis bacaan, fleksibel dalam memilih genre';
          }
     }

     // Helper methods untuk mendapatkan data
     private function getFictionBooksRead($userId)
     {
          return ProgressBaca::where('user_id', $userId)
               ->join('buku', 'progress_baca.buku_id', '=', 'buku.id')
               ->join('kategori_utama', 'buku.kategori_utama_id', '=', 'kategori_utama.id')
               ->where('kategori_utama.nama', 'LIKE', '%fiksi%')
               ->count();
     }

     private function getNonFictionBooksRead($userId)
     {
          return ProgressBaca::where('user_id', $userId)
               ->join('buku', 'progress_baca.buku_id', '=', 'buku.id')
               ->join('kategori_utama', 'buku.kategori_utama_id', '=', 'kategori_utama.id')
               ->where('kategori_utama.nama', 'NOT LIKE', '%fiksi%')
               ->count();
     }

     private function getAverageRating($userId)
     {
          return Rating::where('user_id', $userId)->avg('rating');
     }

     /**
      * Dapatkan cluster user tertentu
      */
     public function getUserCluster($userId)
     {
          $clusters = Cache::get('user_clusters', []);
          return $clusters[$userId] ?? null;
     }

     /**
      * Dapatkan profil semua cluster
      */
     public function getClusterProfiles()
     {
          return Cache::get('cluster_profiles', []);
     }
}
