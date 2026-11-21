<?php

namespace App\Http\Controllers;

use App\Models\Wilayah;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class WilayahController extends Controller
{
    public function index(Request $request)
    {
        $query = Wilayah::with('parent');

        if ($request->filled('cari')) {
            $keyword = $request->cari;
            $query->where(function ($q) use ($keyword) {
                $q->where('provinsi', 'like', '%' . $keyword . '%')
                  ->orWhere('kabupaten_kota', 'like', '%' . $keyword . '%')
                  ->orWhere('kode_wilayah', 'like', '%' . $keyword . '%');
            });
        }

        if ($request->filled('jenis')) {
            if ($request->jenis === 'provinsi') {
                $query->where(function ($q) {
                    $q->whereNull('kabupaten_kota')
                      ->orWhere('kabupaten_kota', '')
                      ->orWhere('kabupaten_kota', '-');
                });
            } elseif ($request->jenis === 'kabupaten_kota') {
                $query->whereNotNull('kabupaten_kota')
                      ->where('kabupaten_kota', '!=', '')
                      ->where('kabupaten_kota', '!=', '-');
            } elseif ($request->jenis === 'kecamatan') {
                $query->whereRaw('1 = 0');
            }
        }

        $wilayah = $query->orderBy('kode_wilayah')
            ->orderBy('provinsi')
            ->orderBy('kabupaten_kota')
            ->paginate(10)
            ->withQueryString();

        return view('wilayah.index', compact('wilayah'));
    }

    public function create()
    {
        $induk = Wilayah::orderBy('kode_wilayah')
            ->orderBy('provinsi')
            ->orderBy('kabupaten_kota')
            ->get();

        return view('wilayah.create', compact('induk'));
    }

    public function store(Request $request)
    {
        $validated = $this->validateWilayah($request);
        $level = (int) $validated['level'];

        $wilayah = new Wilayah();
        $wilayah->kode_wilayah = $validated['kode_wilayah'];
        $wilayah->tahun = (int) ($request->input('tahun') ?: date('Y'));

        if ($level === 1) {
            $wilayah->provinsi = $validated['nama_wilayah'];
            $wilayah->kabupaten_kota = '-';
            $wilayah->parent_id = null;
        } else {
            $parent = !empty($validated['parent_id']) ? Wilayah::find($validated['parent_id']) : null;
            $wilayah->provinsi = $parent ? $parent->provinsi : $validated['nama_wilayah'];
            $wilayah->kabupaten_kota = $validated['nama_wilayah'];
            $wilayah->parent_id = $validated['parent_id'] ?? null;
        }

        $wilayah->save();

        return redirect()
            ->route('wilayah.index')
            ->with('success', 'Data wilayah berhasil ditambahkan!');
    }

    public function edit(Wilayah $wilayah)
    {
        $induk = Wilayah::where('id_wilayah', '!=', $wilayah->id_wilayah)
            ->orderBy('kode_wilayah')
            ->orderBy('provinsi')
            ->orderBy('kabupaten_kota')
            ->get();

        return view('wilayah.edit', compact('wilayah', 'induk'));
    }

    public function update(Request $request, Wilayah $wilayah)
    {
        $validated = $this->validateWilayah($request, $wilayah);
        $level = (int) $validated['level'];

        $wilayah->kode_wilayah = $validated['kode_wilayah'];
        $wilayah->tahun = (int) ($request->input('tahun') ?: ($wilayah->tahun ?: date('Y')));

        if ($level === 1) {
            $wilayah->provinsi = $validated['nama_wilayah'];
            $wilayah->kabupaten_kota = '-';
            $wilayah->parent_id = null;
        } else {
            $parent = !empty($validated['parent_id']) ? Wilayah::find($validated['parent_id']) : null;
            $wilayah->provinsi = $parent ? $parent->provinsi : $validated['nama_wilayah'];
            $wilayah->kabupaten_kota = $validated['nama_wilayah'];
            $wilayah->parent_id = $validated['parent_id'] ?? null;
        }

        $wilayah->save();

        return redirect()
            ->route('wilayah.index')
            ->with('success', 'Data wilayah berhasil diperbarui!');
    }

    public function destroy(Wilayah $wilayah)
    {
        try {
            $nama = $wilayah->nama_wilayah ?? $wilayah->kabupaten_kota ?? $wilayah->provinsi;
            $wilayah->delete();

            return redirect()
                ->route('wilayah.index')
                ->with('success', "Data wilayah \"{$nama}\" berhasil dihapus!");
        } catch (QueryException $e) {
            return redirect()
                ->route('wilayah.index')
                ->with('error', 'Data wilayah tidak dapat dihapus karena masih memiliki wilayah bawahan atau terhubung dengan data lain.');
        }
    }

    private function validateWilayah(Request $request, ?Wilayah $wilayah = null): array
    {
        $uniqueKodeRule = Rule::unique('wilayah', 'kode_wilayah');
        if ($wilayah) {
            $uniqueKodeRule->ignore($wilayah->id_wilayah, 'id_wilayah');
        }

        return $request->validate([
            'kode_wilayah'    => ['required', 'string', 'max:50', $uniqueKodeRule],
            'nama_wilayah'    => ['required', 'string', 'max:150'],
            'level'           => ['required', 'in:1,2,3'],
            'parent_id'       => [
                'nullable',
                'required_if:level,2,3',
                'exists:wilayah,id_wilayah',
                function ($attribute, $value, $fail) use ($request, $wilayah) {
                    if (!$value) {
                        return;
                    }
                    if ($wilayah && (int) $value === (int) $wilayah->id_wilayah) {
                        $fail('Wilayah tidak boleh menjadi induk bagi dirinya sendiri.');
                        return;
                    }
                    $parent = Wilayah::find($value);
                    $level = (int) $request->input('level');
                    if ($level === 2 && (!$parent || (int) $parent->level !== 1)) {
                        $fail('Wilayah induk untuk Kabupaten / Kota harus berupa Provinsi.');
                    }
                    if ($level === 3 && (!$parent || (int) $parent->level !== 2)) {
                        $fail('Wilayah induk untuk Kecamatan harus berupa Kabupaten / Kota.');
                    }
                },
            ],
            'jumlah_penduduk' => ['nullable', 'integer', 'min:0'],
            'latitude'        => ['nullable', 'numeric', 'between:-90,90'],
            'longitude'       => ['nullable', 'numeric', 'between:-180,180'],
        ], [
            'kode_wilayah.required' => 'Kode wilayah wajib diisi.',
            'kode_wilayah.unique'   => 'Kode wilayah sudah terdaftar dalam sistem.',
            'nama_wilayah.required' => 'Nama wilayah wajib diisi.',
            'level.required'        => 'Jenis wilayah wajib dipilih.',
            'level.in'              => 'Jenis wilayah tidak valid.',
            'parent_id.required_if' => 'Wilayah induk wajib dipilih untuk Kabupaten / Kota maupun Kecamatan.',
            'parent_id.exists'      => 'Wilayah induk yang dipilih tidak valid.',
        ]);
    }
}