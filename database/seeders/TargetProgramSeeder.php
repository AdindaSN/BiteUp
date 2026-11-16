<?php

namespace Database\Seeders;

use App\Models\JenisBantuan;
use App\Models\JenisKelompok;
use App\Models\KelompokSasaran;
use App\Models\Program;
use App\Models\ProgramSasaran;
use App\Models\User;
use App\Models\Wilayah;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TargetProgramSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Data Jenis Bantuan
        $bantuanList = [
            'Bantuan Pangan Beras CPP',
            'Bantuan Penanganan Stunting',
            'Bantuan Cadangan Pangan Pemerintah',
        ];

        foreach ($bantuanList as $namaBantuan) {
            DB::table('jenis_bantuan')->updateOrInsert(
                ['nama_bantuan' => $namaBantuan],
                ['nama_bantuan' => $namaBantuan]
            );
        }

        // 2. Data Jenis Kelompok
        $kelompokList = [
            'Keluarga Rentan Rawan Pangan',
            'Balita dan Ibu Hamil Berisiko Stunting',
            'Masyarakat Miskin Desil 1',
        ];

        foreach ($kelompokList as $namaJenis) {
            DB::table('jenis_kelompok')->updateOrInsert(
                ['nama_jenis' => $namaJenis],
                ['nama_jenis' => $namaJenis]
            );
        }

        // Ambil User admin/petugas program
        $user = User::first();
        $userId = $user ? $user->id_user : 1;

        $jb1 = DB::table('jenis_bantuan')->first();
        $jbId = $jb1 ? $jb1->id_jenis_bantuan : 1;

        // 3. Data Contoh Program
        $program1 = Program::firstOrCreate(
            ['nama_program' => 'Program Penyaluran Beras Bapanas 2026'],
            [
                'id_user' => $userId,
                'id_jenis_bantuan' => $jbId,
                'tanggal_mulai' => '2026-01-01',
                'tanggal_selesai' => '2026-12-31',
                'status' => 'Berjalan',
            ]
        );

        $program2 = Program::firstOrCreate(
            ['nama_program' => 'Intervensi Kerentanan Pangan Khusus Stunting'],
            [
                'id_user' => $userId,
                'id_jenis_bantuan' => $jbId,
                'tanggal_mulai' => '2026-03-01',
                'tanggal_selesai' => '2026-10-31',
                'status' => 'Berjalan',
            ]
        );

        // Ambil Wilayah
        $wilayah = Wilayah::first();
        $wilayahId = $wilayah ? $wilayah->id_wilayah : 1;

        $jk1 = DB::table('jenis_kelompok')->first();
        $jkId = $jk1 ? $jk1->id_jenis_kelompok : 1;

        // 4. Data Contoh Kelompok Sasaran
        $ks1 = KelompokSasaran::firstOrCreate(
            [
                'id_wilayah' => $wilayahId,
                'id_jenis_kelompok' => $jkId,
                'tahun' => 2026,
            ],
            [
                'jumlah' => 25000,
            ]
        );

        // 5. Data Contoh Target Program
        ProgramSasaran::firstOrCreate(
            [
                'id_program' => $program1->id_program,
                'id_kelompok' => $ks1->id_kelompok_sasaran,
            ],
            [
                'target_jumlah' => 15000,
            ]
        );
    }
}
