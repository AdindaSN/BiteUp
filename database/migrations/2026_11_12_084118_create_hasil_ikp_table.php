
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hasil_ikp', function (Blueprint $table) {
            $table->bigIncrements('id_hasil_ikp');

            $table->unsignedBigInteger('id_wilayah');

            $table->year('tahun');
            $table->decimal('skor_ikp', 8, 2);
            $table->string('kategori_prioritas', 100);

            $table->foreign('id_wilayah')
                ->references('id_wilayah')
                ->on('wilayah');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hasil_ikp');
    }
};
