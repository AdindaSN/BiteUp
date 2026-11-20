<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Wilayah;

class WilayahSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Contoh data Provinsi (Level 1)
        $jabar = Wilayah::create([
            'kode_wilayah' => '32',
            'provinsi' => 'Jawa Barat',
            'kabupaten_kota' => '-',
            'tahun' => 2026,
            'parent_id' => null,
        ]);

        // Contoh data Kabupaten/Kota di bawah Jawa Barat (Level 2)
        Wilayah::create([
            'kode_wilayah' => '3273',
            'provinsi' => 'Jawa Barat',
            'kabupaten_kota' => 'Kota Bandung',
            'tahun' => 2026,
            'parent_id' => $jabar->id_wilayah,
        ]);
    }
}