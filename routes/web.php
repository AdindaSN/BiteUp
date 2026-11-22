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

        // ==========================================
        // LOGIKA RINGKASAN SEBARAN WILAYAH (PBI-007)
        // ==========================================
        $ringkasanPrioritas = [
            'Sangat Prioritas' => $hasilIkpRows->filter(fn($r) => (float)$r->skor_ikp < 45.60)->count(),
            'Prioritas'        => $hasilIkpRows->filter(fn($r) => (float)$r->skor_ikp >= 45.60 && (float)$r->skor_ikp <= 53.42)->count(),
            'Cukup'            => $hasilIkpRows->filter(fn($r) => (float)$r->skor_ikp > 53.42 && (float)$r->skor_ikp <= 69.52)->count(),
            'Tidak Prioritas'  => $hasilIkpRows->filter(fn($r) => (float)$r->skor_ikp > 69.52)->count(),
        ];

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
                [$level, $namaKat] = $klasifikasiIkp($row->skor_ikp);
                $row->level_prioritas = $level;
                $row->label_prioritas = $row->kategori_prioritas ?? $namaKat;
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
            'ringkasanPrioritas',
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