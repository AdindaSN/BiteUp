<?php

namespace App\Http\Controllers;

use App\Models\JenisBantuan;
use App\Models\Program;
use App\Models\User;
use App\Models\Wilayah;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ProgramController extends Controller
{
    /**
     * Display a listing of the programs.
     */
    public function index(Request $request): View
    {
        $query = Program::with(['jenisBantuan', 'wilayah']);

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('nama_program', 'like', "%{$search}%")
                  ->orWhere('deskripsi', 'like', "%{$search}%")
                  ->orWhereHas('jenisBantuan', function ($jb) use ($search) {
                      $jb->where('nama_bantuan', 'like', "%{$search}%");
                  })
                  ->orWhereHas('wilayah', function ($w) use ($search) {
                      $w->where('provinsi', 'like', "%{$search}%")->orWhere('kabupaten_kota', 'like', "%{$search}%");
                  });
            });
        }

        $programs = $query->orderBy('id_program', 'asc')->get();

        return view('program.index', [
            'programs' => $programs,
            'search' => $request->input('search', ''),
        ]);
    }

    /**
     * Show the form for creating a new program.
     */
    public function create(): View
    {
        $jenisBantuan = JenisBantuan::orderBy('nama_bantuan', 'asc')->get();
        $wilayahList = Wilayah::orderBy('provinsi', 'asc')->get();

        return view('program.create', [
            'jenisBantuan' => $jenisBantuan,
            'wilayahList' => $wilayahList,
        ]);
    }

    /**
     * Store a newly created program in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama_program' => ['required', 'string', 'max:150'],
            'id_jenis_bantuan' => ['required', 'exists:jenis_bantuan,id_jenis_bantuan'],
            'id_wilayah' => ['required', 'exists:wilayah,id_wilayah'],
            'tanggal_pelaksanaan' => ['required', 'date'],
            'deskripsi' => ['nullable', 'string', 'max:1000'],
        ], [
            'nama_program.required' => 'Nama program wajib diisi.',
            'nama_program.string' => 'Nama program harus berupa teks.',
            'nama_program.max' => 'Nama program tidak boleh lebih dari 150 karakter.',
            'id_jenis_bantuan.required' => 'Jenis program wajib dipilih.',
            'id_jenis_bantuan.exists' => 'Jenis program yang dipilih tidak valid.',
            'id_wilayah.required' => 'Wilayah yang dituju wajib dipilih.',
            'id_wilayah.exists' => 'Wilayah yang dipilih tidak valid.',
            'tanggal_pelaksanaan.required' => 'Rencana tanggal pelaksanaan wajib diisi.',
            'tanggal_pelaksanaan.date' => 'Format tanggal pelaksanaan tidak valid.',
            'deskripsi.string' => 'Deskripsi program harus berupa teks.',
            'deskripsi.max' => 'Deskripsi program tidak boleh lebih dari 1000 karakter.',
        ]);

        // Prioritize authenticated user, fallback to Petugas Program or first user, or null
        $userId = Auth::id()
            ?? User::where('email', 'petugas@biteup.com')->value('id_user')
            ?? User::first()?->id_user;

        Program::create([
            'nama_program' => $validated['nama_program'],
            'id_jenis_bantuan' => $validated['id_jenis_bantuan'],
            'id_wilayah' => $validated['id_wilayah'],
            'tanggal_pelaksanaan' => $validated['tanggal_pelaksanaan'],
            'tanggal_mulai' => $validated['tanggal_pelaksanaan'],
            'deskripsi' => $validated['deskripsi'] ?? null,
            'status' => 'Aktif',
            'id_user' => $userId,
        ]);

        return redirect()
            ->route('program.index')
            ->with('success', 'Data program bantuan berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified program.
     */
    public function edit(int $id): View
    {
        $program = Program::with(['jenisBantuan', 'wilayah'])->findOrFail($id);
        $jenisBantuan = JenisBantuan::orderBy('nama_bantuan', 'asc')->get();
        $wilayahList = Wilayah::orderBy('provinsi', 'asc')->get();

        return view('program.edit', [
            'program' => $program,
            'jenisBantuan' => $jenisBantuan,
            'wilayahList' => $wilayahList,
        ]);
    }

    /**
     * Update the specified program in storage.
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $program = Program::findOrFail($id);

        $validated = $request->validate([
            'nama_program' => ['required', 'string', 'max:150'],
            'id_jenis_bantuan' => ['required', 'exists:jenis_bantuan,id_jenis_bantuan'],
            'id_wilayah' => ['required', 'exists:wilayah,id_wilayah'],
            'tanggal_pelaksanaan' => ['required', 'date'],
            'deskripsi' => ['nullable', 'string', 'max:1000'],
        ], [
            'nama_program.required' => 'Nama program wajib diisi.',
            'nama_program.string' => 'Nama program harus berupa teks.',
            'nama_program.max' => 'Nama program tidak boleh lebih dari 150 karakter.',
            'id_jenis_bantuan.required' => 'Jenis program wajib dipilih.',
            'id_jenis_bantuan.exists' => 'Jenis program yang dipilih tidak valid.',
            'id_wilayah.required' => 'Wilayah yang dituju wajib dipilih.',
            'id_wilayah.exists' => 'Wilayah yang dipilih tidak valid.',
            'tanggal_pelaksanaan.required' => 'Rencana tanggal pelaksanaan wajib diisi.',
            'tanggal_pelaksanaan.date' => 'Format tanggal pelaksanaan tidak valid.',
            'deskripsi.string' => 'Deskripsi program harus berupa teks.',
            'deskripsi.max' => 'Deskripsi program tidak boleh lebih dari 1000 karakter.',
        ]);

        $program->update([
            'nama_program' => $validated['nama_program'],
            'id_jenis_bantuan' => $validated['id_jenis_bantuan'],
            'id_wilayah' => $validated['id_wilayah'],
            'tanggal_pelaksanaan' => $validated['tanggal_pelaksanaan'],
            'tanggal_mulai' => $validated['tanggal_pelaksanaan'],
            'deskripsi' => $validated['deskripsi'] ?? null,
        ]);

        return redirect()
            ->route('program.index')
            ->with('success', 'Data program bantuan berhasil diperbarui.');
    }

    /**
     * Remove the specified program from storage.
     */
    public function destroy(int $id): RedirectResponse
    {
        try {
            $program = Program::findOrFail($id);
            $nama = $program->nama_program;
            $program->delete();

            return redirect()
                ->route('program.index')
                ->with('success', 'Data program bantuan berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()
                ->route('program.index')
                ->with('error', 'Gagal menghapus program bantuan: ' . $e->getMessage());
        }
    }
}
