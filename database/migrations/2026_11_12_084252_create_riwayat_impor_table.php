
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('riwayat_impor', function (Blueprint $table) {
            $table->bigIncrements('id_riwayat');
            $table->unsignedBigInteger('id_user');

            $table->string('nama_berkas', 255);
            $table->string('jenis_data', 100);
            $table->year('tahun');
            $table->string('status', 50);
            $table->dateTime('waktu_impor');

            $table->foreign('id_user')
                ->references('id_user')
                ->on('users');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('riwayat_impor');
    }
};
