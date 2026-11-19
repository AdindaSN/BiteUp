<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('program', function (Blueprint $table) {
            if (!Schema::hasColumn('program', 'id_wilayah')) {
                $table->unsignedBigInteger('id_wilayah')->nullable()->after('id_jenis_bantuan');
                $table->foreign('id_wilayah')
                    ->references('id_wilayah')
                    ->on('wilayah')
                    ->nullOnDelete();
            }

            if (!Schema::hasColumn('program', 'tanggal_pelaksanaan')) {
                $table->date('tanggal_pelaksanaan')->nullable()->after('deskripsi');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('program', function (Blueprint $table) {
            if (Schema::hasColumn('program', 'id_wilayah')) {
                $table->dropForeign(['id_wilayah']);
                $table->dropColumn('id_wilayah');
            }

            if (Schema::hasColumn('program', 'tanggal_pelaksanaan')) {
                $table->dropColumn('tanggal_pelaksanaan');
            }
        });
    }
};

