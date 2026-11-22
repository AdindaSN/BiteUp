<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DataIndikatorController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\ProgramSasaranController;
use App\Http\Controllers\KelompokSasaranController;
use App\Models\User;
use App\Http\Controllers\PrioritasWilayahController;
use App\Http\Controllers\HasilIdentifikasiController;
use App\Http\Controllers\WilayahController;
use App\Http\Controllers\DataWilayahFsvaController;

// Halaman utama (Landing Page Publik BiteUp)
Route::get('/', function () {
    $tahunTerbaru = \App\Models\HasilIkp::max('tahun') ?? date('Y');
    $totalWilayah = \App\Models\Wilayah::count();
    $totalProvinsi = \App\Models\Wilayah::whereNotNull('provinsi')
        ->where('provinsi', '!=', '')
        ->distinct('provinsi')
        ->count('provinsi');

    $hasilIkp = \App\Models\HasilIkp::with('wilayah')->where('tahun', $tahunTerbaru)->get();
    $rataRataIkp = $hasilIkp->avg('skor_ikp') ?? 0;
    $wilayahRentan = $hasilIkp->filter(fn($r) => (float) $r->skor_ikp <= 53.42)->count();

    $programAktif = \App\Models\Program::whereIn('status', ['Aktif', 'Berjalan'])->count();
    if ($programAktif === 0) {
        $programAktif = \App\Models\Program::count();
    }
    $totalPenerima = \App\Models\ProgramSasaran::sum('target_jumlah') ?? 0;

    // Agregasi rata-rata skor IKP per provinsi untuk peta publik FSVA
    $petaProvinsi = $hasilIkp
        ->filter(fn($r) => $r->wilayah && !empty($r->wilayah->provinsi))
        ->groupBy(fn($r) => strtoupper(trim($r->wilayah->provinsi)))
        ->map(function ($rows, $prov) {
            $avgSkor = round($rows->avg('skor_ikp'), 2);
            $prio = $avgSkor < 45.60 ? 1
                : ($avgSkor <= 53.42 ? 2
                : ($avgSkor <= 61.47 ? 3
                : ($avgSkor <= 69.52 ? 4
                : ($avgSkor <= 77.35 ? 5 : 6))));
            return [
                'provinsi' => $prov,
                'skor_ikp' => $avgSkor,
                'prioritas' => $prio,
                'jumlah_kab' => $rows->count(),
            ];
        })
        ->values();

    return view('welcome', compact(
        'tahunTerbaru',
        'totalWilayah',
        'totalProvinsi',
        'rataRataIkp',
        'wilayahRentan',
        'programAktif',
        'totalPenerima',
        'petaProvinsi'
    ));
})->name('home');

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

    // Pengaturan Profil Mandiri Pengguna (Semua Role)
    Route::get('/profil', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profil', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profil/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

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
        $wilayahTerjangkau = \App\Models\KelompokSasaran::join('program_sasaran', 'kelompok_sasaran.id_kelompok_sasaran', '=', 'program_sasaran.id_kelompok')
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

        // Statistik analitik khusus Petugas Data Wilayah (Role 1)
        $totalWilayah = \App\Models\Wilayah::count();
        $totalKabKota = \App\Models\Wilayah::whereNotNull('kabupaten_kota')
            ->where('kabupaten_kota', '!=', '')
            ->count();
        $totalProvinsi = \App\Models\Wilayah::whereNotNull('provinsi')
            ->where('provinsi', '!=', '')
            ->distinct('provinsi')
            ->count('provinsi');

        $totalIndikator = \App\Models\Indikator::count();
        $totalDataIndikator = \App\Models\DataIndikator::where('tahun', $tahunFilter)->count();
        $targetTotalEntri = max(1, $totalWilayah * max(1, $totalIndikator));
        $persenKelengkapan = min(100, round(($totalDataIndikator / $targetTotalEntri) * 100));

        $hasilIkpTahun = \App\Models\HasilIkp::with('wilayah')->where('tahun', $tahunFilter)->get();
        $totalHasilIkp = $hasilIkpTahun->count();
        $rataRataIkp = $hasilIkpTahun->avg('skor_ikp') ?? 0;

        $klasifikasiIkp = function ($skor) {
            $s = (float) $skor;
            if ($s < 45.60) return [1, 'Sangat Rentan'];
            if ($s <= 53.42) return [2, 'Rentan'];
            if ($s <= 61.47) return [3, 'Agak Rentan'];
            if ($s <= 69.52) return [4, 'Agak Tahan'];
            if ($s <= 77.35) return [5, 'Tahan'];
            return [6, 'Sangat Tahan'];
        };

        $wilayahPrioritasTinggi = $hasilIkpTahun->filter(fn($r) => (float) $r->skor_ikp <= 53.42)->count();

        $distribusiPrioritas = [];
        $metaPrioritas = [
            1 => ['label' => 'Prioritas 1 — Sangat Rentan', 'range' => '< 45,59', 'color' => '#991B1B'],
            2 => ['label' => 'Prioritas 2 — Rentan', 'range' => '45,60 – 53,42', 'color' => '#EF4444'],
            3 => ['label' => 'Prioritas 3 — Agak Rentan', 'range' => '53,43 – 61,47', 'color' => '#F97316'],
            4 => ['label' => 'Prioritas 4 — Agak Tahan', 'range' => '61,48 – 69,52', 'color' => '#EAB308'],
            5 => ['label' => 'Prioritas 5 — Tahan', 'range' => '69,53 – 77,35', 'color' => '#84CC16'],
            6 => ['label' => 'Prioritas 6 — Sangat Tahan', 'range' => '> 77,35', 'color' => '#15803D'],
        ];
        foreach ($metaPrioritas as $level => $meta) {
            $jml = $hasilIkpTahun->filter(fn($r) => $klasifikasiIkp($r->skor_ikp)[0] === $level)->count();
            $distribusiPrioritas[] = [
                'level' => $level,
                'label' => $meta['label'],
                'range' => $meta['range'],
                'color' => $meta['color'],
                'count' => $jml,
                'pct'   => $totalHasilIkp > 0 ? round(($jml / $totalHasilIkp) * 100, 1) : 0,
            ];
        }

        $entriPerIndikator = \App\Models\DataIndikator::where('tahun', $tahunFilter)
            ->selectRaw('id_indikator, COUNT(*) as total')
            ->groupBy('id_indikator')
            ->pluck('total', 'id_indikator');

        $statistikIndikator = \App\Models\Indikator::all()->map(function ($ind) use ($entriPerIndikator, $totalWilayah) {
            $pk = $ind->getKey();
            $ind->jumlah_entri = (int) ($entriPerIndikator[$pk] ?? 0);
            $ind->persen = $totalWilayah > 0 ? min(100, round(($ind->jumlah_entri / $totalWilayah) * 100)) : 0;
            return $ind;
        });

        $hasilPrioritasTerbaru = $hasilIkpTahun
            ->sortBy('skor_ikp')
            ->values()
            ->take(8)
            ->map(function ($row) use ($klasifikasiIkp) {
                $hasilKlasifikasi = $klasifikasiIkp($row->skor_ikp);
                $row->level_prioritas = $hasilKlasifikasi[0];
                $row->label_prioritas = $row->kategori_prioritas ?? $hasilKlasifikasi[1];
                return $row;
            });

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
            'targetProgramTerbaru',
            'totalWilayah',
            'totalKabKota',
            'totalProvinsi',
            'totalIndikator',
            'totalDataIndikator',
            'persenKelengkapan',
            'totalHasilIkp',
            'rataRataIkp',
            'wilayahPrioritasTinggi',
            'distribusiPrioritas',
            'statistikIndikator',
            'hasilPrioritasTerbaru'
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

    // Petugas Data Wilayah (Role 1), Pimpinan (Role 2), & Admin (Role 4)
    Route::middleware('role:1,2,4')->group(function () {
        // PBI-001 & PBI-002: Manajemen Data Wilayah
        Route::get('/wilayah', [WilayahController::class, 'index'])
            ->name('wilayah.index');
        Route::get('/wilayah/create', [WilayahController::class, 'create'])
            ->name('wilayah.create');
        Route::post('/wilayah', [WilayahController::class, 'store'])
            ->name('wilayah.store');
        Route::get('/wilayah/{wilayah}/edit', [WilayahController::class, 'edit'])
            ->name('wilayah.edit');
        Route::put('/wilayah/{wilayah}', [WilayahController::class, 'update'])
            ->name('wilayah.update');
        Route::delete('/wilayah/{wilayah}', [WilayahController::class, 'destroy'])
            ->name('wilayah.destroy');

        // PBI-005 & PBI-006: Data Wilayah & FSVA
        Route::get('/data-wilayah', [DataWilayahFsvaController::class, 'index'])
            ->name('data-wilayah.index');

        // PBI-005: Route Ringkasan Indikator Utama
        Route::get('/ringkasan-indikator', [DataIndikatorController::class, 'ringkasan'])
            ->name('indikator.ringkasan');

        // PBI-008: Halaman Data Indikator per Wilayah
        Route::get('/indikator-wilayah', [DataIndikatorController::class, 'index'])
            ->name('indikator-wilayah.index');

        // PBI-009: Pengelompokan Wilayah Berdasarkan Prioritas
        Route::get('/prioritas-wilayah', [PrioritasWilayahController::class, 'index'])
            ->name('prioritas-wilayah.index');

        Route::post('/prioritas-wilayah/proses', [PrioritasWilayahController::class, 'prosesPengelompokan'])
            ->name('prioritas-wilayah.proses');

        // PBI-010: Melihat Hasil Identifikasi Wilayah Prioritas
        Route::get('/hasil-identifikasi', [HasilIdentifikasiController::class, 'index'])
            ->name('hasil-identifikasi.index');
    });

    // Pengelolaan Data Program (PBI-017 & PBI-018) - Kecuali show karena didefinisikan manual
    Route::resource('program', ProgramController::class)->except(['show']);

    // PBI-019: Route Detail / Show Program Bantuan
    Route::get('/program/{id}', [ProgramController::class, 'show'])->name('program.show');

    // PBI-018: Route khusus eksplisit untuk Edit & Update Program Bantuan (jika dibutuhkan terpisah)
    Route::middleware('role:3,4')->group(function () {
        Route::get('/program/{id}/edit', [ProgramController::class, 'edit'])->name('program.edit');
        Route::put('/program/{id}', [ProgramController::class, 'update'])->name('program.update');
    });

    // Pengelolaan Target Program (PBI-014)
    Route::get('/target-program', [ProgramSasaranController::class, 'index'])
        ->name('target-program.index');

    Route::middleware('role:3,4')->group(function () {
        Route::get('/target-program/create', [ProgramSasaranController::class, 'create'])
            ->name('target-program.create');

        Route::post('/target-program', [ProgramSasaranController::class, 'store'])
            ->name('target-program.store');

        Route::get('/target-program/{id}/edit', [ProgramSasaranController::class, 'edit'])
            ->name('target-program.edit');

        Route::put('/target-program/{id}', [ProgramSasaranController::class, 'update'])
            ->name('target-program.update');

        Route::delete('/target-program/{id}', [ProgramSasaranController::class, 'destroy'])
            ->name('target-program.destroy');

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