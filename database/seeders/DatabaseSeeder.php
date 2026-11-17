<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(RoleSeeder::class);

        // 1. Pengelola Sistem Bapanas (Role ID 4 - Administrator)
        User::firstOrCreate(
            ['email' => 'admin@bapanas.go.id'],
            [
                'nama' => 'Pengelola Sistem Bapanas',
                'password' => 'admin123',
                'id_roles' => 4,
                'id_wilayah' => null,
                'status_akun' => 'active',
            ]
        );

        // 2. Petugas Program (Role ID 3)
        User::firstOrCreate(
            ['email' => 'program@bapanas.go.id'],
            [
                'nama' => 'Petugas Program Bapanas',
                'password' => 'password123',
                'id_roles' => 3,
                'id_wilayah' => null,
                'status_akun' => 'active',
            ]
        );

        // 3. Pimpinan Bapanas (Role ID 2)
        User::firstOrCreate(
            ['email' => 'pimpinan@bapanas.go.id'],
            [
                'nama' => 'Pimpinan Bapanas',
                'password' => 'password123',
                'id_roles' => 2,
                'id_wilayah' => null,
                'status_akun' => 'active',
            ]
        );

        // 4. Petugas Data Wilayah (Role ID 1)
        User::firstOrCreate(
            ['email' => 'wilayah@bapanas.go.id'],
            [
                'nama' => 'Petugas Wilayah Bapanas',
                'password' => 'password123',
                'id_roles' => 1,
                'id_wilayah' => 1,
                'status_akun' => 'active',
            ]
        );

        // Pemanggilan Seeder Lainnya
        $this->call([
            FsvaDataSeeder::class,
            ProgramSeeder::class,
            TargetProgramSeeder::class,
        ]);
    }
} 