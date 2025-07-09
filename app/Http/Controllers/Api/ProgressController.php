<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ProgressBaca;
use Illuminate\Http\Request;

class ProgressController extends Controller
{
    public function save(Request $request)
    {
        $request->validate([
            'buku_id' => 'required|exists:buku,id',
            'halaman_sekarang' => 'required|integer|min:1',
            'waktu_baca_tambahan' => 'nullable|integer|min:0',
        ]);

        $progress = ProgressBaca::where('user_id', auth()->id())
            ->where('buku_id', $request->buku_id)
            ->first();

        if ($progress) {
            $progress->update([
                'halaman_sekarang' => $request->halaman_sekarang,
                'persentase_baca' => ($request->halaman_sekarang / $progress->total_halaman) * 100,
                'terakhir_baca' => now(),
                'waktu_baca' => $progress->waktu_baca + ($request->waktu_baca_tambahan ?? 0),
                'selesai' => $request->halaman_sekarang >= $progress->total_halaman,
            ]);
        }

        return response()->json(['success' => true]);
    }
}
