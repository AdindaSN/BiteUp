<?php

use App\Http\Controllers\Api\WilayahApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// API endpoint untuk fitur Data Wilayah (PBI-005)
Route::prefix('wilayah')->name('api.wilayah.')->group(function () {
    Route::get('/',  [WilayahApiController::class, 'index'])->name('index');
    Route::post('/', [WilayahApiController::class, 'store'])->name('store');
});
