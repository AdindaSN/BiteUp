<?php

namespace App\Http\Controllers;

use App\Models\DataIndikator;
use App\Models\HasilIkp;
use App\Models\Indikator;
use App\Models\Wilayah;
use Illuminate\Http\Request;

class PrioritasWilayahController extends Controller
{
    public function index(Request $request)
    {
        $daftarTahun = DataIndikator::select('tahun')
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->pluck('tahun');

        if ($daftarTahun->isEmpty()) {
            $daftarTahun = collect([2025]);
        }

        $tahun = $request->input('tahun', $daftarTahun->first() ?? 2025);
        $provinsi = $request->input('provinsi');
        $prioritasFilter = $request->input('prioritas');

        $provinsiList = Wilayah::select('provinsi')
            ->whereNotNull('provinsi')
            ->where('provinsi', '!=', '')
            ->distinct()
            ->orderBy('provinsi')
            ->pluck('provinsi');

        $query = HasilIkp::with('wilayah')->where('tahun', $tahun);

        if ($provinsi) {
            $query->whereHas('wilayah', function ($q) use ($provinsi) {
                $q->where('provinsi', $provinsi);
            });
        }

        if ($prioritasFilter) {
            $query->where('kategori_prioritas', 'like', "Prioritas {$prioritasFilter}%");
        }

        $hasilPrioritas = $query->orderBy('kategori_prioritas', 'asc')
            ->orderBy('skor_ikp', 'asc')
            ->paginate(20)
            ->withQueryString();

        // Ringkasan jumlah wilayah per kategori prioritas (1 s/d 6) dari kolom kategori_prioritas
        $rawRingkasan = HasilIkp::where('tahun', $tahun)
            ->selectRaw('kategori_prioritas, count(*) as total')
            ->groupBy('kategori_prioritas')
            ->pluck('total', 'kategori_prioritas');

        $ringkasanPrioritas = [];
        for ($i = 1; $i <= 6; $i++) {
            $ringkasanPrioritas[$i] = 0;
            foreach ($rawRingkasan as $kat => $total) {
                $teksKat = (string) $kat;
                if (str_starts_with($teksKat, "Prioritas $i") || $teksKat === (string) $i) {
                    $ringkasanPrioritas[$i] += $total;
                }
            }
        }

        return view('prioritas-wilayah.index', compact(
            'hasilPrioritas',
            'daftarTahun',
            'provinsiList',
            'tahun',
            'provinsi',
            'prioritasFilter',
            'ringkasanPrioritas'
        ));
    }

    public function prosesPengelompokan(Request $request)
    {
        $tahun = $request->input('tahun', 2025);

        // Cari data dari indikator IKP jika ada, atau hitung rata-rata indikator per wilayah
        $indikatorIkp = Indikator::where('kode', 'IKP')
            ->orWhere('nama_indikator', 'like', '%IKP%')
            ->first();

        if ($indikatorIkp) {
            $dataWilayahSkor = DataIndikator::where('id_indikator', $indikatorIkp->id_indikator)
                ->where('tahun', $tahun)
                ->selectRaw('id_wilayah, nilai as skor')
                ->get();
        } else {
            // Jika tidak ada kode indikator khusus 'IKP', gunakan komposit nilai dari indikator yang ada
            $dataWilayahSkor = DataIndikator::where('tahun', $tahun)
                ->selectRaw('id_wilayah, AVG(nilai) as skor')
                ->groupBy('id_wilayah')
                ->get();
        }

        if ($dataWilayahSkor->isEmpty()) {
            return redirect()->back()->with('error', "Data indikator untuk tahun $tahun belum tersedia.");
        }

        foreach ($dataWilayahSkor as $item) {
            $skor = round((float) $item->skor, 2);

            // Cut-off 6 Kategori Prioritas sesuai tabel standar FSVA
            if ($skor < 45.60) {
                $label = 'Prioritas 1 - Sangat Rentan';
            } elseif ($skor <= 53.42) {
                $label = 'Prioritas 2 - Rentan';
            } elseif ($skor <= 61.47) {
                $label = 'Prioritas 3 - Agak Rentan';
            } elseif ($skor <= 69.52) {
                $label = 'Prioritas 4 - Agak Tahan';
            } elseif ($skor <= 77.35) {
                $label = 'Prioritas 5 - Tahan';
            } else {
                $label = 'Prioritas 6 - Sangat Tahan';
            }

            HasilIkp::updateOrCreate(
                [
                    'id_wilayah' => $item->id_wilayah,
                    'tahun'      => $tahun,
                ],
                [
                    'skor_ikp'           => $skor,
                    'kategori_prioritas' => $label,
                ]
            );
        }

        return redirect()->route('prioritas-wilayah.index', ['tahun' => $tahun])
            ->with('success', "Berhasil mengelompokkan {$dataWilayahSkor->count()} wilayah ke dalam 6 kategori prioritas untuk tahun $tahun!");
    }
}