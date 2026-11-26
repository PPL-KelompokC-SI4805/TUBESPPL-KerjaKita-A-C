<?php

namespace Tests\Feature;

use App\Models\KategoriLowongan;
use App\Models\Lowongan;
use App\Models\MahasiswaProfile;
use App\Models\PenyediaProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class LowonganTest extends TestCase
{
    use RefreshDatabase;

    protected User $mahasiswaUser;
    protected MahasiswaProfile $mahasiswaProfile;
    protected User $penyediaUser;
    protected PenyediaProfile $penyediaProfile;
    protected KategoriLowongan $kategoriSoftware;
    protected KategoriLowongan $kategoriDesign;
    protected Lowongan $activeJob1;
    protected Lowongan $activeJob2;
    protected Lowongan $closedJob;

    protected function setUp(): void
    {
        parent::setUp();

        // 1. Setup Kategori
        $this->kategoriSoftware = KategoriLowongan::create([
            'nama_kategori' => 'Software & Data',
            'slug'          => 'software-data',
        ]);

        $this->kategoriDesign = KategoriLowongan::create([
            'nama_kategori' => 'UI/UX Design',
            'slug'          => 'ui-ux-design',
        ]);

        // 2. Setup Mitra Penyedia
        $this->penyediaUser = User::create([
            'name'     => 'PT Teknologi Maju Nusantara',
            'email'    => 'mitra@tekmaju.com',
            'password' => bcrypt('password123'),
            'role'     => 'penyedia',
        ]);

        $this->penyediaProfile = PenyediaProfile::create([
            'user_id'         => $this->penyediaUser->id,
            'nama_organisasi' => 'PT Teknologi Maju Nusantara',
            'bidang_usaha'    => 'Perangkat Lunak',
            'alamat'          => 'Jakarta Selatan',
            'kota'            => 'Jakarta Selatan',
        ]);

        // 3. Setup Mahasiswa
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
            'universitas'  => 'Telkom University',
        ]);

        // 4. Setup Lowongan (Aktif & Tutup)
        $this->activeJob1 = Lowongan::create([
            'penyedia_id'     => $this->penyediaProfile->id,
            'kategori_id'     => $this->kategoriSoftware->id,
            'judul_posisi'    => 'Frontend Developer Intern',
            'jenis_pekerjaan' => 'magang',
            'tipe_lokasi'     => 'hybrid',
            'lokasi_kota'     => 'Jakarta Selatan',
            'waktu_kerja'     => '15-20 Jam/Minggu',
            'deskripsi'       => 'Pengembangan web dengan React.js',
            'persyaratan'     => 'Mahasiswa aktif semester 5',
            'kompensasi'      => 'Rp 3.500.000 / bln',
            'status_aktif'    => 1,
        ]);

        $this->activeJob2 = Lowongan::create([
            'penyedia_id'     => $this->penyediaProfile->id,
            'kategori_id'     => $this->kategoriDesign->id,
            'judul_posisi'    => 'UI/UX Designer Part-time',
            'jenis_pekerjaan' => 'paruh_waktu',
            'tipe_lokasi'     => 'remote',
            'lokasi_kota'     => 'Bandung',
            'waktu_kerja'     => '20 Jam/Minggu',
            'deskripsi'       => 'Merancang UI aplikasi fintech',
            'persyaratan'     => 'Mahir Figma',
            'kompensasi'      => 'Rp 2.800.000 / bln',
            'status_aktif'    => 1,
        ]);

        $this->closedJob = Lowongan::create([
            'penyedia_id'     => $this->penyediaProfile->id,
            'kategori_id'     => $this->kategoriSoftware->id,
            'judul_posisi'    => 'Backend Golang Intern (Tutup)',
            'jenis_pekerjaan' => 'magang',
            'tipe_lokasi'     => 'onsite',
            'lokasi_kota'     => 'Jakarta Selatan',
            'waktu_kerja'     => 'Full time',
            'deskripsi'       => 'Lowongan sudah ditutup',
            'status_aktif'    => 0, // Ditutup sesuai S8
        ]);
    }

    /**
     * F-13: Mahasiswa dapat melihat daftar lowongan aktif via REST API.
     * Lowongan yang ditutup tidak boleh tampil di daftar mahasiswa.
     */
    public function test_mahasiswa_can_view_active_lowongan_list(): void
    {
        Sanctum::actingAs($this->mahasiswaUser);

        $response = $this->getJson('/api/mahasiswa/lowongan');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ])
            ->assertJsonCount(2, 'data'); // Hanya 2 lowongan aktif yang tampil, 1 ditutup

        $titles = collect($response->json('data'))->pluck('judul_posisi');
        $this->assertTrue($titles->contains('Frontend Developer Intern'));
        $this->assertTrue($titles->contains('UI/UX Designer Part-time'));
        $this->assertFalse($titles->contains('Backend Golang Intern (Tutup)'));
    }

    /**
     * F-14 / FR-09: Mahasiswa dapat mencari lowongan berdasarkan kata kunci.
     */
    public function test_mahasiswa_can_search_lowongan_by_keyword(): void
    {
        Sanctum::actingAs($this->mahasiswaUser);

        // Cari berdasarkan judul posisi
        $response = $this->getJson('/api/mahasiswa/lowongan?keyword=Frontend');
        $response->assertStatus(200)
            ->assertJsonCount(1, 'data');
        $this->assertEquals('Frontend Developer Intern', $response->json('data.0.judul_posisi'));

        // Cari berdasarkan nama penyedia
        $responseMitra = $this->getJson('/api/mahasiswa/lowongan?keyword=Maju+Nusantara');
        $responseMitra->assertStatus(200);
        $this->assertGreaterThanOrEqual(1, count($responseMitra->json('data')));
    }

    /**
     * F-14 / FR-10: Mahasiswa dapat memfilter lowongan berdasarkan kategori.
     */
    public function test_mahasiswa_can_filter_lowongan_by_kategori(): void
    {
        Sanctum::actingAs($this->mahasiswaUser);

        $response = $this->getJson('/api/mahasiswa/lowongan?kategori_slug=ui-ux-design');
        $response->assertStatus(200)
            ->assertJsonCount(1, 'data');
        $this->assertEquals('UI/UX Designer Part-time', $response->json('data.0.judul_posisi'));
    }

    /**
     * F-14 / FR-10: Mahasiswa dapat memfilter lowongan berdasarkan jenis pekerjaan dan tipe lokasi.
     */
    public function test_mahasiswa_can_filter_lowongan_by_jenis_and_tipe_lokasi(): void
    {
        Sanctum::actingAs($this->mahasiswaUser);

        $response = $this->getJson('/api/mahasiswa/lowongan?jenis_pekerjaan=magang&tipe_lokasi=hybrid');
        $response->assertStatus(200)
            ->assertJsonCount(1, 'data');
        $this->assertEquals('Frontend Developer Intern', $response->json('data.0.judul_posisi'));
    }

    /**
     * F-13 / FR-11: Mahasiswa dapat melihat rincian detail lengkap lowongan.
     */
    public function test_mahasiswa_can_view_lowongan_detail(): void
    {
        Sanctum::actingAs($this->mahasiswaUser);

        $response = $this->getJson('/api/mahasiswa/lowongan/' . $this->activeJob1->id);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'id'           => $this->activeJob1->id,
                    'judul_posisi' => 'Frontend Developer Intern',
                    'tipe_lokasi'  => 'hybrid',
                    'lokasi_kota'  => 'Jakarta Selatan',
                    'kompensasi'   => 'Rp 3.500.000 / bln',
                ],
            ]);
    }

    /**
     * Akses halaman web Blade pencarian lowongan.
     */
    public function test_mahasiswa_can_access_web_view_lowongan(): void
    {
        $response = $this->actingAs($this->mahasiswaUser)->get('/mahasiswa/lowongan');
        $response->assertStatus(200);
        $response->assertSee('Peluang Karir Terbuka');
    }

    /**
     * Pengguna tanpa autentikasi tidak dapat mengakses API lowongan.
     */
    public function test_unauthenticated_user_cannot_access_lowongan_api(): void
    {
        $response = $this->getJson('/api/mahasiswa/lowongan');
        $response->assertStatus(401);
    }
}
