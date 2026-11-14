<?php

use App\Http\Controllers\WilayahController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/wilayah/tambah', [WilayahController::class, 'create'])->name('wilayah.create');
Route::post('/wilayah', [WilayahController::class, 'store'])->name('wilayah.store');