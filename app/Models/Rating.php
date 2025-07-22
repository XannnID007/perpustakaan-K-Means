<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    use HasFactory;

    protected $table = 'rating';

    protected $fillable = [
        'user_id',
        'buku_id',
        'rating',
        'ulasan'
    ];

    protected $casts = [
        'rating' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Validasi rating harus 1-5
    public function setRatingAttribute($value)
    {
        $this->attributes['rating'] = max(1, min(5, (int) $value));
    }

    // Accessor untuk format tanggal
    public function getFormattedDateAttribute()
    {
        return $this->created_at->format('d M Y');
    }

    // Scope untuk rating tinggi
    public function scopeHighRating($query, $minRating = 4)
    {
        return $query->where('rating', '>=', $minRating);
    }

    // Scope untuk rating dengan ulasan
    public function scopeWithReview($query)
    {
        return $query->whereNotNull('ulasan')->where('ulasan', '!=', '');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function buku()
    {
        return $this->belongsTo(Buku::class);
    }
}
