
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('indikator', function (Blueprint $table) {
            $table->renameColumn('id', 'id_indikator');
            $table->dropTimestamps();

            $table->string('kode', 50);
            $table->string('nama_indikator', 150);
            $table->string('dimensi', 100);
            $table->string('satuan', 50);
            $table->decimal('bobot', 8, 2);
        });
    }

    public function down(): void
    {
        Schema::table('indikator', function (Blueprint $table) {
            $table->dropColumn([
                'kode',
                'nama_indikator',
                'dimensi',
                'satuan',
                'bobot',
            ]);

            $table->renameColumn('id_indikator', 'id');
            $table->timestamps();
        });
    }
};
