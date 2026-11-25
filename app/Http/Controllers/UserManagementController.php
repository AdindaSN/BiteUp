<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Models\Wilayah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserManagementController extends Controller
{
    private function authorizeSystemManager(): void
    {
        abort_unless(
            auth()->check() && (int) auth()->user()->id_roles === 4,
            403
        );
    }

    public function index()
    {
        $this->authorizeSystemManager();

        $penggunaPending = User::where('status_akun', 'pending')
            ->orderBy('id_user', 'desc')
            ->get();

        $roles = Role::whereIn('id_roles', [1, 2, 3])
            ->orderBy('id_roles')
            ->get();

        $wilayah = Wilayah::orderBy('provinsi')
            ->orderBy('kabupaten_kota')
            ->get();

        return view('admin.pengguna.index', compact(
            'penggunaPending',
            'roles',
            'wilayah'
        ));
    }

    public function verify(Request $request, $id)
    {
        $this->authorizeSystemManager();

        $validated = $request->validate([
            'id_roles' => ['required', 'exists:roles,id_roles', 'not_in:4'],
            'id_wilayah' => ['nullable', 'exists:wilayah,id_wilayah'],
        ]);

        $user = User::findOrFail($id);

        if ($user->status_akun !== 'pending') {
            return back()->withErrors([
                'status_akun' => 'Akun ini sudah diproses sebelumnya.',
            ]);
        }

        DB::transaction(function () use ($user, $validated) {
            $user->update([
                'id_roles' => $validated['id_roles'],
                'id_wilayah' => $validated['id_wilayah'] ?? null,
                'status_akun' => 'active',
            ]);
        });

        return redirect()
            ->route('admin.pengguna.index')
            ->with('success', 'Akun berhasil diverifikasi dan diaktifkan.');
    }

    public function reject($id)
    {
        $this->authorizeSystemManager();

        $user = User::findOrFail($id);

        if ($user->status_akun !== 'pending') {
            return back()->withErrors([
                'status_akun' => 'Hanya akun pending yang dapat ditolak.',
            ]);
        }

        $user->update([
            'status_akun' => 'rejected',
        ]);

        return back()->with('success', 'Pendaftaran akun berhasil ditolak.');
    }
}