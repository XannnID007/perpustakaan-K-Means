<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Buku;
use App\Models\ProgressBaca;
use App\Models\Rating;
use App\Models\KategoriUtama;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
     // Daftar 50 nama acak untuk pengguna
     private $namaPengguna = [
          'Ahmad Maulana',
          'Aisyah Putri',
          'Aditya Nugraha',
          'Agung Prasetyo',
          'Aliya Safitri',
          'Amelia Cahyani',
          'Andi Wijaya',
          'Angga Pratama',
          'Ani Suryani',
          'Ardiansyah Putra',
          'Ari Wibowo',
          'Bayu Setiawan',
          'Bima Sakti',
          'Cahya Purnama',
          'Citra Lestari',
          'Dedi Kusnandar',
          'Dian Anggraini',
          'Dimas Saputra',
          'Doni Irawan',
          'Eka Yulianti',
          'Fajar Nugroho',
          'Farah Diba',
          'Febri Hariyadi',
          'Fitriani Hasanah',
          'Galih Prakoso',
          'Gilang Dirga',
          'Hafizh Syahrini',
          'Hana Malasan',
          'Haris Munandar',
          'Ika Pertiwi',
          'Indra Gunawan',
          'Irfan Hakim',
          'Ismail Marzuki',
          'Jajang Mulyana',
          'Kartika Sari',
          'Lestari Indah',
          'Mega Wati',
          'Muhammad Rizky',
          'Nadia Paramita',
          'Putra Bangsa',
          'Rahmawati Putri',
          'Rangga Sasongko',
          'Rian Saputra',
          'Rina Nose',
          'Sari Murni',
          'Siska Wulandari',
          'Siti Nurhaliza',
          'Surya Saputra',
          'Taufik Hidayat',
          'Yulia Rachman'
     ];

     public function run()
     {
          // Acak urutan nama
          shuffle($this->namaPengguna);

          $kategoriFiksi = KategoriUtama::where('nama', 'Fiksi')->first();

          if (!$kategoriFiksi) {
               $this->command->error('Kategori "Fiksi" tidak ditemukan. Pastikan KategoriSeeder sudah berjalan.');
               return;
          }

          $fiksiBookIds = Buku::where('kategori_utama_id', $kategoriFiksi->id)->pluck('id');
          $nonFiksiBookIds = Buku::where('kategori_utama_id', '!=', $kategoriFiksi->id)->pluck('id');

          if ($fiksiBookIds->count() < 10 || $nonFiksiBookIds->count() < 10) {
               $this->command->error('Jumlah buku tidak cukup. Diperlukan minimal 10 fiksi dan 10 non-fiksi.');
               return;
          }

          // --- Buat grup pengguna dengan pembagian 17-17-16 ---
          $this->createUserGroup(17, $fiksiBookIds, 8, $nonFiksiBookIds, 2); // 17 Pembaca Fiksi
          $this->createUserGroup(17, $fiksiBookIds, 1, $nonFiksiBookIds, 9); // 17 Pembaca Non-Fiksi
          $this->createUserGroup(16, $fiksiBookIds, 5, $nonFiksiBookIds, 5); // 16 Pembaca Seimbang

          $this->command->info('Berhasil membuat 50 user dengan nama acak dan profil baca yang beragam.');
     }

     private function createUserGroup(int $totalUsers, Collection $fiksiIds, int $fiksiCount, Collection $nonFiksiIds, int $nonFiksiCount)
     {
          for ($i = 0; $i < $totalUsers; $i++) {
               $nama = array_pop($this->namaPengguna);
               if (!$nama) continue; // Hentikan jika nama habis

               $user = User::create([
                    'name' => $nama,
                    'email' => strtolower(str_replace(' ', '', $nama)) . "@gmail.com",
                    'password' => Hash::make('password'),
                    'role' => 'user',
               ]);

               $this->addReadingHistory($user, $fiksiIds, $fiksiCount);
               $this->addReadingHistory($user, $nonFiksiIds, $nonFiksiCount);
          }
     }

     private function addReadingHistory(User $user, Collection $bookIds, int $count)
     {
          if ($bookIds->count() < $count) return;

          $booksToRead = Buku::findMany($bookIds->random($count));

          foreach ($booksToRead as $buku) {
               ProgressBaca::create([
                    'user_id' => $user->id,
                    'buku_id' => $buku->id,
                    'halaman_sekarang' => $buku->total_halaman,
                    'total_halaman' => $buku->total_halaman,
                    'persentase_baca' => 100.00,
                    'terakhir_baca' => now(),
                    'waktu_baca' => rand(3600, 36000),
                    'selesai' => true,
               ]);

               Rating::create([
                    'user_id' => $user->id,
                    'buku_id' => $buku->id,
                    'rating' => rand(3, 5),
               ]);
          }
     }
}
