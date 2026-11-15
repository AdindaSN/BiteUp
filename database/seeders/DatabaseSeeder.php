<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(RoleSeeder::class);

        // Buat user admin default jika belum ada
        User::firstOrCreate(
            ['email' => 'admin@bapanas.go.id'],
            [
                'nama' => 'Pengelola Sistem Bapanas',
                'password' => 'admin123',
                'id_roles' => 4, // Pengelola Sistem Bapanas
                'id_wilayah' => null,
                'status_akun' => 'active',
            ]
        );
    }
}
