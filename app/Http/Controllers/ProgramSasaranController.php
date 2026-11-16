<?php

namespace App\Http\Controllers;

use App\Models\DataIndikator;
use App\Models\Indikator;
use App\Models\JenisBantuan;
use App\Models\JenisKelompok;
use App\Models\KelompokSasaran;
use App\Models\Program;
use App\Models\ProgramSasaran;
use App\Models\Wilayah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProgramSasaranController extends Controller
{
    /**
     * Memeriksa otorisasi hak akses untuk manajemen target program.
     * Hanya Petugas Program (Role 3) dan Pengelola Sistem Bapanas (Role 4) yang berwenang.
     */
    private function authorizeManageTarget(): void
    {
        abort_unless(
            auth()->check() && in_array((int) auth()->user()->id_roles, [3, 4], true),
            403,
            'Anda tidak memiliki hak akses untuk mengelola target program.'
        );
    }

    /**
     * Menampilkan daftar target program.
     */
    public function index(Request $request)
    {
        $query = ProgramSasaran::with([
            'program',
            'kelompokSasaran.wilayah',
            'kelompokSasaran.jenisKelompok',
        ]);

        // Fitur pencarian opsional berdasarkan nama program, wilayah, atau tahun
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->whereHas('program', function ($p) use ($search) {
                    $p->where('nama_program', 'like', "%{$search}%");
                })->orWhereHas('kelompokSasaran.wilayah', function ($w) use ($search) {
                    $w->where('nama_wilayah', 'like', "%{$search}%");
                })->orWhereHas('kelompokSasaran', function ($k) use ($search) {
                    $k->where('tahun', 'like', "%{$search}%");
                });
            });
        }

        $dataTarget = $query->orderByDesc('id_program_sasaran')
            ->paginate(10)
            ->withQueryString();

        return view('target-program.index', compact('dataTarget'));
    }

    /**
     * Menampilkan formulir penambahan target program baru.
     */
    public function create()
    {
        $this->authorizeManageTarget();

        // Daftar wilayah kabupaten/kota (level 2) untuk pilihan wilayah prioritas
        $daftarWilayah = Wilayah::where('level', 2)
            ->orderBy('nama_wilayah')
            ->get();

        // Daftar jenis kelompok sasaran
        $daftarJenisKelompok = JenisKelompok::orderBy('nama_jenis')->get();

        // Daftar kelompok sasaran yang sudah ada (untuk reference)
        $daftarKelompok = KelompokSasaran::with([
            'wilayah',
            'jenisKelompok',
        ])->orderByDesc('tahun')->get();

        return view('target-program.create', compact(
            'daftarWilayah',
            'daftarJenisKelompok',
            'daftarKelompok'
        ));
    }

    /**
     * Menyimpan target program baru ke database.
     */
    public function store(Request $request)
    {
        $this->authorizeManageTarget();

        $validated = $request->validate([
            'nama_program' => [
                'required',
                'string',
                'max:150',
            ],
            'id_kelompok' => [
                'required',
                'exists:kelompok_sasaran,id_kelompok_sasaran',
            ],
            'target_jumlah' => [
                'required',
                'integer',
                'min:1',
            ],
        ], [
            'nama_program.required' => 'Nama program bantuan wajib diisi.',
            'nama_program.max' => 'Nama program bantuan maksimal 150 karakter.',
            'id_kelompok.required' => 'Kelompok sasaran wajib dipilih.',
            'id_kelompok.exists' => 'Kelompok sasaran yang dipilih tidak valid.',
            'target_jumlah.required' => 'Jumlah target penerima wajib diisi.',
            'target_jumlah.integer' => 'Jumlah target harus berupa angka bulat.',
            'target_jumlah.min' => 'Jumlah target minimal 1 penerima.',
        ]);

        // Cari atau buat program berdasarkan nama yang diketik
        $program = DB::transaction(function () use ($validated) {
            // Cari program yang sudah ada dengan nama yang sama
            $program = Program::where('nama_program', $validated['nama_program'])->first();

            if (!$program) {
                // Buat program baru dengan data default
                $jenisBantuan = JenisBantuan::first();

                $program = Program::create([
                    'id_user' => auth()->user()->id_user,
                    'id_jenis_bantuan' => $jenisBantuan ? $jenisBantuan->id_jenis_bantuan : 1,
                    'nama_program' => $validated['nama_program'],
                    'tanggal_mulai' => now()->toDateString(),
                    'tanggal_selesai' => now()->addYear()->toDateString(),
                    'status' => 'Berjalan',
                ]);
            }

            return $program;
        });

        // Cek duplikasi agar tidak ada target ganda pada kombinasi program & kelompok sasaran yang sama
        $isDuplicate = ProgramSasaran::where('id_program', $program->id_program)
            ->where('id_kelompok', $validated['id_kelompok'])
            ->exists();

        if ($isDuplicate) {
            return back()->withInput()->withErrors([
                'id_kelompok' => 'Target untuk program dan kelompok sasaran ini sudah pernah ditambahkan.',
            ]);
        }

        DB::transaction(function () use ($program, $validated) {
            ProgramSasaran::create([
                'id_program' => $program->id_program,
                'id_kelompok' => $validated['id_kelompok'],
                'target_jumlah' => $validated['target_jumlah'],
            ]);
        });

        return redirect()
            ->route('target-program.index')
            ->with('success', 'Target program berhasil ditambahkan.');
    }

    /**
     * API: Mengambil data FSVA berdasarkan wilayah terpilih.
     */
    public function getFsvaData(Request $request)
    {
        $request->validate([
            'id_wilayah' => 'required|integer|exists:wilayah,id_wilayah',
        ]);

        $wilayah = Wilayah::with('parent')->find($request->id_wilayah);

        // Ambil data indikator FSVA untuk wilayah terpilih
        $dataFsva = DataIndikator::where('id_wilayah', $request->id_wilayah)
            ->with('indikator')
            ->orderBy('id_indikator')
            ->get()
            ->map(function ($item) {
                return [
                    'kode' => $item->indikator->kode ?? '-',
                    'nama' => $item->indikator->nama_indikator ?? '-',
                    'dimensi' => $item->indikator->dimensi ?? '-',
                    'nilai' => $item->nilai,
                    'satuan' => $item->indikator->satuan ?? '-',
                    'tahun' => $item->tahun,
                    'sumber_data' => $item->sumber_data,
                ];
            });

        // Ambil IKP dan PKOMP jika ada
        $ikpData = $dataFsva->firstWhere('kode', 'IKP');

        return response()->json([
            'wilayah' => [
                'id' => $wilayah->id_wilayah,
                'nama' => $wilayah->nama_wilayah,
                'kode' => $wilayah->kode_wilayah,
                'provinsi' => $wilayah->parent->nama_wilayah ?? '-',
            ],
            'fsva_tersedia' => $dataFsva->isNotEmpty(),
            'ikp' => $ikpData ? $ikpData['nilai'] : null,
            'indikator' => $dataFsva,
        ]);
    }

    /**
     * API: Mengambil daftar kelompok sasaran berdasarkan wilayah.
     */
    public function getKelompokByWilayah(Request $request)
    {
        $request->validate([
            'id_wilayah' => 'required|integer|exists:wilayah,id_wilayah',
        ]);

        $kelompok = KelompokSasaran::where('id_wilayah', $request->id_wilayah)
            ->with('jenisKelompok')
            ->orderByDesc('tahun')
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id_kelompok_sasaran,
                    'jenis' => $item->jenisKelompok->nama_jenis ?? '-',
                    'jumlah' => $item->jumlah,
                    'tahun' => $item->tahun,
                ];
            });

        return response()->json($kelompok);
    }
}
