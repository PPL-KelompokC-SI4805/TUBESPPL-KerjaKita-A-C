# Proposal Pengembangan Produk

# KerjaKita: Platform Lowongan Kerja Mahasiswa

**Disusun oleh:**
* 102022400320 - Ilham Ramadhan
* 102022400356 - Muhammad Hadi Saputra
* 102022400326 - Natasya Adawiyah Ariqah
* 102022400295 - Rehulina Siahaan
* 102022400199 - Renjiro Athaya
* 102022400213 - Daniel Desmon Christoper L

**Tanggal:** 8 Oktober 2026

---

## 1. Pendahuluan

### 1.1 Latar Belakang
Mahasiswa saat ini tidak hanya dituntut menguasai materi perkuliahan, tetapi juga perlu memiliki pengalaman dan keterampilan praktis sebelum lulus. Kegiatan magang, *freelance*, dan pekerjaan paruh waktu menjadi sarana untuk memperoleh pengalaman tersebut. Namun, informasi lowongan tersebar di media sosial, situs lowongan umum, grup percakapan, dan informasi dari rekan. Mahasiswa harus menelusuri banyak sumber dan membandingkan lowongan satu per satu.

Permasalahan lain adalah ketidaksesuaian lowongan dengan kondisi mahasiswa. Jadwal kerja sering bertabrakan dengan jadwal kuliah, lokasi terlalu jauh dari kampus, persyaratan pengalaman terlalu tinggi, atau bidang pekerjaan tidak sesuai dengan kemampuan. Proses pendaftaran juga berbeda-beda pada setiap lowongan (formulir daring, surel, atau kontak pribadi), sehingga mahasiswa kesulitan mengelola dan memantau lamaran yang telah dikirim.

Masalah ini perlu diselesaikan karena pengalaman kerja yang sesuai membantu mahasiswa mengembangkan keterampilan dan mengenal dunia kerja sebelum lulus. Permasalahan ini berkaitan dengan Sustainable Development Goal (SDG) 8: *Decent Work and Economic Growth*, khususnya target peningkatan akses terhadap kesempatan kerja yang layak dan produktif bagi kaum muda.

Berdasarkan permasalahan tersebut, kelompok kami mengusulkan **KerjaKita**, yaitu platform web lowongan kerja khusus mahasiswa. Pengembangan difokuskan pada tiga fitur utama:
1. Manajemen Akun dan Profil bagi mahasiswa dan penyedia lowongan.
2. Manajemen dan Pencarian Lowongan Kerja.
3. Pengajuan dan Riwayat Lamaran Kerja.

Ketiga fitur ini mengumpulkan informasi lowongan dalam satu tempat, memudahkan pencarian berdasarkan bidang, lokasi, dan waktu kerja, serta menyederhanakan pengajuan dan pemantauan lamaran.

### 1.2 Tujuan
Tujuan pengembangan KerjaKita adalah:
1. Menyediakan satu platform yang mengumpulkan informasi magang, *freelance*, dan pekerjaan paruh waktu bagi mahasiswa.
2. Memudahkan mahasiswa menemukan lowongan yang sesuai dengan bidang, kemampuan, lokasi, dan waktu yang tersedia melalui fitur pencarian dan penyaringan.
3. Menyediakan sarana bagi penyedia lowongan untuk mengelola lowongan dan menerima lamaran secara terpusat.
4. Menyederhanakan proses pengajuan lamaran dan memungkinkan mahasiswa memantau riwayat lamarannya.
5. Mendukung pencapaian SDG 8 dengan memperluas akses mahasiswa terhadap kesempatan kerja yang layak.

### 1.3 Output
Output pengembangan adalah web KerjaKita yang dapat dijalankan dan didemonstrasikan. Website terdiri dari **7 modul dengan 21 fitur**, dan setiap modul mencakup operasi CRUD. Pengguna sistem adalah **Mahasiswa** dan **Penyedia Lowongan**.

| Modul | ID | Fitur | Hasil yang Dibutuhkan | Pengguna | Penanggung Jawab |
| :--- | :--- | :--- | :--- | :--- | :--- |
| M1. Autentikasi | F-01 | Registrasi | Formulir pendaftaran akun mahasiswa dan penyedia. | Mahasiswa, Penyedia | Ilham |
| | F-02 | Login, logout, dan lihat akun | Autentikasi dan manajemen sesi pengguna sesuai peran. | Mahasiswa, Penyedia | Ilham |
| | F-03 | Ubah kata sandi | Penggantian kata sandi akun pengguna. | Mahasiswa, Penyedia | Ilham |
| M2. Profil Mahasiswa | F-04 | Melihat profil | Halaman data diri dan akademik mahasiswa. | Mahasiswa | Renjiro |
| | F-05 | Mengubah profil | Formulir ubah data diri, program studi, keahlian, dan ketersediaan waktu. | Mahasiswa | Renjiro |
| | F-06 | Kelola CV | Unggah, ganti, dan hapus CV berformat PDF. | Mahasiswa | Renjiro |
| | F-19 | Kelola Dokumen & Pendukung Profil | Unggah, perbarui, dan hapus PDF Transkrip Nilai serta tautan portofolio eksternal. | Mahasiswa | Ilham |
| | F-20 | Dashboard Statistik & Overview Mahasiswa | Panel dashboard ringkasan status kelengkapan profil dan statistik lamaran. | Mahasiswa | Ilham / Rehulina |
| M3. Profil Penyedia | F-07 | Melihat profil organisasi | Halaman informasi organisasi penyedia. | Penyedia | Natasya |
| | F-08 | Mengubah profil organisasi | Formulir ubah data organisasi. | Penyedia | Natasya |
| | F-09 | Kelola logo | Unggah, ganti, dan hapus logo organisasi. | Penyedia | Natasya |
| M4. Manajemen Lowongan | F-10 | Tambah lowongan | Formulir pembuatan lowongan. | Penyedia | Rehulina |
| | F-11 | Daftar lowongan saya dan detail | Tabel lowongan milik penyedia beserta halaman detailnya. | Penyedia | Rehulina |
| | F-12 | Ubah dan hapus lowongan | Ubah data dan status (aktif/ditutup), serta hapus dengan konfirmasi. | Penyedia | Rehulina |
| M5. Pencarian Lowongan | F-13 | Daftar dan detail lowongan | Daftar lowongan aktif berbentuk kartu dan halaman detail. | Mahasiswa | Hadi |
| | F-14 | Pencarian dan filter | Pencarian kata kunci dan filter kategori, jenis, lokasi. | Mahasiswa | Hadi |
| | F-15 | Lowongan tersimpan | Simpan lowongan, lihat daftar simpanan, ubah catatan, dan hapus simpanan. | Mahasiswa | Hadi |
| M6. Lamaran | F-16 | Mengajukan lamaran | Formulir lamaran (CV dan surat lamaran) tanpa lamaran ganda. | Mahasiswa | Desmon |
| | F-17 | Melihat lamaran | Riwayat lamaran (mahasiswa) serta daftar pelamar dan unduh CV (penyedia). | Mahasiswa, Penyedia | Desmon |
| | F-18 | Ubah dan batalkan lamaran | Perubahan surat lamaran dan pembatalan lamaran. | Mahasiswa | Desmon |
| M7. Informasi dan Bantuan | F-21 | Halaman Bantuan & Panduan Registrasi / Kemitraan | Halaman informasi interaktif berisi panduan pendaftaran/kemitraan dan FAQ. | Mahasiswa, Penyedia | Ilham / Hadi |

#### 1.3.1 Batasan Produk
* Sistem mencakup 21 fitur pada tabel di atas, dari pendaftaran akun sampai pengajuan lamaran dan bantuan.
* Proses setelah lamaran (seleksi, wawancara, penawaran, keputusan penerimaan) dilakukan di luar sistem oleh penyedia lowongan.
* Hanya ada dua peran: **Mahasiswa** dan **Penyedia Lowongan** (tanpa Admin). Data kategori disiapkan melalui *seeding*.
* Tidak ada fitur *chat*, notifikasi *real-time*, pembayaran, reset kata sandi via email, maupun rekomendasi berbasis AI.
* Penutupan lowongan dilakukan manual oleh penyedia (atau otomatis saat melewati batas waktu).
* Status lamaran di sistem hanya `"Terkirim"`. Lamaran hanya dapat diubah atau dibatalkan selama lowongan masih aktif.
* Platform berupa web responsif, bukan aplikasi *mobile* native.
* Sistem tidak memverifikasi keaslian penyedia maupun data mahasiswa secara manual oleh admin.

---

## 2. Deskripsi Produk

### 2.1 Usulan Solusi
| Masalah | Solusi pada KerjaKita |
| :--- | :--- |
| Informasi lowongan tersebar di banyak sumber | Lowongan dikelola penyedia dan ditampilkan dalam satu daftar (F-10 s.d. F-13). |
| Lowongan tidak sesuai bidang dan lokasi | Pencarian dan filter berdasarkan kata kunci, kategori, jenis, dan lokasi; detail lowongan memuat persyaratan lengkap (F-13, F-14). |
| Sulit membandingkan banyak lowongan | Mahasiswa menyimpan lowongan incaran beserta catatan pribadi (F-15). |
| Cara mendaftar berbeda-beda | Satu formulir lamaran yang memakai CV dari profil (F-06, F-16). |
| Sulit memantau dan mengelola lamaran | Riwayat lamaran, serta fitur ubah dan batalkan lamaran (F-17, F-18). |
| Penyedia sulit merekap pelamar | Daftar pelamar per lowongan di satu tempat (F-17). |
| Mahasiswa/penyedia butuh panduan | Halaman Bantuan & Panduan Registrasi / Kemitraan (F-21). |

### 2.2 Deskripsi Produk
1. **M1 Akun:** Pengguna mendaftar, login dan logout dengan hak akses sesuai peran, serta mengganti kata sandi.
2. **M2 Profil Mahasiswa:** Mahasiswa melihat dan mengubah data diri, program studi, keahlian, ketersediaan waktu, mengelola CV & dokumen pendukung, serta melihat dashboard ringkasan mahasiswa.
3. **M3 Profil Penyedia:** Penyedia melihat dan mengubah profil organisasi serta mengelola logo.
4. **M4 Manajemen Lowongan:** Penyedia menambah, melihat, mengubah (termasuk menutup), dan menghapus lowongan.
5. **M5 Pencarian Lowongan:** Mahasiswa melihat daftar dan detail lowongan, mencari dan memfilter, serta menyimpan lowongan dengan catatan pribadi.
6. **M6 Lamaran:** Mahasiswa mengajukan, melihat, mengubah, dan membatalkan lamaran; penyedia melihat pelamar dan mengunduh CV.
7. **M7 Informasi dan Bantuan:** Pengguna mengakses pusat bantuan, FAQ, dan panduan kemitraan.

### 2.3 Proses Bisnis

#### 2.3.1 Proses Bisnis Existing
Mahasiswa mencari informasi dari media sosial, situs lowongan, grup percakapan, atau teman secara terpisah, menilai kesesuaian manual, dan mengirim lamaran lewat berbagai kanal tanpa pemantauan terpusat. Penyedia juga menyebarkan lowongan dan merekap pelamar secara manual.

#### 2.3.2 Proses Bisnis Usulan
Aktor sistem terdiri dari **Mahasiswa**, **Penyedia Lowongan**, dan **Sistem KerjaKita**:

* **Fase A. Akun (M1: F-01 s.d. F-03):**
  * `U1` (Mahasiswa/Penyedia): Mengisi formulir registrasi sesuai peran (F-01).
  * `S1` (Sistem): Memvalidasi format dan keunikan email, meng-hash kata sandi (F-01).
  * `S2` (Sistem): Menyimpan akun ke tabel `users` dan profil sesuai peran (F-01).
  * `U2` (Mahasiswa/Penyedia): Login dengan email dan kata sandi (F-02).
  * `S3` (Sistem): Memverifikasi kredensial, membuat token Sanctum, mengarahkan ke halaman sesuai peran (F-02).
  * `U3` & `S4` (Pengguna & Sistem): Mengubah kata sandi atau logout/menghapus token sesi (F-02, F-03).

* **Fase B. Profil (M2 & M3: F-04 s.d. F-09, F-19, F-20):**
  * `U4` (Mahasiswa): Melihat dan mengubah data diri, prodi, keahlian, ketersediaan waktu (F-04, F-05).
  * `U5` & `S5` (Mahasiswa & Sistem): Unggah/ganti/hapus CV PDF (maks. 2 MB), transkrip nilai PDF, dan link portofolio (F-06, F-19).
  * `U6`, `U7`, `S6` (Penyedia & Sistem): Melihat/mengubah profil organisasi serta unggah/ganti/hapus logo (F-07, F-08, F-09).

* **Fase C. Manajemen Lowongan (M4: F-10 s.d. F-12):**
  * `U8` & `S7` (Penyedia & Sistem): Mengisi form lowongan (posisi, kategori, jenis, lokasi, waktu kerja, persyaratan, kompensasi) dan menyimpan dengan status aktif (F-10).
  * `U9`, `U10`, `S8` (Penyedia & Sistem): Melihat daftar lowongan milik sendiri, mengubah data, menutup status, atau menghapus lowongan dengan konfirmasi (F-11, F-12).

* **Fase D. Pencarian Lowongan (M5: F-13 s.d. F-15):**
  * `S9`, `U11`, `S10` (Mahasiswa & Sistem): Menampilkan daftar lowongan aktif berbentuk kartu, pencarian kata kunci, dan filter kategori/jenis/lokasi (F-13, F-14).
  * `U12`, `U13`, `S11` (Mahasiswa & Sistem): Membuka detail lowongan serta menyimpan/mengubah catatan/menghapus simpanan lowongan (F-13, F-15).

* **Fase E. Lamaran (M6: F-16 s.d. F-18):**
  * `U14`, `S12`, `S13` (Mahasiswa & Sistem): Mengisi form lamaran (pakai CV profil + surat lamaran). Sistem memvalidasi lowongan aktif & belum pernah dilamar, lalu menyalin CV sebagai *snapshot* dan menyimpan lamaran berstatus `"Terkirim"` (F-16).
  * `U15`, `U16`, `S14` (Mahasiswa & Sistem): Melihat riwayat lamaran, mengubah surat lamaran, atau membatalkan lamaran selama lowongan masih aktif (F-17, F-18).
  * `U17`, `S15` (Penyedia & Sistem): Melihat daftar pelamar khusus untuk lowongan miliknya (RBAC) dan mengunduh CV *snapshot* pelamar (F-17).

* **Fase F. Bantuan / FAQ (M7: F-21):**
  * `U18`, `S16`, `U19` (Pengguna & Sistem): Mengakses halaman pusat bantuan, memilih kategori FAQ, atau mencari topik panduan registrasi/kemitraan (F-21).

---

## 3. Kebutuhan Sistem

### 3.1 Kebutuhan Fungsional

| ID | Kebutuhan Fungsional | Deskripsi |
| :--- | :--- | :--- |
| FR-01 | Registrasi Mahasiswa | Form pendaftaran akun mahasiswa (nama, email, kata sandi, NIM, universitas). Email unik dan tervalidasi. |
| FR-02 | Registrasi Mitra | Form pendaftaran akun penyedia lowongan (nama organisasi, email, kata sandi, bidang usaha, kontak). |
| FR-03 | Login | Memverifikasi email dan kata sandi, lalu mengarahkan pengguna ke halaman sesuai perannya (`mahasiswa` atau `penyedia`). |
| FR-04 | Logout | Mengakhiri sesi pengguna dan menghapus token autentikasi. |
| FR-05 | Ubah Kata Sandi | Pengguna dapat mengubah kata sandi akunnya di dalam sistem. |
| FR-06 | Kelola Profil Mahasiswa | Mahasiswa melihat dan mengubah data diri, prodi, semester, domisili, keahlian, ketersediaan waktu, bio singkat, unggah CV (PDF), transkrip PDF, dan link portofolio. |
| FR-07 | Kelola Profil Mitra | Mitra melihat dan mengubah profil organisasi (nama, deskripsi, alamat, kota, website, kontak, logo). |
| FR-08 | Melihat Daftar Lowongan | Menampilkan daftar lowongan berstatus aktif dalam bentuk kartu/list dengan pagination. |
| FR-09 | Pencarian Lowongan | Mencari lowongan berdasarkan kata kunci pada judul posisi atau nama perusahaan. |
| FR-10 | Filter Lowongan | Menyaring lowongan berdasarkan kategori, jenis (magang/freelance/paruh waktu), lokasi/kota, tipe kerja (onsite/remote/hybrid), dan waktu kerja. |
| FR-11 | Detail Lowongan | Menampilkan informasi lengkap lowongan: posisi, deskripsi, persyaratan, lokasi, waktu kerja, kompensasi, batas waktu, dan profil singkat mitra. |
| FR-12 | Mengajukan Lamaran | Mahasiswa mengajukan lamaran pada lowongan aktif dengan CV dari profil dan surat lamaran singkat. Sistem mencegah lamaran ganda pada lowongan yang sama. |
| FR-13 | Riwayat & Kelola Lamaran | Mahasiswa melihat daftar lamaran terkirim, mengubah surat lamaran, atau membatalkan lamaran selama lowongan aktif. |
| FR-14 | Dashboard Mitra & Mahasiswa | Menampilkan ringkasan metrik lowongan aktif & pelamar bagi mitra, serta ringkasan kelengkapan profil & lamaran bagi mahasiswa. |
| FR-15 | Tambah Lowongan | Mitra membuat lowongan baru (posisi, kategori, jenis, deskripsi, persyaratan, lokasi, waktu kerja, kompensasi, batas waktu). |
| FR-16 | Ubah, Hapus, dan Tutup Lowongan | Mitra mengubah, menghapus, atau menutup lowongan miliknya. |
| FR-17 | Daftar Pelamar | Mitra melihat daftar pelamar pada setiap lowongan miliknya lengkap dengan tanggal lamaran. |
| FR-18 | Detail Pelamar dan Unduh CV | Mitra melihat profil pelamar, surat lamaran, dan mengunduh CV *snapshot*. |

#### 3.1.2 Karakteristik Pengguna
* **Mahasiswa:** Mahasiswa aktif pencari magang, *freelance*, atau paruh waktu. Hak akses: CRUD profil/CV/dokumen sendiri, Read/Search/Filter lowongan, CRUD simpanan lowongan, Create/Read/Update/Delete lamaran sendiri.
* **Penyedia Lowongan (Mitra):** Perusahaan, startup, UMKM, atau organisasi pembuka lowongan. Hak akses: Read/Update profil organisasi & logo sendiri, CRUD lowongan miliknya, Read pelamar dan unduh CV pada lowongan miliknya.

### 3.2 Kebutuhan Non-Fungsional
* **NFR-01 (Usability):** Alur "cari lowongan -> ajukan lamaran" maksimal 5 langkah.
* **NFR-02 (Responsif):** Tampilan responsif untuk desktop, tablet, dan ponsel (min. lebar 360 px).
* **NFR-03 (Kompatibilitas):** Berjalan baik pada Chrome, Firefox, Edge, dan Safari versi terbaru.
* **NFR-04 (Umpan Balik):** Pesan validasi, konfirmasi, dan galat jelas dalam Bahasa Indonesia.
* **NFR-05 & NFR-06 (Performance):** Waktu muat halaman <= 3 detik; waktu respons pencarian/filter <= 2 detik.
* **NFR-08 (Security - Auth):** Kata sandi di-hash menggunakan bcrypt/Argon2.
* **NFR-09 (Security - RBAC):** Pembatasan akses berdasarkan peran (`mahasiswa` vs `penyedia`) dan kepemilikan resource.
* **NFR-10 & NFR-11 (Proteksi & File):** Validasi input terhadap SQL Injection/XSS/CSRF; file CV wajib PDF maks. 2 MB yang divalidasi di sisi server.

### 3.3 Kebutuhan Teknis
* **TR-01 & TR-02 (Frontend):** React.js (Vite), Tailwind CSS, React Router, Axios.
* **TR-03 & TR-04 (Backend):** PHP 8.2+ dengan Laravel 11 (arsitektur MVC / REST API), Laravel Sanctum (token-based authentication) + middleware RBAC.
* **TR-05 (Database):** MySQL 8.0 dengan Eloquent ORM dan database migrations.
* **TR-06 (Storage):** Local storage (tahap pengembangan) atau object storage (AWS S3 / Cloudflare R2) untuk CV dan logo.

---

## 4. Rancangan Sistem

### 4.2 Arsitektur Sistem
Client-Server 3-Tier dengan pola MVC + Service Layer pada backend REST API:
* **React SPA:** Antarmuka, validasi sisi klien, dan pemanggilan API via Axios.
* **Route & Middleware:** Pemetaan endpoint, autentikasi Sanctum, dan otorisasi RBAC.
* **Controller:** Menerima HTTP *request* dan mengembalikan *response* JSON terstandar.
* **Service:** Logika bisnis (cegah lamaran ganda, cek status aktif lowongan, salin file CV *snapshot* saat melamar).
* **Model (Eloquent):** Representasi entitas, relasi, dan *query scope*.
* **MySQL & File Storage:** Penyimpanan data relasional dan berkas fisik.

### 4.3 Rancangan ERD (`db_kerjakita`)
1. **`users`**
   * **Atribut:** `id` (PK), `email` (Unique), `password` (Hashed), `role` (enum: `'mahasiswa'`, `'penyedia'`), `timestamps`.
   * **Relasi:** 1:1 ke `mahasiswa_profiles` (`user_id`), 1:1 ke `penyedia_profiles` (`user_id`).
2. **`mahasiswa_profiles`**
   * **Atribut:** `id` (PK), `user_id` (FK -> `users.id`), `nama_lengkap`, `nim`, `universitas`, `fakultas`, `program_studi`, `jenjang`, `semester`, `domisili`, `keahlian`, `ketersediaan_waktu`, `bio`, `cv_file_path`, `transkrip_file_path`, `portfolio_url`, `timestamps`.
   * **Relasi:** 1:N ke `lamaran`, 1:N ke `lowongan_tersimpan`.
3. **`penyedia_profiles`**
   * **Atribut:** `id` (PK), `user_id` (FK -> `users.id`), `nama_organisasi`, `bidang_usaha`, `deskripsi`, `alamat`, `kota`, `website`, `kontak`, `logo_path`, `timestamps`.
   * **Relasi:** 1:N ke `lowongan`.
4. **`kategori_lowongan`** (Diisi melalui *Seeder*)
   * **Atribut:** `id` (PK), `nama_kategori`, `slug`, `timestamps`.
   * **Relasi:** 1:N ke `lowongan`.
5. **`lowongan`**
   * **Atribut:** `id` (PK), `penyedia_id` (FK -> `penyedia_profiles.id`), `kategori_id` (FK -> `kategori_lowongan.id`), `judul_posisi`, `jenis_pekerjaan` (enum: `'magang'`, `'freelance'`, `'paruh_waktu'`), `tipe_lokasi` (enum: `'onsite'`, `'remote'`, `'hybrid'`), `lokasi_kota`, `waktu_kerja`, `deskripsi`, `persyaratan`, `kompensasi`, `batas_waktu`, `status_aktif` (boolean, default `1`), `timestamps`.
   * **Relasi:** 1:N ke `lamaran`, 1:N ke `lowongan_tersimpan`.
6. **`lowongan_tersimpan`**
   * **Atribut:** `id` (PK), `mahasiswa_id` (FK -> `mahasiswa_profiles.id`), `lowongan_id` (FK -> `lowongan.id`), `catatan_pribadi` (nullable), `timestamps`.
   * **Constraint:** Unique (`mahasiswa_id`, `lowongan_id`).
7. **`lamaran`**
   * **Atribut:** `id` (PK), `lowongan_id` (FK -> `lowongan.id`), `mahasiswa_id` (FK -> `mahasiswa_profiles.id`), `surat_lamaran`, `cv_snapshot_path`, `status_lamaran` (default `'Terkirim'`), `timestamps`.
   * **Constraint:** Unique (`mahasiswa_id`, `lowongan_id`).

### 4.4 Aturan Model & Logika Bisnis Penting
* Saat mahasiswa melamar (`LamaranService`), file CV dari `mahasiswa_profiles.cv_file_path` harus disalin (*copy*) ke folder *snapshot* baru dan path-nya disimpan di `lamaran.cv_snapshot_path`, agar perubahan CV profil di kemudian hari tidak mengubah CV pada lamaran yang sudah terkirim.
* Model `Lowongan` memiliki helper/scope seperti `isAktif()`, `tutup()`, `scopeCari()`, dan `scopeFilter()`.

### 4.5 Deskripsi Halaman UI (Mockup)
1. **Halaman Login (Mahasiswa & Mitra):** Tab switcher peran di atas form. Input email dan kata sandi, serta tautan ke halaman registrasi.
2. **Halaman Registrasi Mahasiswa:** Input Nama Lengkap, Email, Universitas, Fakultas, Program Studi, Jenjang, Semester, NIM, Kata Sandi & Konfirmasi.
3. **Halaman Registrasi Mitra:** Input Nama Lengkap PIC, Jabatan, Email Kerja, Nama Organisasi, Sektor Industri, Alamat, Website, Kata Sandi & Konfirmasi.
4. **Halaman Utama Pencarian & Detail Lowongan:** Layout split-panel (panel kiri daftar kartu lowongan aktif + estimasi kompensasi; panel kanan detail lengkap posisi, persyaratan, profil perusahaan, tombol Lamar & Simpan) dilengkapi search bar dan multi-filter di bagian atas.
5. **Halaman Lowongan Tersimpan:** Daftar kartu lowongan yang disimpan mahasiswa dengan input/edit `catatan_pribadi` inline, tombol hapus simpanan, dan tombol lamar cepat.
6. **Halaman Riwayat Lamaran:** Ringkasan statistik total lamaran berdasarkan tipe pekerjaan di atas, serta tabel riwayat lamaran dengan opsi edit surat lamaran dan batalkan lamaran (jika lowongan masih aktif).
7. **Halaman Profil Mahasiswa & Kelola CV:** Panel kiri form edit data diri/akademik/keahlian/ketersediaan waktu; panel kanan manajemen unggah/pratinjau/hapus CV PDF, transkrip PDF, dan link portofolio.
8. **Halaman Dashboard Ringkasan Mitra:** Sidebar navigasi mitra, kartu metrik real-time (lowongan aktif, total pelamar), tabel pelamar terbaru beserta tombol unduh CV, toggle aktif/nonaktif lowongan, dan tombol pasang lowongan baru.
9. **Halaman Pusat Bantuan & Panduan (FAQ):** Accordion daftar pertanyaan umum dan panduan penggunaan untuk mahasiswa dan mitra.

---

## 5. Metode & Rencana Pengembangan (Scrum)

* **Sprint 0:** Setup repositori, proyek Laravel 11 + React Vite, konfigurasi Tailwind, migrasi database, dan seeding kategori.
* **Sprint 1:** Fondasi: Autentikasi (M1), Profil Mahasiswa (M2), Manajemen Lowongan (M4), dan Daftar Lowongan (M5).
* **Sprint 2:** Profil Penyedia (M3), Lowongan Tersimpan (F-15), dan Pengajuan Lamaran (M6).
* **Sprint 3:** Penyelesaian fitur Riwayat/Kelola Lamaran, Dashboard statistik, Halaman Bantuan (M7), dan integrasi penuh.
* **Sprint 4:** Pengujian end-to-end, UAT, perbaikan bug, dan deployment.
```[cite: 1]