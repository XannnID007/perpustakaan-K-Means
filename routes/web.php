<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\PerpustakaanSayaController;
use App\Http\Controllers\RecommendationController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\BukuController as AdminBukuController;
use App\Http\Controllers\Admin\KategoriController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - FIXED VERSION
|--------------------------------------------------------------------------
*/

// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/buku', [BukuController::class, 'index'])->name('buku.index');
Route::get('/buku/{buku}', [BukuController::class, 'show'])->name('buku.show');

// Authenticated User Routes
Route::middleware('auth')->group(function () {
    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Reading
    Route::get('/buku/{buku}/baca', [BukuController::class, 'read'])->name('buku.read');
    Route::get('/pdf/{buku}', [BukuController::class, 'streamPdf'])->name('pdf.stream');

    // Personal Library
    Route::get('/perpustakaan-saya', [PerpustakaanSayaController::class, 'index'])->name('perpustakaan-saya');

    // Recommendations
    Route::get('/rekomendasi', [RecommendationController::class, 'index'])->name('rekomendasi');
});

// Admin Routes - FIXED: Pindahkan get-sub-kategori ke dalam group admin
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard - PASTIKAN INI ROUTE PERTAMA
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');

    // AJAX Routes - PINDAHKAN KE ATAS SEBELUM RESOURCE ROUTES
    Route::get('/get-sub-kategori', [AdminBukuController::class, 'getSubKategori'])->name('get-sub-kategori');

    // Book Management
    Route::resource('buku', AdminBukuController::class);

    // Category Management
    Route::get('/kategori', [KategoriController::class, 'index'])->name('kategori.index');
    Route::post('/kategori', [KategoriController::class, 'store'])->name('kategori.store');
    Route::put('/kategori/{id}', [KategoriController::class, 'update'])->name('kategori.update');
    Route::delete('/kategori/{tipe}/{id}', [KategoriController::class, 'destroy'])->name('kategori.destroy');

    // User Management
    Route::resource('users', AdminUserController::class)->only(['index', 'show']);

    // Reports
    Route::get('/laporan', function () {
        return view('admin.laporan');
    })->name('laporan');
});

// API Routes for AJAX
Route::middleware('auth')->prefix('api')->group(function () {
    Route::post('/progress', [App\Http\Controllers\Api\ProgressController::class, 'save']);
    Route::post('/bookmark', [App\Http\Controllers\Api\BookmarkController::class, 'store']);
    Route::delete('/bookmark/{bookmark}', [App\Http\Controllers\Api\BookmarkController::class, 'destroy']);

    Route::post('/rating', [App\Http\Controllers\Api\RatingController::class, 'store']);
    Route::put('/rating/{rating}', [App\Http\Controllers\Api\RatingController::class, 'update']);
    Route::delete('/rating/{rating}', [App\Http\Controllers\Api\RatingController::class, 'destroy']);
    Route::get('/rating/{buku_id}', [App\Http\Controllers\Api\RatingController::class, 'getRatings']);
});

// Auth routes (handled by Breeze)
require __DIR__ . '/auth.php';
