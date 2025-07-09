<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Buku;
use App\Models\KategoriUtama;
use App\Models\SubKategori;

class BukuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $fiksi = KategoriUtama::where('slug', 'fiksi')->first();
        $nonFiksi = KategoriUtama::where('slug', 'non-fiksi')->first();

        if ($fiksi && $nonFiksi) {
            // Sample Fiksi Books
            $romance = SubKategori::where('kategori_utama_id', $fiksi->id)
                ->where('slug', 'romance')->first();
            $fantasy = SubKategori::where('kategori_utama_id', $fiksi->id)
                ->where('slug', 'fantasy')->first();

            // Sample Non-Fiksi Books
            $pengembangan = SubKategori::where('kategori_utama_id', $nonFiksi->id)
                ->where('slug', 'pengembangan-diri')->first();
            $bisnis = SubKategori::where('kategori_utama_id', $nonFiksi->id)
                ->where('slug', 'bisnis-keuangan')->first();

            // Create sample books (you'll need to add actual PDF files)
            $sampleBooks = [
                [
                    'judul' => 'Cinta di Musim Hujan',
                    'penulis' => 'Jane Doe',
                    'deskripsi' => 'Sebuah kisah cinta yang mengharukan di tengah hujan Jakarta.',
                    'kategori_utama_id' => $fiksi->id,
                    'sub_kategori_id' => $romance?->id,
                    'tahun_terbit' => 2023,
                    'file_pdf' => 'sample-romance.pdf',
                    'total_halaman' => 250,
                    'rating_rata_rata' => 4.5,
                    'total_pembaca' => 150,
                ],
                [
                    'judul' => 'Dunia Ajaib Nusantara',
                    'penulis' => 'John Smith',
                    'deskripsi' => 'Petualangan magis di dunia paralel Indonesia.',
                    'kategori_utama_id' => $fiksi->id,
                    'sub_kategori_id' => $fantasy?->id,
                    'tahun_terbit' => 2023,
                    'file_pdf' => 'sample-fantasy.pdf',
                    'total_halaman' => 320,
                    'rating_rata_rata' => 4.2,
                    'total_pembaca' => 89,
                ],
                [
                    'judul' => 'Mindset Sukses',
                    'penulis' => 'Dr. Ahmad',
                    'deskripsi' => 'Panduan praktis mengembangkan mindset untuk meraih kesuksesan.',
                    'kategori_utama_id' => $nonFiksi->id,
                    'sub_kategori_id' => $pengembangan?->id,
                    'tahun_terbit' => 2024,
                    'file_pdf' => 'sample-mindset.pdf',
                    'total_halaman' => 180,
                    'rating_rata_rata' => 4.7,
                    'total_pembaca' => 201,
                ],
                [
                    'judul' => 'Investasi untuk Pemula',
                    'penulis' => 'Sarah Finance',
                    'deskripsi' => 'Belajar investasi dari nol hingga mahir dengan strategi yang terbukti.',
                    'kategori_utama_id' => $nonFiksi->id,
                    'sub_kategori_id' => $bisnis?->id,
                    'tahun_terbit' => 2024,
                    'file_pdf' => 'sample-investasi.pdf',
                    'total_halaman' => 200,
                    'rating_rata_rata' => 4.3,
                    'total_pembaca' => 167,
                ],
            ];

            foreach ($sampleBooks as $bookData) {
                Buku::create($bookData);
            }
        }
    }
}
