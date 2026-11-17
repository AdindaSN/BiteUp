<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('hasil_ikp')) {
            Schema::create('hasil_ikp', function (Blueprint $table) {
                $table->id('id_hasil_ikp');
                $table->unsignedBigInteger('id_wilayah');
                $table->integer('tahun');
                $table->decimal('skor_ikp', 8, 2);
                $table->tinyInteger('prioritas')->comment('Kategori Prioritas 1 - 6');
                $table->string('kategori_prioritas', 100);
                $table->timestamp('waktu_proses')->nullable();

                $table->unique(['id_wilayah', 'tahun']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('hasil_ikp');
    }
};