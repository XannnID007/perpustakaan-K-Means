<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Buku extends Model
{
    use HasFactory;

    protected $table = 'buku';

    protected $fillable = [
        'judul',
        'penulis',
        'deskripsi',
        'kategori_utama_id',
        'sub_kategori_id',
        'gambar_sampul',
        'file_pdf',
        'ukuran_file',
        'total_halaman',
        'rating_rata_rata',
        'total_pembaca',
        'tahun_terbit',
        'aktif'
    ];

    protected $casts = [
        'aktif' => 'boolean',
        'rating_rata_rata' => 'decimal:2'
    ];

    public function kategoriUtama()
    {
        return $this->belongsTo(KategoriUtama::class);
    }

    public function subKategori()
    {
        return $this->belongsTo(SubKategori::class);
    }

    public function progressBaca()
    {
        return $this->hasMany(ProgressBaca::class);
    }

    public function bookmark()
    {
        return $this->hasMany(Bookmark::class);
    }

    public function rating()
    {
        return $this->hasMany(Rating::class);
    }

    public function getGambarSampulUrlAttribute()
    {
        return $this->gambar_sampul ? asset('storage/covers/' . $this->gambar_sampul) : asset('images/default-book-cover.jpg');
    }

    public function getFilePdfUrlAttribute()
    {
        return route('pdf.stream', $this->id);
    }
}
