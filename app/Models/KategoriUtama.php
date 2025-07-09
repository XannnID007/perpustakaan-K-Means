<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriUtama extends Model
{
    use HasFactory;

    protected $table = 'kategori_utama';

    protected $fillable = [
        'nama',
        'slug',
        'deskripsi'
    ];

    public function subKategori()
    {
        return $this->hasMany(SubKategori::class);
    }

    public function buku()
    {
        return $this->hasMany(Buku::class);
    }
}
