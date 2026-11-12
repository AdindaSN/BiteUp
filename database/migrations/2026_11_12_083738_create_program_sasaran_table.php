
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('program_sasaran', function (Blueprint $table) {
            $table->bigIncrements('id_program_sasaran');

            $table->unsignedBigInteger('id_program');
            $table->unsignedBigInteger('id_kelompok');

            $table->integer('target_jumlah');

            $table->foreign('id_program')
                ->references('id_program')
                ->on('program');

            $table->foreign('id_kelompok')
                ->references('id_kelompok_sasaran')
                ->on('kelompok_sasaran');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('program_sasaran');
    }
};
