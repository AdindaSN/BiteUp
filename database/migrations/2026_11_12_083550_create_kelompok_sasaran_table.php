
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kelompok_sasaran', function (Blueprint $table) {
            $table->bigIncrements('id_kelompok_sasaran');
            $table->unsignedBigInteger('id_wilayah');
            $table->unsignedBigInteger('id_jenis_kelompok');

            $table->integer('jumlah');
            $table->year('tahun');

            $table->foreign('id_wilayah')
                ->references('id_wilayah')
                ->on('wilayah');

            $table->foreign('id_jenis_kelompok')
                ->references('id_jenis_kelompok')
                ->on('jenis_kelompok');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kelompok_sasaran');
    }
};
