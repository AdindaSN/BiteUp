<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class HistoricalIndikatorSeeder extends Seeder
{
    public function run(): void
    {
        $baseRows = DB::table('data_indikator')->where('tahun', 2025)->get();

        if ($baseRows->isEmpty()) {
            $this->command?->warn('Data dasar tahun 2025 tidak ditemukan di tabel data_indikator.');
            return;
        }

        $years = [2022, 2023, 2024];

        // Hapus data dummy tahun 2022-2024 jika seeder dijalankan ulang
        DB::table('data_indikator')->whereIn('tahun', $years)->delete();

        $batch = [];
        $now = now();

        foreach ($baseRows as $row) {
            $baseVal = (float) $row->nilai;

            foreach ($years as $year) {
                $step = 2025 - $year; // 3 untuk 2022, 2 untuk 2023, 1 untuk 2024
                // Variasi deterministik berdasarkan id_wilayah & id_indikator agar tren terlihat alami
                $seed = (($row->id_wilayah * 17 + $row->id_indikator * 31 + $year * 7) % 15) - 7; // -7 s.d. +7
                $factor = 1 + (($seed * 0.008) * $step) - (0.015 * $step);

                $newVal = round(max(0.01, $baseVal * $factor), 2);
                if ($baseVal <= 100 && $newVal > 100) {
                    $newVal = round(100 - (($row->id_wilayah + $year) % 4) * 0.4, 2);
                }

                $batch[] = [
                    'id_wilayah'   => $row->id_wilayah,
                    'id_indikator' => $row->id_indikator,
                    'id_user'      => $row->id_user,
                    'tahun'        => $year,
                    'nilai'        => $newVal,
                    'sumber_data'  => 'Simulasi Historis FSVA ' . $year,
                    'waktu_update' => $now,
                ];

                if (count($batch) >= 500) {
                    DB::table('data_indikator')->insert($batch);
                    $batch = [];
                }
            }
        }

        if (!empty($batch)) {
            DB::table('data_indikator')->insert($batch);
        }

        // Sekalian lengkapi tabel hasil_ikp untuk tahun 2022-2024 jika tabelnya ada
        if (Schema::hasTable('hasil_ikp')) {
            $baseIkp = DB::table('hasil_ikp')->where('tahun', 2025)->get();
            if ($baseIkp->isNotEmpty()) {
                DB::table('hasil_ikp')->whereIn('tahun', $years)->delete();
                $ikpBatch = [];

                foreach ($baseIkp as $ikp) {
                    $baseScore = (float) $ikp->skor_ikp;
                    foreach ($years as $year) {
                        $step = 2025 - $year;
                        $delta = ((($ikp->id_wilayah * 13 + $year) % 9) - 4) * 0.35 - (0.85 * $step);
                        $score = round(min(99.5, max(20.0, $baseScore + $delta)), 2);

                        $rowArr = (array) $ikp;
                        unset($rowArr['id_hasil_ikp'], $rowArr['id']);
                        $rowArr['tahun'] = $year;
                        $rowArr['skor_ikp'] = $score;

                        $ikpBatch[] = $rowArr;
                        if (count($ikpBatch) >= 500) {
                            DB::table('hasil_ikp')->insert($ikpBatch);
                            $ikpBatch = [];
                        }
                    }
                }

                if (!empty($ikpBatch)) {
                    DB::table('hasil_ikp')->insert($ikpBatch);
                }
            }
        }
    }
}