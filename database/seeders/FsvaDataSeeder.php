<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class FsvaDataSeeder extends Seeder
{
    public function run(): void
    {
        $filePath = database_path('seeders/data/FSVA_Data_Nasional_2025.csv');

        if (!file_exists($filePath)) {
            $this->command->error("File CSV tidak ditemukan di: $filePath");
            return;
        }

        $role = DB::table('roles')->where('nama_role', 'Petugas Data Wilayah')->first();
        $roleId = $role ? $role->id_roles : DB::table('roles')->insertGetId([
            'nama_role' => 'Petugas Data Wilayah',
        ]);

        $user = DB::table('users')->where('email', 'petugas@biteup.id')->first();
        $userId = $user ? $user->id_user : DB::table('users')->insertGetId([
            'id_roles'   => $roleId,
            'id_wilayah' => null,
            'nama'       => 'Petugas Data Wilayah',
            'email'      => 'petugas@biteup.id',
            'password'   => Hash::make('password123'),
        ]);

        $masterIndikator = [
            ['kode' => 'IKP',       'nama_indikator' => 'Indeks Ketahanan Pangan (IKP)',              'dimensi' => 'Komposit FSVA',        'satuan' => 'Skor',  'bobot' => 100.00, 'col' => 28],
            ['kode' => 'STUNTING',  'nama_indikator' => 'Prevalensi Balita Stunting',                 'dimensi' => 'Pemanfaatan Pangan',   'satuan' => '%',     'bobot' => 10.00,  'col' => 26],
            ['kode' => 'MISKIN',    'nama_indikator' => 'Persentase Penduduk Miskin',                 'dimensi' => 'Keterjangkauan Pangan','satuan' => '%',     'bobot' => 15.00,  'col' => 12],
            ['kode' => 'NCPR',      'nama_indikator' => 'Rasio Konsumsi Normatif per Kapita (NCPR)',  'dimensi' => 'Ketersediaan Pangan',  'satuan' => 'Rasio', 'bobot' => 10.00,  'col' => 4],
            ['kode' => 'ENERGI',    'nama_indikator' => 'Rasio Ketersediaan Energi per Kapita',       'dimensi' => 'Ketersediaan Pangan',  'satuan' => '%',     'bobot' => 10.00,  'col' => 6],
            ['kode' => 'PROHE',     'nama_indikator' => 'Rasio Ketersediaan Protein Hewani',          'dimensi' => 'Ketersediaan Pangan',  'satuan' => '%',     'bobot' => 10.00,  'col' => 8],
            ['kode' => 'CBPK',      'nama_indikator' => 'Rasio Cadangan Beras Pemerintah (CBPK)',     'dimensi' => 'Ketersediaan Pangan',  'satuan' => '%',     'bobot' => 5.00,   'col' => 10],
            ['kode' => 'CVHARGA',   'nama_indikator' => 'Koefisien Variasi Harga Pangan',             'dimensi' => 'Keterjangkauan Pangan','satuan' => '%',     'bobot' => 5.00,   'col' => 14],
            ['kode' => 'POU',       'nama_indikator' => 'Prevalence of Undernourishment (PoU)',       'dimensi' => 'Keterjangkauan Pangan','satuan' => '%',     'bobot' => 10.00,  'col' => 16],
            ['kode' => 'RLSP',      'nama_indikator' => 'Rata-Rata Lama Sekolah Perempuan >15 Thn',   'dimensi' => 'Pemanfaatan Pangan',   'satuan' => 'Tahun', 'bobot' => 5.00,   'col' => 18],
            ['kode' => 'TNPAIR',    'nama_indikator' => 'Persentase RT Tanpa Akses Air Bersih',       'dimensi' => 'Pemanfaatan Pangan',   'satuan' => '%',     'bobot' => 10.00,  'col' => 20],
            ['kode' => 'AMANPANGN', 'nama_indikator' => 'Persentase Keamanan Pangan Memenuhi Standar','dimensi' => 'Pemanfaatan Pangan',   'satuan' => '%',     'bobot' => 5.00,   'col' => 22],
            ['kode' => 'PPH',       'nama_indikator' => 'Skor Pola Pangan Harapan (PPH) Konsumsi',    'dimensi' => 'Pemanfaatan Pangan',   'satuan' => 'Skor',  'bobot' => 5.00,   'col' => 24],
        ];

        $indikatorMap = [];
        foreach ($masterIndikator as $ind) {
            $existing = DB::table('indikator')->where('kode', $ind['kode'])->first();
            $idInd = $existing ? $existing->id_indikator : DB::table('indikator')->insertGetId([
                'kode'           => $ind['kode'],
                'nama_indikator' => $ind['nama_indikator'],
                'dimensi'        => $ind['dimensi'],
                'satuan'         => $ind['satuan'],
                'bobot'          => $ind['bobot'],
            ]);
            $indikatorMap[$ind['kode']] = ['id' => $idInd, 'col' => $ind['col']];
        }

        DB::table('data_indikator')->delete();

        $file = fopen($filePath, 'r');
        fgetcsv($file, 0, ',');
        fgetcsv($file, 0, ',');

        $provinsiMap = [];
        $now = now();
        $batchData = [];

        while (($row = fgetcsv($file, 0, ',')) !== false) {
            if (count($row) === 1 && str_contains($row[0], ';')) {
                $row = str_getcsv($row[0], ';');
            }
            if (empty($row[0]) || $row[0] === 'KDPKAB') {
                continue;
            }

            $kodeKab  = trim($row[0]);
            $namaProv = trim($row[1]);
            $namaKab  = trim($row[2]);
            $tahun    = (int) $row[3];

            if (!isset($provinsiMap[$namaProv])) {
                $prov = DB::table('wilayah')->where('nama_wilayah', $namaProv)->where('level', 1)->first();
                $provinsiMap[$namaProv] = $prov ? $prov->id_wilayah : DB::table('wilayah')->insertGetId([
                    'parent_id'    => null,
                    'kode_wilayah' => substr($kodeKab, 0, 2),
                    'nama_wilayah' => $namaProv,
                    'level'        => 1,
                ]);
            }

            $kab = DB::table('wilayah')->where('kode_wilayah', $kodeKab)->first();
            $idWilayah = $kab ? $kab->id_wilayah : DB::table('wilayah')->insertGetId([
                'parent_id'    => $provinsiMap[$namaProv],
                'kode_wilayah' => $kodeKab,
                'nama_wilayah' => $namaKab,
                'level'        => 2,
            ]);

            foreach ($indikatorMap as $meta) {
                $nilai = (float) str_replace(',', '.', $row[$meta['col']] ?? 0);
                $batchData[] = [
                    'id_wilayah'   => $idWilayah,
                    'id_indikator' => $meta['id'],
                    'id_user'      => $userId,
                    'tahun'        => $tahun,
                    'nilai'        => $nilai,
                    'sumber_data'  => 'FSVA Nasional 2025',
                    'waktu_update' => $now,
                ];
            }

            if (count($batchData) >= 650) {
                DB::table('data_indikator')->insert($batchData);
                $batchData = [];
            }
        }

        if (!empty($batchData)) {
            DB::table('data_indikator')->insert($batchData);
        }

        fclose($file);
        $this->command->info('Berhasil mengimpor 514 Kabupaten/Kota dan 13 Indikator FSVA 2025!');
    }
}