<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DataIndikator;
use App\Models\Wilayah;

class DataIndikatorController extends Controller
{
    // Tambahkan method ini untuk PBI-005 (Ringkasan Indikator Utama)
    public function ringkasan(Request $request)
    {
        // Ambil filter tahun (default tahun saat ini) dan wilayah dari request
        $tahun = $request->input('tahun', date('Y'));
        $idWilayah = $request->input('id_wilayah');

        // Query dasar mengambil data indikator beserta relasi wilayah dan master indikatornya
        $query = DataIndikator::with(['indikator', 'wilayah'])
            ->where('tahun', $tahun);

        // Jika user memilih filter wilayah tertentu
        if ($idWilayah) {
            $query->where('id_wilayah', $idWilayah);
        }

        // Eksekusi query
        $ringkasanIndikator = $query->get();

        // Ambil daftar wilayah untuk pilihan dropdown pada filter tampilan
        $listWilayah = Wilayah::all();

        // Kembalikan ke view dengan variabel yang lengkap
        return view('indikator-wilayah.ringkasan', compact('ringkasanIndikator', 'listWilayah', 'tahun', 'idWilayah'));
    }
    
    // ... (metode bawaan controller lainnya biarkan tetap ada)
}