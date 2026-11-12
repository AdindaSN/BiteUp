
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wilayah', function (Blueprint $table) {
            $table->bigIncrements('id_wilayah');
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->string('kode_wilayah', 50);
            $table->string('nama_wilayah', 150);
            $table->integer('level');
            $table->unsignedBigInteger('jumlah_penduduk')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();

            $table->foreign('parent_id')
                ->references('id_wilayah')
                ->on('wilayah')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wilayah');
    }
};
