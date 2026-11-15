
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('realisasi_program', function (Blueprint $table) {
            $table->bigIncrements('id_realisasi');

            $table->unsignedBigInteger('id_program_sasaran');
            $table->unsignedBigInteger('id_user');

            $table->date('tanggal');
            $table->integer('jumlah_realisasi');
            $table->text('keterangan')->nullable();

            $table->foreign('id_program_sasaran')
                ->references('id_program_sasaran')
                ->on('program_sasaran');

            $table->foreign('id_user')
                ->references('id_user')
                ->on('users');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('realisasi_program');
    }
};
