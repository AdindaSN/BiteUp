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
        $tahun = $request->input('tahun', 2025);
        $provinsiId = $request->input('provinsi_id');
        $wilayahId = $request->input('wilayah_id');
        $indikatorId = $request->input('indikator_id');

        $daftarTahun = DataIndikator::select('tahun')->distinct()->orderBy('tahun', 'desc')->pluck('tahun');
        
        // Menggunakan alias agar sesuai dengan variabel di view Blade (provinsiList, kabupatenList, indikatorList)
        $provinsiList = Wilayah::where('level', 1)->orderBy('nama_wilayah')->get();
        $kabupatenList = Wilayah::where('level', 2)
            ->when($provinsiId, fn($q) => $q->where('parent_id', $provinsiId))
            ->orderBy('nama_wilayah')
            ->get();
        $indikatorList = Indikator::orderBy('id_indikator')->get();

        $query = DataIndikator::with(['wilayah.parent', 'indikator'])
            ->where('tahun', $tahun);

        if ($wilayahId) {
            $query->where('id_wilayah', $wilayahId);
        } elseif ($provinsiId) {
            $query->whereHas('wilayah', fn($q) => $q->where('parent_id', $provinsiId));
        }

        if ($indikatorId) {
            $query->where('id_indikator', $indikatorId);
        }

        $dataIndikator = $query->orderBy('id_wilayah')->orderBy('id_indikator')->paginate(20)->withQueryString();

        return view('indikator-wilayah.index', compact(
            'dataIndikator',
            'daftarTahun',
            'provinsiList',
            'kabupatenList',
            'indikatorList',
            'tahun',
            'provinsiId',
            'wilayahId',
            'indikatorId'
        ));
    }
}