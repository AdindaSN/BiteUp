
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('indikator', function (Blueprint $table) {
            $table->bigIncrements('id_indikator');
            $table->string('kode', 50);
            $table->string('nama_indikator', 150);
            $table->string('dimensi', 100);
            $table->string('satuan', 50);
            $table->decimal('bobot', 8, 2);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('indikator');
    }
};
