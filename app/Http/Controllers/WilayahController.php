<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Pagination\LengthAwarePaginator;

class WilayahController extends Controller
{
    public function index(Request $request)
    {
        // Memanggil API lokal /api/wilayah
        $response = Http::get(url('/api/wilayah'), [
            'page' => $request->query('page', 1),
            'cari' => $request->query('cari')
        ]);

        $responseData = $response->json();
        
        $items = $responseData['data'] ?? [];
        $meta = $responseData['meta'] ?? [
            'current_page' => 1,
            'per_page' => 15,
            'total' => 0
        ];

        // Membungkus kembali data array API ke LengthAwarePaginator agar Blade links() berfungsi normal
        $wilayah = new LengthAwarePaginator(
            collect($items),
            $meta['total'],
            $meta['per_page'],
            $meta['current_page'],
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('wilayah.index', compact('wilayah'));
    }
}