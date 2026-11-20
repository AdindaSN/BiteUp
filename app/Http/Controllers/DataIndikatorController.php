<?php

namespace App\Http\Controllers;

use App\Models\DataIndikator;
use App\Models\Indikator;
use App\Models\Wilayah;
use Illuminate\Http\Request;

class DataIndikatorController extends Controller
{
    public function index(Request $request)
    {
        $daftarTahun = DataIndikator::select('tahun')
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->pluck('tahun');

        $tahun = $request->input('tahun', $daftarTahun->first() ?? 2025);
        $provinsi = $request->input('provinsi');
        $wilayahId = $request->input('wilayah_id');
        $indikatorId = $request->input('indikator_id');

        // Ambil daftar nama provinsi unik dari kolom 'provinsi'
        $provinsiList = Wilayah::select('provinsi')
            ->whereNotNull('provinsi')
            ->where('provinsi', '!=', '')
            ->distinct()
            ->orderBy('provinsi')
            ->pluck('provinsi');

        // Ambil daftar kabupaten/kota berdasarkan provinsi yang dipilih
        $kabupatenList = Wilayah::when($provinsi, fn($q) => $q->where('provinsi', $provinsi))
            ->orderBy('kabupaten_kota')
            ->get();

        // Reset pilihan kabupaten/kota jika tidak cocok dengan provinsi yang sedang dipilih
        if ($wilayahId && !$kabupatenList->contains('id_wilayah', $wilayahId)) {
            $wilayahId = null;
        }

        $indikatorList = Indikator::orderBy('id_indikator')->get();

        $query = DataIndikator::with(['wilayah', 'indikator'])
            ->where('tahun', $tahun);

        if ($wilayahId) {
            $query->where('id_wilayah', $wilayahId);
        } elseif ($provinsi) {
            $query->whereHas('wilayah', fn($q) => $q->where('provinsi', $provinsi));
        }

        if ($indikatorId) {
            $query->where('id_indikator', $indikatorId);
        }

        $dataIndikator = $query
            ->orderBy('id_wilayah')
            ->orderBy('id_indikator')
            ->paginate(20)
            ->withQueryString();

        return view('indikator-wilayah.index', compact(
            'dataIndikator',
            'daftarTahun',
            'provinsiList',
            'kabupatenList',
            'indikatorList',
            'tahun',
            'provinsi',
            'wilayahId',
            'indikatorId'
        ));
    }
}