<?php

namespace App\Http\Controllers;

use App\Models\ProgressBaca;
use App\Models\Bookmark;
use Illuminate\Http\Request;

class PerpustakaanSayaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = auth()->user();

        // Sedang dibaca
        $sedangDibaca = ProgressBaca::where('user_id', $user->id)
            ->where('selesai', false)
            ->with('buku.kategoriUtama')
            ->latest('terakhir_baca')
            ->get();

        // Selesai dibaca
        $selesaiDibaca = ProgressBaca::where('user_id', $user->id)
            ->where('selesai', true)
            ->with('buku.kategoriUtama')
            ->latest('updated_at')
            ->get();

        // Bookmark
        $bookmark = Bookmark::where('user_id', $user->id)
            ->with('buku')
            ->latest()
            ->limit(10)
            ->get();

        // Statistik
        $stats = [
            'total_dibaca' => ProgressBaca::where('user_id', $user->id)->count(),
            'selesai_dibaca' => ProgressBaca::where('user_id', $user->id)->where('selesai', true)->count(),
            'total_bookmark' => Bookmark::where('user_id', $user->id)->count(),
            'waktu_baca_total' => ProgressBaca::where('user_id', $user->id)->sum('waktu_baca'),
        ];

        return view('perpustakaan-saya', compact('sedangDibaca', 'selesaiDibaca', 'bookmark', 'stats'));
    }
}
