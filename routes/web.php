<?php

use App\Http\Controllers\KelompokSasaranController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/kelompok-sasaran', [KelompokSasaranController::class, 'index'])
    ->name('kelompok-sasaran.index');