<?php

namespace App\Services;

use App\Models\User;
use App\Models\ProgressBaca;
use App\Models\Rating;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class SimpleKMeansService
{
     private $k = 5; // UPGRADE: 5 cluster untuk lebih beragam
     private $maxIterations = 100; // Lebih banyak iterasi untuk konvergensi yang lebih baik

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

          // 3. Simpan hasil ke cache
          Cache::put('user_clusters', $clusters, now()->addDays(7));

          // 4. Buat profil setiap cluster
          $this->createClusterProfiles($clusters, $userData);

          // 5. Update cache timestamp
          Cache::put('clustering_last_updated', now()->format('Y-m-d H:i:s'), now()->addDays(7));

          return $clusters;
     }

     /**
      * IMPROVED: Ambil fitur user yang lebih comprehensive
      */
     private function getUserFeatures()
     {
          $users = User::where('role', 'user')
               ->withCount(['progressBaca', 'rating'])
               ->having('progress_baca_count', '>', 0)
               ->get();

          $features = [];

          foreach ($users as $user) {
               // Hitung berbagai metrik
               $fiksiCount = $this->getFictionBooksRead($user->id);
               $nonFiksiCount = $this->getNonFictionBooksRead($user->id);
               $totalBooks = $fiksiCount + $nonFiksiCount;
               $avgRating = $this->getAverageRating($user->id);

               // FITUR BARU: Aktivitas membaca
               $readingActivity = $this->getReadingActivity($user->id);
               $completionRate = $this->getCompletionRate($user->id);

               // Avoid division by zero
               if ($totalBooks == 0) continue;

               $features[$user->id] = [
                    'user_id' => $user->id,
                    // Preferensi genre (0 = non-fiksi murni, 1 = fiksi murni)
                    'fiction_preference' => $fiksiCount / $totalBooks,
                    // Total intensitas membaca (normalized)
                    'reading_intensity' => min($totalBooks / 20, 1.0), // Cap at 20 books = 1.0
                    // Standar kualitas rating
                    'rating_standard' => ($avgRating ?: 3.0) / 5.0, // Normalize ke 0-1
                    // Konsistensi membaca (berapa sering baca per bulan)
                    'reading_consistency' => min($readingActivity / 10, 1.0), // Cap at 10 = 1.0
                    // Tingkat penyelesaian buku
                    'completion_rate' => $completionRate,
                    // Metadata untuk profiling
                    'total_books' => $totalBooks,
                    'avg_rating' => $avgRating ?: 3.0,
                    'fiction_count' => $fiksiCount,
                    'non_fiction_count' => $nonFiksiCount
               ];
          }

          return $features;
     }

     /**
      * IMPROVED: Algoritma K-Means dengan inisialisasi yang lebih baik
      */
     private function runKMeans($data)
     {
          $dataPoints = array_values($data);
          $n = count($dataPoints);

          // 1. Inisialisasi centroids dengan K-Means++
          $centroids = $this->initializeCentroidsKMeansPlusPlus($dataPoints);

          $assignments = [];
          $prevCost = PHP_FLOAT_MAX;

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

               // Hitung cost untuk konvergensi
               $currentCost = $this->calculateTotalCost($dataPoints, $newAssignments, $newCentroids);

               // Cek konvergensi berdasarkan cost
               if (abs($prevCost - $currentCost) < 0.001) {
                    break;
               }

               $assignments = $newAssignments;
               $centroids = $newCentroids;
               $prevCost = $currentCost;
          }

          return $assignments;
     }

     /**
      * NEW: K-Means++ initialization untuk hasil yang lebih baik
      */
     private function initializeCentroidsKMeansPlusPlus($data)
     {
          $centroids = [];
          $n = count($data);

          // Pilih centroid pertama secara random
          $firstIndex = array_rand($data);
          $centroids[0] = $this->extractFeatureVector($data[$firstIndex]);

          // Pilih centroids selanjutnya berdasarkan jarak terjauh
          for ($c = 1; $c < $this->k; $c++) {
               $distances = [];
               $totalDistance = 0;

               foreach ($data as $i => $point) {
                    $minDist = PHP_FLOAT_MAX;
                    foreach ($centroids as $centroid) {
                         $dist = $this->calculateDistance($this->extractFeatureVector($point), $centroid);
                         $minDist = min($minDist, $dist);
                    }
                    $distances[$i] = $minDist * $minDist; // Square distance
                    $totalDistance += $distances[$i];
               }

               // Pilih berdasarkan probabilitas proporsional dengan jarak
               $randomValue = mt_rand() / mt_getrandmax() * $totalDistance;
               $cumSum = 0;

               foreach ($distances as $i => $dist) {
                    $cumSum += $dist;
                    if ($cumSum >= $randomValue) {
                         $centroids[$c] = $this->extractFeatureVector($data[$i]);
                         break;
                    }
               }
          }

          return $centroids;
     }

     /**
      * IMPROVED: Extract feature vector untuk perhitungan
      */
     private function extractFeatureVector($point)
     {
          return [
               'fiction_preference' => $point['fiction_preference'],
               'reading_intensity' => $point['reading_intensity'],
               'rating_standard' => $point['rating_standard'],
               'reading_consistency' => $point['reading_consistency'],
               'completion_rate' => $point['completion_rate']
          ];
     }

     /**
      * IMPROVED: Perhitungan jarak dengan weighted features
      */
     private function calculateDistance($point1, $point2)
     {
          $weights = [
               'fiction_preference' => 2.0,  // Weight tinggi untuk preferensi genre
               'reading_intensity' => 1.5,   // Weight sedang untuk intensitas
               'rating_standard' => 1.0,     // Weight normal untuk rating
               'reading_consistency' => 1.0, // Weight normal untuk konsistensi
               'completion_rate' => 1.2      // Weight sedikit tinggi untuk completion
          ];

          $sum = 0;
          foreach ($point1 as $feature => $value1) {
               if (isset($point2[$feature]) && isset($weights[$feature])) {
                    $diff = $value1 - $point2[$feature];
                    $sum += $weights[$feature] * ($diff * $diff);
               }
          }

          return sqrt($sum);
     }

     /**
      * NEW: Hitung total cost untuk konvergensi
      */
     private function calculateTotalCost($dataPoints, $assignments, $centroids)
     {
          $totalCost = 0;

          foreach ($dataPoints as $point) {
               $clusterId = $assignments[$point['user_id']];
               $centroid = $centroids[$clusterId];
               $distance = $this->calculateDistance($this->extractFeatureVector($point), $centroid);
               $totalCost += $distance * $distance;
          }

          return $totalCost;
     }

     /**
      * IMPROVED: Update centroids dengan handling cluster kosong
      */
     private function updateCentroids($dataPoints, $assignments)
     {
          $newCentroids = [];

          for ($clusterId = 0; $clusterId < $this->k; $clusterId++) {
               $clusterPoints = [];

               // Ambil semua point dalam cluster ini
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
                    // Hitung rata-rata untuk setiap fitur
                    $newCentroids[$clusterId] = [
                         'fiction_preference' => array_sum(array_column($clusterPoints, 'fiction_preference')) / count($clusterPoints),
                         'reading_intensity' => array_sum(array_column($clusterPoints, 'reading_intensity')) / count($clusterPoints),
                         'rating_standard' => array_sum(array_column($clusterPoints, 'rating_standard')) / count($clusterPoints),
                         'reading_consistency' => array_sum(array_column($clusterPoints, 'reading_consistency')) / count($clusterPoints),
                         'completion_rate' => array_sum(array_column($clusterPoints, 'completion_rate')) / count($clusterPoints)
                    ];
               } else {
                    // Cluster kosong - assign centroid random
                    $randomPoint = $dataPoints[array_rand($dataPoints)];
                    $newCentroids[$clusterId] = $this->extractFeatureVector($randomPoint);
               }
          }

          return $newCentroids;
     }

     /**
      * IMPROVED: Buat profil cluster yang lebih detail dan seimbang
      */
     private function createClusterProfiles($clusters, $userData)
     {
          $profiles = [];

          for ($clusterId = 0; $clusterId < $this->k; $clusterId++) {
               $clusterUsers = array_keys(array_filter($clusters, fn($c) => $c === $clusterId));

               if (count($clusterUsers) === 0) continue;

               // Kumpulkan data cluster
               $clusterData = [];
               foreach ($clusterUsers as $userId) {
                    if (isset($userData[$userId])) {
                         $clusterData[] = $userData[$userId];
                    }
               }

               if (empty($clusterData)) continue;

               // Hitung statistik cluster
               $avgFictionPref = array_sum(array_column($clusterData, 'fiction_preference')) / count($clusterData);
               $avgIntensity = array_sum(array_column($clusterData, 'reading_intensity')) / count($clusterData);
               $avgRating = array_sum(array_column($clusterData, 'avg_rating')) / count($clusterData);
               $avgCompletion = array_sum(array_column($clusterData, 'completion_rate')) / count($clusterData);
               $avgTotalBooks = array_sum(array_column($clusterData, 'total_books')) / count($clusterData);

               // IMPROVED: Tentukan nama dan deskripsi cluster berdasarkan karakteristik
               $clusterInfo = $this->determineClusterType($avgFictionPref, $avgIntensity, $avgCompletion, $avgTotalBooks);

               $profiles[$clusterId] = [
                    'name' => $clusterInfo['name'],
                    'description' => $clusterInfo['description'],
                    'icon' => $clusterInfo['icon'],
                    'color' => $clusterInfo['color'],
                    'user_count' => count($clusterUsers),
                    'avg_fiction_preference' => $avgFictionPref,
                    'avg_reading_intensity' => $avgIntensity,
                    'avg_total_books' => $avgTotalBooks,
                    'avg_rating' => $avgRating,
                    'avg_completion_rate' => $avgCompletion,
                    'characteristics' => $clusterInfo['characteristics']
               ];
          }

          Cache::put('cluster_profiles', $profiles, now()->addDays(7));
          return $profiles;
     }

     /**
      * NEW: Tentukan tipe cluster berdasarkan karakteristik
      */
     private function determineClusterType($fictionPref, $intensity, $completion, $totalBooks)
     {
          // Pecinta Fiksi Aktif
          if ($fictionPref > 0.7 && $intensity > 0.6) {
               return [
                    'name' => '📚 Pecinta Fiksi Aktif',
                    'description' => 'Pembaca setia novel, cerita, dan karya sastra dengan intensitas tinggi',
                    'icon' => '📚',
                    'color' => 'bg-purple-500',
                    'characteristics' => ['Novel & Cerita', 'Intensitas Tinggi', 'Sastra & Drama']
               ];
          }

          // Pencinta Pengetahuan (Non-Fiksi Dominan)
          if ($fictionPref < 0.3 && $intensity > 0.4) {
               return [
                    'name' => '🎓 Pencinta Pengetahuan',
                    'description' => 'Fokus pada pembelajaran, pengembangan diri, dan buku-buku faktual',
                    'icon' => '🎓',
                    'color' => 'bg-blue-500',
                    'characteristics' => ['Self-Development', 'Ilmu Pengetahuan', 'Biografi & Sejarah']
               ];
          }

          // Pembaca Seimbang Aktif
          if ($fictionPref >= 0.3 && $fictionPref <= 0.7 && $intensity > 0.5) {
               return [
                    'name' => '⚖️ Pembaca Seimbang Aktif',
                    'description' => 'Menikmati berbagai genre dengan keseimbangan fiksi dan non-fiksi',
                    'icon' => '⚖️',
                    'color' => 'bg-green-500',
                    'characteristics' => ['Multi-Genre', 'Fleksibel', 'Eksplorasi Beragam']
               ];
          }

          // Pembaca Kasual
          if ($intensity <= 0.4 && $totalBooks < 10) {
               $genreType = $fictionPref > 0.5 ? 'Fiksi' : 'Non-Fiksi';
               return [
                    'name' => "🌱 Pembaca Kasual ($genreType)",
                    'description' => "Membaca dengan santai, lebih suka $genreType dengan intensitas rendah",
                    'icon' => '🌱',
                    'color' => 'bg-yellow-500',
                    'characteristics' => ['Santai', $genreType, 'Intensitas Rendah']
               ];
          }

          // Default - Pembaca Pemula
          return [
               'name' => '🌟 Pembaca Pemula',
               'description' => 'Masih mengeksplorasi preferensi membaca dan membangun kebiasaan',
               'icon' => '🌟',
               'color' => 'bg-indigo-500',
               'characteristics' => ['Eksplorasi', 'Membangun Kebiasaan', 'Beragam']
          ];
     }

     // Helper methods yang sudah ada...
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
      * NEW: Hitung aktivitas membaca (berapa buku per bulan)
      */
     private function getReadingActivity($userId)
     {
          $monthsActive = ProgressBaca::where('user_id', $userId)
               ->selectRaw('COUNT(DISTINCT DATE_FORMAT(created_at, "%Y-%m")) as months')
               ->value('months') ?: 1;

          $totalBooks = ProgressBaca::where('user_id', $userId)->count();

          return $totalBooks / $monthsActive; // Buku per bulan
     }

     /**
      * NEW: Hitung tingkat penyelesaian buku
      */
     private function getCompletionRate($userId)
     {
          $totalStarted = ProgressBaca::where('user_id', $userId)->count();
          $totalCompleted = ProgressBaca::where('user_id', $userId)->where('selesai', true)->count();

          return $totalStarted > 0 ? $totalCompleted / $totalStarted : 0;
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

     private function findClosestCluster($point, $centroids)
     {
          $minDistance = PHP_FLOAT_MAX;
          $closestCluster = 0;
          $featureVector = $this->extractFeatureVector($point);

          foreach ($centroids as $clusterId => $centroid) {
               $distance = $this->calculateDistance($featureVector, $centroid);

               if ($distance < $minDistance) {
                    $minDistance = $distance;
                    $closestCluster = $clusterId;
               }
          }

          return $closestCluster;
     }
}
