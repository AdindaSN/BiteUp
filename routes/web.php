<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DataIndikatorController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Autentikasi bawaan Laravel/BiteUp (jika ada)
// Auth::routes();

// Group rute yang memerlukan autentikasi
Route::middleware(['auth'])->group(function () {
    
    // Rute PBI-005: Ringkasan Indikator Utama
    Route::get('/ringkasan-indikator', [DataIndikatorController::class, 'ringkasan'])->name('indikator.ringkasan');

    // Rute PBI-006: Visualisasi Statistik dan Grafik Tren
    Route::get('/grafik-tren-indikator', [DataIndikatorController::class, 'grafikTren'])->name('indikator.grafik-tren');

});