<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            'Petugas Data Wilayah',
            'Pimpinan Bapanas',
            'Petugas Program',
            'Pengelola Sistem Bapanas',
        ];

        foreach ($roles as $role) {
            DB::table('roles')->updateOrInsert(
                ['nama_role' => $role],
                ['nama_role' => $role]
            );
        }
    }
}
