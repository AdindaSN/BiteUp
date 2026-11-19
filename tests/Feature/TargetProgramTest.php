<?php

namespace Tests\Feature;

use App\Models\JenisBantuan;
use App\Models\JenisKelompok;
use App\Models\KelompokSasaran;
use App\Models\Program;
use App\Models\ProgramSasaran;
use App\Models\Role;
use App\Models\User;
use App\Models\Wilayah;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TargetProgramTest extends TestCase
{
    use RefreshDatabase;

    private function setupInitialData(): array
    {
        // Setup Role
        $role1 = Role::create(['id_roles' => 1, 'nama_role' => 'Petugas Data Wilayah']);
        $role2 = Role::create(['id_roles' => 2, 'nama_role' => 'Pimpinan Bapanas']);
        $role3 = Role::create(['id_roles' => 3, 'nama_role' => 'Petugas Program']);
        $role4 = Role::create(['id_roles' => 4, 'nama_role' => 'Pengelola Sistem Bapanas']);

        // Setup Wilayah
        $provinsi = Wilayah::create([
            'id_wilayah' => 1,
            'kode_wilayah' => '32',
            'provinsi' => 'Jawa Barat',
            'kabupaten_kota' => '-',
            'tahun' => 2026,
            'parent_id' => null,
        ]);

        $wilayah = Wilayah::create([
            'id_wilayah' => 2,
            'parent_id' => $provinsi->id_wilayah,
            'kode_wilayah' => '3201',
            'provinsi' => 'Jawa Barat',
            'kabupaten_kota' => 'Kabupaten Bogor',
            'tahun' => 2026,
        ]);

        // Setup Users
        $petugasProgram = User::create([
            'nama' => 'Petugas Program Test',
            'email' => 'petugas.program@bapanas.go.id',
            'password' => 'password123',
            'id_roles' => 3,
            'status_akun' => 'active',
        ]);

        $petugasWilayah = User::create([
            'nama' => 'Petugas Wilayah Test',
            'email' => 'petugas.wilayah@bapanas.go.id',
            'password' => 'password123',
            'id_roles' => 1,
            'status_akun' => 'active',
        ]);

        $admin = User::create([
            'nama' => 'Admin Bapanas Test',
            'email' => 'admin@bapanas.go.id',
            'password' => 'password123',
            'id_roles' => 4,
            'status_akun' => 'active',
        ]);

        // Setup Jenis Bantuan & Program
        $jenisBantuan = JenisBantuan::create([
            'id_jenis_bantuan' => 1,
            'nama_bantuan' => 'Beras CPP',
        ]);

        $program = Program::create([
            'id_program' => 1,
            'id_user' => $admin->id_user,
            'id_jenis_bantuan' => $jenisBantuan->id_jenis_bantuan,
            'nama_program' => 'Program Beras Cadangan 2026',
            'tanggal_mulai' => '2026-01-01',
            'tanggal_selesai' => '2026-12-31',
            'status' => 'Berjalan',
        ]);

        // Setup Jenis Kelompok & Kelompok Sasaran
        $jenisKelompok = JenisKelompok::create([
            'id_jenis_kelompok' => 1,
            'nama_jenis' => 'Keluarga Miskin Rawan Pangan',
        ]);

        $kelompokSasaran = KelompokSasaran::create([
            'id_kelompok_sasaran' => 1,
            'id_wilayah' => $wilayah->id_wilayah,
            'id_jenis_kelompok' => $jenisKelompok->id_jenis_kelompok,
            'jumlah' => 10000,
            'tahun' => 2026,
        ]);

        return compact('petugasProgram', 'petugasWilayah', 'admin', 'program', 'kelompokSasaran', 'wilayah', 'jenisBantuan');
    }

    public function test_guest_is_redirected_to_login_when_accessing_target_program(): void
    {
        $response = $this->get('/target-program');
        $response->assertRedirect('/login');

        $responseCreate = $this->get('/target-program/create');
        $responseCreate->assertRedirect('/login');
    }

    public function test_unauthorized_role_cannot_access_create_target_program(): void
    {
        $data = $this->setupInitialData();

        // Login as Petugas Wilayah (Role 1)
        $response = $this->actingAs($data['petugasWilayah'])->get('/target-program/create');
        $response->assertStatus(403);

        $responseStore = $this->actingAs($data['petugasWilayah'])->post('/target-program', [
            'nama_program' => 'Program Test',
            'id_kelompok' => $data['kelompokSasaran']->id_kelompok_sasaran,
            'target_jumlah' => 5000,
        ]);
        $responseStore->assertStatus(403);
    }

    public function test_petugas_program_can_view_target_program_index(): void
    {
        $data = $this->setupInitialData();

        // Buat contoh ProgramSasaran
        ProgramSasaran::create([
            'id_program' => $data['program']->id_program,
            'id_kelompok' => $data['kelompokSasaran']->id_kelompok_sasaran,
            'target_jumlah' => 2500,
        ]);

        $response = $this->actingAs($data['petugasProgram'])->get('/target-program');
        $response->assertStatus(200);
        $response->assertSee('Program Beras Cadangan 2026');
        $response->assertSee('2.500');
        $response->assertSee('Tambah Target');
    }

    public function test_petugas_program_can_view_create_target_program_page(): void
    {
        $data = $this->setupInitialData();

        $response = $this->actingAs($data['petugasProgram'])->get('/target-program/create');
        $response->assertStatus(200);
        $response->assertSee('Alokasi Target Program Baru');
        $response->assertSee('Nama Program Bantuan');
        $response->assertSee('Keluarga Miskin Rawan Pangan');
    }

    public function test_validation_fails_when_fields_are_missing(): void
    {
        $data = $this->setupInitialData();

        $response = $this->actingAs($data['petugasProgram'])->post('/target-program', []);
        $response->assertSessionHasErrors(['nama_program', 'id_kelompok', 'target_jumlah']);
    }

    public function test_validation_fails_when_nama_program_is_empty(): void
    {
        $data = $this->setupInitialData();

        $response = $this->actingAs($data['petugasProgram'])->post('/target-program', [
            'nama_program' => '',
            'id_kelompok' => $data['kelompokSasaran']->id_kelompok_sasaran,
            'target_jumlah' => 5000,
        ]);
        $response->assertSessionHasErrors(['nama_program']);
    }

    public function test_validation_fails_if_target_jumlah_is_zero_or_negative(): void
    {
        $data = $this->setupInitialData();

        $response = $this->actingAs($data['petugasProgram'])->post('/target-program', [
            'nama_program' => 'Program Test',
            'id_kelompok' => $data['kelompokSasaran']->id_kelompok_sasaran,
            'target_jumlah' => 0,
        ]);
        $response->assertSessionHasErrors(['target_jumlah']);

        $responseNegative = $this->actingAs($data['petugasProgram'])->post('/target-program', [
            'nama_program' => 'Program Test',
            'id_kelompok' => $data['kelompokSasaran']->id_kelompok_sasaran,
            'target_jumlah' => -100,
        ]);
        $responseNegative->assertSessionHasErrors(['target_jumlah']);
    }

    public function test_successfully_stores_new_target_program_with_free_text_name(): void
    {
        $data = $this->setupInitialData();

        $response = $this->actingAs($data['petugasProgram'])->post('/target-program', [
            'nama_program' => 'Program Bantuan Pangan Baru 2026',
            'id_kelompok' => $data['kelompokSasaran']->id_kelompok_sasaran,
            'target_jumlah' => 7500,
        ]);

        $response->assertRedirect('/target-program');
        $response->assertSessionHas('success', 'Target program berhasil ditambahkan.');

        // Program baru harus terbuat di database
        $this->assertDatabaseHas('program', [
            'nama_program' => 'Program Bantuan Pangan Baru 2026',
        ]);

        // Target harus tersimpan
        $newProgram = Program::where('nama_program', 'Program Bantuan Pangan Baru 2026')->first();
        $this->assertNotNull($newProgram);

        $this->assertDatabaseHas('program_sasaran', [
            'id_program' => $newProgram->id_program,
            'id_kelompok' => $data['kelompokSasaran']->id_kelompok_sasaran,
            'target_jumlah' => 7500,
        ]);
    }

    public function test_reuses_existing_program_when_name_matches(): void
    {
        $data = $this->setupInitialData();

        // Use existing program name
        $response = $this->actingAs($data['petugasProgram'])->post('/target-program', [
            'nama_program' => 'Program Beras Cadangan 2026',
            'id_kelompok' => $data['kelompokSasaran']->id_kelompok_sasaran,
            'target_jumlah' => 5000,
        ]);

        $response->assertRedirect('/target-program');

        // Should reuse existing program, not create a new one
        $this->assertEquals(1, Program::where('nama_program', 'Program Beras Cadangan 2026')->count());

        $this->assertDatabaseHas('program_sasaran', [
            'id_program' => $data['program']->id_program,
            'id_kelompok' => $data['kelompokSasaran']->id_kelompok_sasaran,
            'target_jumlah' => 5000,
        ]);
    }

    public function test_prevents_duplicate_target_program_for_same_program_and_kelompok(): void
    {
        $data = $this->setupInitialData();

        // Tambah target pertama
        ProgramSasaran::create([
            'id_program' => $data['program']->id_program,
            'id_kelompok' => $data['kelompokSasaran']->id_kelompok_sasaran,
            'target_jumlah' => 5000,
        ]);

        // Coba tambah lagi untuk program dan kelompok yang sama
        $response = $this->actingAs($data['petugasProgram'])->post('/target-program', [
            'nama_program' => 'Program Beras Cadangan 2026',
            'id_kelompok' => $data['kelompokSasaran']->id_kelompok_sasaran,
            'target_jumlah' => 8000,
        ]);

        $response->assertSessionHasErrors(['id_kelompok']);
    }

    public function test_fsva_api_returns_data_for_valid_wilayah(): void
    {
        $data = $this->setupInitialData();

        $response = $this->actingAs($data['petugasProgram'])
            ->getJson('/target-program/api/fsva?id_wilayah=' . $data['wilayah']->id_wilayah);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'wilayah' => ['id', 'nama', 'kode', 'provinsi'],
            'fsva_tersedia',
            'ikp',
            'indikator',
        ]);
    }

    public function test_kelompok_api_returns_data_for_valid_wilayah(): void
    {
        $data = $this->setupInitialData();

        $response = $this->actingAs($data['petugasProgram'])
            ->getJson('/target-program/api/kelompok?id_wilayah=' . $data['wilayah']->id_wilayah);

        $response->assertStatus(200);
        $response->assertJsonCount(1);
    }
}
