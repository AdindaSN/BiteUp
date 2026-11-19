<?php

namespace Tests\Feature;

use App\Models\JenisBantuan;
use App\Models\Program;
use App\Models\Role;
use App\Models\User;
use App\Models\Wilayah;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class ProgramTest extends TestCase
{
    use DatabaseTransactions;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        // Setup standard roles
        Role::firstOrCreate(['id_roles' => 1], ['nama_role' => 'Petugas Data Wilayah']);
        Role::firstOrCreate(['id_roles' => 2], ['nama_role' => 'Pimpinan Bapanas']);
        $role3 = Role::firstOrCreate(['id_roles' => 3], ['nama_role' => 'Petugas Program']);
        Role::firstOrCreate(['id_roles' => 4], ['nama_role' => 'Pengelola Sistem Bapanas']);

        $this->user = User::create([
            'nama' => 'Petugas Program Test',
            'email' => 'petugas_' . uniqid() . '@biteup.com',
            'password' => bcrypt('password123'),
            'id_roles' => $role3->id_roles,
        ]);

        $this->actingAs($this->user);

        // Ensure master data jenis bantuan exist
        JenisBantuan::firstOrCreate(['nama_bantuan' => 'Bantuan Pangan']);
        JenisBantuan::firstOrCreate(['nama_bantuan' => 'Intervensi Gizi']);

        // Ensure master data wilayah exist
        Wilayah::firstOrCreate(
            ['kode_wilayah' => '32'],
            ['nama_wilayah' => 'Jawa Barat', 'level' => 1]
        );
        Wilayah::firstOrCreate(
            ['kode_wilayah' => '31'],
            ['nama_wilayah' => 'DKI Jakarta', 'level' => 1]
        );
    }

    /**
     * Skenario Umum: Halaman daftar data program dapat diakses.
     */
    public function test_halaman_data_program_dapat_diakses(): void
    {
        $response = $this->get('/program');

        $response->assertStatus(200);
        $response->assertSee('Data Program');
        $response->assertSee('Tambah Program');
        $response->assertSee('Wilayah');
        $response->assertSee('Rencana Pelaksanaan');
    }

    /**
     * Skenario 1 & 3: Admin berhasil menambahkan program dengan seluruh field terisi,
     * serta tanggal pelaksanaan dan wilayah tersimpan dengan benar.
     */
    public function test_admin_berhasil_menambahkan_program_dengan_seluruh_field_terisi(): void
    {
        $jenisBantuan = JenisBantuan::where('nama_bantuan', 'Bantuan Pangan')->first();
        $wilayah = Wilayah::where('nama_wilayah', 'Jawa Barat')->first();

        $payload = [
            'nama_program' => 'Program Bantuan Beras Sejahtera 2026',
            'id_jenis_bantuan' => $jenisBantuan->id_jenis_bantuan,
            'id_wilayah' => $wilayah->id_wilayah,
            'tanggal_pelaksanaan' => '2026-06-15',
            'deskripsi' => 'Penyaluran beras kualitas premium untuk 50.000 KK di Jawa Barat.',
        ];

        $response = $this->post('/program', $payload);

        // Redirect ke daftar dengan notifikasi sukses
        $response->assertRedirect(route('program.index'));
        $response->assertSessionHas('success', 'Data program bantuan berhasil ditambahkan.');

        // Pastikan tersimpan di database
        $this->assertDatabaseHas('program', [
            'nama_program' => 'Program Bantuan Beras Sejahtera 2026',
            'id_jenis_bantuan' => $jenisBantuan->id_jenis_bantuan,
            'id_wilayah' => $wilayah->id_wilayah,
            'tanggal_pelaksanaan' => '2026-06-15',
            'deskripsi' => 'Penyaluran beras kualitas premium untuk 50.000 KK di Jawa Barat.',
            'status' => 'Aktif',
        ]);
    }

    /**
     * Skenario 2: Admin tidak dapat menyimpan program jika field wajib belum diisi.
     */
    public function test_admin_tidak_dapat_menyimpan_program_jika_field_wajib_belum_diisi(): void
    {
        // Kirim request kosong
        $response = $this->post('/program', [
            'nama_program' => '',
            'id_jenis_bantuan' => '',
            'id_wilayah' => '',
            'tanggal_pelaksanaan' => '',
            'deskripsi' => '',
        ]);

        $response->assertSessionHasErrors([
            'nama_program' => 'Nama program wajib diisi.',
            'id_jenis_bantuan' => 'Jenis program wajib dipilih.',
            'id_wilayah' => 'Wilayah yang dituju wajib dipilih.',
            'tanggal_pelaksanaan' => 'Rencana tanggal pelaksanaan wajib diisi.',
        ]);
    }

    /**
     * Skenario 5: Formulir edit menampilkan data sebelumnya dengan benar.
     */
    public function test_formulir_edit_menampilkan_data_sebelumnya_dengan_benar(): void
    {
        $jenisBantuan = JenisBantuan::where('nama_bantuan', 'Intervensi Gizi')->first();
        $wilayah = Wilayah::where('nama_wilayah', 'DKI Jakarta')->first();

        $program = Program::create([
            'nama_program' => 'Program Gizi Balita Stunting',
            'id_jenis_bantuan' => $jenisBantuan->id_jenis_bantuan,
            'id_wilayah' => $wilayah->id_wilayah,
            'tanggal_pelaksanaan' => '2026-07-01',
            'deskripsi' => 'Program intervensi spesifik gizi balita.',
            'status' => 'Aktif',
        ]);

        $response = $this->get(route('program.edit', $program->id_program));

        $response->assertStatus(200);
        $response->assertSee('Edit Program');
        $response->assertSee('Program Gizi Balita Stunting');
        $response->assertSee('2026-07-01');
        $response->assertSee('Program intervensi spesifik gizi balita.');
    }

    /**
     * Skenario 4 & 6: Admin dapat mengedit data program yang sudah ada dan
     * perubahan tersimpan tanpa membuat duplikasi.
     */
    public function test_admin_dapat_mengedit_data_program_dan_tersimpan_tanpa_duplikasi(): void
    {
        $jenisAwal = JenisBantuan::first();
        $jenisBaru = JenisBantuan::orderBy('id_jenis_bantuan', 'desc')->first();
        $wilayahAwal = Wilayah::first();
        $wilayahBaru = Wilayah::orderBy('id_wilayah', 'desc')->first();

        $program = Program::create([
            'nama_program' => 'Program Lama Sebelum Diedit',
            'id_jenis_bantuan' => $jenisAwal->id_jenis_bantuan,
            'id_wilayah' => $wilayahAwal->id_wilayah,
            'tanggal_pelaksanaan' => '2026-08-01',
            'deskripsi' => 'Deskripsi lama',
            'status' => 'Aktif',
        ]);

        $initialCount = Program::count();

        $updatePayload = [
            'nama_program' => 'Program Baru Hasil Suntingan',
            'id_jenis_bantuan' => $jenisBaru->id_jenis_bantuan,
            'id_wilayah' => $wilayahBaru->id_wilayah,
            'tanggal_pelaksanaan' => '2026-09-10',
            'deskripsi' => 'Deskripsi yang telah diperbarui oleh Admin.',
        ];

        $response = $this->put(route('program.update', $program->id_program), $updatePayload);

        // Redirect ke daftar dengan notifikasi sukses
        $response->assertRedirect(route('program.index'));
        $response->assertSessionHas('success', 'Data program bantuan berhasil diperbarui.');

        // Pastikan jumlah data tidak bertambah (tidak duplikat)
        $this->assertEquals($initialCount, Program::count());

        // Pastikan record terupdate
        $this->assertDatabaseHas('program', [
            'id_program' => $program->id_program,
            'nama_program' => 'Program Baru Hasil Suntingan',
            'id_jenis_bantuan' => $jenisBaru->id_jenis_bantuan,
            'id_wilayah' => $wilayahBaru->id_wilayah,
            'tanggal_pelaksanaan' => '2026-09-10',
            'deskripsi' => 'Deskripsi yang telah diperbarui oleh Admin.',
        ]);

        // Pastikan nama lama sudah tidak ada
        $this->assertDatabaseMissing('program', [
            'nama_program' => 'Program Lama Sebelum Diedit',
        ]);
    }

    /**
     * Skenario 7 & 8: Admin dapat menghapus program dan program tidak lagi muncul pada daftar.
     */
    public function test_admin_dapat_menghapus_program_dan_tidak_muncul_lagi_pada_daftar(): void
    {
        $jenis = JenisBantuan::first();
        $wilayah = Wilayah::first();

        $program = Program::create([
            'nama_program' => 'Program Yang Akan Dihapus',
            'id_jenis_bantuan' => $jenis->id_jenis_bantuan,
            'id_wilayah' => $wilayah->id_wilayah,
            'tanggal_pelaksanaan' => '2026-10-01',
            'deskripsi' => 'Untuk diuji penghapusan',
            'status' => 'Aktif',
        ]);

        $deleteResponse = $this->delete(route('program.destroy', $program->id_program));

        $deleteResponse->assertRedirect(route('program.index'));
        $deleteResponse->assertSessionHas('success');

        // Pastikan terhapus dari database
        $this->assertDatabaseMissing('program', [
            'id_program' => $program->id_program,
            'nama_program' => 'Program Yang Akan Dihapus',
        ]);

        // Pastikan tidak muncul pada daftar program
        $listResponse = $this->get('/program');
        $listResponse->assertDontSee('Program Yang Akan Dihapus');
    }

    /**
     * Test fitur pencarian data program.
     */
    public function test_pencarian_program_berdasarkan_nama_atau_wilayah(): void
    {
        $jenis = JenisBantuan::first();
        $wilayah = Wilayah::first();

        Program::create([
            'nama_program' => 'Bantuan Khusus Sembako Murah',
            'id_jenis_bantuan' => $jenis->id_jenis_bantuan,
            'id_wilayah' => $wilayah->id_wilayah,
            'tanggal_pelaksanaan' => '2026-11-01',
            'deskripsi' => 'Khusus daerah terpencil',
            'status' => 'Aktif',
        ]);

        $response = $this->get('/program?search=Sembako');
        $response->assertStatus(200);
        $response->assertSee('Bantuan Khusus Sembako Murah');
    }
}
