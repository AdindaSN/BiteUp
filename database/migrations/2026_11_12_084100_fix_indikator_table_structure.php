<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Only modify the table if it exists
        if (Schema::hasTable('indikator')) {
            Schema::table('indikator', function (Blueprint $table) {
                // Rename primary key column if it exists
                if (Schema::hasColumn('indikator', 'id')) {
                    $table->renameColumn('id', 'id_indikator');
                }

                // Remove default timestamps if they exist
                if (Schema::hasColumn('indikator', 'created_at') && Schema::hasColumn('indikator', 'updated_at')) {
                    $table->dropTimestamps();
                }

                // Add new columns that are missing
                if (!Schema::hasColumn('indikator', 'kode')) {
                    $table->string('kode', 50);
                    $table->string('nama_indikator', 150);
                    $table->string('dimensi', 100);
                    $table->string('satuan', 50);
                    $table->decimal('bobot', 8, 2);
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('indikator')) {
            Schema::table('indikator', function (Blueprint $table) {
                // Drop newly added columns
                if (Schema::hasColumn('indikator', 'kode')) {
                    $table->dropColumn(['kode', 'nama_indikator', 'dimensi', 'satuan', 'bobot']);
                }

                // Rename primary key back if it was renamed
                if (Schema::hasColumn('indikator', 'id_indikator') && !Schema::hasColumn('indikator', 'id')) {
                    $table->renameColumn('id_indikator', 'id');
                }

                // Restore timestamps if they were removed
                if (!Schema::hasColumn('indikator', 'created_at')) {
                    $table->timestamps();
                }
            });
        }
    }
};