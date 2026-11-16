<?php

namespace App\Http\Controllers;

use App\Models\Program;
use App\Models\ProgramSasaran;
use App\Models\KelompokSasaran;
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

        $daftarProgram = Program::orderBy('nama_program')->get();

        $daftarKelompok = KelompokSasaran::with([
            'wilayah',
            'jenisKelompok',
        ])->orderByDesc('tahun')->get();

        return view('target-program.create', compact(
            'daftarProgram',
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
            'id_program' => [
                'required',
                'exists:program,id_program',
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
            'id_program.required' => 'Program bantuan wajib dipilih.',
            'id_program.exists' => 'Program bantuan yang dipilih tidak valid.',
            'id_kelompok.required' => 'Kelompok sasaran wajib dipilih.',
            'id_kelompok.exists' => 'Kelompok sasaran yang dipilih tidak valid.',
            'target_jumlah.required' => 'Jumlah target penerima wajib diisi.',
            'target_jumlah.integer' => 'Jumlah target harus berupa angka bulat.',
            'target_jumlah.min' => 'Jumlah target minimal 1 penerima.',
        ]);

        // Cek duplikasi agar tidak ada target ganda pada kombinasi program & kelompok sasaran yang sama
        $isDuplicate = ProgramSasaran::where('id_program', $validated['id_program'])
            ->where('id_kelompok', $validated['id_kelompok'])
            ->exists();

        if ($isDuplicate) {
            return back()->withInput()->withErrors([
                'id_kelompok' => 'Target untuk program dan kelompok sasaran ini sudah pernah ditambahkan.',
            ]);
        }

        DB::transaction(function () use ($validated) {
            ProgramSasaran::create($validated);
        });

        return redirect()
            ->route('target-program.index')
            ->with('success', 'Target program berhasil ditambahkan.');
    }
}
