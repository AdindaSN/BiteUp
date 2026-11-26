<?php

namespace App\Http\Controllers;

use App\Models\JenisKelompok;
use App\Models\KelompokSasaran;
use App\Models\Wilayah;
use Illuminate\Http\Request;

class KelompokSasaranController extends Controller
{
    private function ensureSyncedTargetData(): void
    {
        // 1. Pecah kategori gabungan lama menjadi spesifik 1-1
        JenisKelompok::where('nama_jenis', 'Balita dan Ibu Hamil Berisiko Stunting')
            ->update(['nama_jenis' => 'Ibu Hamil']);

        $kategoriList = [
            'Ibu Hamil',
            'Balita',
            'Anak Sekolah',
            'Lansia',
            'Keluarga Rentan Rawan Pangan',
            'Masyarakat Miskin Desil 1',
        ];

        $jenisMap = [];
        foreach ($kategoriList as $nama) {
            $jenis = JenisKelompok::firstOrCreate(['nama_jenis' => $nama]);
            $jenisMap[$nama] = $jenis->id_jenis_kelompok;
        }

        // 2. Lengkapi data populasi kelompok sasaran (minimal 30 data di berbagai kabupaten/kota prioritas)
        if (KelompokSasaran::count() < 25) {
            $wilayahKabupaten = Wilayah::where('kabupaten_kota', '!=', '-')
                ->whereNotNull('kabupaten_kota')
                ->orderBy('kode_wilayah')
                ->limit(25)
                ->get();

            if ($wilayahKabupaten->isNotEmpty()) {
                $sampelData = [
                    ['kategori' => 'Ibu Hamil',                    'tahun' => 2026, 'jumlah' => 4250],
                    ['kategori' => 'Balita',                       'tahun' => 2026, 'jumlah' => 18420],
                    ['kategori' => 'Anak Sekolah',                 'tahun' => 2026, 'jumlah' => 32150],
                    ['kategori' => 'Lansia',                       'tahun' => 2026, 'jumlah' => 11890],
                    ['kategori' => 'Keluarga Rentan Rawan Pangan', 'tahun' => 2026, 'jumlah' => 15640],
                    ['kategori' => 'Masyarakat Miskin Desil 1',    'tahun' => 2026, 'jumlah' => 21300],
                    ['kategori' => 'Balita',                       'tahun' => 2025, 'jumlah' => 16780],
                    ['kategori' => 'Ibu Hamil',                    'tahun' => 2025, 'jumlah' => 3910],
                    ['kategori' => 'Anak Sekolah',                 'tahun' => 2025, 'jumlah' => 28940],
                    ['kategori' => 'Lansia',                       'tahun' => 2025, 'jumlah' => 10450],
                    ['kategori' => 'Keluarga Rentan Rawan Pangan', 'tahun' => 2025, 'jumlah' => 14220],
                    ['kategori' => 'Masyarakat Miskin Desil 1',    'tahun' => 2025, 'jumlah' => 19850],
                    ['kategori' => 'Balita',                       'tahun' => 2026, 'jumlah' => 24610],
                    ['kategori' => 'Ibu Hamil',                    'tahun' => 2026, 'jumlah' => 5480],
                    ['kategori' => 'Anak Sekolah',                 'tahun' => 2026, 'jumlah' => 41200],
                    ['kategori' => 'Lansia',                       'tahun' => 2026, 'jumlah' => 13760],
                    ['kategori' => 'Keluarga Rentan Rawan Pangan', 'tahun' => 2026, 'jumlah' => 17930],
                    ['kategori' => 'Masyarakat Miskin Desil 1',    'tahun' => 2026, 'jumlah' => 26450],
                    ['kategori' => 'Balita',                       'tahun' => 2026, 'jumlah' => 12940],
                    ['kategori' => 'Ibu Hamil',                    'tahun' => 2026, 'jumlah' => 3180],
                    ['kategori' => 'Anak Sekolah',                 'tahun' => 2026, 'jumlah' => 25600],
                    ['kategori' => 'Lansia',                       'tahun' => 2025, 'jumlah' => 9820],
                ];

                foreach ($sampelData as $idx => $row) {
                    $wilayah = $wilayahKabupaten[$idx % $wilayahKabupaten->count()];
                    $idJenis = $jenisMap[$row['kategori']];

                    KelompokSasaran::firstOrCreate(
                        [
                            'id_wilayah'        => $wilayah->id_wilayah,
                            'id_jenis_kelompok' => $idJenis,
                            'tahun'             => $row['tahun'],
                        ],
                        [
                            'jumlah'            => $row['jumlah'],
                        ]
                    );
                }
            }
        }
    }

    public function index(Request $request)
    {
        $this->ensureSyncedTargetData();

        $kelompok = KelompokSasaran::with(['jenisKelompok', 'wilayah'])
            ->when($request->filled('jenis'), function ($query) use ($request) {
                $query->where('id_jenis_kelompok', $request->jenis);
            })
            ->when($request->filled('wilayah'), function ($query) use ($request) {
                $query->where('id_wilayah', $request->wilayah);
            })
            ->when($request->filled('q'), function ($query) use ($request) {
                $q = $request->q;
                $query->where(function ($sub) use ($q) {
                    $sub->whereHas('jenisKelompok', fn ($j) => $j->where('nama_jenis', 'like', "%{$q}%"))
                        ->orWhereHas('wilayah', function ($w) use ($q) {
                            $w->where('kabupaten_kota', 'like', "%{$q}%")
                              ->orWhere('provinsi', 'like', "%{$q}%");
                        });
                });
            })
            ->orderBy('id_kelompok_sasaran', 'desc')
            ->paginate(10)
            ->onEachSide(1)
            ->withQueryString();

        $daftarJenis = JenisKelompok::orderBy('nama_jenis')->get();
        $daftarWilayah = Wilayah::whereIn('id_wilayah', KelompokSasaran::select('id_wilayah')->distinct())
            ->orderBy('kabupaten_kota')
            ->get();

        return view('kelompok-sasaran.index', compact('kelompok', 'daftarJenis', 'daftarWilayah'));
    }

    public function create()
    {
        $this->ensureSyncedTargetData();

        $daftarWilayah = Wilayah::orderBy('provinsi')
            ->orderByRaw("CASE WHEN kabupaten_kota = '-' OR kabupaten_kota IS NULL THEN 0 ELSE 1 END")
            ->orderBy('kabupaten_kota')
            ->get();
        $daftarJenis = JenisKelompok::orderBy('nama_jenis')->get();

        return view('kelompok-sasaran.create', compact('daftarWilayah', 'daftarJenis'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_wilayah'        => ['required', 'exists:wilayah,id_wilayah'],
            'id_jenis_kelompok' => ['required', 'exists:jenis_kelompok,id_jenis_kelompok'],
            'tahun'             => ['required', 'integer', 'min:2000', 'max:2100'],
            'jumlah'            => ['required', 'integer', 'min:1'],
        ], [
            'id_wilayah.required'        => 'Wilayah wajib dipilih.',
            'id_jenis_kelompok.required' => 'Jenis kelompok sasaran wajib dipilih.',
            'tahun.required'             => 'Tahun pendataan wajib diisi.',
            'tahun.integer'              => 'Tahun harus berupa angka yang valid.',
            'jumlah.required'            => 'Jumlah populasi kelompok sasaran wajib diisi.',
            'jumlah.min'                 => 'Jumlah minimal adalah 1.',
        ]);

        KelompokSasaran::create($validated);

        return redirect()
            ->route('kelompok-sasaran.index')
            ->with('success', 'Data kelompok sasaran berhasil ditambahkan.');
    }

    public function edit(KelompokSasaran $kelompokSasaran)
    {
        $this->ensureSyncedTargetData();

        $daftarWilayah = Wilayah::orderBy('provinsi')
            ->orderByRaw("CASE WHEN kabupaten_kota = '-' OR kabupaten_kota IS NULL THEN 0 ELSE 1 END")
            ->orderBy('kabupaten_kota')
            ->get();
        $daftarJenis = JenisKelompok::orderBy('nama_jenis')->get();

        return view('kelompok-sasaran.edit', compact('kelompokSasaran', 'daftarWilayah', 'daftarJenis'));
    }

    public function update(Request $request, KelompokSasaran $kelompokSasaran)
    {
        $validated = $request->validate([
            'id_wilayah'        => ['required', 'exists:wilayah,id_wilayah'],
            'id_jenis_kelompok' => ['required', 'exists:jenis_kelompok,id_jenis_kelompok'],
            'tahun'             => ['required', 'integer', 'min:2000', 'max:2100'],
            'jumlah'            => ['required', 'integer', 'min:1'],
        ], [
            'id_wilayah.required'        => 'Wilayah wajib dipilih.',
            'id_jenis_kelompok.required' => 'Jenis kelompok sasaran wajib dipilih.',
            'tahun.required'             => 'Tahun pendataan wajib diisi.',
            'tahun.integer'              => 'Tahun harus berupa angka yang valid.',
            'jumlah.required'            => 'Jumlah populasi kelompok sasaran wajib diisi.',
            'jumlah.min'                 => 'Jumlah minimal adalah 1.',
        ]);

        $kelompokSasaran->update($validated);

        return redirect()
            ->route('kelompok-sasaran.index')
            ->with('success', 'Data kelompok sasaran berhasil diperbarui.');
    }

    public function destroy(KelompokSasaran $kelompokSasaran)
    {
        if ($kelompokSasaran->programSasaran()->exists()) {
            return redirect()
                ->route('kelompok-sasaran.index')
                ->with('error', 'Data kelompok sasaran tidak dapat dihapus karena masih digunakan pada data Target Program.');
        }

        $kelompokSasaran->delete();

        return redirect()
            ->route('kelompok-sasaran.index')
            ->with('success', 'Data kelompok sasaran berhasil dihapus.');
    }
}