<?php

namespace Database\Seeders;

use App\Models\JenisKelompok;
use App\Models\KelompokSasaran;
use App\Models\Wilayah;
use Illuminate\Database\Seeder;

class KelompokSasaranSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Jenis kelompok (sesuai mockup)
        $jenis = ['Rumah Tangga Miskin', 'Ibu Hamil', 'Balita', 'Anak Sekolah', 'Lansia'];
        foreach ($jenis as $nama) {
            JenisKelompok::firstOrCreate(['nama_jenis' => $nama]);
        }

        // 2. Wilayah dari CSV (hasil Excel FSVA)
        $lines = file(database_path('seeders/data/wilayah.csv'), FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $lines[0] = preg_replace('/^\xEF\xBB\xBF/', '', $lines[0]); // buang BOM kalau ada
        $delimiter = str_contains($lines[0], ';') ? ';' : ',';
        array_shift($lines); // lewati header

        $rows = [];
        foreach ($lines as $line) {
            $r = str_getcsv($line, $delimiter);
            if (count($r) >= 3) {
                $rows[] = array_pad($r, 4, '');
            }
        }

        // provinsi dulu (level 1), baru kab/kota (level 2)
        foreach ($rows as [$kode, $nama, $level, $kodeParent]) {
            if ((int) $level === 1) {
                Wilayah::firstOrCreate(['kode_wilayah' => $kode], ['nama_wilayah' => $nama, 'level' => 1]);
            }
        }
        foreach ($rows as [$kode, $nama, $level, $kodeParent]) {
            if ((int) $level === 2) {
                $parent = Wilayah::where('kode_wilayah', $kodeParent)->first();
                Wilayah::firstOrCreate(
                    ['kode_wilayah' => $kode],
                    ['nama_wilayah' => $nama, 'level' => 2, 'parent_id' => $parent?->id_wilayah]
                );
            }
        }

        // 3. Data dummy kelompok sasaran per provinsi (hanya untuk testing)
        $idJenis = JenisKelompok::pluck('id_jenis_kelompok');
        foreach (Wilayah::where('level', 1)->get() as $prov) {
            foreach ($idJenis as $id) {
                KelompokSasaran::firstOrCreate(
                    ['id_wilayah' => $prov->id_wilayah, 'id_jenis_kelompok' => $id, 'tahun' => 2025],
                    ['jumlah' => rand(10000, 500000)]
                );
            }
        }
    }
}