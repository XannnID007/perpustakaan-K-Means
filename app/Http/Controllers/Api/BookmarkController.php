<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Bookmark;
use Illuminate\Http\Request;

class BookmarkController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'buku_id' => 'required|exists:buku,id',
            'nomor_halaman' => 'required|integer|min:1',
            'catatan' => 'nullable|string',
            'tipe' => 'in:bookmark,highlight,catatan',
        ]);

        $bookmark = Bookmark::create([
            'user_id' => auth()->id(),
            'buku_id' => $request->buku_id,
            'nomor_halaman' => $request->nomor_halaman,
            'catatan' => $request->catatan,
            'tipe' => $request->tipe ?? 'bookmark',
        ]);

        return response()->json(['success' => true, 'bookmark' => $bookmark]);
    }

    public function destroy(Bookmark $bookmark)
    {
        if ($bookmark->user_id !== auth()->id()) {
            abort(403);
        }

        $bookmark->delete();

        return response()->json(['success' => true]);
    }
}
