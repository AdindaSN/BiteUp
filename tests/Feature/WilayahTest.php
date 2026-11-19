<?php

namespace Tests\Feature;

use App\Models\Wilayah;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Feature Test untuk PBI-005 — Tambah Data Wilayah (F-02)
 *
 * Mencakup seluruh Acceptance Criteria:
 * - AC-1: Sistem menyimpan data wilayah jika seluruh kolom wajib telah diisi dengan valid
 * - AC-2: Data wilayah yang berhasil ditambahkan muncul pada daftar wilayah
 */
class WilayahTest extends TestCase
{
    use RefreshDatabase;

    // =========================================================
    // Helper
    // =========================================================

    /**
     * Data wilayah valid sebagai baseline test.
     */
    private function dataWilayahValid(array $override = []): array
    {
        return array_merge([
            'nama_wilayah'  => 'DKI Jakarta',
            'kode_wilayah'  => 'ID-JK',
            'jenis_wilayah' => 'provinsi',
            'deskripsi'     => 'Ibu kota negara Indonesia',
        ], $override);
    }

    // =========================================================
    // T-1: Halaman daftar wilayah dapat diakses
    // =========================================================

    /** @test */
    public function test_halaman_daftar_wilayah_dapat_diakses(): void
    {
        $response = $this->get(route('wilayah.index'));

        $response->assertStatus(200);
        $response->assertViewIs('wilayah.index');
    }

    // =========================================================
    // T-2: Halaman form tambah wilayah dapat diakses
    // =========================================================

    /** @test */
    public function test_halaman_form_tambah_wilayah_dapat_diakses(): void
    {
        $response = $this->get(route('wilayah.create'));

        $response->assertStatus(200);
        $response->assertViewIs('wilayah.create');
        $response->assertSee('Tambah Data Wilayah');
    }

    // =========================================================
    // T-3: Tambah wilayah berhasil dengan data valid (AC-1 + AC-2)
    // =========================================================

    /** @test */
    public function test_tambah_wilayah_berhasil_dengan_data_valid(): void
    {
        $data = $this->dataWilayahValid();

        $response = $this->post(route('wilayah.store'), $data);

        // AC-1: Redirect ke daftar setelah simpan sukses
        $response->assertRedirect(route('wilayah.index'));
        $response->assertSessionHas('success');

        // AC-1: Data tersimpan di database
        $this->assertDatabaseHas('wilayah', [
            'nama_wilayah'  => 'DKI Jakarta',
            'kode_wilayah'  => 'ID-JK',
            'jenis_wilayah' => 'provinsi',
        ]);
    }

    // =========================================================
    // T-4: Gagal jika kolom wajib kosong (AC-1)
    // =========================================================

    /** @test */
    public function test_tambah_wilayah_gagal_jika_nama_wilayah_kosong(): void
    {
        $response = $this->post(route('wilayah.store'), $this->dataWilayahValid([
            'nama_wilayah' => '',
        ]));

        $response->assertSessionHasErrors('nama_wilayah');
        $this->assertDatabaseCount('wilayah', 0);
    }

    /** @test */
    public function test_tambah_wilayah_gagal_jika_kode_wilayah_kosong(): void
    {
        $response = $this->post(route('wilayah.store'), $this->dataWilayahValid([
            'kode_wilayah' => '',
        ]));

        $response->assertSessionHasErrors('kode_wilayah');
        $this->assertDatabaseCount('wilayah', 0);
    }

    /** @test */
    public function test_tambah_wilayah_gagal_jika_jenis_wilayah_tidak_dipilih(): void
    {
        $response = $this->post(route('wilayah.store'), $this->dataWilayahValid([
            'jenis_wilayah' => '',
        ]));

        $response->assertSessionHasErrors('jenis_wilayah');
        $this->assertDatabaseCount('wilayah', 0);
    }

    /** @test */
    public function test_tambah_wilayah_gagal_jika_jenis_wilayah_tidak_valid(): void
    {
        $response = $this->post(route('wilayah.store'), $this->dataWilayahValid([
            'jenis_wilayah' => 'negara', // tidak ada dalam enum
        ]));

        $response->assertSessionHasErrors('jenis_wilayah');
        $this->assertDatabaseCount('wilayah', 0);
    }

    // =========================================================
    // T-5: Gagal jika kode wilayah duplikat (AC-1)
    // =========================================================

    /** @test */
    public function test_tambah_wilayah_gagal_jika_kode_wilayah_duplikat(): void
    {
        // Buat data pertama
        Wilayah::create($this->dataWilayahValid());

        // Coba tambah dengan kode yang sama
        $response = $this->post(route('wilayah.store'), $this->dataWilayahValid([
            'nama_wilayah' => 'Wilayah Lain',
        ]));

        $response->assertSessionHasErrors('kode_wilayah');
        $this->assertDatabaseCount('wilayah', 1); // masih 1, bukan 2
    }

    // =========================================================
    // T-6: Data muncul di daftar setelah ditambah (AC-2)
    // =========================================================

    /** @test */
    public function test_data_wilayah_muncul_di_daftar_setelah_ditambah(): void
    {
        // Tambah data langsung ke DB
        Wilayah::create($this->dataWilayahValid());

        $response = $this->get(route('wilayah.index'));

        $response->assertStatus(200);
        $response->assertSee('DKI Jakarta');
        $response->assertSee('ID-JK');
        $response->assertSee('Provinsi');
    }

    /** @test */
    public function test_daftar_wilayah_menampilkan_seluruh_data(): void
    {
        // Tambah beberapa data
        Wilayah::create($this->dataWilayahValid(['nama_wilayah' => 'Jawa Barat',  'kode_wilayah' => 'ID-JB']));
        Wilayah::create($this->dataWilayahValid(['nama_wilayah' => 'Jawa Timur',  'kode_wilayah' => 'ID-JI']));
        Wilayah::create($this->dataWilayahValid(['nama_wilayah' => 'Jawa Tengah', 'kode_wilayah' => 'ID-JT']));

        $response = $this->get(route('wilayah.index'));

        $response->assertSee('Jawa Barat');
        $response->assertSee('Jawa Timur');
        $response->assertSee('Jawa Tengah');
    }

    // =========================================================
    // T-7: Deskripsi bersifat opsional (AC-1)
    // =========================================================

    /** @test */
    public function test_tambah_wilayah_berhasil_tanpa_deskripsi(): void
    {
        $response = $this->post(route('wilayah.store'), [
            'nama_wilayah'  => 'Bali',
            'kode_wilayah'  => 'ID-BA',
            'jenis_wilayah' => 'provinsi',
            'deskripsi'     => '', // kosong = boleh
        ]);

        $response->assertRedirect(route('wilayah.index'));
        $this->assertDatabaseHas('wilayah', [
            'nama_wilayah' => 'Bali',
            'kode_wilayah' => 'ID-BA',
        ]);
    }

    // =========================================================
    // T-8: Validasi panjang karakter (AC-1)
    // =========================================================

    /** @test */
    public function test_tambah_wilayah_gagal_jika_nama_wilayah_terlalu_panjang(): void
    {
        $response = $this->post(route('wilayah.store'), $this->dataWilayahValid([
            'nama_wilayah' => str_repeat('A', 101), // melebihi max:100
        ]));

        $response->assertSessionHasErrors('nama_wilayah');
    }

    /** @test */
    public function test_tambah_wilayah_gagal_jika_kode_wilayah_terlalu_panjang(): void
    {
        $response = $this->post(route('wilayah.store'), $this->dataWilayahValid([
            'kode_wilayah' => str_repeat('X', 21), // melebihi max:20
        ]));

        $response->assertSessionHasErrors('kode_wilayah');
    }

    // =========================================================
    // T-9: API endpoint (bonus)
    // =========================================================

    /** @test */
    public function test_api_tambah_wilayah_berhasil_dengan_data_valid(): void
    {
        $response = $this->postJson('/api/wilayah', $this->dataWilayahValid());

        $response->assertStatus(201);
        $response->assertJsonPath('status', 'success');
        $response->assertJsonPath('data.nama_wilayah', 'DKI Jakarta');
    }

    /** @test */
    public function test_api_daftar_wilayah_mengembalikan_json(): void
    {
        Wilayah::create($this->dataWilayahValid());

        $response = $this->getJson('/api/wilayah');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'status', 'message', 'data', 'meta',
        ]);
    }
}
