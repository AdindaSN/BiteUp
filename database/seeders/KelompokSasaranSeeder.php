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

        // 2. Pakai wilayah yang sudah diisi FsvaDataSeeder (provinsi dulu, kalau tidak ada ambil 40 pertama)
        $wilayah = Wilayah::whereNull('parent_id')->get();
        if ($wilayah->isEmpty()) {
            $wilayah = Wilayah::limit(40)->get();
        }
        if ($wilayah->isEmpty()) {
            $this->command->warn('Tabel wilayah masih kosong. Jalankan db:seed (FsvaDataSeeder) dulu.');
            return;
        }

        // 3. Data dummy kelompok sasaran (hanya untuk testing tampilan)
        $idJenis = JenisKelompok::pluck('id_jenis_kelompok');
        foreach ($wilayah as $w) {
            foreach ($idJenis as $id) {
                KelompokSasaran::firstOrCreate(
                    ['id_wilayah' => $w->getKey(), 'id_jenis_kelompok' => $id, 'tahun' => 2025],
                    ['jumlah' => rand(10000, 500000)]
                );
            }
        }
    }
}