<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DataIndikatorController;

Route::get('/', function () {
    return redirect()->route('indikator-wilayah.index');
});

Route::get('/indikator-wilayah', [DataIndikatorController::class, 'index'])->name('indikator-wilayah.index');
