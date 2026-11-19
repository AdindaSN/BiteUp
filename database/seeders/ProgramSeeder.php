<?php

namespace Database\Seeders;

use App\Models\JenisBantuan;
use App\Models\Program;
use App\Models\Role;
use App\Models\User;
use App\Models\Wilayah;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ProgramSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Roles
        $rolePetugas = Role::firstOrCreate(
            ['nama_role' => 'Petugas Program']
        );

        $roleAdmin = Role::firstOrCreate(
            ['nama_role' => 'Admin']
        );

        // 2. Default Petugas Program User
        $userPetugas = User::where('email', 'program@bapanas.go.id')->first()
            ?? User::firstOrCreate(
                ['email' => 'petugas@biteup.com'],
                [
                    'id_roles' => $rolePetugas->id_roles,
                    'nama' => 'Petugas Program',
                    'password' => Hash::make('password123'),
                    'status_akun' => 'active',
                ]
            );

        // 3. Jenis Bantuan (Kategori Program)
        $bantuanPangan = JenisBantuan::firstOrCreate(
            ['nama_bantuan' => 'Bantuan Pangan']
        );

        $intervensiGizi = JenisBantuan::firstOrCreate(
            ['nama_bantuan' => 'Intervensi Gizi']
        );

        // 4. Master Data Wilayah (referensi Sprint 1 & 2)
        $wilayahList = [
            ['kode_wilayah' => '31', 'nama_wilayah' => 'DKI Jakarta', 'level' => 1, 'jumlah_penduduk' => 10672100],
            ['kode_wilayah' => '32', 'nama_wilayah' => 'Jawa Barat', 'level' => 1, 'jumlah_penduduk' => 49935858],
            ['kode_wilayah' => '33', 'nama_wilayah' => 'Jawa Tengah', 'level' => 1, 'jumlah_penduduk' => 37032410],
            ['kode_wilayah' => '53', 'nama_wilayah' => 'Nusa Tenggara Timur', 'level' => 1, 'jumlah_penduduk' => 5466285],
            ['kode_wilayah' => '91', 'nama_wilayah' => 'Papua', 'level' => 1, 'jumlah_penduduk' => 4303707],
            ['kode_wilayah' => '3273', 'nama_wilayah' => 'Bandung', 'level' => 2, 'jumlah_penduduk' => 2527854],
            ['kode_wilayah' => '3201', 'nama_wilayah' => 'Bogor', 'level' => 2, 'jumlah_penduduk' => 5364000],
            ['kode_wilayah' => '3202', 'nama_wilayah' => 'Sukabumi', 'level' => 2, 'jumlah_penduduk' => 2615000],
            ['kode_wilayah' => '3276', 'nama_wilayah' => 'Depok', 'level' => 2, 'jumlah_penduduk' => 2134600],
        ];

        $wilayahMap = [];
        foreach ($wilayahList as $w) {
            $created = Wilayah::firstOrCreate(
                ['kode_wilayah' => $w['kode_wilayah']],
                $w
            );
            $wilayahMap[$w['nama_wilayah']] = $created->id_wilayah;
        }

        // 5. Sample Program Data matching the reference
        $programs = [
            [
                'nama_program' => 'Bantuan Pangan Beras',
                'id_jenis_bantuan' => $bantuanPangan->id_jenis_bantuan,
                'id_wilayah' => $wilayahMap['Jawa Barat'] ?? null,
                'deskripsi' => 'Distribusi beras untuk rumah tangga miskin',
                'tanggal_pelaksanaan' => '2026-03-01',
                'tanggal_mulai' => '2026-03-01',
                'status' => 'Aktif',
                'id_user' => $userPetugas->id_user,
            ],
            [
                'nama_program' => 'Pangan Lokal',
                'id_jenis_bantuan' => $bantuanPangan->id_jenis_bantuan,
                'id_wilayah' => $wilayahMap['Papua'] ?? null,
                'deskripsi' => 'Penguatan pangan lokal di daerah',
                'tanggal_pelaksanaan' => '2026-03-15',
                'tanggal_mulai' => '2026-03-15',
                'status' => 'Aktif',
                'id_user' => $userPetugas->id_user,
            ],
            [
                'nama_program' => 'Fortifikasi Pangan',
                'id_jenis_bantuan' => $intervensiGizi->id_jenis_bantuan,
                'id_wilayah' => $wilayahMap['Jawa Tengah'] ?? null,
                'deskripsi' => 'Fortifikasi makanan untuk anak sekolah',
                'tanggal_pelaksanaan' => '2026-04-01',
                'tanggal_mulai' => '2026-04-01',
                'status' => 'Aktif',
                'id_user' => $userPetugas->id_user,
            ],
            [
                'nama_program' => 'Program Gizi Ibu Hamil',
                'id_jenis_bantuan' => $intervensiGizi->id_jenis_bantuan,
                'id_wilayah' => $wilayahMap['Nusa Tenggara Timur'] ?? null,
                'deskripsi' => 'Pemberian makanan tambahan ibu hamil',
                'tanggal_pelaksanaan' => '2026-04-10',
                'tanggal_mulai' => '2026-04-10',
                'status' => 'Aktif',
                'id_user' => $userPetugas->id_user,
            ],
            [
                'nama_program' => 'Program Gizi Anak Sekolah',
                'id_jenis_bantuan' => $intervensiGizi->id_jenis_bantuan,
                'id_wilayah' => $wilayahMap['DKI Jakarta'] ?? null,
                'deskripsi' => 'Pemberian makanan bergizi di sekolah',
                'tanggal_pelaksanaan' => '2026-05-01',
                'tanggal_mulai' => '2026-05-01',
                'status' => 'Aktif',
                'id_user' => $userPetugas->id_user,
            ],
        ];

        foreach ($programs as $prog) {
            $existing = Program::where('nama_program', $prog['nama_program'])->first();
            if ($existing) {
                $existing->update([
                    'id_wilayah' => $prog['id_wilayah'],
                    'tanggal_pelaksanaan' => $prog['tanggal_pelaksanaan'],
                    'tanggal_mulai' => $prog['tanggal_mulai'],
                ]);
            } else {
                Program::create($prog);
            }
        }
    }
}
