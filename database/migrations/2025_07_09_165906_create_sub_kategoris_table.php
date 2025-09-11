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
        Schema::create('sub_kategori', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('kategori_utama_id'); // Buat kolom-nya dulu
            $table->foreign('kategori_utama_id')             // Baru tambahkan foreign key-nya
                ->references('id')->on('kategori_utama')
                ->onDelete('cascade');
            $table->string('nama', 50);
            $table->string('slug', 100);
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sub_kategori');
    }
};
