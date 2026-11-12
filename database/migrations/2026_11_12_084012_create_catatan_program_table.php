
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('catatan_program', function (Blueprint $table) {
            $table->bigIncrements('id_catatan');

            $table->unsignedBigInteger('id_program');
            $table->unsignedBigInteger('id_user');

            $table->date('tanggal');
            $table->text('isi_catatan');

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
        Schema::dropIfExists('catatan_program');
    }
};
