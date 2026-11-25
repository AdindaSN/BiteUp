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
        $query = Wilayah::with('parent')
            ->withMax('hasilIkp', 'tahun')
            ->withMax('dataIndikator', 'tahun');

        if ($request->filled('cari')) {
            $keyword = $request->cari;
            $query->where(function ($q) use ($keyword) {
                $q->where('kode_wilayah', 'like', '%' . $keyword . '%')
                  ->orWhere('provinsi', 'like', '%' . $keyword . '%')
                  ->orWhere('kabupaten_kota', 'like', '%' . $keyword . '%');
            });
        }

        if ($request->filled('jenis')) {
            if ($request->jenis === 'provinsi') {
                $query->where(function ($q) {
                    $q->whereNull('kabupaten_kota')->orWhere('kabupaten_kota', '');
                });
            } elseif ($request->jenis === 'kabupaten_kota') {
                $query->whereNotNull('kabupaten_kota')
                      ->where('kabupaten_kota', '!=', '');
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
        $induk = $this->getDaftarProvinsi();

        return view('wilayah.create', compact('induk'));
    }

    public function store(Request $request)
    {
        $validated = $this->validateWilayah($request);
        $level = (int) $validated['level'];

        $wilayah = new Wilayah();
        $this->fillWilayahData($wilayah, $validated, $level);
        $wilayah->save();

        return redirect()
            ->route('wilayah.index')
            ->with('success', 'Data wilayah berhasil ditambahkan!');
    }

    public function edit(Wilayah $wilayah)
    {
        $induk = $this->getDaftarProvinsi();

        $selectedParentId = null;
        if ($wilayah->level === 2 && !empty($wilayah->provinsi)) {
            $matched = $induk->firstWhere('provinsi', $wilayah->provinsi);
            $selectedParentId = $matched ? $matched->id_wilayah : null;
        }

        $tahunDefault = $wilayah->tahun
            ?? $wilayah->hasilIkp()->max('tahun')
            ?? $wilayah->dataIndikator()->max('tahun');

        return view('wilayah.edit', compact('wilayah', 'induk', 'selectedParentId', 'tahunDefault'));
    }

    public function update(Request $request, Wilayah $wilayah)
    {
        $validated = $this->validateWilayah($request, $wilayah);
        $level = (int) $validated['level'];

        $this->fillWilayahData($wilayah, $validated, $level);
        $wilayah->save();

        return redirect()
            ->route('wilayah.index')
            ->with('success', 'Data wilayah berhasil diperbarui!');
    }

    public function destroy(Wilayah $wilayah)
    {
        try {
            $nama = $wilayah->nama_wilayah;
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

    private function getDaftarProvinsi()
    {
        return Wilayah::selectRaw('MIN(id_wilayah) as id_wilayah, provinsi')
            ->whereNotNull('provinsi')
            ->where('provinsi', '!=', '')
            ->groupBy('provinsi')
            ->orderBy('provinsi')
            ->get();
    }

    private function fillWilayahData(Wilayah $wilayah, array $validated, int $level): void
    {
        $wilayah->kode_wilayah = $validated['kode_wilayah'];
        $wilayah->tahun = (int) $validated['tahun'];

        if ($level === 1) {
            $wilayah->parent_id = null;
            $wilayah->provinsi = $validated['nama_wilayah'];
            $wilayah->kabupaten_kota = null;
        } else {
            $parent = Wilayah::findOrFail($validated['parent_id']);
            $wilayah->parent_id = $parent->level === 1 ? $parent->id_wilayah : null;
            $wilayah->provinsi = $parent->provinsi;
            $wilayah->kabupaten_kota = $validated['nama_wilayah'];
        }
    }

    private function validateWilayah(Request $request, ?Wilayah $wilayah = null): array
    {
        $uniqueKodeRule = Rule::unique('wilayah', 'kode_wilayah');
        if ($wilayah) {
            $uniqueKodeRule->ignore($wilayah->id_wilayah, 'id_wilayah');
        }

        return $request->validate([
            'kode_wilayah' => ['required', 'string', 'max:50', $uniqueKodeRule],
            'nama_wilayah' => ['required', 'string', 'max:150'],
            'level'        => ['required', 'in:1,2'],
            'parent_id'    => [
                'nullable',
                'required_if:level,2',
                'exists:wilayah,id_wilayah',
                function ($attribute, $value, $fail) use ($request) {
                    if (!$value) {
                        return;
                    }
                    $parent = Wilayah::find($value);
                    if ((int) $request->input('level') === 2 && (!$parent || empty($parent->provinsi))) {
                        $fail('Wilayah induk provinsi tidak valid.');
                    }
                },
            ],
            'tahun'        => ['required', 'integer', 'digits:4', 'min:2000', 'max:2100'],
        ], [
            'kode_wilayah.required' => 'Kode wilayah wajib diisi.',
            'kode_wilayah.unique'   => 'Kode wilayah sudah terdaftar dalam sistem.',
            'nama_wilayah.required' => 'Nama wilayah wajib diisi.',
            'level.required'        => 'Jenis wilayah wajib dipilih.',
            'level.in'              => 'Jenis wilayah tidak valid.',
            'parent_id.required_if' => 'Wilayah induk (Provinsi) wajib dipilih untuk Kabupaten / Kota.',
            'parent_id.exists'      => 'Wilayah induk yang dipilih tidak valid.',
            'tahun.required'        => 'Tahun wajib diisi.',
            'tahun.integer'         => 'Tahun harus berupa angka.',
            'tahun.digits'          => 'Tahun harus terdiri dari 4 digit angka (contoh: 2025).',
            'tahun.min'             => 'Tahun minimal 2000.',
            'tahun.max'             => 'Tahun maksimal 2100.',
        ]);
    }
}