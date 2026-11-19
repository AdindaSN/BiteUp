<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Wilayah;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WilayahApiController extends Controller
{
    /**
     * Mengambil seluruh data wilayah dalam format JSON.
     * GET /api/wilayah
     */
    public function index(): JsonResponse
    {
        $wilayah = Wilayah::latest()->paginate(15);

        return response()->json([
            'status'  => 'success',
            'message' => 'Data wilayah berhasil diambil.',
            'data'    => $wilayah->items(),
            'meta'    => [
                'current_page' => $wilayah->currentPage(),
                'last_page'    => $wilayah->lastPage(),
                'per_page'     => $wilayah->perPage(),
                'total'        => $wilayah->total(),
            ],
        ]);
    }

    /**
     * Menyimpan data wilayah baru melalui API.
     * POST /api/wilayah
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'nama_wilayah'  => ['required', 'string', 'max:100'],
            'kode_wilayah'  => ['required', 'string', 'max:20', 'unique:wilayah,kode_wilayah'],
            'jenis_wilayah' => ['required', 'in:' . implode(',', array_keys(Wilayah::JENIS_WILAYAH))],
            'deskripsi'     => ['nullable', 'string'],
        ]);

        $wilayah = Wilayah::create($validated);

        return response()->json([
            'status'  => 'success',
            'message' => 'Data wilayah berhasil ditambahkan.',
            'data'    => $wilayah,
        ], 201);
    }
}
