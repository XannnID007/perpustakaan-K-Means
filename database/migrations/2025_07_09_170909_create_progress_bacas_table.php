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
        Schema::create('progress_baca', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('buku_id')->constrained('buku')->onDelete('cascade');
            $table->integer('halaman_sekarang')->default(1);
            $table->integer('total_halaman');
            $table->decimal('persentase_baca', 5, 2)->default(0.00);
            $table->timestamp('terakhir_baca')->nullable();
            $table->integer('waktu_baca')->default(0); // dalam detik
            $table->boolean('selesai')->default(false);
            $table->timestamps();

            $table->unique(['user_id', 'buku_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('progress_baca');
    }
};
