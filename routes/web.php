<?php

use App\Http\Controllers\DataIndikatorController;
use App\Http\Controllers\KelompokSasaranController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('indikator-wilayah.index');
});

Route::get('/indikator-wilayah', [DataIndikatorController::class, 'index'])->name('indikator-wilayah.index');

Route::get('/kelompok-sasaran', [KelompokSasaranController::class, 'index'])->name('kelompok-sasaran.index');