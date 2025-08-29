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
            'deskripsi' => 'Kategori buku fiksi dan karya imajinatif yang menghadirkan cerita-cerita menarik dari berbagai genre'
        ]);

        $subKategoriFiksi = [
            [
                'nama' => 'Sastra Klasik',
                'slug' => 'sastra-klasik',
                'deskripsi' => 'Karya sastra klasik yang telah teruji waktu'
            ],
            [
                'nama' => 'Novel Keluarga',
                'slug' => 'novel-keluarga',
                'deskripsi' => 'Cerita tentang kehidupan keluarga dan hubungan antar generasi'
            ],
            [
                'nama' => 'Petualangan & Fantasi',
                'slug' => 'petualangan-fantasi',
                'deskripsi' => 'Cerita petualangan dan dunia fantasi yang memukau'
            ],
            [
                'nama' => 'Drama & Psikologi',
                'slug' => 'drama-psikologi',
                'deskripsi' => 'Novel yang menggali aspek psikologis dan drama kehidupan'
            ],
            [
                'nama' => 'Romance',
                'slug' => 'romance',
                'deskripsi' => 'Cerita percintaan dan hubungan romantis'
            ],
            [
                'nama' => 'Misteri & Thriller',
                'slug' => 'misteri-thriller',
                'deskripsi' => 'Cerita menegangkan dengan unsur misteri dan investigasi'
            ],
            [
                'nama' => 'Science Fiction',
                'slug' => 'science-fiction',
                'deskripsi' => 'Fiksi ilmiah dengan teknologi dan masa depan'
            ],
            [
                'nama' => 'Horror',
                'slug' => 'horror',
                'deskripsi' => 'Cerita horor dan supernatural'
            ],
            [
                'nama' => 'Young Adult',
                'slug' => 'young-adult',
                'deskripsi' => 'Novel untuk remaja dan dewasa muda'
            ],
            [
                'nama' => 'Fiksi Anak',
                'slug' => 'fiksi-anak',
                'deskripsi' => 'Cerita untuk anak-anak dengan nilai edukatif'
            ]
        ];

        foreach ($subKategoriFiksi as $subKat) {
            SubKategori::create([
                'kategori_utama_id' => $fiksi->id,
                'nama' => $subKat['nama'],
                'slug' => $subKat['slug'],
                'deskripsi' => $subKat['deskripsi']
            ]);
        }

        // Kategori Non-Fiksi
        $nonFiksi = KategoriUtama::create([
            'nama' => 'Non-Fiksi',
            'slug' => 'non-fiksi',
            'deskripsi' => 'Kategori buku non-fiksi yang berisi fakta, pengetahuan, dan informasi edukatif'
        ]);

        $subKategoriNonFiksi = [
            [
                'nama' => 'Biografi & Autobiografi',
                'slug' => 'biografi-autobiografi',
                'deskripsi' => 'Kisah hidup tokoh-tokoh penting dan berpengaruh'
            ],
            [
                'nama' => 'Pengembangan Diri',
                'slug' => 'pengembangan-diri',
                'deskripsi' => 'Buku untuk meningkatkan kualitas hidup dan kemampuan personal'
            ],
            [
                'nama' => 'Bisnis & Keuangan',
                'slug' => 'bisnis-keuangan',
                'deskripsi' => 'Panduan bisnis, investasi, dan pengelolaan keuangan'
            ],
            [
                'nama' => 'Kesehatan & Kebugaran',
                'slug' => 'kesehatan-kebugaran',
                'deskripsi' => 'Informasi tentang kesehatan, nutrisi, dan gaya hidup sehat'
            ],
            [
                'nama' => 'Sejarah',
                'slug' => 'sejarah',
                'deskripsi' => 'Catatan peristiwa masa lalu dan perkembangan peradaban'
            ],
            [
                'nama' => 'Politik & Pemerintahan',
                'slug' => 'politik-pemerintahan',
                'deskripsi' => 'Buku tentang sistem politik, pemerintahan, dan demokrasi'
            ],
            [
                'nama' => 'Sains & Teknologi',
                'slug' => 'sains-teknologi',
                'deskripsi' => 'Pengetahuan sains, teknologi, dan inovasi terkini'
            ],
            [
                'nama' => 'Pendidikan & Pembelajaran',
                'slug' => 'pendidikan-pembelajaran',
                'deskripsi' => 'Metode belajar dan teknik pembelajaran efektif'
            ],
            [
                'nama' => 'Agama & Spiritualitas',
                'slug' => 'agama-spiritualitas',
                'deskripsi' => 'Buku tentang ajaran agama dan pengembangan spiritual'
            ],
            [
                'nama' => 'Travel & Geografi',
                'slug' => 'travel-geografi',
                'deskripsi' => 'Panduan perjalanan dan pengetahuan geografis'
            ],
            [
                'nama' => 'Kuliner & Masakan',
                'slug' => 'kuliner-masakan',
                'deskripsi' => 'Resep masakan dan tips kuliner'
            ],
            [
                'nama' => 'Seni & Kerajinan',
                'slug' => 'seni-kerajinan',
                'deskripsi' => 'Panduan seni, kerajinan, dan kreativitas'
            ],
            [
                'nama' => 'Psikologi & Mental Health',
                'slug' => 'psikologi-mental-health',
                'deskripsi' => 'Pemahaman psikologi dan kesehatan mental'
            ]
        ];

        foreach ($subKategoriNonFiksi as $subKat) {
            SubKategori::create([
                'kategori_utama_id' => $nonFiksi->id,
                'nama' => $subKat['nama'],
                'slug' => $subKat['slug'],
                'deskripsi' => $subKat['deskripsi']
            ]);
        }
    }
}
