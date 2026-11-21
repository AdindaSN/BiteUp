<?php

namespace App\Http\Controllers;

use App\Models\Wilayah;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(): View
    {
        $user = Auth::user()->load(['role', 'wilayah']);
        $daftarWilayah = Wilayah::orderBy('provinsi')->orderBy('kabupaten_kota')->get();

        return view('profile.edit', compact('user', 'daftarWilayah'));
    }

    public function update(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:150'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id_user, 'id_user'),
            ],
            'id_wilayah' => ['nullable', 'exists:wilayah,id_wilayah'],
        ], [
            'nama.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Format alamat email tidak valid.',
            'email.unique' => 'Alamat email sudah digunakan oleh akun lain.',
            'id_wilayah.exists' => 'Wilayah tugas yang dipilih tidak valid.',
        ]);

        $user->update([
            'nama' => $validated['nama'],
            'email' => $validated['email'],
            'id_wilayah' => $validated['id_wilayah'] ?? null,
        ]);

        return redirect()
            ->route('profile.edit')
            ->with('success', 'Informasi profil Anda berhasil diperbarui.');
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'current_password.required' => 'Kata sandi saat ini wajib diisi.',
            'current_password.current_password' => 'Kata sandi saat ini tidak sesuai.',
            'password.required' => 'Kata sandi baru wajib diisi.',
            'password.min' => 'Kata sandi baru minimal terdiri dari 8 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi baru tidak cocok.',
        ]);

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()
            ->route('profile.edit')
            ->with('success', 'Kata sandi Anda berhasil diubah.');
    }
}