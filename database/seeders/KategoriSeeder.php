<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\KategoriUtama;
use App\Models\SubKategori;

class KategoriSeeder extends Seeder
{
    public function run(): void
    {
        // Kategori Fiksi
        $fiksi = KategoriUtama::create([
            'nama' => 'Fiksi',
            'slug' => 'fiksi',
            'deskripsi' => 'Kategori buku fiksi dan karya imajinatif'
        ]);

        $subKategoriFiksi = [
            ['nama' => 'Romance', 'slug' => 'romance'],
            ['nama' => 'Misteri & Thriller', 'slug' => 'misteri-thriller'],
            ['nama' => 'Fantasy', 'slug' => 'fantasy'],
            ['nama' => 'Science Fiction', 'slug' => 'science-fiction'],
            ['nama' => 'Fiksi Sejarah', 'slug' => 'fiksi-sejarah'],
            ['nama' => 'Fiksi Sastra', 'slug' => 'fiksi-sastra'],
            ['nama' => 'Horror', 'slug' => 'horror'],
            ['nama' => 'Petualangan', 'slug' => 'petualangan'],
            ['nama' => 'Young Adult', 'slug' => 'young-adult'],
        ];

        foreach ($subKategoriFiksi as $subKat) {
            SubKategori::create([
                'kategori_utama_id' => $fiksi->id,
                'nama' => $subKat['nama'],
                'slug' => $subKat['slug'],
                'deskripsi' => 'Sub kategori ' . $subKat['nama']
            ]);
        }

        // Kategori Non-Fiksi
        $nonFiksi = KategoriUtama::create([
            'nama' => 'Non-Fiksi',
            'slug' => 'non-fiksi',
            'deskripsi' => 'Kategori buku non-fiksi dan faktual'
        ]);

        $subKategoriNonFiksi = [
            ['nama' => 'Biografi & Autobiografi', 'slug' => 'biografi-autobiografi'],
            ['nama' => 'Pengembangan Diri', 'slug' => 'pengembangan-diri'],
            ['nama' => 'Bisnis & Keuangan', 'slug' => 'bisnis-keuangan'],
            ['nama' => 'Kesehatan & Kebugaran', 'slug' => 'kesehatan-kebugaran'],
            ['nama' => 'Sejarah', 'slug' => 'sejarah'],
            ['nama' => 'Sains & Teknologi', 'slug' => 'sains-teknologi'],
            ['nama' => 'Politik', 'slug' => 'politik'],
            ['nama' => 'Agama & Spiritualitas', 'slug' => 'agama-spiritualitas'],
            ['nama' => 'Travel', 'slug' => 'travel'],
            ['nama' => 'Pendidikan', 'slug' => 'pendidikan'],
        ];

        foreach ($subKategoriNonFiksi as $subKat) {
            SubKategori::create([
                'kategori_utama_id' => $nonFiksi->id,
                'nama' => $subKat['nama'],
                'slug' => $subKat['slug'],
                'deskripsi' => 'Sub kategori ' . $subKat['nama']
            ]);
        }
    }
}
