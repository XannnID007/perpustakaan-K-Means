<?php

namespace App\Http\Controllers\Api;

use App\Models\Buku;
use App\Models\Rating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class RatingController extends Controller
{
     public function store(Request $request)
     {
          $request->validate([
               'buku_id' => 'required|exists:buku,id',
               'rating' => 'required|integer|min:1|max:5',
               'ulasan' => 'nullable|string|max:500',
          ], [
               'buku_id.required' => 'ID buku harus diisi',
               'buku_id.exists' => 'Buku tidak ditemukan',
               'rating.required' => 'Rating harus diisi',
               'rating.integer' => 'Rating harus berupa angka',
               'rating.min' => 'Rating minimal 1',
               'rating.max' => 'Rating maksimal 5',
               'ulasan.max' => 'Ulasan maksimal 500 karakter',
          ]);

          try {
               DB::beginTransaction();

               // Update atau create rating
               $rating = Rating::updateOrCreate(
                    [
                         'user_id' => auth()->id(),
                         'buku_id' => $request->buku_id
                    ],
                    [
                         'rating' => $request->rating,
                         'ulasan' => $request->ulasan
                    ]
               );

               // Update rating rata-rata buku
               $this->updateBookAverageRating($request->buku_id);

               DB::commit();

               return response()->json([
                    'success' => true,
                    'message' => 'Rating berhasil disimpan',
                    'rating' => $rating->load('user')
               ]);
          } catch (\Exception $e) {
               DB::rollback();
               Log::error('Error saving rating: ' . $e->getMessage());

               return response()->json([
                    'success' => false,
                    'message' => 'Gagal menyimpan rating'
               ], 500);
          }
     }

     public function update(Request $request, Rating $rating)
     {
          // Pastikan user hanya bisa update rating sendiri
          if ($rating->user_id !== auth()->id()) {
               return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak memiliki izin untuk mengubah rating ini'
               ], 403);
          }

          $request->validate([
               'rating' => 'required|integer|min:1|max:5',
               'ulasan' => 'nullable|string|max:500',
          ], [
               'rating.required' => 'Rating harus diisi',
               'rating.integer' => 'Rating harus berupa angka',
               'rating.min' => 'Rating minimal 1',
               'rating.max' => 'Rating maksimal 5',
               'ulasan.max' => 'Ulasan maksimal 500 karakter',
          ]);

          try {
               DB::beginTransaction();

               $rating->update([
                    'rating' => $request->rating,
                    'ulasan' => $request->ulasan
               ]);

               // Update rating rata-rata buku
               $this->updateBookAverageRating($rating->buku_id);

               DB::commit();

               return response()->json([
                    'success' => true,
                    'message' => 'Rating berhasil diperbarui',
                    'rating' => $rating->load('user')
               ]);
          } catch (\Exception $e) {
               DB::rollback();
               Log::error('Error updating rating: ' . $e->getMessage());

               return response()->json([
                    'success' => false,
                    'message' => 'Gagal memperbarui rating'
               ], 500);
          }
     }

     public function destroy(Rating $rating)
     {
          // Pastikan user hanya bisa hapus rating sendiri
          if ($rating->user_id !== auth()->id()) {
               return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak memiliki izin untuk menghapus rating ini'
               ], 403);
          }

          try {
               DB::beginTransaction();

               $bukuId = $rating->buku_id;
               $rating->delete();

               // Update rating rata-rata buku
               $this->updateBookAverageRating($bukuId);

               DB::commit();

               return response()->json([
                    'success' => true,
                    'message' => 'Rating berhasil dihapus'
               ]);
          } catch (\Exception $e) {
               DB::rollback();
               Log::error('Error deleting rating: ' . $e->getMessage());

               return response()->json([
                    'success' => false,
                    'message' => 'Gagal menghapus rating'
               ], 500);
          }
     }

     public function getRatings(Request $request, $bukuId)
     {
          try {
               $ratings = Rating::where('buku_id', $bukuId)
                    ->with('user:id,name')
                    ->latest()
                    ->paginate(10);

               return response()->json($ratings);
          } catch (\Exception $e) {
               Log::error('Error getting ratings: ' . $e->getMessage());

               return response()->json([
                    'success' => false,
                    'message' => 'Gagal mengambil data rating'
               ], 500);
          }
     }

     /**
      * Update rating rata-rata buku
      */
     private function updateBookAverageRating($bukuId)
     {
          $averageRating = Rating::where('buku_id', $bukuId)->avg('rating');

          Buku::where('id', $bukuId)->update([
               'rating_rata_rata' => $averageRating ? round($averageRating, 2) : 0
          ]);
     }
}
