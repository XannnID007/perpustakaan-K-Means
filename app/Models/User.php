<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'terakhir_aktif'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'terakhir_aktif' => 'datetime',
        'password' => 'hashed',
    ];

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

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function getProgressForBook($bukuId)
    {
        return $this->progressBaca()->where('buku_id', $bukuId)->first();
    }

    public function canAccessBook(Buku $buku)
    {
        return $buku->aktif;
    }
}
