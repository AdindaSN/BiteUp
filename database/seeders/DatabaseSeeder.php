<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash; // Penting agar password ter-hash bcrypt

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Jalankan master roles terlebih dahulu
        $this->call(RoleSeeder::class);

        // 2. Jalankan master wilayah agar id_wilayah 1 tersedia di database
        $this->call(WilayahSeeder::class);

        // 3. Pengelola Sistem Bapanas (Role ID 4 - Administrator)
        User::firstOrCreate(
            ['email' => 'admin@bapanas.go.id'],
            [
                'nama' => 'Pengelola Sistem Bapanas',
                'password' => Hash::make('admin123'), // Di-hash agar bisa untuk login
                'id_roles' => 4,
                'id_wilayah' => null,
                'status_akun' => 'active',
            ]
        );

        // 4. Petugas Program (Role ID 3)
        User::firstOrCreate(
            ['email' => 'program@bapanas.go.id'],
            [
                'nama' => 'Petugas Program Bapanas',
                'password' => Hash::make('password123'),
                'id_roles' => 3,
                'id_wilayah' => null,
                'status_akun' => 'active',
            ]
        );

        // 5. Pimpinan Bapanas (Role ID 2)
        User::firstOrCreate(
            ['email' => 'pimpinan@bapanas.go.id'],
            [
                'nama' => 'Pimpinan Bapanas',
                'password' => Hash::make('password123'),
                'id_roles' => 2,
                'id_wilayah' => null,
                'status_akun' => 'active',
            ]
        );

        // 6. Petugas Data Wilayah (Role ID 1)
        User::firstOrCreate(
            ['email' => 'wilayah@bapanas.go.id'],
            [
                'nama' => 'Petugas Wilayah Bapanas',
                'password' => Hash::make('password123'),
                'id_roles' => 1,
                'id_wilayah' => 1, // Menggunakan id_wilayah 1 dari WilayahSeeder
                'status_akun' => 'active',
            ]
        );

        // 7. Pemanggilan Seeder Lainnya (termasuk ProgramSeeder untuk PBI-017)
        $this->call([
            FsvaDataSeeder::class,
            ProgramSeeder::class,
            TargetProgramSeeder::class,
        ]);
    }
}