<?php

namespace App\Http\Controllers;

use App\Models\HasilIkp;
use App\Models\Wilayah;
use Illuminate\Http\Request;

class HasilIdentifikasiController extends Controller
{
    public function index(Request $request)
    {
        // Daftar tahun yang tersedia di hasil_ikp untuk dropdown filter tahun
        $daftarTahun = HasilIkp::select('tahun')
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->pluck('tahun')
            ->toArray();

        if (empty($daftarTahun)) {
            $daftarTahun = [(int) date('Y')];
        }

        $tahunDipilih = (int) $request->input('tahun', $daftarTahun[0]);
        if (!in_array($tahunDipilih, $daftarTahun, true)) {
            $tahunDipilih = $daftarTahun[0];
        }

        // Daftar provinsi untuk filter tambahan
        $daftarProvinsi = Wilayah::whereNotNull('provinsi')
            ->where('provinsi', '!=', '')
            ->distinct()
            ->orderBy('provinsi')
            ->pluck('provinsi');

        // Helper klasifikasi 6 kategori prioritas FSVA berdasarkan skor IKP
        $klasifikasi = function ($skor, $kategoriDb = null): array {
            $s = (float) $skor;
            $level = $s < 45.60 ? 1
                : ($s <= 53.42 ? 2
                : ($s <= 61.47 ? 3
                : ($s <= 69.52 ? 4
                : ($s <= 77.35 ? 5 : 6))));

            $labelDefault = [
                1 => 'Prioritas 1 - Sangat Rentan',
                2 => 'Prioritas 2 - Rentan',
                3 => 'Prioritas 3 - Agak Rentan',
                4 => 'Prioritas 4 - Agak Tahan',
                5 => 'Prioritas 5 - Tahan',
                6 => 'Prioritas 6 - Sangat Tahan',
            ];

            $label = !empty($kategoriDb) ? $kategoriDb : ($labelDefault[$level] ?? 'Belum Diklasifikasi');

            return [$level, $label];
        };

        // Hitung ringkasan untuk tahun yang dipilih
        $dataTahunIni = HasilIkp::where('tahun', $tahunDipilih)->get();
        $totalWilayah = $dataTahunIni->count();
        $rataRataIkp = $totalWilayah > 0 ? round($dataTahunIni->avg('skor_ikp'), 2) : 0;
        $butuhPerhatian = $dataTahunIni->filter(function ($item) use ($klasifikasi) {
            [$level] = $klasifikasi($item->skor_ikp, $item->kategori_prioritas);
            return in_array($level, [1, 2], true);
        })->count();
        $agakRentan = $dataTahunIni->filter(function ($item) use ($klasifikasi) {
            [$level] = $klasifikasi($item->skor_ikp, $item->kategori_prioritas);
            return $level === 3;
        })->count();
        $tahanPangan = $dataTahunIni->filter(function ($item) use ($klasifikasi) {
            [$level] = $klasifikasi($item->skor_ikp, $item->kategori_prioritas);
            return in_array($level, [4, 5, 6], true);
        })->count();
        $waktuProsesTerakhir = $dataTahunIni->max('waktu_proses');

        // Query utama tabel hasil identifikasi
        $query = HasilIkp::with('wilayah')
            ->where('tahun', $tahunDipilih);

        if ($request->filled('provinsi')) {
            $provinsi = $request->input('provinsi');
            $query->whereHas('wilayah', function ($q) use ($provinsi) {
                $q->where('provinsi', $provinsi);
            });
        }

        if ($request->filled('prioritas')) {
            $prioritasFilter = $request->input('prioritas');
            if ($prioritasFilter === 'perhatian') {
                $query->where('skor_ikp', '<=', 53.42);
            } else {
                match ((int) $prioritasFilter) {
                    1 => $query->where('skor_ikp', '<', 45.60),
                    2 => $query->where('skor_ikp', '>=', 45.60)->where('skor_ikp', '<=', 53.42),
                    3 => $query->where('skor_ikp', '>', 53.42)->where('skor_ikp', '<=', 61.47),
                    4 => $query->where('skor_ikp', '>', 61.47)->where('skor_ikp', '<=', 69.52),
                    5 => $query->where('skor_ikp', '>', 69.52)->where('skor_ikp', '<=', 77.35),
                    6 => $query->where('skor_ikp', '>', 77.35),
                    default => null,
                };
            }
        }

        if ($request->filled('cari')) {
            $keyword = trim($request->input('cari'));
            $query->whereHas('wilayah', function ($q) use ($keyword) {
                $q->where('kabupaten_kota', 'like', "%{$keyword}%")
                  ->orWhere('provinsi', 'like', "%{$keyword}%")
                  ->orWhere('kode_wilayah', 'like', "%{$keyword}%");
            });
        }

        // Pengurutan (default: skor IKP terendah di atas agar wilayah paling butuh perhatian langsung terlihat)
        $urut = $request->input('urut', 'skor_asc');
        if ($urut === 'skor_desc') {
            $query->orderBy('skor_ikp', 'desc');
        } else {
            $query->orderBy('skor_ikp', 'asc');
        }

        $hasilIdentifikasi = $query->paginate(15)->withQueryString();

        // Sisipkan atribut level & label prioritas ke setiap baris agar Blade tinggal render badge CSS
        $hasilIdentifikasi->getCollection()->transform(function ($row) use ($klasifikasi) {
            [$level, $label] = $klasifikasi($row->skor_ikp, $row->kategori_prioritas);
            $row->level_prioritas = $level;
            $row->label_prioritas = $label;
            return $row;
        });

        return view('hasil-identifikasi.index', compact(
            'hasilIdentifikasi',
            'daftarTahun',
            'tahunDipilih',
            'daftarProvinsi',
            'totalWilayah',
            'butuhPerhatian',
            'agakRentan',
            'tahanPangan',
            'rataRataIkp',
            'waktuProsesTerakhir',
            'urut'
        ));
    }
}