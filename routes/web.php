<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DataIndikatorController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\ProgramSasaranController;
use App\Http\Controllers\KelompokSasaranController;
use App\Models\User;
use App\Http\Controllers\PrioritasWilayahController;

// Halaman utama
Route::get('/', function () {
    return redirect()->route('login');
});

// Route untuk pengguna yang belum login
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])
        ->name('login');

    Route::post('/login', [AuthController::class, 'login'])
        ->name('login.post');

    Route::get('/register', [AuthController::class, 'showRegister'])
        ->name('register');

    Route::post('/register', [AuthController::class, 'register'])
        ->name('register.post');
});

// Route untuk pengguna yang sudah login
Route::middleware('auth')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('logout');

    Route::get('/dashboard', function () {
        $user = auth()->user();
        $isAdmin = (int) $user->id_roles === 4;

        $pendingUsersCount = 0;
        $activeUsersCount = 0;
        $rejectedUsersCount = 0;
        $latestPendingUsers = collect();

        if ($isAdmin) {
            $pendingUsersCount = User::where('status_akun', 'pending')->count();
            $activeUsersCount = User::where('status_akun', 'active')->count();
            $rejectedUsersCount = User::where('status_akun', 'rejected')->count();

            $latestPendingUsers = User::where('status_akun', 'pending')
                ->orderBy('id_user', 'desc')
                ->take(5)
                ->get();
        }

        return view('dashboard', compact(
            'pendingUsersCount',
            'activeUsersCount',
            'rejectedUsersCount',
            'latestPendingUsers'
        ));
    })->name('dashboard');

<<<<<<< HEAD
=======
    // Pengelolaan Data Kelompok Sasaran (PBI-011)
    Route::get('/kelompok-sasaran', [KelompokSasaranController::class, 'index'])
        ->name('kelompok-sasaran.index');

>>>>>>> main
    // Khusus Petugas Data Wilayah (Role ID 1) & Admin (Role ID 4)
    Route::middleware('role:1,4')->group(function () {
        // PBI-008: Halaman Data Indikator per Wilayah
        Route::get('/indikator-wilayah', [DataIndikatorController::class, 'index'])
            ->name('indikator-wilayah.index');

        // PBI-009: Pengelompokan Wilayah Berdasarkan Prioritas
        Route::get('/prioritas-wilayah', [PrioritasWilayahController::class, 'index'])
            ->name('prioritas-wilayah.index');

        Route::post('/prioritas-wilayah/proses', [PrioritasWilayahController::class, 'prosesPengelompokan'])
            ->name('prioritas-wilayah.proses');
    });

    // Pengelolaan Target Program (PBI-014)
    Route::get('/target-program', [ProgramSasaranController::class, 'index'])
        ->name('target-program.index');

    Route::middleware('role:3,4')->group(function () {
        Route::get('/target-program/create', [ProgramSasaranController::class, 'create'])
            ->name('target-program.create');

        Route::post('/target-program', [ProgramSasaranController::class, 'store'])
            ->name('target-program.store');

        // API FSVA untuk formulir target program
        Route::get('/target-program/api/fsva', [ProgramSasaranController::class, 'getFsvaData'])
            ->name('target-program.api.fsva');

        Route::get('/target-program/api/kelompok', [ProgramSasaranController::class, 'getKelompokByWilayah'])
            ->name('target-program.api.kelompok');
    });
});

// Route khusus Pengelola Sistem Bapanas (Role ID 4)
Route::middleware(['auth', 'role:4'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/pengguna', [UserManagementController::class, 'index'])
            ->name('pengguna.index');

        Route::post('/pengguna/{id}/verify', [UserManagementController::class, 'verify'])
            ->name('pengguna.verify');

        Route::post('/pengguna/{id}/reject', [UserManagementController::class, 'reject'])
            ->name('pengguna.reject');
    });