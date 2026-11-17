<?php

namespace App\Http\Controllers;

use App\Models\KelompokSasaran;
use Illuminate\Http\Request;

class KelompokSasaranController extends Controller
{
    public function index(Request $request)
    {
        $kelompok = KelompokSasaran::with(['jenisKelompok', 'wilayah'])
            ->when($request->q, function ($query, $q) {
                $query->where(function ($sub) use ($q) {
                    $sub->whereHas('jenisKelompok', fn ($j) => $j->where('nama_jenis', 'like', "%{$q}%"))
                        ->orWhereHas('wilayah', fn ($w) => $w->where('nama_wilayah', 'like', "%{$q}%"));
                });
            })
            ->orderBy('id_kelompok_sasaran')
            ->paginate(10)
            ->onEachSide(1)
            ->withQueryString();

        return view('kelompok-sasaran.index', compact('kelompok'));
    }
}