<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Cek dulu agar tidak error jika kolomnya sudah terlanjur ada di laptop Anda
            if (!Schema::hasColumn('users', 'status_akun')) {
                $table->string('status_akun')->default('active')->after('id_wilayah'); // Sesuaikan posisi kolom jika perlu
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('status_akun');
        });
    }
};