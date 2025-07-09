<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('buku', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->string('penulis');
            $table->text('deskripsi');
            $table->foreignId('kategori_utama_id')->constrained('kategori_utama')->onDelete('cascade');
            $table->foreignId('sub_kategori_id')->constrained('sub_kategori')->onDelete('cascade');
            $table->string('gambar_sampul')->nullable();
            $table->string('file_pdf');
            $table->integer('ukuran_file')->nullable();
            $table->integer('total_halaman')->default(0);
            $table->decimal('rating_rata_rata', 3, 2)->default(0);
            $table->integer('total_pembaca')->default(0);
            $table->year('tahun_terbit')->nullable();
            $table->boolean('aktif')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('buku');
    }
};
