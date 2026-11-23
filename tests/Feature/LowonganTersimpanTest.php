<?php

namespace Tests\Feature;

use App\Models\KategoriLowongan;
use App\Models\Lowongan;
use App\Models\LowonganTersimpan;
use App\Models\MahasiswaProfile;
use App\Models\PenyediaProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class LowonganTersimpanTest extends TestCase
{
    use RefreshDatabase;

    protected User $mahasiswaUser;
    protected MahasiswaProfile $mahasiswaProfile;
    protected User $penyediaUser;
    protected PenyediaProfile $penyediaProfile;
    protected Lowongan $job;
    protected Lowongan $closedJob;

    protected function setUp(): void
    {
        parent::setUp();

        $kategori = KategoriLowongan::create([
            'nama_kategori' => 'Software & Data',
            'slug'          => 'software-data',
        ]);

        $this->penyediaUser = User::create([
            'name'     => 'PT Teknologi Maju Nusantara',
            'email'    => 'penyedia@test.com',
            'password' => bcrypt('password123'),
            'role'     => 'penyedia',
        ]);

        $this->penyediaProfile = PenyediaProfile::create([
            'user_id'         => $this->penyediaUser->id,
            'nama_organisasi' => 'PT Teknologi Maju Nusantara',
        ]);

        $this->mahasiswaUser = User::create([
            'name'     => 'Ilham Ramadhan',
            'email'    => 'ilham@mahasiswa.ac.id',
            'password' => bcrypt('password123'),
            'role'     => 'mahasiswa',
        ]);

        $this->mahasiswaProfile = MahasiswaProfile::create([
            'user_id'      => $this->mahasiswaUser->id,
            'nama_lengkap' => 'Ilham Ramadhan',
            'nim'          => '102022400320',
        ]);

        $this->job = Lowongan::create([
            'penyedia_id'     => $this->penyediaProfile->id,
            'kategori_id'     => $kategori->id,
            'judul_posisi'    => 'Frontend Developer Intern',
            'jenis_pekerjaan' => 'magang',
            'tipe_lokasi'     => 'hybrid',
            'lokasi_kota'     => 'Jakarta Selatan',
            'waktu_kerja'     => '15-20 Jam/Minggu',
            'deskripsi'       => 'Deskripsi program',
            'status_aktif'    => 1,
        ]);

        $this->closedJob = Lowongan::create([
            'penyedia_id'     => $this->penyediaProfile->id,
            'kategori_id'     => $kategori->id,
            'judul_posisi'    => 'Lowongan Ditutup',
            'jenis_pekerjaan' => 'magang',
            'tipe_lokasi'     => 'onsite',
            'deskripsi'       => 'Deskripsi',
            'status_aktif'    => 0,
        ]);
    }

    /**
     * F-15: Mahasiswa dapat menyimpan lowongan dengan catatan pribadi.
     */
    public function test_mahasiswa_can_save_lowongan_with_note(): void
    {
        Sanctum::actingAs($this->mahasiswaUser);

        $response = $this->postJson('/api/mahasiswa/lowongan-tersimpan', [
            'lowongan_id'     => $this->job->id,
            'catatan_pribadi' => 'Perlu mempersiapkan portofolio redesign kampus.',
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
                'message' => 'Lowongan berhasil disimpan ke daftar minat karir Anda.',
                'data'    => [
                    'mahasiswa_id'    => $this->mahasiswaProfile->id,
                    'lowongan_id'     => $this->job->id,
                    'catatan_pribadi' => 'Perlu mempersiapkan portofolio redesign kampus.',
                ],
            ]);

        $this->assertDatabaseHas('lowongan_tersimpan', [
            'mahasiswa_id'    => $this->mahasiswaProfile->id,
            'lowongan_id'     => $this->job->id,
            'catatan_pribadi' => 'Perlu mempersiapkan portofolio redesign kampus.',
        ]);
    }

    /**
     * F-15: Mahasiswa tidak dapat menyimpan lowongan yang sudah tidak aktif / ditutup.
     */
    public function test_mahasiswa_cannot_save_inactive_lowongan(): void
    {
        Sanctum::actingAs($this->mahasiswaUser);

        $response = $this->postJson('/api/mahasiswa/lowongan-tersimpan', [
            'lowongan_id' => $this->closedJob->id,
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['lowongan_id']);
    }

    /**
     * F-15 / Constraint ERD: Menyimpan lowongan yang sama akan memperbarui catatan
     * dan mencegah duplikasi data (Unique Constraint mahasiswa_id, lowongan_id).
     */
    public function test_saving_same_lowongan_updates_record_without_duplicate(): void
    {
        Sanctum::actingAs($this->mahasiswaUser);

        // Simpan pertama kali
        $this->postJson('/api/mahasiswa/lowongan-tersimpan', [
            'lowongan_id'     => $this->job->id,
            'catatan_pribadi' => 'Catatan pertama',
        ])->assertStatus(201);

        // Simpan kedua kali untuk lowongan yang sama dengan catatan baru
        $this->postJson('/api/mahasiswa/lowongan-tersimpan', [
            'lowongan_id'     => $this->job->id,
            'catatan_pribadi' => 'Catatan diperbarui',
        ])->assertStatus(201);

        // Pastikan hanya ada 1 record di database
        $count = LowonganTersimpan::where('mahasiswa_id', $this->mahasiswaProfile->id)
            ->where('lowongan_id', $this->job->id)
            ->count();
        $this->assertEquals(1, $count);

        $saved = LowonganTersimpan::first();
        $this->assertEquals('Catatan diperbarui', $saved->catatan_pribadi);
    }

    /**
     * F-15: Mahasiswa dapat melihat daftar lowongan yang telah disimpan.
     */
    public function test_mahasiswa_can_view_saved_lowongan_list(): void
    {
        Sanctum::actingAs($this->mahasiswaUser);

        LowonganTersimpan::create([
            'mahasiswa_id'    => $this->mahasiswaProfile->id,
            'lowongan_id'     => $this->job->id,
            'catatan_pribadi' => 'Simpanan aktif',
        ]);

        $response = $this->getJson('/api/mahasiswa/lowongan-tersimpan');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'total'   => 1,
            ]);

        $this->assertEquals('Frontend Developer Intern', $response->json('data.0.lowongan.judul_posisi'));
        $this->assertEquals('Simpanan aktif', $response->json('data.0.catatan_pribadi'));
    }

    /**
     * F-15: Mahasiswa dapat mengubah catatan pribadi lowongan tersimpan.
     */
    public function test_mahasiswa_can_update_catatan_pribadi(): void
    {
        Sanctum::actingAs($this->mahasiswaUser);

        LowonganTersimpan::create([
            'mahasiswa_id'    => $this->mahasiswaProfile->id,
            'lowongan_id'     => $this->job->id,
            'catatan_pribadi' => 'Catatan lama',
        ]);

        $response = $this->putJson('/api/mahasiswa/lowongan-tersimpan/' . $this->job->id, [
            'catatan_pribadi' => 'Catatan telah direvisi dengan jadwal kuliah.',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Catatan pribadi berhasil diperbarui.',
            ]);

        $this->assertDatabaseHas('lowongan_tersimpan', [
            'lowongan_id'     => $this->job->id,
            'catatan_pribadi' => 'Catatan telah direvisi dengan jadwal kuliah.',
        ]);
    }

    /**
     * F-15: Mahasiswa dapat menghapus lowongan dari daftar simpanan.
     */
    public function test_mahasiswa_can_delete_saved_lowongan(): void
    {
        Sanctum::actingAs($this->mahasiswaUser);

        $saved = LowonganTersimpan::create([
            'mahasiswa_id'    => $this->mahasiswaProfile->id,
            'lowongan_id'     => $this->job->id,
            'catatan_pribadi' => 'Catatan akan dihapus',
        ]);

        $response = $this->deleteJson('/api/mahasiswa/lowongan-tersimpan/' . $saved->id);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Lowongan berhasil dihapus dari daftar tersimpan.',
            ]);

        $this->assertDatabaseMissing('lowongan_tersimpan', [
            'id' => $saved->id,
        ]);
    }

    /**
     * Akses halaman web Blade lowongan tersimpan.
     */
    public function test_mahasiswa_can_access_web_view_lowongan_tersimpan(): void
    {
        $response = $this->actingAs($this->mahasiswaUser)->get('/mahasiswa/lowongan-tersimpan');
        $response->assertStatus(200);
        $response->assertSee('Lowongan Tersimpan');
    }

    /**
     * Pengguna dengan role penyedia tidak boleh mengakses fitur lowongan tersimpan mahasiswa (RBAC).
     */
    public function test_penyedia_cannot_access_mahasiswa_saved_lowongan(): void
    {
        Sanctum::actingAs($this->penyediaUser);

        $response = $this->getJson('/api/mahasiswa/lowongan-tersimpan');
        $response->assertStatus(403);
    }
}
