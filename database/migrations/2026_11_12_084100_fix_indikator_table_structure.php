<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Jika tabel belum ada, buat baru
        if (!Schema::hasTable('indikator')) {
            Schema::create('indikator', function (Blueprint $table) {
                $table->id('id_indikator');
                $table->string('kode', 50);
                $table->string('nama_indikator', 150);
                $table->string('dimensi', 100);
                $table->string('satuan', 50);
                $table->decimal('bobot', 8, 2);
            });
        } else {
            // Jika tabel sudah ada, sesuaikan kolomnya dengan aman tanpa hapus data
            if (Schema::hasColumn('indikator', 'id') && !Schema::hasColumn('indikator', 'id_indikator')) {
                DB::statement('ALTER TABLE indikator CHANGE id id_indikator BIGINT UNSIGNED NOT NULL AUTO_INCREMENT');
            }

            if (Schema::hasColumn('indikator', 'created_at') && Schema::hasColumn('indikator', 'updated_at')) {
                Schema::table('indikator', function (Blueprint $table) {
                    $table->dropTimestamps();
                });
            }

            Schema::table('indikator', function (Blueprint $table) {
                if (!Schema::hasColumn('indikator', 'kode')) {
                    $table->string('kode', 50);
                }
                if (!Schema::hasColumn('indikator', 'nama_indikator')) {
                    $table->string('nama_indikator', 150);
                }
                if (!Schema::hasColumn('indikator', 'dimensi')) {
                    $table->string('dimensi', 100);
                }
                if (!Schema::hasColumn('indikator', 'satuan')) {
                    $table->string('satuan', 50);
                }
                if (!Schema::hasColumn('indikator', 'bobot')) {
                    $table->decimal('bobot', 8, 2);
                }
            });
        }
    }

    public function down(): void
    {
        // Tidak melakukan rollback otomatis agar data tetap aman.
    }
};