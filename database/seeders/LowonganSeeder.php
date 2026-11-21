<?php

namespace Database\Seeders;

use App\Models\KategoriLowongan;
use App\Models\Lowongan;
use App\Models\LowonganTersimpan;
use App\Models\MahasiswaProfile;
use App\Models\PenyediaProfile;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class LowonganSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // ─── 1. Kategori Lowongan ──────────────────────────────────────
        $categories = [
            ['nama_kategori' => 'Software & Data', 'slug' => 'software-data'],
            ['nama_kategori' => 'UI/UX Design', 'slug' => 'ui-ux-design'],
            ['nama_kategori' => 'Digital Marketing', 'slug' => 'digital-marketing'],
            ['nama_kategori' => 'Data Analytics', 'slug' => 'data-analytics'],
            ['nama_kategori' => 'Finance & Accounting', 'slug' => 'finance-accounting'],
            ['nama_kategori' => 'Content Writing', 'slug' => 'content-writing'],
        ];

        $categoryMap = [];
        foreach ($categories as $cat) {
            $created = KategoriLowongan::firstOrCreate(
                ['slug' => $cat['slug']],
                ['nama_kategori' => $cat['nama_kategori']]
            );
            $categoryMap[$cat['slug']] = $created->id;
        }

        // ─── 2. Akun & Profil Penyedia (Mitra) ──────────────────────────
        $mitraData = [
            [
                'email'           => 'mitra.tekmaju@kerjakita.id',
                'name'            => 'PT Teknologi Maju Nusantara',
                'bidang_usaha'    => 'Perangkat Lunak & Solusi Digital SaaS',
                'deskripsi'       => 'Perusahaan pengembang platform digital terkemuka yang berfokus pada solusi SaaS publik dan enterprise berskala nasional.',
                'alamat'          => 'Kawasan Mega Kuningan Lot 5, Gedung Menara Digital Lt. 12',
                'kota'            => 'Jakarta Selatan',
                'website'         => 'https://tekmajunusantara.co.id',
                'kontak'          => '021-5550192',
            ],
            [
                'email'           => 'mitra.nusantara@kerjakita.id',
                'name'            => 'Nusantara Finansial Kreatif',
                'bidang_usaha'    => 'Desain & Konsultan Finansial',
                'deskripsi'       => 'Studio kreatif finansial digital yang merancang pengalaman perbankan modern dan aplikasi keuangan inklusif.',
                'alamat'          => 'Jl. Dago Asri No. 88, Coblong',
                'kota'            => 'Bandung',
                'website'         => 'https://nusantaracreative.id',
                'kontak'          => '022-8920193',
            ],
            [
                'email'           => 'mitra.akselerasi@kerjakita.id',
                'name'            => 'Akselerasi Data Mandiri',
                'bidang_usaha'    => 'Data Science & Konsultan AI',
                'deskripsi'       => 'Penyedia layanan analitika data besar dan automasi cerdas untuk efisiensi bisnis retail dan industri manufaktur.',
                'alamat'          => 'Jl. Raya Darmo Square Blok B-4',
                'kota'            => 'Surabaya',
                'website'         => 'https://akselerasidata.com',
                'kontak'          => '031-7789201',
            ],
            [
                'email'           => 'mitra.sibermedia@kerjakita.id',
                'name'            => 'Siber Mediatek Indonesia',
                'bidang_usaha'    => 'Digital Marketing & Media Kreatif',
                'deskripsi'       => 'Agensi periklanan digital dan produksi konten multimedia berbasis riset audiens generasi muda.',
                'alamat'          => 'Jl. Palagan Tentara Pelajar Km 7, Sariharjo',
                'kota'            => 'Yogyakarta',
                'website'         => 'https://sibermediatek.id',
                'kontak'          => '0274-889922',
            ],
            [
                'email'           => 'mitra.fintek@kerjakita.id',
                'name'            => 'PT Fintek Karya Gemilang',
                'bidang_usaha'    => 'Fintech & Payment Gateway',
                'deskripsi'       => 'Platform pembayaran elektronik terlisensi resmi yang menghubungkan jutaan pengguna dengan sistem perbankan nasional.',
                'alamat'          => 'Digital Hub BSD City, Foresta Business Loft 6',
                'kota'            => 'Tangerang',
                'website'         => 'https://fintekgemilang.id',
                'kontak'          => '021-2998811',
            ],
        ];

        $mitraMap = [];
        foreach ($mitraData as $mitra) {
            $user = User::firstOrCreate(
                ['email' => $mitra['email']],
                [
                    'name'     => $mitra['name'],
                    'password' => Hash::make('password123'),
                    'role'     => 'penyedia',
                ]
            );

            $profile = PenyediaProfile::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'nama_organisasi' => $mitra['name'],
                    'bidang_usaha'    => $mitra['bidang_usaha'],
                    'deskripsi'       => $mitra['deskripsi'],
                    'alamat'          => $mitra['alamat'],
                    'kota'            => $mitra['kota'],
                    'website'         => $mitra['website'],
                    'kontak'          => $mitra['kontak'],
                ]
            );

            $mitraMap[$mitra['name']] = $profile->id;
        }

        // ─── 3. Lowongan Kerja (Aktif & Tutup) ──────────────────────────
        $lowonganData = [
            [
                'penyedia_name'   => 'PT Teknologi Maju Nusantara',
                'kategori_slug'   => 'software-data',
                'judul_posisi'    => 'Frontend Developer Intern',
                'jenis_pekerjaan' => 'magang',
                'tipe_lokasi'     => 'hybrid',
                'lokasi_kota'     => 'Jakarta Selatan',
                'waktu_kerja'     => 'Fleksibel Mahasiswa (15-20 jam/minggu)',
                'kompensasi'      => 'Rp 3.500.000 / bln',
                'deskripsi'       => 'Program magang jalur akselerasi talenta rekayasa perangkat lunak untuk berkontribusi langsung pada implementasi antarmuka portal layanan publik nasional berkinerja tinggi. Peserta magang akan ditempatkan di dalam tim inti pengembangan produk sistem layanan data terdistribusi dan dibimbing langsung oleh Senior Software Engineer selama 6 bulan penuh.',
                'persyaratan'     => "• Mahasiswa aktif jenjang S1/D4 jurusan Teknik Informatika, Sistem Informasi, atau serumpun min. semester 5.\n• Mengembangkan komponen antarmuka web responsif menggunakan React.js, Tailwind CSS, dan Next.js sesuai rancangan sistem desain tim UI/UX.\n• Menguasai dasar arsitektur REST API, State Management, dan version control Git.\n• Mampu berkomitmen 15-20 jam per minggu dengan skema hybrid yang ramah jadwal kuliah.",
                'status_aktif'    => 1,
            ],
            [
                'penyedia_name'   => 'Nusantara Finansial Kreatif',
                'kategori_slug'   => 'ui-ux-design',
                'judul_posisi'    => 'UI/UX Designer Part-time',
                'jenis_pekerjaan' => 'paruh_waktu',
                'tipe_lokasi'     => 'remote',
                'lokasi_kota'     => 'Bandung',
                'waktu_kerja'     => '20 jam/minggu (Fleksibel Tugas Akhir)',
                'kompensasi'      => 'Rp 2.800.000 / bln',
                'deskripsi'       => 'Membantu tim produk merancang antarmuka aplikasi fintech mobile dan dashboard web analytics. Cocok bagi mahasiswa tingkat akhir yang sedang mengerjakan skripsi karena jam kerja sangat fleksibel dan 100% remote.',
                'persyaratan'     => "• Mahasiswa DKV, Desain Produk, atau Informatika.\n• Mahir menggunakan Figma (auto-layout, components, interactive prototypes).\n• Memahami dasar design thinking, wireframing, dan usability testing.\n• Memiliki portofolio studi kasus desain antarmuka digital.",
                'status_aktif'    => 1,
            ],
            [
                'penyedia_name'   => 'Akselerasi Data Mandiri',
                'kategori_slug'   => 'data-analytics',
                'judul_posisi'    => 'Data Analyst Freelance',
                'jenis_pekerjaan' => 'freelance',
                'tipe_lokasi'     => 'remote',
                'lokasi_kota'     => 'Surabaya',
                'waktu_kerja'     => 'Project Based (Fleksibel Mahasiswa)',
                'kompensasi'      => 'Rp 4.000.000 / modul',
                'deskripsi'       => 'Menganalisis dataset perilaku transaksi pelanggan dan menyusun insight visual interaktif untuk mitra korporasi. Kerja mandiri berbasis target modul penyelesaian laporan.',
                'persyaratan'     => "• Mahasiswa Statistika, Matematika, Data Science, atau Sistem Informasi.\n• Menguasai SQL kueri lanjutan dan Python (Pandas, Numpy, Seaborn/Plotly).\n• Berpengalaman membuat visualisasi dashboard di Tableau atau Power BI.\n• Memiliki sertifikat data science (Google, IBM, atau setara) menjadi nilai tambah.",
                'status_aktif'    => 1,
            ],
            [
                'penyedia_name'   => 'Siber Mediatek Indonesia',
                'kategori_slug'   => 'digital-marketing',
                'judul_posisi'    => 'Social Media Specialist Freelance',
                'jenis_pekerjaan' => 'freelance',
                'tipe_lokasi'     => 'remote',
                'lokasi_kota'     => 'Yogyakarta',
                'waktu_kerja'     => 'Fleksibel (Bisa Sambil Kuliah)',
                'kompensasi'      => 'Rp 1.500.000 / proyek',
                'deskripsi'       => 'Mengelola strategi konten media sosial (TikTok, Instagram, LinkedIn), menganalisis tren viral, dan membuat naskah copywriting promosi yang memikat segmen mahasiswa.',
                'persyaratan'     => "• Mahasiswa Ilmu Komunikasi, Manajemen Pemasaran, atau setara.\n• Aktif dan melek tren algoritma TikTok & Instagram Reels terkini.\n• Menguasai copywriting kreatif, Canva / CapCut.\n• Melampirkan portofolio akun media sosial yang pernah dikelola.",
                'status_aktif'    => 1,
            ],
            [
                'penyedia_name'   => 'PT Fintek Karya Gemilang',
                'kategori_slug'   => 'software-data',
                'judul_posisi'    => 'QA Engineering Intern',
                'jenis_pekerjaan' => 'magang',
                'tipe_lokasi'     => 'onsite',
                'lokasi_kota'     => 'Tangerang',
                'waktu_kerja'     => '3 hari/minggu (Senin, Rabu, Jumat)',
                'kompensasi'      => 'Rp 3.800.000 / bln',
                'deskripsi'       => 'Berperan dalam pengujian otomatis dan manual modul transaksi finansial, memastikan kepatuhan standar keamanan data perbankan dan keandalan sistem API payment gateway.',
                'persyaratan'     => "• Mahasiswa aktif semester 6 ke atas jurusan Teknik Komputer / Informatika.\n• Menguasai pengujian API dengan Postman.\n• Memiliki pemahaman tentang automated testing dasar (Cypress, Selenium, atau PHPUnit).\n• Bersedia magang onsite di BSD City.",
                'status_aktif'    => 1,
            ],
            [
                'penyedia_name'   => 'Siber Mediatek Indonesia',
                'kategori_slug'   => 'content-writing',
                'judul_posisi'    => 'Content Writer Paruh Waktu',
                'jenis_pekerjaan' => 'paruh_waktu',
                'tipe_lokasi'     => 'remote',
                'lokasi_kota'     => 'Jakarta',
                'waktu_kerja'     => 'Fleksibel 15 jam/minggu',
                'kompensasi'      => 'Rp 2.000.000 / bln',
                'deskripsi'       => 'Menulis artikel edukasi karir, panduan magang mahasiswa, dan wawasan industri teknologi secara berkala untuk portal media online.',
                'persyaratan'     => "• Mahasiswa Sastra, Jurnalistik, Hubungan Masyarakat, atau umum dengan minat menulis.\n• Memahami kaidah SEO on-page dasar dan riset kata kunci.\n• Melampirkan contoh tulisan atau artikel yang pernah dipublikasikan.",
                'status_aktif'    => 1,
            ],
            [
                'penyedia_name'   => 'PT Teknologi Maju Nusantara',
                'kategori_slug'   => 'software-data',
                'judul_posisi'    => 'Mobile App Developer Intern (Batch Lalu)',
                'jenis_pekerjaan' => 'magang',
                'tipe_lokasi'     => 'onsite',
                'lokasi_kota'     => 'Jakarta Pusat',
                'waktu_kerja'     => 'Penuh Waktu (Selesai Perekrutan)',
                'kompensasi'      => 'Rp 4.000.000 / bln',
                'deskripsi'       => 'Lowongan periode lalu yang sudah ditutup. Tidak ditampilkan pada eksplorasi mahasiswa aktif.',
                'persyaratan'     => 'Flutter, Kotlin, atau React Native.',
                'status_aktif'    => 0, // Ditutup
            ],
        ];

        $createdJobs = [];
        foreach ($lowonganData as $job) {
            $penyediaId = $mitraMap[$job['penyedia_name']] ?? null;
            $kategoriId = $categoryMap[$job['kategori_slug']] ?? null;

            if ($penyediaId && $kategoriId) {
                $created = Lowongan::firstOrCreate(
                    [
                        'penyedia_id'  => $penyediaId,
                        'judul_posisi' => $job['judul_posisi'],
                    ],
                    [
                        'kategori_id'     => $kategoriId,
                        'jenis_pekerjaan' => $job['jenis_pekerjaan'],
                        'tipe_lokasi'     => $job['tipe_lokasi'],
                        'lokasi_kota'     => $job['lokasi_kota'],
                        'waktu_kerja'     => $job['waktu_kerja'],
                        'kompensasi'      => $job['kompensasi'],
                        'deskripsi'       => $job['deskripsi'],
                        'persyaratan'     => $job['persyaratan'],
                        'status_aktif'    => $job['status_aktif'],
                    ]
                );
                $createdJobs[$job['judul_posisi']] = $created;
            }
        }

        // ─── 4. Akun & Profil Mahasiswa Demo (Ilham Ramadhan) ──────────
        $mahasiswaUser = User::firstOrCreate(
            ['email' => 'mahasiswa@kerjakita.id'],
            [
                'name'     => 'Ilham Ramadhan',
                'password' => Hash::make('password123'),
                'role'     => 'mahasiswa',
            ]
        );

        $mhsProfile = MahasiswaProfile::firstOrCreate(
            ['user_id' => $mahasiswaUser->id],
            [
                'nama_lengkap'       => 'Ilham Ramadhan',
                'nim'                => '102022400320',
                'universitas'        => 'Telkom University',
                'fakultas'           => 'Fakultas Rekayasa Industri',
                'program_studi'      => 'Sistem Informasi',
                'jenjang'            => 'S1',
                'semester'           => 5,
                'domisili'           => 'Jakarta Selatan',
                'keahlian'           => 'React.js, Tailwind CSS, Next.js, Figma, SQL, Git',
                'ketersediaan_waktu' => '20 Jam/Minggu (Fleksibel - Hari Bebas Kuliah)',
                'bio'                => 'Mahasiswa Sistem Informasi semester 5 Telkom University dengan fokus minat pada Web Engineering, UI/UX Implementation, dan Cloud Technologies.',
            ]
        );

        // ─── 5. Data Lowongan Tersimpan Contoh (F-15 / Mockup Halaman 42) ──
        $savedSamples = [
            'Frontend Developer Intern' => 'Perlu mempersiapkan portofolio studi kasus redesign aplikasi kampus sebelum tanggal 20. Pastikan transkrip semester 6 terbaru sudah terlampir.',
            'UI/UX Designer Part-time'  => 'Jadwal kerja 20 jam per minggu. Sudah disesuaikan dengan jadwal perkuliahan semester ganjil hari Selasa dan Kamis sore.',
            'Data Analyst Freelance'    => 'Fokus tugas pembuatan dashboard visualisasi Python dan SQL. Sertakan sertifikat pelatihan data science Google Career Certificates.',
            'Social Media Specialist Freelance' => 'Lampirkan data engagement rate akun organisasi BEM fakultas yang dikelola periode lalu sebagai bukti performa copywriting.',
            'QA Engineering Intern'     => 'Pelajari Cypress dan API testing Postman.',
        ];

        foreach ($savedSamples as $posisi => $catatan) {
            if (isset($createdJobs[$posisi])) {
                LowonganTersimpan::firstOrCreate(
                    [
                        'mahasiswa_id' => $mhsProfile->id,
                        'lowongan_id'  => $createdJobs[$posisi]->id,
                    ],
                    [
                        'catatan_pribadi' => $catatan,
                    ]
                );
            }
        }
    }
}
