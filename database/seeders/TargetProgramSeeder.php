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

        $program3 = Program::firstOrCreate(
            ['nama_program' => 'Bantuan Pangan Non-Tunai (BPNT) 2026'],
            [
                'id_user' => $userId,
                'id_jenis_bantuan' => $jbId,
                'tanggal_mulai' => '2026-01-01',
                'tanggal_selesai' => '2026-12-31',
                'status' => 'Berjalan',
            ]
        );

        // Ambil jenis kelompok
        $jkRawan = DB::table('jenis_kelompok')->where('nama_jenis', 'Keluarga Rentan Rawan Pangan')->first();
        $jkStunting = DB::table('jenis_kelompok')->where('nama_jenis', 'Balita dan Ibu Hamil Berisiko Stunting')->first();
        $jkMiskin = DB::table('jenis_kelompok')->where('nama_jenis', 'Masyarakat Miskin Desil 1')->first();

        // 4. Buat kelompok sasaran untuk beberapa wilayah kabupaten/kota yang ada
        $wilayahSamples = Wilayah::where('kabupaten_kota', '!=', '-')->take(6)->get();

        foreach ($wilayahSamples as $idx => $wil) {
            $jkId = match ($idx % 3) {
                0 => $jkRawan ? $jkRawan->id_jenis_kelompok : 1,
                1 => $jkStunting ? $jkStunting->id_jenis_kelompok : 1,
                2 => $jkMiskin ? $jkMiskin->id_jenis_kelompok : 1,
            };

            $ks = KelompokSasaran::firstOrCreate(
                [
                    'id_wilayah' => $wil->id_wilayah,
                    'id_jenis_kelompok' => $jkId,
                    'tahun' => 2025,
                ],
                [
                    'jumlah' => rand(5000, 30000),
                ]
            );

            // Buat target program untuk kelompok sasaran ini
            $programForTarget = match ($idx % 3) {
                0 => $program1,
                1 => $program2,
                2 => $program3,
            };

            ProgramSasaran::firstOrCreate(
                [
                    'id_program' => $programForTarget->id_program,
                    'id_kelompok' => $ks->id_kelompok_sasaran,
                ],
                [
                    'target_jumlah' => rand(3000, 15000),
                ]
            );
        }

        // 5. Buat juga kelompok sasaran tambahan dengan tahun berbeda
        if ($wilayahSamples->count() >= 2) {
            $wil1 = $wilayahSamples[0];
            $wil2 = $wilayahSamples[1];

            $ks2026_1 = KelompokSasaran::firstOrCreate(
                [
                    'id_wilayah' => $wil1->id_wilayah,
                    'id_jenis_kelompok' => $jkRawan ? $jkRawan->id_jenis_kelompok : 1,
                    'tahun' => 2026,
                ],
                [
                    'jumlah' => 18000,
                ]
            );

            ProgramSasaran::firstOrCreate(
                [
                    'id_program' => $program1->id_program,
                    'id_kelompok' => $ks2026_1->id_kelompok_sasaran,
                ],
                [
                    'target_jumlah' => 12000,
                ]
            );

            $ks2026_2 = KelompokSasaran::firstOrCreate(
                [
                    'id_wilayah' => $wil2->id_wilayah,
                    'id_jenis_kelompok' => $jkStunting ? $jkStunting->id_jenis_kelompok : 1,
                    'tahun' => 2026,
                ],
                [
                    'jumlah' => 22000,
                ]
            );

            ProgramSasaran::firstOrCreate(
                [
                    'id_program' => $program2->id_program,
                    'id_kelompok' => $ks2026_2->id_kelompok_sasaran,
                ],
                [
                    'target_jumlah' => 8500,
                ]
            );
        }
    }
}