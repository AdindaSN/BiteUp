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
            $cari = trim($request->cari);
            $query->where(function ($q) use ($cari) {
                $q->where('provinsi', 'like', "%{$cari}%")
                  ->orWhere('kabupaten_kota', 'like', "%{$cari}%")
                  ->orWhere('kode_wilayah', 'like', "%{$cari}%");
            });
        }

        if ($request->filled('provinsi')) {
            $query->where('provinsi', $request->provinsi);
        }

        $wilayah = $query->orderBy('kode_wilayah', 'asc')
            ->paginate(15)
            ->withQueryString();

        $daftarProvinsi = Wilayah::whereNotNull('provinsi')
            ->where('provinsi', '!=', '')
            ->distinct()
            ->orderBy('provinsi')
            ->pluck('provinsi');

        return view('wilayah.index', compact('wilayah', 'daftarProvinsi'));
    }

    public function create()
    {
        $daftarProvinsi = $this->getProvinsiIndukList();

        return view('wilayah.create', compact('daftarProvinsi'));
    }

    public function store(Request $request)
    {
        $data = $this->validateWilayah($request);

        Wilayah::create($data);

        $nama = $data['kabupaten_kota'] ?? $data['provinsi'];

        return redirect()->route('wilayah.index')
            ->with('success', "Data wilayah \"{$nama}\" (Kode: {$data['kode_wilayah']}) berhasil ditambahkan.");
    }

    public function edit($id)
    {
        $wilayah = Wilayah::findOrFail($id);
        $daftarProvinsi = $this->getProvinsiIndukList($wilayah->id_wilayah);

        return view('wilayah.edit', compact('wilayah', 'daftarProvinsi'));
    }

    public function update(Request $request, $id)
    {
        $wilayah = Wilayah::findOrFail($id);
        $data = $this->validateWilayah($request, $wilayah->id_wilayah);

        $wilayah->update($data);

        $nama = $data['kabupaten_kota'] ?? $data['provinsi'];

        return redirect()->route('wilayah.index')
            ->with('success', "Data wilayah \"{$nama}\" (Kode: {$data['kode_wilayah']}) berhasil diperbarui.");
    }

    public function destroy($id)
    {
        $wilayah = Wilayah::findOrFail($id);
        $nama = $wilayah->kabupaten_kota ?: $wilayah->provinsi;

        if ($wilayah->children()->exists()) {
            return redirect()->route('wilayah.index')
                ->with('error', "Provinsi \"{$nama}\" tidak dapat dihapus karena masih memiliki kabupaten/kota di bawahnya.");
        }

        try {
            $wilayah->delete();

            return redirect()->route('wilayah.index')
                ->with('success', "Data wilayah \"{$nama}\" berhasil dihapus.");
        } catch (QueryException $e) {
            return redirect()->route('wilayah.index')
                ->with('error', "Wilayah \"{$nama}\" tidak dapat dihapus karena masih terhubung dengan data indikator FSVA atau program.");
        }
    }

    private function getProvinsiIndukList($excludeId = null)
    {
        $query = Wilayah::where(function ($q) {
            $q->whereNull('kabupaten_kota')
              ->orWhere('kabupaten_kota', '');
        })->orderBy('provinsi');

        if ($excludeId) {
            $query->where('id_wilayah', '!=', $excludeId);
        }

        return $query->get();
    }

    private function validateWilayah(Request $request, $ignoreId = null): array
    {
        $cleanNama = preg_replace('/\s+/', ' ', trim((string) $request->input('nama_wilayah')));

        $request->merge([
            'kode_wilayah' => trim((string) $request->input('kode_wilayah')),
            'nama_wilayah' => $cleanNama,
            'tahun' => (int) date('Y'),
        ]);

        $validated = $request->validate([
            'level' => ['required', 'in:1,2'],
            'kode_wilayah' => [
                'required',
                'regex:/^[0-9]+$/',
                'max:20',
                Rule::unique('wilayah', 'kode_wilayah')->ignore($ignoreId, 'id_wilayah'),
            ],
            'nama_wilayah' => [
                'required',
                'string',
                'max:150',
                function ($attribute, $value, $fail) use ($request, $ignoreId) {
                    $normalizedNama = mb_strtolower(preg_replace('/\s+/', ' ', trim((string) $value)), 'UTF-8');
                    $level = (string) $request->input('level');

                    $duplicateQuery = Wilayah::query();

                    if ($ignoreId) {
                        $duplicateQuery->where('id_wilayah', '!=', $ignoreId);
                    }

                    if ($level === '1') {
                        $duplicateQuery->where(function ($q) {
                            $q->whereNull('kabupaten_kota')
                              ->orWhere('kabupaten_kota', '');
                        })->whereRaw('LOWER(TRIM(provinsi)) = ?', [$normalizedNama]);
                    } else {
                        $duplicateQuery->whereNotNull('kabupaten_kota')
                            ->where('kabupaten_kota', '!=', '')
                            ->whereRaw('LOWER(TRIM(kabupaten_kota)) = ?', [$normalizedNama]);
                    }

                    if ($duplicateQuery->exists()) {
                        $fail('Nama wilayah sudah ada. Silakan masukkan nama daerah yang berbeda.');
                    }
                },
            ],
            'parent_id' => [
                'nullable',
                'required_if:level,2',
                function ($attribute, $value, $fail) use ($request, $ignoreId) {
                    if ((string) $request->input('level') === '2') {
                        if ($ignoreId && (int) $value === (int) $ignoreId) {
                            $fail('Wilayah tidak dapat menjadi induk bagi dirinya sendiri.');
                            return;
                        }

                        $parent = Wilayah::where('id_wilayah', $value)
                            ->where(function ($q) {
                                $q->whereNull('kabupaten_kota')
                                  ->orWhere('kabupaten_kota', '');
                            })
                            ->first();

                        if (!$parent) {
                            $fail('Provinsi induk yang dipilih tidak valid.');
                        }
                    }
                },
            ],
            'tahun' => ['required', 'integer', 'min:2020', 'max:2100'],
        ], [
            'level.required' => 'Jenis wilayah wajib dipilih.',
            'level.in' => 'Jenis wilayah harus Provinsi atau Kabupaten/Kota.',
            'kode_wilayah.required' => 'Kode wilayah wajib diisi.',
            'kode_wilayah.regex' => 'Kode wilayah harus berupa angka.',
            'kode_wilayah.max' => 'Kode wilayah maksimal 20 digit angka.',
            'kode_wilayah.unique' => 'Kode wilayah sudah digunakan oleh daerah lain. Silakan masukkan kode angka yang berbeda.',
            'nama_wilayah.required' => 'Nama daerah wajib diisi.',
            'nama_wilayah.max' => 'Nama daerah maksimal 150 karakter.',
            'parent_id.required_if' => 'Provinsi induk wajib dipilih untuk tingkat Kabupaten/Kota.',
            'tahun.in' => 'Tahun data terbaru ditetapkan pada tahun 2025.',
        ]);

        if ((string) $validated['level'] === '1') {
            return [
                'kode_wilayah' => $validated['kode_wilayah'],
                'provinsi' => $validated['nama_wilayah'],
                'kabupaten_kota' => null,
                'parent_id' => null,
                'tahun' => (int) date('Y'),
            ];
        }

        $parent = Wilayah::findOrFail($validated['parent_id']);

        return [
            'kode_wilayah' => $validated['kode_wilayah'],
            'provinsi' => $parent->provinsi,
            'kabupaten_kota' => $validated['nama_wilayah'],
            'parent_id' => $parent->id_wilayah,
            'tahun' => (int) date('Y'),
        ];
    }
}