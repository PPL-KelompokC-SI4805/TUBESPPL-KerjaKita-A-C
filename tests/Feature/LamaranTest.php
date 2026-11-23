<?php

namespace Tests\Feature;

use App\Models\KategoriLowongan;
use App\Models\Lamaran;
use App\Models\Lowongan;
use App\Models\MahasiswaProfile;
use App\Models\PenyediaProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class LamaranTest extends TestCase
{
    use RefreshDatabase;

    protected User $mhsUser;
    protected MahasiswaProfile $mhs;
    protected User $penyediaUser;
    protected PenyediaProfile $penyedia;
    protected Lowongan $lowongan;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');

        $kategori = KategoriLowongan::create(['nama_kategori' => 'Software', 'slug' => 'software']);

        $this->penyediaUser = $this->buatUser('penyedia', 'mitra@x.com');
        $this->penyedia = PenyediaProfile::create([
            'user_id' => $this->penyediaUser->id, 'nama_organisasi' => 'PT Maju',
        ]);

        $this->mhsUser = $this->buatUser('mahasiswa', 'mhs@x.com');
        Storage::disk('public')->put('cv/cv_1.pdf', 'dummy');
        $this->mhs = MahasiswaProfile::create([
            'user_id' => $this->mhsUser->id, 'nama_lengkap' => 'Mhs Satu', 'cv_file_path' => 'cv/cv_1.pdf',
        ]);

        $this->lowongan = Lowongan::create([
            'penyedia_id' => $this->penyedia->id, 'kategori_id' => $kategori->id,
            'judul_posisi' => 'Backend Intern', 'jenis_pekerjaan' => 'magang',
            'tipe_lokasi' => 'remote', 'deskripsi' => 'Deskripsi', 'status_aktif' => true,
        ]);
    }

    private function buatUser(string $role, string $email): User
    {
        return User::create(['name' => $email, 'email' => $email, 'password' => bcrypt('password'), 'role' => $role]);
    }

    private function lamar(array $extra = [])
    {
        Sanctum::actingAs($this->mhsUser);
        return $this->postJson('/api/mahasiswa/lamaran', ['lowongan_id' => $this->lowongan->id] + $extra);
    }

    public function test_mahasiswa_bisa_mengajukan_lamaran(): void
    {
        $this->lamar(['surat_lamaran' => 'Saya tertarik'])
            ->assertCreated()
            ->assertJsonPath('data.status_lamaran', 'Terkirim');

        $this->assertDatabaseHas('lamaran', ['mahasiswa_id' => $this->mhs->id, 'lowongan_id' => $this->lowongan->id]);
    }

    public function test_pengajuan_gagal_tanpa_cv(): void
    {
        $this->mhs->update(['cv_file_path' => null]);
        $this->lamar()->assertStatus(422)->assertJsonValidationErrors('cv');
    }

    public function test_tidak_bisa_melamar_dua_kali(): void
    {
        $this->lamar()->assertCreated();
        $this->lamar()->assertStatus(422)->assertJsonValidationErrors('lowongan_id');
    }

    public function test_tidak_bisa_melamar_lowongan_tertutup(): void
    {
        $this->lowongan->update(['status_aktif' => false]);
        $this->lamar()->assertStatus(422)->assertJsonValidationErrors('lowongan_id');
    }

    public function test_riwayat_hanya_menampilkan_lamaran_sendiri(): void
    {
        $this->lamar()->assertCreated();

        $lain = $this->buatUser('mahasiswa', 'lain@x.com');
        $lainProfile = MahasiswaProfile::create(['user_id' => $lain->id, 'nama_lengkap' => 'Lain']);
        Lamaran::create(['mahasiswa_id' => $lainProfile->id, 'lowongan_id' => $this->lowongan->id]);

        Sanctum::actingAs($this->mhsUser);
        $this->getJson('/api/mahasiswa/lamaran')->assertOk()->assertJsonPath('total', 1);
    }

    public function test_ubah_lamaran(): void
    {
        $id = $this->lamar(['surat_lamaran' => 'lama'])->json('data.id');

        $this->putJson("/api/mahasiswa/lamaran/$id", ['surat_lamaran' => 'baru'])
            ->assertOk()->assertJsonPath('data.surat_lamaran', 'baru');
    }

    public function test_ubah_dan_batal_ditolak_jika_sudah_diproses(): void
    {
        $id = $this->lamar()->json('data.id');
        Lamaran::whereKey($id)->update(['status_lamaran' => 'Diterima']);

        $this->putJson("/api/mahasiswa/lamaran/$id", ['surat_lamaran' => 'x'])->assertStatus(422);
        $this->deleteJson("/api/mahasiswa/lamaran/$id")->assertStatus(422);
    }

    public function test_batal_lalu_bisa_melamar_lagi(): void
    {
        $id = $this->lamar()->json('data.id');

        $this->deleteJson("/api/mahasiswa/lamaran/$id")
            ->assertOk()->assertJsonPath('data.status_lamaran', 'Dibatalkan');

        $this->lamar()->assertCreated()->assertJsonPath('data.status_lamaran', 'Terkirim');
        $this->assertDatabaseCount('lamaran', 1);
    }

    public function test_tidak_bisa_mengakses_lamaran_milik_orang_lain(): void
    {
        $id = $this->lamar()->json('data.id');

        Sanctum::actingAs($this->buatUser('mahasiswa', 'intruder@x.com'));
        $this->getJson("/api/mahasiswa/lamaran/$id")->assertNotFound();
        $this->deleteJson("/api/mahasiswa/lamaran/$id")->assertNotFound();
    }

    public function test_penyedia_melihat_pelamar_lowongannya_tanpa_yang_dibatalkan(): void
    {
        $id = $this->lamar()->json('data.id');

        Sanctum::actingAs($this->penyediaUser);
        $this->getJson("/api/penyedia/lowongan/{$this->lowongan->id}/pelamar")
            ->assertOk()->assertJsonPath('total', 1);

        Sanctum::actingAs($this->mhsUser);
        $this->deleteJson("/api/mahasiswa/lamaran/$id");

        Sanctum::actingAs($this->penyediaUser);
        $this->getJson("/api/penyedia/lowongan/{$this->lowongan->id}/pelamar")->assertJsonPath('total', 0);
    }

    public function test_penyedia_lain_tidak_bisa_melihat_pelamar(): void
    {
        $lain = $this->buatUser('penyedia', 'lain-penyedia@x.com');
        PenyediaProfile::create(['user_id' => $lain->id, 'nama_organisasi' => 'PT Lain']);

        Sanctum::actingAs($lain);
        $this->getJson("/api/penyedia/lowongan/{$this->lowongan->id}/pelamar")->assertNotFound();
    }

    public function test_mahasiswa_tidak_bisa_akses_endpoint_penyedia(): void
    {
        Sanctum::actingAs($this->mhsUser);
        $this->getJson("/api/penyedia/lowongan/{$this->lowongan->id}/pelamar")->assertForbidden();
    }
}
