<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Membuat tabel wilayah untuk menyimpan data wilayah (PBI-005).
     */
    public function up(): void
    {
        Schema::create('wilayah', function (Blueprint $table) {
            $table->id();
            $table->string('nama_wilayah', 100);
            $table->string('kode_wilayah', 20)->unique();
            $table->enum('jenis_wilayah', ['provinsi', 'kota', 'kabupaten', 'kecamatan']);
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wilayah');
    }
};
