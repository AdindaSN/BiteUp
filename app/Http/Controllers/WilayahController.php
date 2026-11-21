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
                $q->where('nama_wilayah', 'like', '%' . $keyword . '%')
                  ->orWhere('kode_wilayah', 'like', '%' . $keyword . '%');
            });
        }

        if ($request->filled('jenis')) {
            if ($request->jenis === 'provinsi') {
                $query->where('level', 1);
            } elseif ($request->jenis === 'kabupaten_kota') {
                $query->where('level', 2);
            } elseif ($request->jenis === 'kecamatan') {
                $query->where('level', 3);
            }
        }

        $wilayah = $query->orderBy('kode_wilayah')
            ->orderBy('nama_wilayah')
            ->paginate(10)
            ->withQueryString();

        return view('wilayah.index', compact('wilayah'));
    }

    public function create()
    {
        $induk = Wilayah::whereIn('level', [1, 2])
            ->orderBy('kode_wilayah')
            ->orderBy('nama_wilayah')
            ->get();

        return view('wilayah.create', compact('induk'));
    }

    public function store(Request $request)
    {
        $validated = $this->validateWilayah($request);
        $level = (int) $validated['level'];

        $wilayah = new Wilayah();
        $wilayah->kode_wilayah    = $validated['kode_wilayah'];
        $wilayah->nama_wilayah    = $validated['nama_wilayah'];
        $wilayah->level           = $level;
        $wilayah->parent_id       = $level === 1 ? null : ($validated['parent_id'] ?? null);
        $wilayah->jumlah_penduduk = (int) $validated['jumlah_penduduk'];
        $wilayah->latitude        = (float) $validated['latitude'];
        $wilayah->longitude       = (float) $validated['longitude'];
        $wilayah->save();

        return redirect()
            ->route('wilayah.index')
            ->with('success', 'Data wilayah berhasil ditambahkan!');
    }

    public function edit(Wilayah $wilayah)
    {
        $induk = Wilayah::whereIn('level', [1, 2])
            ->where('id_wilayah', '!=', $wilayah->id_wilayah)
            ->orderBy('kode_wilayah')
            ->orderBy('nama_wilayah')
            ->get();

        return view('wilayah.edit', compact('wilayah', 'induk'));
    }

    public function update(Request $request, Wilayah $wilayah)
    {
        $validated = $this->validateWilayah($request, $wilayah);
        $level = (int) $validated['level'];

        $wilayah->kode_wilayah    = $validated['kode_wilayah'];
        $wilayah->nama_wilayah    = $validated['nama_wilayah'];
        $wilayah->level           = $level;
        $wilayah->parent_id       = $level === 1 ? null : ($validated['parent_id'] ?? null);
        $wilayah->jumlah_penduduk = (int) $validated['jumlah_penduduk'];
        $wilayah->latitude        = (float) $validated['latitude'];
        $wilayah->longitude       = (float) $validated['longitude'];
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
            'jumlah_penduduk' => ['required', 'integer', 'min:0'],
            'latitude'        => ['required', 'numeric', 'between:-90,90'],
            'longitude'       => ['required', 'numeric', 'between:-180,180'],
        ], [
            'kode_wilayah.required'    => 'Kode wilayah wajib diisi.',
            'kode_wilayah.unique'      => 'Kode wilayah sudah terdaftar dalam sistem.',
            'nama_wilayah.required'    => 'Nama wilayah wajib diisi.',
            'level.required'           => 'Jenis wilayah wajib dipilih.',
            'level.in'                 => 'Jenis wilayah tidak valid.',
            'parent_id.required_if'    => 'Wilayah induk wajib dipilih untuk Kabupaten / Kota maupun Kecamatan.',
            'parent_id.exists'         => 'Wilayah induk yang dipilih tidak valid.',
            'jumlah_penduduk.required' => 'Jumlah penduduk wajib diisi.',
            'jumlah_penduduk.integer'  => 'Jumlah penduduk harus berupa angka bulat.',
            'jumlah_penduduk.min'      => 'Jumlah penduduk tidak boleh kurang dari 0.',
            'latitude.required'        => 'Latitude wajib diisi.',
            'latitude.numeric'         => 'Latitude harus berupa angka koordinat.',
            'latitude.between'         => 'Latitude harus berada di antara -90 sampai 90.',
            'longitude.required'       => 'Longitude wajib diisi.',
            'longitude.numeric'        => 'Longitude harus berupa angka koordinat.',
            'longitude.between'       => 'Longitude harus berada di antara -180 sampai 180.',
        ]);
    }
}