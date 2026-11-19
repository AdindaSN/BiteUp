<?php

namespace App\Http\Controllers;

use App\Models\JenisKelompok;
use App\Models\KelompokSasaran;
use App\Models\Wilayah;
use Illuminate\Http\Request;

class KelompokSasaranController extends Controller
{
    public function index(Request $request)
    {
        $kelompok = KelompokSasaran::with(['jenisKelompok', 'wilayah'])
            ->when($request->q, function ($query, $q) {
                $query->where(function ($sub) use ($q) {
                    $sub->whereHas('jenisKelompok', fn ($j) => $j->where('nama_jenis', 'like', "%{$q}%"))
                        ->orWhereHas('wilayah', function ($w) use ($q) {
                            $w->where('kabupaten_kota', 'like', "%{$q}%")
                              ->orWhere('provinsi', 'like', "%{$q}%");
                        });
                });
            })
            ->orderBy('id_kelompok_sasaran', 'desc')
            ->paginate(10)
            ->onEachSide(1)
            ->withQueryString();

        return view('kelompok-sasaran.index', compact('kelompok'));
    }

    public function create()
    {
        $daftarWilayah = Wilayah::orderBy('kabupaten_kota')->get();
        $daftarJenis   = JenisKelompok::orderBy('nama_jenis')->get();

        return view('kelompok-sasaran.create', compact('daftarWilayah', 'daftarJenis'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_wilayah'        => ['required', 'exists:wilayah,id_wilayah'],
            'id_jenis_kelompok' => ['required', 'exists:jenis_kelompok,id_jenis_kelompok'],
            'tahun'             => ['required', 'integer', 'min:2000', 'max:2100'],
            'jumlah'            => ['required', 'integer', 'min:1'],
        ], [
            'id_wilayah.required'        => 'Wilayah wajib dipilih.',
            'id_jenis_kelompok.required' => 'Jenis kelompok sasaran wajib dipilih.',
            'tahun.required'             => 'Tahun pendataan wajib diisi.',
            'tahun.integer'              => 'Tahun harus berupa angka yang valid.',
            'jumlah.required'            => 'Jumlah populasi kelompok sasaran wajib diisi.',
            'jumlah.min'                 => 'Jumlah minimal adalah 1.',
        ]);

        KelompokSasaran::create($validated);

        return redirect()
            ->route('kelompok-sasaran.index')
            ->with('success', 'Data kelompok sasaran berhasil ditambahkan.');
    }

    public function edit(KelompokSasaran $kelompokSasaran)
    {
        $daftarWilayah = Wilayah::orderBy('kabupaten_kota')->get();
        $daftarJenis   = JenisKelompok::orderBy('nama_jenis')->get();

        return view('kelompok-sasaran.edit', compact('kelompokSasaran', 'daftarWilayah', 'daftarJenis'));
    }

    public function update(Request $request, KelompokSasaran $kelompokSasaran)
    {
        $validated = $request->validate([
            'id_wilayah'        => ['required', 'exists:wilayah,id_wilayah'],
            'id_jenis_kelompok' => ['required', 'exists:jenis_kelompok,id_jenis_kelompok'],
            'tahun'             => ['required', 'integer', 'min:2000', 'max:2100'],
            'jumlah'            => ['required', 'integer', 'min:1'],
        ], [
            'id_wilayah.required'        => 'Wilayah wajib dipilih.',
            'id_jenis_kelompok.required' => 'Jenis kelompok sasaran wajib dipilih.',
            'tahun.required'             => 'Tahun pendataan wajib diisi.',
            'tahun.integer'              => 'Tahun harus berupa angka yang valid.',
            'jumlah.required'            => 'Jumlah populasi kelompok sasaran wajib diisi.',
            'jumlah.min'                 => 'Jumlah minimal adalah 1.',
        ]);

        $kelompokSasaran->update($validated);

        return redirect()
            ->route('kelompok-sasaran.index')
            ->with('success', 'Data kelompok sasaran berhasil diperbarui.');
    }

    public function destroy(KelompokSasaran $kelompokSasaran)
    {
        if ($kelompokSasaran->programSasaran()->exists()) {
            return redirect()
                ->route('kelompok-sasaran.index')
                ->with('error', 'Data kelompok sasaran tidak dapat dihapus karena masih digunakan pada data Target Program.');
        }

        $kelompokSasaran->delete();

        return redirect()
            ->route('kelompok-sasaran.index')
            ->with('success', 'Data kelompok sasaran berhasil dihapus.');
    }