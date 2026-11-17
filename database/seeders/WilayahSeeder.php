<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WilayahSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('wilayah')->updateOrInsert(
            ['id_wilayah' => 1],
            ['nama_wilayah' => 'Wilayah Pusat / Nasional'] // Sesuaikan nama kolom jika di tabel wilayah Anda berbeda (misal 'nama' atau 'nama_wilayah')
        );
    }
}
