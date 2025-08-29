<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Buku;
use App\Models\KategoriUtama;
use App\Models\SubKategori;

class BukuSeeder extends Seeder
{
    public function run(): void
    {
        $fiksi = KategoriUtama::where('slug', 'fiksi')->first();
        $nonFiksi = KategoriUtama::where('slug', 'non-fiksi')->first();

        if (!$fiksi || !$nonFiksi) {
            $this->command->error('Kategori tidak ditemukan. Pastikan KategoriSeeder sudah dijalankan terlebih dahulu.');
            return;
        }

        // Get sub categories
        $sastraKlasik = SubKategori::where('slug', 'sastra-klasik')->first();
        $novelKeluarga = SubKategori::where('slug', 'novel-keluarga')->first();
        $petualanganFantasi = SubKategori::where('slug', 'petualangan-fantasi')->first();
        $dramaPsikologi = SubKategori::where('slug', 'drama-psikologi')->first();
        $fiksiAnak = SubKategori::where('slug', 'fiksi-anak')->first();
        $misteriThriller = SubKategori::where('slug', 'misteri-thriller')->first();

        // Non-Fiksi sub categories
        $politikPemerintahan = SubKategori::where('slug', 'politik-pemerintahan')->first();
        $kulinerMasakan = SubKategori::where('slug', 'kuliner-masakan')->first();
        $bisnisKeuangan = SubKategori::where('slug', 'bisnis-keuangan')->first();
        $pendidikanPembelajaran = SubKategori::where('slug', 'pendidikan-pembelajaran')->first();
        $psikologiMentalHealth = SubKategori::where('slug', 'psikologi-mental-health')->first();
        $travelGeografi = SubKategori::where('slug', 'travel-geografi')->first();
        $seniKerajinan = SubKategori::where('slug', 'seni-kerajinan')->first();

        // === BUKU FIKSI ===
        $bukuFiksi = [
            [
                'judul' => 'A Little Princess',
                'penulis' => 'Frances Hodgson Burnett',
                'deskripsi' => 'Kisah Sara Crewe, seorang gadis kecil yang berubah dari seorang putri kecil yang dimanja menjadi seorang pelayan di sekolah asrama yang sama. Meski dalam kemiskinan, Sara tetap mempertahankan sikap mulia dan imajinasinya yang kaya, membuktikan bahwa keanggunan sejati datang dari dalam hati.',
                'kategori_utama_id' => $fiksi->id,
                'sub_kategori_id' => $fiksiAnak->id,
                'tahun_terbit' => 1905,
                'file_pdf' => 'a_little_princess.pdf',
                'total_halaman' => 320,
                'rating_rata_rata' => 4.5,
                'total_pembaca' => 245,
                'ukuran_file' => 2800000, // 2.8MB
            ],
            [
                'judul' => 'A Room With a View',
                'penulis' => 'E. M. Forster',
                'deskripsi' => 'Novel yang menggambarkan perjalanan seorang wanita muda Inggris, Lucy Honeychurch, dalam menemukan cinta sejati dan kebebasan pribadi. Berlatar di Italia dan Inggris pada awal abad ke-20, novel ini mengeksplorasi tema kelas sosial, cinta, dan pencarian jati diri.',
                'kategori_utama_id' => $fiksi->id,
                'sub_kategori_id' => $sastraKlasik->id,
                'tahun_terbit' => 1908,
                'file_pdf' => 'a_room_with_a_view.pdf',
                'total_halaman' => 280,
                'rating_rata_rata' => 4.2,
                'total_pembaca' => 189,
                'ukuran_file' => 2500000,
            ],
            [
                'judul' => 'Alice\'s Adventures in Wonderland',
                'penulis' => 'Lewis Carroll',
                'deskripsi' => 'Petualangan magis Alice yang jatuh ke dalam lubang kelinci dan memasuki dunia yang penuh dengan karakter-karakter aneh dan situasi yang absurd. Karya klasik ini telah menjadi salah satu buku anak-anak paling terkenal di dunia dengan pesan tentang imajinasi dan keingintahuan.',
                'kategori_utama_id' => $fiksi->id,
                'sub_kategori_id' => $petualanganFantasi->id,
                'tahun_terbit' => 1965,
                'file_pdf' => 'alice_adventures_wonderland.pdf',
                'total_halaman' => 200,
                'rating_rata_rata' => 4.7,
                'total_pembaca' => 432,
                'ukuran_file' => 2200000,
            ],
            [
                'judul' => 'Anne of Avonlea',
                'penulis' => 'Lucy Maud Montgomery',
                'deskripsi' => 'Kelanjutan dari Anne of Green Gables, mengikuti kehidupan Anne Shirley sebagai guru muda di Avonlea. Novel ini menggambarkan pertumbuhan Anne menjadi seorang wanita muda yang bijaksana, dengan tetap mempertahankan semangat dan optimisme yang menggemaskan.',
                'kategori_utama_id' => $fiksi->id,
                'sub_kategori_id' => $novelKeluarga->id,
                'tahun_terbit' => 1909,
                'file_pdf' => 'anne_of_avonlea.pdf',
                'total_halaman' => 350,
                'rating_rata_rata' => 4.4,
                'total_pembaca' => 298,
                'ukuran_file' => 3100000,
            ],
            [
                'judul' => 'Anne of Green Gables',
                'penulis' => 'Lucy Maud Montgomery',
                'deskripsi' => 'Kisah klasik tentang Anne Shirley, seorang gadis panti asuhan berambut merah dengan imajinasi yang luar biasa, yang diadopsi oleh pasangan tua di Prince Edward Island. Novel yang mengharukan ini mengeksplorasi tema keluarga, persahabatan, dan menemukan tempat di dunia.',
                'kategori_utama_id' => $fiksi->id,
                'sub_kategori_id' => $novelKeluarga->id,
                'tahun_terbit' => 1908,
                'file_pdf' => 'anne_of_green_gables.pdf',
                'total_halaman' => 380,
                'rating_rata_rata' => 4.6,
                'total_pembaca' => 356,
                'ukuran_file' => 3300000,
            ],
            [
                'judul' => 'Anne\'s House of Dreams',
                'penulis' => 'Lucy Maud Montgomery',
                'deskripsi' => 'Novel ketiga dalam seri Anne, mengikuti Anne dan Gilbert dalam kehidupan pernikahan mereka di Four Winds Point. Cerita ini menggambarkan tantangan dan kebahagiaan kehidupan dewasa, persahabatan baru, dan kehilangan yang mendalam.',
                'kategori_utama_id' => $fiksi->id,
                'sub_kategori_id' => $novelKeluarga->id,
                'tahun_terbit' => 1917,
                'file_pdf' => 'annes_house_of_dreams.pdf',
                'total_halaman' => 340,
                'rating_rata_rata' => 4.3,
                'total_pembaca' => 234,
                'ukuran_file' => 3000000,
            ],
            [
                'judul' => 'Crime and Punishment',
                'penulis' => 'Fyodor Dostoevsky',
                'deskripsi' => 'Mahakarya psikologis yang mengikuti Rodion Raskolnikov, seorang mahasiswa miskin di St. Petersburg yang merencanakan dan melaksanakan pembunuhan. Novel ini menyelami aspek psikologi manusia, moralitas, dan penebusan dosa dengan kedalaman yang luar biasa.',
                'kategori_utama_id' => $fiksi->id,
                'sub_kategori_id' => $dramaPsikologi->id,
                'tahun_terbit' => 1966,
                'file_pdf' => 'crime_and_punishment.pdf',
                'total_halaman' => 550,
                'rating_rata_rata' => 4.8,
                'total_pembaca' => 423,
                'ukuran_file' => 4800000,
            ],
            [
                'judul' => 'Max Havelaar: or, the coffee auctions of the Dutch trading company',
                'penulis' => 'Multatuli',
                'deskripsi' => 'Novel klasik Belanda yang mengkritik sistem kolonial di Hindia Belanda (Indonesia). Karya Multatuli ini membuka mata dunia tentang penindasan yang terjadi dalam sistem tanam paksa dan menjadi salah satu karya sastra paling berpengaruh dalam sejarah Belanda.',
                'kategori_utama_id' => $fiksi->id,
                'sub_kategori_id' => $sastraKlasik->id,
                'tahun_terbit' => 1960,
                'file_pdf' => 'max_havelaar.pdf',
                'total_halaman' => 420,
                'rating_rata_rata' => 4.1,
                'total_pembaca' => 156,
                'ukuran_file' => 3700000,
            ],
            [
                'judul' => 'Missing Men',
                'penulis' => 'Vincent Starrett',
                'deskripsi' => 'Sebuah novel misteri yang menegangkan tentang pencarian orang-orang yang hilang secara misterius. Vincent Starrett, yang dikenal sebagai penulis cerita detektif, menghadirkan plot yang rumit dengan karakter-karakter yang menarik dan atmosfer yang mencekam.',
                'kategori_utama_id' => $fiksi->id,
                'sub_kategori_id' => $misteriThriller->id,
                'tahun_terbit' => 1922,
                'file_pdf' => 'missing_men.pdf',
                'total_halaman' => 280,
                'rating_rata_rata' => 3.9,
                'total_pembaca' => 134,
                'ukuran_file' => 2600000,
            ],
            [
                'judul' => 'The Beautiful and Damned',
                'penulis' => 'F. Scott Fitzgerald',
                'deskripsi' => 'Novel yang menggambarkan kemerosotan moral Anthony Patch dan istrinya Gloria dalam menantikan warisan. Karya Fitzgerald ini mengkritik gaya hidup berlebihan era Jazz Age dan mengeksplorasi tema kekayaan, kemewahan, dan dekadansi moral.',
                'kategori_utama_id' => $fiksi->id,
                'sub_kategori_id' => $dramaPsikologi->id,
                'tahun_terbit' => 1922,
                'file_pdf' => 'the_beautiful_and_damned.pdf',
                'total_halaman' => 450,
                'rating_rata_rata' => 4.0,
                'total_pembaca' => 187,
                'ukuran_file' => 4000000,
            ],
            [
                'judul' => 'The Blue Bird for Children',
                'penulis' => 'Georgette Leblanc',
                'deskripsi' => 'Adaptasi dari drama terkenal Maurice Maeterlinck untuk anak-anak. Cerita tentang Tyltyl dan Mytyl yang mencari Burung Biru kebahagiaan, mengajarkan anak-anak bahwa kebahagiaan sebenarnya dapat ditemukan di rumah dan dalam keluarga.',
                'kategori_utama_id' => $fiksi->id,
                'sub_kategori_id' => $fiksiAnak->id,
                'tahun_terbit' => 1913,
                'file_pdf' => 'the_blue_bird_for_children.pdf',
                'total_halaman' => 180,
                'rating_rata_rata' => 4.2,
                'total_pembaca' => 145,
                'ukuran_file' => 1800000,
            ],
            [
                'judul' => 'The Brothers Karamazov',
                'penulis' => 'Fyodor Dostoyevsky',
                'deskripsi' => 'Mahakarya terakhir Dostoyevsky yang mengeksplorasi tema iman, keraguan, dan moralitas melalui kisah keluarga Karamazov. Novel filosofis ini menggabungkan elemen misteri pembunuhan dengan pertanyaan mendalam tentang eksistensi Tuhan dan sifat manusia.',
                'kategori_utama_id' => $fiksi->id,
                'sub_kategori_id' => $dramaPsikologi->id,
                'tahun_terbit' => 1980,
                'file_pdf' => 'the_brothers_karamazov.pdf',
                'total_halaman' => 820,
                'rating_rata_rata' => 4.9,
                'total_pembaca' => 312,
                'ukuran_file' => 7200000,
            ]
        ];

        // === BUKU NON-FIKSI ===
        $bukuNonFiksi = [
            [
                'judul' => 'Demokrasi Kita',
                'penulis' => 'Moh Hatta',
                'deskripsi' => 'Pemikiran mendalam tentang konsep demokrasi dari perspektif Bung Hatta, salah satu founding father Indonesia. Buku ini membahas visi demokrasi ekonomi dan politik yang sesuai dengan nilai-nilai dan kebutuhan bangsa Indonesia.',
                'kategori_utama_id' => $nonFiksi->id,
                'sub_kategori_id' => $politikPemerintahan->id,
                'tahun_terbit' => 1960,
                'file_pdf' => 'demokrasi_kita.pdf',
                'total_halaman' => 220,
                'rating_rata_rata' => 4.6,
                'total_pembaca' => 289,
                'ukuran_file' => 2400000,
            ],
            [
                'judul' => 'Foods That Will Win the War And How to Cook Them',
                'penulis' => 'Goudiss and Goudiss',
                'deskripsi' => 'Panduan praktis untuk memasak makanan bergizi di masa perang dengan bahan-bahan terbatas. Buku ini memberikan resep-resep kreatif dan tips nutrisi untuk menjaga kesehatan keluarga di masa sulit.',
                'kategori_utama_id' => $nonFiksi->id,
                'sub_kategori_id' => $kulinerMasakan->id,
                'tahun_terbit' => 1918,
                'file_pdf' => 'foods_that_will_win_the_war.pdf',
                'total_halaman' => 180,
                'rating_rata_rata' => 3.8,
                'total_pembaca' => 95,
                'ukuran_file' => 2000000,
            ],
            [
                'judul' => 'How to Invest Money',
                'penulis' => 'George Garr Henry',
                'deskripsi' => 'Panduan investasi klasik yang mengajarkan prinsip-prinsip dasar investasi yang masih relevan hingga kini. Henry menjelaskan berbagai instrumen investasi dan strategi untuk membangun kekayaan jangka panjang.',
                'kategori_utama_id' => $nonFiksi->id,
                'sub_kategori_id' => $bisnisKeuangan->id,
                'tahun_terbit' => 1908,
                'file_pdf' => 'how_to_invest_money.pdf',
                'total_halaman' => 250,
                'rating_rata_rata' => 4.2,
                'total_pembaca' => 178,
                'ukuran_file' => 2300000,
            ],
            [
                'judul' => 'How to Make Money',
                'penulis' => 'John V. Dunlap',
                'deskripsi' => 'Panduan praktis untuk menghasilkan uang melalui berbagai cara yang etis dan berkelanjutan. Dunlap membagikan strategi bisnis, investasi, dan pengembangan keterampilan untuk meningkatkan penghasilan.',
                'kategori_utama_id' => $nonFiksi->id,
                'sub_kategori_id' => $bisnisKeuangan->id,
                'tahun_terbit' => 1995,
                'file_pdf' => 'how_to_make_money.pdf',
                'total_halaman' => 300,
                'rating_rata_rata' => 3.9,
                'total_pembaca' => 156,
                'ukuran_file' => 2700000,
            ],
            [
                'judul' => 'How to Master the Spoken Word',
                'penulis' => 'Edwin Gordon Lawrence',
                'deskripsi' => 'Panduan komprehensif untuk mengembangkan kemampuan berbicara di depan umum. Lawrence memberikan teknik-teknik praktis untuk mengatasi demam panggung, menyusun pidato yang efektif, dan menjadi pembicara yang percaya diri.',
                'kategori_utama_id' => $nonFiksi->id,
                'sub_kategori_id' => $pendidikanPembelajaran->id,
                'tahun_terbit' => 1915,
                'file_pdf' => 'how_to_master_spoken_word.pdf',
                'total_halaman' => 280,
                'rating_rata_rata' => 4.3,
                'total_pembaca' => 203,
                'ukuran_file' => 2500000,
            ],
            [
                'judul' => 'How to Study',
                'penulis' => 'George Fillmore Swain',
                'deskripsi' => 'Metode belajar efektif yang telah terbukti membantu mahasiswa mencapai prestasi akademik terbaik. Swain menjelaskan teknik membaca, mencatat, mengingat, dan mengorganisir waktu belajar secara optimal.',
                'kategori_utama_id' => $nonFiksi->id,
                'sub_kategori_id' => $pendidikanPembelajaran->id,
                'tahun_terbit' => 1917,
                'file_pdf' => 'how_to_study.pdf',
                'total_halaman' => 200,
                'rating_rata_rata' => 4.4,
                'total_pembaca' => 267,
                'ukuran_file' => 2100000,
            ],
            [
                'judul' => 'Indonesia Menggugat!',
                'penulis' => 'Sukarno',
                'deskripsi' => 'Pembelaan bersejarah Bung Karno di depan pengadilan kolonial Belanda tahun 1930. Dokumen penting ini berisi argumentasi tentang hak bangsa Indonesia untuk merdeka dan mengkritik sistem kolonialisme. Karya ini menjadi fondasi ideologi perjuangan kemerdekaan Indonesia.',
                'kategori_utama_id' => $nonFiksi->id,
                'sub_kategori_id' => $politikPemerintahan->id,
                'tahun_terbit' => 1930,
                'file_pdf' => 'indonesia_menggugat.pdf',
                'total_halaman' => 180,
                'rating_rata_rata' => 4.8,
                'total_pembaca' => 345,
                'ukuran_file' => 2200000,
            ],
            [
                'judul' => 'Mind Reading',
                'penulis' => 'W.E Skinner',
                'deskripsi' => 'Eksplorasi fascinasi tentang kemampuan membaca pikiran dan teknik-teknik psikologi untuk memahami bahasa tubuh dan sinyal non-verbal. Skinner menjelaskan metode observasi dan analisis perilaku manusia.',
                'kategori_utama_id' => $nonFiksi->id,
                'sub_kategori_id' => $psikologiMentalHealth->id,
                'tahun_terbit' => 1920,
                'file_pdf' => 'mind_reading.pdf',
                'total_halaman' => 240,
                'rating_rata_rata' => 3.7,
                'total_pembaca' => 123,
                'ukuran_file' => 2300000,
            ],
            [
                'judul' => 'Oxford',
                'penulis' => 'Andrew Lang',
                'deskripsi' => 'Sebuah potret mendalam tentang Universitas Oxford, sejarahnya, tradisi akademiknya, dan kehidupan mahasiswa. Lang menghadirkan gambaran vivid tentang salah satu institusi pendidikan tertua dan paling prestisius di dunia.',
                'kategori_utama_id' => $nonFiksi->id,
                'sub_kategori_id' => $travelGeografi->id,
                'tahun_terbit' => 1992,
                'file_pdf' => 'oxford.pdf',
                'total_halaman' => 320,
                'rating_rata_rata' => 4.1,
                'total_pembaca' => 167,
                'ukuran_file' => 3000000,
            ],
            [
                'judul' => 'Perfumes And Their Preparation',
                'penulis' => 'George William Askinson, Dr. Chem.',
                'deskripsi' => 'Panduan komprehensif tentang seni pembuatan parfum dari perspektif ilmiah. Dr. Askinson menjelaskan kimia di balik wangi-wangian, teknik ekstraksi, dan formulasi parfum dengan pendekatan yang sistematis dan mudah dipahami.',
                'kategori_utama_id' => $nonFiksi->id,
                'sub_kategori_id' => $seniKerajinan->id,
                'tahun_terbit' => 1992,
                'file_pdf' => 'perfumes_and_their_preparation.pdf',
                'total_halaman' => 280,
                'rating_rata_rata' => 4.0,
                'total_pembaca' => 89,
                'ukuran_file' => 2600000,
            ],
            [
                'judul' => 'The Art of Perfumery',
                'penulis' => 'G.W. Septimus Piesse',
                'deskripsi' => 'Karya klasik tentang seni pembuatan parfum yang telah menjadi rujukan para perfumer selama puluhan tahun. Piesse menggabungkan pengetahuan kimia dengan estetika seni untuk menciptakan panduan yang komprehensif tentang dunia parfum.',
                'kategori_utama_id' => $nonFiksi->id,
                'sub_kategori_id' => $seniKerajinan->id,
                'tahun_terbit' => 1957,
                'file_pdf' => 'the_art_of_perfumery.pdf',
                'total_halaman' => 350,
                'rating_rata_rata' => 4.3,
                'total_pembaca' => 112,
                'ukuran_file' => 3200000,
            ]
        ];

        // Insert all books
        $allBooks = array_merge($bukuFiksi, $bukuNonFiksi);

        foreach ($allBooks as $book) {
            Buku::create($book);
        }

        $this->command->info('Berhasil menambahkan ' . count($allBooks) . ' buku (' . count($bukuFiksi) . ' fiksi, ' . count($bukuNonFiksi) . ' non-fiksi)');
    }
}
