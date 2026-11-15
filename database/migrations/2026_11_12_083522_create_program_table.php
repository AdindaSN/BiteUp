
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('program', function (Blueprint $table) {
            $table->bigIncrements('id_program');
            $table->unsignedBigInteger('id_user');
            $table->unsignedBigInteger('id_jenis_bantuan');

            $table->string('nama_program', 150);
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->string('status', 50);

            $table->foreign('id_user')
                ->references('id_user')
                ->on('users');

            $table->foreign('id_jenis_bantuan')
                ->references('id_jenis_bantuan')
                ->on('jenis_bantuan');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('program');
    }
};
