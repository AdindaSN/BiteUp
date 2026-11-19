<?php

namespace App\Http\Controllers;

use App\Models\Wilayah;
use Illuminate\Http\Request;

class WilayahController extends Controller
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

        if ($request->filled('jenis')) {
            if ($request->jenis === 'provinsi') {
                $query->where(function ($q) {
                    $q->whereNull('kabupaten_kota')
                      ->orWhere('kabupaten_kota', '-')
                      ->orWhere('kabupaten_kota', '');
                });
            } elseif ($request->jenis === 'kabupaten_kota') {
                $query->whereNotNull('kabupaten_kota')
                      ->where('kabupaten_kota', '!=', '-')
                      ->where('kabupaten_kota', '!=', '');
            }
        }

        $wilayah = $query->orderBy('kode_wilayah')
            ->orderBy('provinsi')
            ->paginate(10)
            ->withQueryString();

        return view('wilayah.index', compact('wilayah'));
    }

    public function create()
    {
        $induk = Wilayah::orderBy('provinsi')->get();
        return view('wilayah.create', compact('induk'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_wilayah' => ['required', 'string', 'max:50', 'unique:wilayah,kode_wilayah'],
            'nama_wilayah' => ['required', 'string', 'max:150'],
            'level'        => ['required'],
        ]);

        $wilayah = new Wilayah();
        $wilayah->kode_wilayah   = $request->input('kode_wilayah');
        $wilayah->provinsi       = $request->input('nama_wilayah');
        $wilayah->kabupaten_kota = $request->input('level') == 1 ? '-' : $request->input('nama_wilayah');
        $wilayah->tahun          = 2026;
        $wilayah->parent_id      = $request->input('parent_id') ?: null;
        $wilayah->save();

        return redirect()
            ->route('wilayah.index')
            ->with('success', 'Data wilayah berhasil ditambahkan!');
    }
}