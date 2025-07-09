<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubKategori extends Model
{
    use HasFactory;

    protected $table = 'sub_kategori';

    protected $fillable = [
        'kategori_utama_id',
        'nama',
        'slug',
        'deskripsi'
    ];

    public function kategoriUtama()
    {
        return $this->belongsTo(KategoriUtama::class);
    }

    public function buku()
    {
        return $this->hasMany(Buku::class);
    }
}
