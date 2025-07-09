<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgressBaca extends Model
{
    use HasFactory;

    protected $table = 'progress_baca';

    protected $fillable = [
        'user_id',
        'buku_id',
        'halaman_sekarang',
        'total_halaman',
        'persentase_baca',
        'terakhir_baca',
        'waktu_baca',
        'selesai'
    ];

    protected $casts = [
        'terakhir_baca' => 'datetime',
        'selesai' => 'boolean',
        'persentase_baca' => 'decimal:2'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function buku()
    {
        return $this->belongsTo(Buku::class);
    }
}
