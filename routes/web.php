<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DataIndikatorController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\ProgramController;
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

    Route::get('/dashboard', function (\Illuminate\Http\Request $request) {
        $user = auth()->user();
        $roleId = (int) $user->id_roles;
        $isAdmin = $roleId === 4;

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

        $tahunFilter = (int) $request->input('tahun', date('Y'));
        $indikatorFilter = $request->input('indikator', 'Indeks Ketahanan Pangan');

        $daftarTahun = \App\Models\HasilIkp::select('tahun')
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->pluck('tahun')
            ->toArray();
        if (empty($daftarTahun)) {
            $daftarTahun = [(int) date('Y'), (int) date('Y') - 1];
        }
        if (!in_array($tahunFilter, $daftarTahun, true)) {
            $tahunFilter = $daftarTahun[0];
        }

        $totalProgram = \App\Models\Program::count();
        $programAktif = \App\Models\Program::whereIn('status', ['Aktif', 'Berjalan'])->count();
        $totalTargetPenerima = (int) \App\Models\ProgramSasaran::sum('target_jumlah');
        $totalAlokasiTarget = \App\Models\ProgramSasaran::count();
        $totalKelompokSasaran = \App\Models\KelompokSasaran::count();
        $totalPendudukRentan = (int) \App\Models\KelompokSasaran::sum('jumlah');
        $wilayahTerjangkau = \App\Models\KelompokSasaran::whereHas('programSasaran')
            ->distinct('id_wilayah')
            ->count('id_wilayah');

        $hasilIkpRows = \App\Models\HasilIkp::with('wilayah')
            ->where('tahun', $tahunFilter)
            ->get();

        $petaProvinsi = [];
        foreach ($hasilIkpRows->groupBy(fn($r) => strtoupper(trim($r->wilayah->provinsi ?? ''))) as $provName => $items) {
            if ($provName === '') continue;
            $avgSkor = round($items->avg('skor_ikp'), 2);
            $kategori = $avgSkor < 50 ? 'Sangat Prioritas'
                : ($avgSkor < 62 ? 'Prioritas'
                : ($avgSkor < 72 ? 'Cukup' : 'Tidak Prioritas'));

            $petaProvinsi[$provName] = [
                'provinsi' => $items->first()->wilayah->provinsi,
                'skor_ikp' => $avgSkor,
                'kategori' => $kategori,
                'jumlah_wilayah' => $items->count(),
            ];
        }

        $targetProgramTerbaru = \App\Models\ProgramSasaran::with(['program.jenisBantuan', 'kelompokSasaran.wilayah', 'kelompokSasaran.jenisKelompok'])
            ->orderBy('id_program_sasaran', 'desc')
            ->take(6)
            ->get();

        return view('dashboard', compact(
            'pendingUsersCount',
            'activeUsersCount',
            'rejectedUsersCount',
            'latestPendingUsers',
            'tahunFilter',
            'indikatorFilter',
            'daftarTahun',
            'totalProgram',
            'programAktif',
            'totalTargetPenerima',
            'totalAlokasiTarget',
            'totalKelompokSasaran',
            'totalPendudukRentan',
            'wilayahTerjangkau',
            'petaProvinsi',
            'targetProgramTerbaru'
        ));
    })->name('dashboard');

    // Pengelolaan Data Kelompok Sasaran (PBI-011)
    Route::get('/kelompok-sasaran', [KelompokSasaranController::class, 'index'])
        ->name('kelompok-sasaran.index');

    // Menambahkan Data Kelompok Sasaran (PBI-012)
    Route::middleware('role:3,4')->group(function () {
        Route::get('/kelompok-sasaran/create', [KelompokSasaranController::class, 'create'])
            ->name('kelompok-sasaran.create');
        Route::post('/kelompok-sasaran', [KelompokSasaranController::class, 'store'])
            ->name('kelompok-sasaran.store');
    });

    // Mengubah Data Kelompok Sasaran (PBI-013)
    Route::middleware('role:3,4')->group(function () {
        Route::get('/kelompok-sasaran/{kelompokSasaran}/edit', [KelompokSasaranController::class, 'edit'])
            ->name('kelompok-sasaran.edit');
        Route::put('/kelompok-sasaran/{kelompokSasaran}', [KelompokSasaranController::class, 'update'])
            ->name('kelompok-sasaran.update');
    });

    // Menghapus Data Kelompok Sasaran (PBI-014)
    Route::middleware('role:3,4')->group(function () {
        Route::delete('/kelompok-sasaran/{kelompokSasaran}', [KelompokSasaranController::class, 'destroy'])
            ->name('kelompok-sasaran.destroy');
    });

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

    // Pengelolaan Data Program (PBI-017)
    Route::resource('program', ProgramController::class)->except(['show']);

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