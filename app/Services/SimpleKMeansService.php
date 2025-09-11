<?php

namespace App\Services;

use App\Models\User;
use App\Models\ProgressBaca;
use App\Models\Rating;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class SimpleKMeansService
{
     private $k = 3;
     private $maxIterations = 50;

     public function clusterUsers()
     {
          $userData = $this->getUserFeatures();

          if (count($userData) < $this->k) {
               return false;
          }

          $clusters = $this->runKMeans($userData);

          Cache::put('user_clusters', $clusters, now()->addDays(7));
          Cache::put('clustering_last_updated', now()->toDateTimeString(), now()->addDays(7));

          $this->createClusterProfiles($clusters, $userData);

          return $clusters;
     }

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

     private function runKMeans($data)
     {
          $dataPoints = array_values($data);
          $centroids = $this->initializeCentroids($dataPoints);
          $assignments = [];

          for ($iter = 0; $iter < $this->maxIterations; $iter++) {
               $newAssignments = [];
               foreach ($dataPoints as $point) {
                    $closestCluster = $this->findClosestCluster($point, $centroids);
                    $newAssignments[$point['user_id']] = $closestCluster;
               }

               $newCentroids = $this->updateCentroids($dataPoints, $newAssignments);

               if ($assignments === $newAssignments) break;
               $assignments = $newAssignments;
               $centroids = $newCentroids;
          }

          return $assignments;
     }

     private function initializeCentroids($data)
     {
          $centroids = [];
          $randomIndices = array_rand($data, $this->k);

          foreach ((array) $randomIndices as $i => $index) {
               $centroids[$i] = [
                    'fiction_ratio' => $data[$index]['fiction_ratio'],
                    'total_books' => $data[$index]['total_books'],
                    'avg_rating' => $data[$index]['avg_rating']
               ];
          }

          return $centroids;
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
          $sum += pow(($point1['total_books'] - $point2['total_books']) / 100, 2);
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
                    $newCentroids[$clusterId] = ['fiction_ratio' => 0.5, 'total_books' => 5, 'avg_rating' => 3.0];
               }
          }
          return $newCentroids;
     }

     private function createClusterProfiles($clusters, $userData)
     {
          $profiles = [];
          $clusterData = [];

          // Mengelompokkan data pengguna berdasarkan cluster
          foreach ($clusters as $userId => $clusterId) {
               if (isset($userData[$userId])) {
                    $clusterData[$clusterId][] = $userData[$userId];
               }
          }

          // Membuat profil untuk setiap cluster
          foreach ($clusterData as $clusterId => $users) {
               if (empty($users)) continue;

               $fictionRatios = array_column($users, 'fiction_ratio');
               $totalBooks = array_column($users, 'total_books');
               $avgRatings = array_column($users, 'avg_rating');

               $profiles[$clusterId] = [
                    'name' => $this->getClusterName($fictionRatios),
                    'user_count' => count($users),
                    'avg_fiction_ratio' => array_sum($fictionRatios) / count($fictionRatios),
                    'avg_total_books' => array_sum($totalBooks) / count($totalBooks),
                    'avg_rating' => array_sum($avgRatings) / count($avgRatings),
                    'description' => $this->getClusterDescription($fictionRatios)
               ];
          }

          Cache::put('cluster_profiles', $profiles, now()->addDays(7));
          return $profiles;
     }

     private function getClusterName($fictionRatios)
     {
          $avgFiction = array_sum($fictionRatios) / count($fictionRatios);
          if ($avgFiction > 0.7) return 'Pembaca Fiksi';
          if ($avgFiction < 0.3) return 'Pembaca Non-Fiksi';
          return 'Pembaca Seimbang';
     }

     private function getClusterDescription($fictionRatios)
     {
          $avgFiction = array_sum($fictionRatios) / count($fictionRatios);
          if ($avgFiction > 0.7) return 'Cenderung menikmati cerita imajinatif, novel, dan karya sastra.';
          if ($avgFiction < 0.3) return 'Lebih fokus pada pembelajaran, pengembangan diri, dan buku berdasarkan fakta.';
          return 'Menikmati berbagai jenis bacaan, baik fiksi maupun non-fiksi secara seimbang.';
     }

     // === PERBAIKAN LOGIKA PENCARIAN BUKU ===
     private function getFictionBooksRead($userId)
     {
          return ProgressBaca::where('user_id', $userId)
               ->join('buku', 'progress_baca.buku_id', '=', 'buku.id')
               ->join('kategori_utama', 'buku.kategori_utama_id', '=', 'kategori_utama.id')
               ->where('kategori_utama.nama', 'Fiksi') // Pencarian nama yang eksak
               ->count();
     }

     private function getNonFictionBooksRead($userId)
     {
          return ProgressBaca::where('user_id', $userId)
               ->join('buku', 'progress_baca.buku_id', '=', 'buku.id')
               ->join('kategori_utama', 'buku.kategori_utama_id', '=', 'kategori_utama.id')
               ->where('kategori_utama.nama', '!=', 'Fiksi') // Semua yang bukan Fiksi
               ->count();
     }
     // === AKHIR PERBAIKAN ===

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
