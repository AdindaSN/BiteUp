<?php

namespace App\Http\Controllers;

use App\Models\Wilayah;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class WilayahController extends Controller
{
    // Daftar wilayah, dengan pencarian nama dan filter jenis
    public function index(Request $request)
    {
        $query = Wilayah::with('induk');

        if ($request->filled('cari')) {
            $query->where('nama_wilayah', 'like', '%' . $request->cari . '%');
        }

        if ($request->filled('level')) {
            $query->where('level', $request->level);
        }

        $wilayah = $query->orderBy('nama_wilayah')
            ->paginate(10)
            ->withQueryString();

        return view('wilayah.index', compact('wilayah'));
    }

    public function create()
    {
        $induk = Wilayah::orderBy('nama_wilayah')->get();

        return view('wilayah.create', compact('induk'));
    }

    public function store(Request $request)
    {
        $request->merge([
            'jumlah_penduduk' => $this->bersihkanAngka($request->input('jumlah_penduduk')),
        ]);

        $validated = $request->validate([
            'kode_wilayah'    => ['required', 'string', 'max:50', 'unique:wilayah,kode_wilayah'],
            'nama_wilayah'    => ['required', 'string', 'max:150'],
            'level'           => ['required', Rule::in(array_keys(Wilayah::LEVEL))],
            'parent_id'       => [Rule::requiredIf(fn () => (int) $request->level !== 1), 'nullable', 'exists:wilayah,id_wilayah'],
            'jumlah_penduduk' => ['required', 'integer', 'min:0'],
            'latitude'        => ['nullable', 'numeric', 'between:-90,90'],
            'longitude'       => ['nullable', 'numeric', 'between:-180,180'],
        ], [
            'kode_wilayah.required'    => 'Kode wilayah wajib diisi.',
            'kode_wilayah.unique'      => 'Kode wilayah sudah dipakai.',
            'nama_wilayah.required'    => 'Nama wilayah wajib diisi.',
            'level.required'           => 'Jenis wilayah wajib dipilih.',
            'level.in'                 => 'Jenis wilayah tidak valid.',
            'parent_id.required'       => 'Wilayah induk wajib dipilih.',
            'parent_id.exists'         => 'Wilayah induk tidak ditemukan.',
            'jumlah_penduduk.required' => 'Jumlah penduduk wajib diisi.',
            'jumlah_penduduk.integer'  => 'Jumlah penduduk harus berupa angka.',
            'jumlah_penduduk.min'      => 'Jumlah penduduk tidak boleh negatif.',
            'latitude.numeric'         => 'Latitude harus berupa angka.',
            'latitude.between'         => 'Latitude harus antara -90 dan 90.',
            'longitude.numeric'        => 'Longitude harus berupa angka.',
            'longitude.between'        => 'Longitude harus antara -180 dan 180.',
        ]);

        if ((int) $validated['level'] === 1) {
            $validated['parent_id'] = null;
        }

        Wilayah::create($validated);

        return redirect()
            ->route('wilayah.index')
            ->with('success', 'Wilayah berhasil ditambahkan.');
    }

    private function bersihkanAngka($nilai)
    {
        $nilai = trim((string) $nilai);

        if (preg_match('/^\d{1,3}([.,]\d{3})+$/', $nilai)) {
            return str_replace(['.', ','], '', $nilai);
        }

        return $nilai;
    }
}