<?php

namespace App\Http\Controllers;

use App\Models\Wilayah;
use Illuminate\Http\Request;

class WilayahController extends Controller
{
    public function index(Request $request)
{
    $query = Wilayah::query();

    // Fitur pencarian berdasarkan kode, provinsi, atau kabupaten/kota
    if ($request->filled('cari')) {
        $keyword = $request->cari;
        $query->where('provinsi', 'like', '%' . $keyword . '%')
              ->orWhere('kabupaten_kota', 'like', '%' . $keyword . '%')
              ->orWhere('kode_wilayah', 'like', '%' . $keyword . '%');
    }

    $wilayah = $query->orderBy('provinsi')
        ->paginate(10)
        ->withQueryString();

    return view('wilayah.index', compact('wilayah'));

    }

    public function create()
    {
        // Mengirim data wilayah untuk pilihan induk (jika di form menggunakan select induk)
        $induk = Wilayah::orderBy('provinsi')->get();
        return view('wilayah.create', compact('induk'));
    }

    public function store(Request $request)
    {
        // Validasi input dari form web
        $request->validate([
            'kode_wilayah' => ['required', 'string', 'max:50', 'unique:wilayah,kode_wilayah'],
            'nama_wilayah' => ['required', 'string', 'max:150'],
            'level'        => ['required'],
        ]);

        $wilayah = new Wilayah();
        $wilayah->kode_wilayah   = $request->input('kode_wilayah');
        
        // Memetakan input nama_wilayah ke kolom database (provinsi & kabupaten_kota)
        $wilayah->provinsi       = $request->input('nama_wilayah');
        $wilayah->kabupaten_kota = $request->input('level') == 1 ? '-' : $request->input('nama_wilayah');
        
        // Otomatis mengisi tahun dan parent_id agar struktur database terpenuhi
        $wilayah->tahun          = 2026;
        $wilayah->parent_id      = $request->input('parent_id') ?: null;
        
        // Simpan ke database phpMyAdmin
        $wilayah->save();

        return redirect()
            ->route('wilayah.index')
            ->with('success', 'Data wilayah berhasil ditambahkan!');
    }
}