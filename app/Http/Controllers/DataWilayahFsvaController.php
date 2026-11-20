<?php

namespace App\Http\Controllers;

use App\Models\Wilayah;
use Illuminate\Http\Request;

class DataWilayahFsvaController extends Controller
{
    public function index(Request $request)
    {
        $query = Wilayah::query();

        if ($request->filled('cari')) {
            $keyword = $request->cari;
            $query->where(function ($q) use ($keyword) {
                $q->where('provinsi', 'like', '%' . $keyword . '%')
                  ->orWhere('kabupaten_kota', 'like', '%' . $keyword . '%')
                  ->orWhere('kode_wilayah', 'like', '%' . $keyword . '%');
            });
        }

        $wilayah = $query->orderBy('provinsi')
            ->paginate(10)
            ->withQueryString();

        return view('data-wilayah.index', compact('wilayah'));
    }
}