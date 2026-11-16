
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DataIndikatorController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserManagementController;
use App\Models\User;

// Halaman utama
Route::get('/', function () {
    return redirect()->route('login');
});

// Halaman Data Indikator
Route::get('/indikator-wilayah', [DataIndikatorController::class, 'index'])
    ->name('indikator-wilayah.index');

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
