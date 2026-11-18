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
            [
                'kode_wilayah' => '00',
                'nama_wilayah' => 'Wilayah Pusat / Nasional',
                'level'        => 0,
            ]
        );
    }
}