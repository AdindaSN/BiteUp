
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tindak_lanjut', function (Blueprint $table) {
            $table->bigIncrements('id_tindak_lanjut');

            $table->unsignedBigInteger('id_program');
            $table->unsignedBigInteger('id_user');

            $table->string('keputusan', 255);
            $table->date('tanggal');
            $table->string('status', 50);

            $table->foreign('id_program')
                ->references('id_program')
                ->on('program');

            $table->foreign('id_user')
                ->references('id_user')
                ->on('users');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tindak_lanjut');
    }
};
