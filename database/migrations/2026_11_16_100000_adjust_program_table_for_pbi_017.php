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
            if (!Schema::hasColumn('program', 'deskripsi')) {
                $table->text('deskripsi')->nullable()->after('nama_program');
            }
            $table->unsignedBigInteger('id_user')->nullable()->change();
            $table->date('tanggal_mulai')->nullable()->change();
            $table->date('tanggal_selesai')->nullable()->change();
            $table->string('status', 50)->default('Aktif')->change();
            if (!Schema::hasColumn('program', 'created_at')) {
                $table->timestamps();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('program', function (Blueprint $table) {
            if (Schema::hasColumn('program', 'deskripsi')) {
                $table->dropColumn('deskripsi');
            }
            if (Schema::hasColumn('program', 'created_at')) {
                $table->dropTimestamps();
            }
        });
    }
};

