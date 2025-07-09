<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use App\Models\User;
use App\Models\ProgressBaca;
use App\Models\KategoriUtama;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    public function dashboard()
    {
        $stats = [
            'total_buku' => Buku::count(),
            'total_pengguna' => User::where('role', 'user')->count(),
            'total_kategori' => KategoriUtama::count(),
            'buku_dibaca_bulan_ini' => ProgressBaca::whereMonth('created_at', now()->month)->count(),
        ];

        $bukuPopuler = Buku::orderBy('total_pembaca', 'desc')->limit(5)->get();
        $penggunaAktif = User::where('role', 'user')
            ->whereDate('terakhir_aktif', '>=', now()->subDays(7))
            ->limit(5)->get();

        return view('admin.dashboard', compact('stats', 'bukuPopuler', 'penggunaAktif'));
    }
}
