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

        // 1. Logika Pencarian (Search)
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('nama_program', 'like', "%{$search}%")
                  ->orWhere('deskripsi', 'like', "%{$search}%")
                  ->orWhere('nama_penanggung_jawab', 'like', "%{$search}%")
                  ->orWhere('instansi', 'like', "%{$search}%")
                  ->orWhereHas('jenisBantuan', function ($jb) use ($search) {
                      $jb->where('nama_bantuan', 'like', "%{$search}%");
                  })
                  ->orWhereHas('wilayah', function ($w) use ($search) {
                      $w->where('provinsi', 'like', "%{$search}%")->orWhere('kabupaten_kota', 'like', "%{$search}%");
                  });
            });
        }

        // 2. Logika Filter Status
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        // 3. Logika Sorting Berdasarkan Tanggal Mulai / Pelaksanaan
        $sort = $request->input('sort', 'latest'); // Default: terbaru
        if ($sort == 'oldest') {
            $query->orderBy('tanggal_pelaksanaan', 'asc');
        } else {
            $query->orderBy('tanggal_pelaksanaan', 'desc');
        }

        $programs = $query->get();

        return view('program.index', [
            'programs' => $programs,
            'search' => $request->input('search', ''),
            'status' => $request->input('status', ''),
            'sort' => $sort,
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
            'tanggal_selesai' => ['nullable', 'date', 'after_or_equal:tanggal_pelaksanaan'],
            'status' => ['required', 'string', 'in:Aktif,Berjalan,Selesai'],
            'deskripsi' => ['nullable', 'string', 'max:1000'],
            'nama_penanggung_jawab' => ['nullable', 'string', 'max:150'],
            'instansi' => ['nullable', 'string', 'max:150'],
        ], [
            'nama_program.required' => 'Nama program wajib diisi.',
            'id_jenis_bantuan.required' => 'Jenis program wajib dipilih.',
            'id_wilayah.required' => 'Wilayah yang dituju wajib dipilih.',
            'tanggal_pelaksanaan.required' => 'Rencana tanggal pelaksanaan wajib diisi.',
            'tanggal_selesai.after_or_equal' => 'Tanggal selesai harus sama atau setelah tanggal pelaksanaan.',
            'status.required' => 'Status program wajib dipilih.',
        ]);

        $userId = Auth::id()
            ?? User::where('email', 'petugas@biteup.com')->value('id_user')
            ?? User::first()?->id_user;

        Program::create([
            'nama_program' => $validated['nama_program'],
            'id_jenis_bantuan' => $validated['id_jenis_bantuan'],
            'id_wilayah' => $validated['id_wilayah'],
            'tanggal_pelaksanaan' => $validated['tanggal_pelaksanaan'],
            'tanggal_mulai' => $validated['tanggal_pelaksanaan'],
            'tanggal_selesai' => $validated['tanggal_selesai'] ?? null,
            'status' => $validated['status'],
            'deskripsi' => $validated['deskripsi'] ?? null,
            'nama_penanggung_jawab' => $validated['nama_penanggung_jawab'] ?? null,
            'instansi' => $validated['instansi'] ?? null,
            'id_user' => $userId,
        ]);

        return redirect()
            ->route('program.index')
            ->with('success', 'Data program bantuan berhasil ditambahkan.');
    }

    /**
     * Display the specified program detail.
     */
    public function show(int $id): View
    {
        // Mengambil data program beserta relasi wilayah, jenisBantuan, dan user
        $program = Program::with(['jenisBantuan', 'wilayah', 'user'])->findOrFail($id);

        return view('program.show', [
            'program' => $program,
        ]);
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
            'tanggal_selesai' => ['nullable', 'date', 'after_or_equal:tanggal_pelaksanaan'],
            'status' => ['required', 'string', 'in:Aktif,Berjalan,Selesai'],
            'deskripsi' => ['nullable', 'string', 'max:1000'],
            'nama_penanggung_jawab' => ['nullable', 'string', 'max:150'],
            'instansi' => ['nullable', 'string', 'max:150'],
        ], [
            'nama_program.required' => 'Nama program wajib diisi.',
            'id_jenis_bantuan.required' => 'Jenis program wajib dipilih.',
            'id_wilayah.required' => 'Wilayah yang dituju wajib dipilih.',
            'tanggal_pelaksanaan.required' => 'Rencana tanggal pelaksanaan wajib diisi.',
            'tanggal_selesai.after_or_equal' => 'Tanggal selesai harus sama atau setelah tanggal pelaksanaan.',
            'status.required' => 'Status program wajib dipilih.',
        ]);

        $program->update([
            'nama_program' => $validated['nama_program'],
            'id_jenis_bantuan' => $validated['id_jenis_bantuan'],
            'id_wilayah' => $validated['id_wilayah'],
            'tanggal_pelaksanaan' => $validated['tanggal_pelaksanaan'],
            'tanggal_mulai' => $validated['tanggal_pelaksanaan'],
            'tanggal_selesai' => $validated['tanggal_selesai'] ?? null,
            'status' => $validated['status'],
            'deskripsi' => $validated['deskripsi'] ?? null,
            'nama_penanggung_jawab' => $validated['nama_penanggung_jawab'] ?? null,
            'instansi' => $validated['instansi'] ?? null,
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