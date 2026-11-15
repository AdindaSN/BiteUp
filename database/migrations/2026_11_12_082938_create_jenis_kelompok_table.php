
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jenis_kelompok', function (Blueprint $table) {
            $table->bigIncrements('id_jenis_kelompok');
            $table->string('nama_jenis', 150);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jenis_kelompok');
    }
};
