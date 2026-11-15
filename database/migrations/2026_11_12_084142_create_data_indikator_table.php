
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('data_indikator', function (Blueprint $table) {
            $table->bigIncrements('id_data_indikator');

            $table->unsignedBigInteger('id_wilayah');
            $table->unsignedBigInteger('id_indikator');
            $table->unsignedBigInteger('id_user');

            $table->year('tahun');
            $table->decimal('nilai', 15, 2);
            $table->text('sumber_data');
            $table->dateTime('waktu_update');

            $table->foreign('id_wilayah')
                ->references('id_wilayah')
                ->on('wilayah');

            $table->foreign('id_indikator')
                ->references('id_indikator')
                ->on('indikator');

            $table->foreign('id_user')
                ->references('id_user')
                ->on('users');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('data_indikator');
    }
};
