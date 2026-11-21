<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    private function createAuthenticatedUser(): User
    {
        $role = Role::firstOrCreate(
            ['id_roles' => 3],
            ['nama_role' => 'Petugas Program Bantuan']
        );

        return User::create([
            'id_roles' => $role->id_roles,
            'nama' => 'Adinda Putri',
            'email' => 'adinda@biteup.id',
            'password' => Hash::make('password123'),
            'status_akun' => 'active',
        ]);
    }

    public function test_guest_cannot_access_profile_page(): void
    {
        $this->get('/profil')->assertRedirect('/login');
    }

    public function test_authenticated_user_can_view_own_profile(): void
    {
        $user = $this->createAuthenticatedUser();

        $this->actingAs($user)
            ->get('/profil')
            ->assertStatus(200)
            ->assertSee('Adinda Putri')
            ->assertSee('adinda@biteup.id');
    }

    public function test_user_can_update_profile_information(): void
    {
        $user = $this->createAuthenticatedUser();

        $this->actingAs($user)
            ->put('/profil', [
                'nama' => 'Adinda Salsabila',
                'email' => 'adinda.baru@biteup.id',
            ])
            ->assertRedirect('/profil')
            ->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'id_user' => $user->id_user,
            'nama' => 'Adinda Salsabila',
            'email' => 'adinda.baru@biteup.id',
        ]);
    }

    public function test_user_can_update_password_with_valid_current_password(): void
    {
        $user = $this->createAuthenticatedUser();

        $this->actingAs($user)
            ->put('/profil/password', [
                'current_password' => 'password123',
                'password' => 'newpassword999',
                'password_confirmation' => 'newpassword999',
            ])
            ->assertRedirect('/profil')
            ->assertSessionHas('success');

        $user->refresh();
        $this->assertTrue(Hash::check('newpassword999', $user->password));
    }

    public function test_user_cannot_update_password_with_wrong_current_password(): void
    {
        $user = $this->createAuthenticatedUser();

        $this->actingAs($user)
            ->put('/profil/password', [
                'current_password' => 'salah123',
                'password' => 'newpassword999',
                'password_confirmation' => 'newpassword999',
            ])
            ->assertSessionHasErrors('current_password');
    }
}