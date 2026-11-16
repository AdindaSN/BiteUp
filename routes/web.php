<?php

use App\Http\Controllers\ProgramController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ProgramController::class, 'index'])->name('home');

Route::prefix('program')->name('program.')->group(function () {
    Route::get('/', [ProgramController::class, 'index'])->name('index');
    Route::get('/tambah', [ProgramController::class, 'create'])->name('create');
    Route::post('/', [ProgramController::class, 'store'])->name('store');
    Route::get('/{id}/edit', [ProgramController::class, 'edit'])->name('edit');
    Route::put('/{id}', [ProgramController::class, 'update'])->name('update');
    Route::delete('/{id}', [ProgramController::class, 'destroy'])->name('destroy');
});
