<?php

namespace App\Http\Requests;

use App\Models\Wilayah;
use Illuminate\Foundation\Http\FormRequest;

class StoreWilayahRequest extends FormRequest
{
    /**
     * Menentukan apakah user berwenang melakukan request ini.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Aturan validasi untuk data wilayah.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nama_wilayah'  => ['required', 'string', 'max:100'],
            'kode_wilayah'  => ['required', 'string', 'max:20', 'unique:wilayah,kode_wilayah'],
            'jenis_wilayah' => ['required', 'in:' . implode(',', array_keys(Wilayah::JENIS_WILAYAH))],
            'deskripsi'     => ['nullable', 'string'],
        ];
    }

    /**
     * Pesan error kustom dalam Bahasa Indonesia.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'nama_wilayah.required'  => 'Nama wilayah wajib diisi.',
            'nama_wilayah.max'       => 'Nama wilayah tidak boleh lebih dari 100 karakter.',
            'kode_wilayah.required'  => 'Kode wilayah wajib diisi.',
            'kode_wilayah.max'       => 'Kode wilayah tidak boleh lebih dari 20 karakter.',
            'kode_wilayah.unique'    => 'Kode wilayah sudah digunakan oleh wilayah lain.',
            'jenis_wilayah.required' => 'Jenis wilayah wajib dipilih.',
            'jenis_wilayah.in'       => 'Jenis wilayah tidak valid.',
        ];
    }

    /**
     * Nama atribut kustom untuk tampilan error.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'nama_wilayah'  => 'Nama Wilayah',
            'kode_wilayah'  => 'Kode Wilayah',
            'jenis_wilayah' => 'Jenis Wilayah',
            'deskripsi'     => 'Deskripsi',
        ];
    }
}
