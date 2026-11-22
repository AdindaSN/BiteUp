<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DataIndikator;
use App\Models\Indikator;
use App\Models\Wilayah;

class DataIndikatorController extends Controller
{
    /**
     * Method untuk PBI-005: Ringkasan Indikator Utama
     */
    public function ringkasan(Request $request)
    {
        $tahun = $request->input('tahun', date('Y'));
        $idWilayah = $request->input('id_wilayah');

        $query = DataIndikator::with(['indikator', 'wilayah'])
                    ->where('tahun', $tahun);

        if ($idWilayah) {
            $query->where('id_wilayah', $idWilayah);
        }

        $ringkasanIndikator = $query->get();
        $listWilayah = Wilayah::all();

        return view('indikator-wilayah.ringkasan', compact('ringkasanIndikator', 'listWilayah', 'tahun', 'idWilayah'));
    }

    /**
     * Method untuk PBI-006: Visualisasi Statistik dan Grafik Tren
     */
    public function grafikTren(Request $request)
    {
        $idIndikator = $request->input('id_indikator', 1); 
        $idWilayah   = $request->input('id_wilayah');       

        $query = DataIndikator::with(['indikator', 'wilayah'])
                    ->where('id_indikator', $idIndikator);

        if ($idWilayah) {
            $query->where('id_wilayah', $idWilayah);
        }

        $dataTren = $query->orderBy('tahun', 'asc')->get();

        $labels = $dataTren->pluck('tahun')->unique()->values();
        $values = $dataTren->pluck('nilai');

        if ($request->ajax()) {
            return response()->json([
                'labels' => $labels,
                'values' => $values,
                'data'   => $dataTren
            ]);
        }

        $listIndikator = Indikator::all();
        $listWilayah = Wilayah::all();

        return view('indikator-wilayah.grafik-tren', compact(
            'listIndikator', 
            'listWilayah', 
            'labels', 
            'values', 
            'idIndikator', 
            'idWilayah'
        ));
    }
}