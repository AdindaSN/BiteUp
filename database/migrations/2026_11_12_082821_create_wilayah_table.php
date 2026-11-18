
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::create('wilayah', function (Blueprint $table) {
        $table->id('id_wilayah'); // Sesuai primaryKey di model
        $table->string('kode_wilayah')->unique();
        $table->string('provinsi');
        $table->string('kabupaten_kota');
        $table->year('tahun');
        $table->unsignedBigInteger('parent_id')->nullable();
        // Tambahkan timestamps jika dibutuhkan, atau biarkan kosong jika model $timestamps = false
    });

    }

    public function down(): void
    {
        Schema::dropIfExists('wilayah');
    }
};
