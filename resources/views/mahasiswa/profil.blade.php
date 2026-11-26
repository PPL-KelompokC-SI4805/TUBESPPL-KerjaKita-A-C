<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Profil Mahasiswa & Kelola CV - KerjaKita</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <style>
        [x-cloak] { display: none !important; }
        .spinner {
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: #ffffff;
            width: 1rem;
            height: 1rem;
            animation: spin 0.8s linear infinite;
        }
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    </style>
</head>
<body class="bg-slate-50 min-h-screen flex flex-col font-sans text-slate-800">

    <!-- Inject Bearer Token & User Data dari Controller Blade -->
    <script>
        window.AUTH_TOKEN = "{{ $apiToken ?? '' }}";
        if (window.AUTH_TOKEN) {
            localStorage.setItem('auth_token', window.AUTH_TOKEN);
        }
    </script>

    <!-- Top Navigation Bar -->
    <nav class="bg-white border-b border-slate-200 sticky top-0 z-30 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <div class="flex items-center gap-8">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5 group">
                        <div class="w-9 h-9 rounded-xl bg-blue-600 text-white font-bold flex items-center justify-center shadow-sm group-hover:bg-blue-700 transition">
                            KK
                        </div>
                        <span class="font-extrabold text-xl tracking-tight text-slate-900">KerjaKita</span>
                    </a>

                    <div class="hidden md:flex items-center gap-1">
                        <a href="{{ route('mahasiswa.lowongan') }}" class="px-3 py-2 text-sm font-semibold text-slate-600 hover:text-blue-600 transition">
                            Cari Lowongan
                        </a>
                        <a href="{{ route('mahasiswa.lowongan_tersimpan') }}" class="px-3 py-2 text-sm font-semibold text-slate-600 hover:text-blue-600 transition">
                            Lowongan Tersimpan
                        </a>
                        <a href="{{ route('mahasiswa.profil') }}" class="px-3 py-2 text-sm font-bold text-blue-600 border-b-2 border-blue-600 transition">
                            Profil Mahasiswa
                        </a>
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    <div class="hidden sm:block text-right">
                        <p class="text-xs font-bold text-slate-800" id="navUserName">{{ Auth::user()->name }}</p>
                        <p class="text-[10px] font-semibold text-blue-600 uppercase tracking-wider">Mahasiswa</p>
                    </div>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="px-3.5 py-1.5 bg-slate-100 hover:bg-red-50 hover:text-red-600 text-slate-600 text-xs font-bold rounded-xl border border-slate-200 transition">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Container -->
    <main class="max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8 flex-1">

        <!-- Page Header & Notification Banner -->
        <div class="mb-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-2">
                <div>
                    <h1 class="text-2xl font-black text-slate-900 tracking-tight">Profil Mahasiswa & Kelola CV</h1>
                    <p class="text-xs sm:text-sm text-slate-500 mt-0.5">Kelola identitas diri, latar belakang pendidikan, keahlian, dan dokumen CV untuk keperluan melamar lowongan kerja.</p>
                </div>
                <div class="flex items-center gap-2">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-blue-50 text-blue-700 border border-blue-100">
                        <span class="w-2 h-2 rounded-full bg-blue-600 animate-pulse"></span>
                        Modul M2 (F-04, F-05, F-06)
                    </span>
                </div>
            </div>

            <!-- Global Alert Container -->
            <div id="alertContainer" class="hidden mt-4 p-4 rounded-2xl text-xs sm:text-sm font-medium border flex items-start justify-between shadow-sm transition">
                <div class="flex items-center gap-2" id="alertMessage"></div>
                <button onclick="closeAlert()" class="text-slate-400 hover:text-slate-600 text-base leading-none">&times;</button>
            </div>
        </div>

        <!-- Initial Skeleton Loading Indicator -->
        <div id="loadingSkeleton" class="grid grid-cols-1 lg:grid-cols-12 gap-8 animate-pulse">
            <div class="lg:col-span-8 bg-white p-8 rounded-3xl border border-slate-200 h-96"></div>
            <div class="lg:col-span-4 bg-white p-8 rounded-3xl border border-slate-200 h-96"></div>
        </div>

        <!-- Split Panel Main Content (Hidden until API loaded) -->
        <div id="profileContent" class="hidden grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

            <!-- ============================================================== -->
            <!-- PANEL KIRI: Data Diri & Akademik Mahasiswa (F-04 & F-05)     -->
            <!-- ============================================================== -->
            <div class="lg:col-span-8 bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm">
                
                <!-- Subheader Panel Kiri -->
                <div class="flex items-center justify-between pb-5 border-b border-slate-100 mb-6">
                    <div>
                        <h2 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            Data Pribadi & Akademik
                        </h2>
                        <p class="text-xs text-slate-500 mt-0.5">Perbarui informasi profil Anda secara berkala agar penyedia lowongan dapat mengenali kualifikasi Anda.</p>
                    </div>
                    <span class="text-[11px] font-semibold px-2.5 py-1 rounded-lg bg-slate-100 text-slate-600">F-04 & F-05</span>
                </div>

                <form id="profileForm" onsubmit="handleUpdateProfile(event)" class="space-y-6">
                    
                    <!-- Bagian 1: Identitas Diri -->
                    <div>
                        <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-3">1. Identitas Mahasiswa</h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <!-- Nama Lengkap -->
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">
                                    Nama Lengkap <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="nama_lengkap" name="nama_lengkap" required
                                    class="w-full px-3.5 py-2.5 text-xs sm:text-sm border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition"
                                    placeholder="Contoh: Renjiro Athaya">
                                <p class="text-[11px] text-red-500 mt-1 hidden" id="err_nama_lengkap"></p>
                            </div>

                            <!-- Email (Read Only) -->
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1 flex items-center justify-between">
                                    <span>Alamat Email</span>
                                    <span class="text-[10px] text-slate-400 font-normal">Dari Akun (Tidak dapat diubah)</span>
                                </label>
                                <input type="email" id="user_email" readonly
                                    class="w-full px-3.5 py-2.5 text-xs sm:text-sm bg-slate-50 border border-slate-200 rounded-xl text-slate-500 cursor-not-allowed outline-none">
                            </div>

                            <!-- Domisili -->
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Domisili / Kota</label>
                                <input type="text" id="domisili" name="domisili"
                                    class="w-full px-3.5 py-2.5 text-xs sm:text-sm border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition"
                                    placeholder="Contoh: Bandung, Jawa Barat">
                                <p class="text-[11px] text-red-500 mt-1 hidden" id="err_domisili"></p>
                            </div>

                            <!-- Ketersediaan Waktu -->
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Ketersediaan Waktu Kerja</label>
                                <input type="text" id="ketersediaan_waktu" name="ketersediaan_waktu"
                                    class="w-full px-3.5 py-2.5 text-xs sm:text-sm border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition"
                                    placeholder="Contoh: 20 jam/minggu, Senin-Kamis sore">
                                <p class="text-[11px] text-red-500 mt-1 hidden" id="err_ketersediaan_waktu"></p>
                            </div>
                        </div>
                    </div>

                    <!-- Bagian 2: Informasi Akademik -->
                    <div class="pt-2">
                        <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-3">2. Latar Belakang Akademik</h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <!-- NIM -->
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Nomor Induk Mahasiswa (NIM)</label>
                                <input type="text" id="nim" name="nim"
                                    class="w-full px-3.5 py-2.5 text-xs sm:text-sm border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition"
                                    placeholder="Contoh: 102022400199">
                                <p class="text-[11px] text-red-500 mt-1 hidden" id="err_nim"></p>
                            </div>

                            <!-- Universitas -->
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Perguruan Tinggi / Universitas</label>
                                <input type="text" id="universitas" name="universitas"
                                    class="w-full px-3.5 py-2.5 text-xs sm:text-sm border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition"
                                    placeholder="Contoh: Telkom University">
                                <p class="text-[11px] text-red-500 mt-1 hidden" id="err_universitas"></p>
                            </div>

                            <!-- Fakultas -->
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Fakultas</label>
                                <input type="text" id="fakultas" name="fakultas"
                                    class="w-full px-3.5 py-2.5 text-xs sm:text-sm border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition"
                                    placeholder="Contoh: Fakultas Informatika">
                                <p class="text-[11px] text-red-500 mt-1 hidden" id="err_fakultas"></p>
                            </div>

                            <!-- Program Studi -->
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Program Studi</label>
                                <input type="text" id="program_studi" name="program_studi"
                                    class="w-full px-3.5 py-2.5 text-xs sm:text-sm border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition"
                                    placeholder="Contoh: S1 Informatika">
                                <p class="text-[11px] text-red-500 mt-1 hidden" id="err_program_studi"></p>
                            </div>

                            <!-- Jenjang Pendidikan -->
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Jenjang</label>
                                <select id="jenjang" name="jenjang"
                                    class="w-full px-3.5 py-2.5 text-xs sm:text-sm border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition bg-white">
                                    <option value="">-- Pilih Jenjang --</option>
                                    <option value="D3">Diploma 3 (D3)</option>
                                    <option value="D4">Diploma 4 (D4)</option>
                                    <option value="S1">Sarjana (S1)</option>
                                    <option value="S2">Magister (S2)</option>
                                    <option value="S3">Doktor (S3)</option>
                                </select>
                                <p class="text-[11px] text-red-500 mt-1 hidden" id="err_jenjang"></p>
                            </div>

                            <!-- Semester -->
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Semester Saat Ini</label>
                                <input type="number" id="semester" name="semester" min="1" max="14"
                                    class="w-full px-3.5 py-2.5 text-xs sm:text-sm border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition"
                                    placeholder="Contoh: 5">
                                <p class="text-[11px] text-red-500 mt-1 hidden" id="err_semester"></p>
                            </div>
                        </div>
                    </div>

                    <!-- Bagian 3: Kompetensi & Bio -->
                    <div class="pt-2">
                        <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-3">3. Keahlian & Deskripsi Diri</h3>
                        
                        <!-- Input Keahlian / Skill Tags -->
                        <div class="mb-4">
                            <label class="block text-xs font-bold text-slate-700 mb-1">
                                Keahlian / Skills
                                <span class="text-[10px] text-slate-400 font-normal ml-1">(Ketik dan tekan Enter atau pisahkan dengan koma)</span>
                            </label>
                            
                            <!-- Wadah Badge Tags -->
                            <div id="skillsBadgeContainer" class="flex flex-wrap gap-1.5 mb-2 min-h-[28px]"></div>

                            <div class="flex gap-2">
                                <input type="text" id="skillInput"
                                    class="flex-1 px-3.5 py-2.5 text-xs sm:text-sm border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition"
                                    placeholder="Contoh: Laravel, React, UI/UX Design, Python"
                                    onkeydown="handleSkillKeydown(event)">
                                <button type="button" onclick="addSkillFromInput()"
                                    class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl transition">
                                    Tambah
                                </button>
                            </div>
                            <input type="hidden" id="keahlian" name="keahlian">
                            <p class="text-[11px] text-red-500 mt-1 hidden" id="err_keahlian"></p>
                        </div>

                        <!-- Bio / Ringkasan Diri -->
                        <div>
                            <div class="flex justify-between items-center mb-1">
                                <label class="block text-xs font-bold text-slate-700">Bio / Ringkasan Profil</label>
                                <span class="text-[10px] text-slate-400" id="bioCounter">0 / 2000 karakter</span>
                            </div>
                            <textarea id="bio" name="bio" rows="3"
                                class="w-full px-3.5 py-2.5 text-xs sm:text-sm border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition"
                                placeholder="Tuliskan deskripsi singkat mengenai minat karir, pengalaman proyek, dan motivasi kerja Anda..."
                                oninput="updateBioCounter()"></textarea>
                            <p class="text-[11px] text-red-500 mt-1 hidden" id="err_bio"></p>
                        </div>
                    </div>

                    <!-- Tombol Aksi Simpan -->
                    <div class="pt-4 flex items-center justify-end gap-3 border-t border-slate-100">
                        <button type="submit" id="btnSaveProfile"
                            class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs sm:text-sm rounded-xl transition shadow-sm hover:shadow flex items-center gap-2">
                            <span id="saveSpinner" class="spinner hidden"></span>
                            <span id="saveBtnText">Simpan Perubahan Profil</span>
                        </button>
                    </div>
                </form>
            </div>


            <!-- ============================================================== -->
            <!-- PANEL KANAN: Kelola Berkas CV PDF (F-06)                      -->
            <!-- ============================================================== -->
            <div class="lg:col-span-4 space-y-6">

                <!-- Kartu Utama Kelola CV -->
                <div class="bg-white rounded-3xl p-6 sm:p-7 border border-slate-200 shadow-sm">
                    <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-5">
                        <div>
                            <h2 class="text-base font-bold text-slate-900 flex items-center gap-2">
                                <svg class="w-5 h-5 text-red-600" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M14 2H6c-1.1 0-1.99.9-1.99 2L4 20c0 1.1.89 2 1.99 2H18c1.1 0 2-.9 2-2V8l-6-6zm2 16H8v-2h8v2zm0-4H8v-2h8v2zm-3-5V3.5L18.5 9H13z"/>
                                </svg>
                                Kelola CV (PDF)
                            </h2>
                            <p class="text-[11px] text-slate-500 mt-0.5">Berkas utama untuk melamar posisi magang & kerja.</p>
                        </div>
                        <span class="text-[11px] font-semibold px-2 py-0.5 rounded bg-slate-100 text-slate-600">F-06</span>
                    </div>

                    <!-- Input File Tersembunyi -->
                    <input type="file" id="cvFileInput" accept="application/pdf" class="hidden" onchange="handleCvFileSelected(event)">

                    <!-- Status 1: JIKA CV SUDAH TERSEDIA -->
                    <div id="cvActiveState" class="hidden space-y-4">
                        <div class="p-4 rounded-2xl bg-red-50 border border-red-100 flex items-start gap-3">
                            <div class="w-10 h-10 rounded-xl bg-red-600 text-white flex items-center justify-center font-bold text-xs flex-shrink-0 shadow-sm">
                                PDF
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-bold text-slate-800 truncate" id="cvFileNameDisplay">curriculum_vitae.pdf</p>
                                <p class="text-[10px] text-emerald-600 font-semibold mt-0.5 flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    CV Aktif & Siap Digunakan
                                </p>
                            </div>
                        </div>

                        <!-- Tombol Aksi CV -->
                        <div class="grid grid-cols-1 gap-2 pt-1">
                            <!-- Lihat CV -->
                            <a id="btnViewCv" href="#" target="_blank"
                                class="w-full py-2.5 px-4 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl transition text-center flex items-center justify-center gap-2 border border-slate-200">
                                <svg class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                </svg>
                                Lihat CV (PDF)
                            </a>

                            <!-- Ganti CV -->
                            <button type="button" onclick="triggerCvUpload()"
                                class="w-full py-2.5 px-4 bg-blue-50 hover:bg-blue-100 text-blue-700 font-bold text-xs rounded-xl transition text-center flex items-center justify-center gap-2 border border-blue-200">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                </svg>
                                Ganti Berkas CV
                            </button>

                            <!-- Hapus CV -->
                            <button type="button" onclick="openDeleteCvModal()"
                                class="w-full py-2 px-4 text-red-600 hover:bg-red-50 font-bold text-xs rounded-xl transition text-center flex items-center justify-center gap-1.5">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                Hapus CV
                            </button>
                        </div>
                    </div>

                    <!-- Status 2: JIKA CV BELUM DIUNGGAH (Dropzone) -->
                    <div id="cvEmptyState" class="space-y-4">
                        <div onclick="triggerCvUpload()"
                            class="border-2 border-dashed border-slate-200 hover:border-blue-400 bg-slate-50 hover:bg-blue-50/50 rounded-2xl p-6 text-center cursor-pointer transition group">
                            <div class="w-12 h-12 rounded-2xl bg-white border border-slate-200 group-hover:border-blue-300 flex items-center justify-center mx-auto mb-3 text-red-500 shadow-sm transition">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M14 2H6c-1.1 0-1.99.9-1.99 2L4 20c0 1.1.89 2 1.99 2H18c1.1 0 2-.9 2-2V8l-6-6zm2 16H8v-2h8v2zm0-4H8v-2h8v2zm-3-5V3.5L18.5 9H13z"/>
                                </svg>
                            </div>
                            <p class="text-xs font-bold text-slate-800">Unggah Berkas CV Anda</p>
                            <p class="text-[11px] text-slate-500 mt-1">Klik di sini untuk memilih file PDF dari perangkat Anda.</p>
                            <span class="inline-block mt-3 px-3 py-1 bg-white border border-slate-200 rounded-lg text-[10px] font-bold text-blue-600 shadow-2xs">
                                Pilih File PDF
                            </span>
                        </div>
                    </div>

                    <!-- Status Uploading Progress Spinner -->
                    <div id="cvUploadingState" class="hidden p-6 text-center space-y-3">
                        <div class="inline-block w-8 h-8 border-3 border-blue-600 border-t-transparent rounded-full animate-spin"></div>
                        <p class="text-xs font-bold text-slate-800" id="cvUploadStatusText">Mengunggah file CV...</p>
                        <p class="text-[10px] text-slate-500">Mohon tunggu sebentar, file sedang diverifikasi oleh sistem.</p>
                    </div>

                    <!-- Validasi Info Bawah -->
                    <div class="mt-5 pt-4 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-500">
                        <span>Format: <strong>PDF</strong></span>
                        <span>Maksimal: <strong>2 MB</strong></span>
                    </div>
                </div>

                <!-- Card Panduan CV -->
                <div class="bg-blue-50/70 border border-blue-100 rounded-3xl p-5 text-xs text-blue-900">
                    <p class="font-bold flex items-center gap-1.5 mb-1.5 text-blue-800">
                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Tips CV untuk Mahasiswa
                    </p>
                    <ul class="list-disc list-inside space-y-1 text-[11px] text-blue-800/90">
                        <li>Cantumkan pengalaman kepanitiaan, organisasi, atau proyek perkuliahan yang relevan.</li>
                        <li>Pastikan nomor kontak dan alamat email aktif.</li>
                        <li>Saat melamar, CV ini akan disalin sebagai <em>snapshot</em> arsip.</li>
                    </ul>
                </div>
            </div>
        </div>
    </main>

    <!-- ============================================================== -->
    <!-- MODAL KONFIRMASI HAPUS CV                                      -->
    <!-- ============================================================== -->
    <div id="deleteCvModal" class="hidden fixed inset-0 z-50 bg-slate-900/40 backdrop-blur-xs flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl p-6 max-w-sm w-full shadow-2xl border border-slate-100 transform transition-all">
            <div class="w-12 h-12 rounded-2xl bg-red-100 text-red-600 flex items-center justify-center mx-auto mb-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
            </div>
            <h3 class="text-base font-bold text-slate-900 text-center mb-1">Hapus Berkas CV?</h3>
            <p class="text-xs text-slate-500 text-center mb-6">
                Apakah Anda yakin ingin menghapus file CV ini? Berkas fisik akan dihapus dari server dan Anda harus mengunggahnya kembali saat melamar posisi kerja.
            </p>
            <div class="flex items-center gap-2">
                <button type="button" onclick="closeDeleteCvModal()"
                    class="w-1/2 py-2.5 px-4 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl transition">
                    Batal
                </button>
                <button type="button" id="btnConfirmDeleteCv" onclick="handleDeleteCvConfirm()"
                    class="w-1/2 py-2.5 px-4 bg-red-600 hover:bg-red-700 text-white font-bold text-xs rounded-xl transition flex items-center justify-center gap-1.5 shadow-sm">
                    <span id="deleteCvSpinner" class="spinner hidden"></span>
                    <span id="deleteCvBtnText">Ya, Hapus</span>
                </button>
            </div>
        </div>
    </div>


    <!-- ============================================================== -->
    <!-- LOGIKA JAVASCRIPT & INTEGRASI API AXIOS (F-04, F-05, F-06)     -->
    <!-- ============================================================== -->
    <script>
        // Setup API Endpoint & Bearer Token
        const API_PROFILE = '/api/mahasiswa/profile';
        const API_CV = '/api/mahasiswa/profile/cv';
        
        let userSkills = [];
        let currentProfileData = null;

        // Inisialisasi Axios Client dengan Auth Header
        function getApiClient() {
            const token = window.AUTH_TOKEN || localStorage.getItem('auth_token') || '';
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            return axios.create({
                headers: {
                    'Authorization': token ? `Bearer ${token}` : '',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken || '',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
        }

        // ==============================================================
        // 1. F-04: Mengambil Data Profil saat Halaman Dimuat
        // ==============================================================
        async function fetchProfileData() {
            try {
                const api = getApiClient();
                const response = await api.get(API_PROFILE);

                if (response.data && response.data.success) {
                    currentProfileData = response.data.data;
                    populateProfileForm(currentProfileData);
                    renderCvSection(currentProfileData);
                } else {
                    showAlert('Gagal memuat profil mahasiswa.', 'error');
                }
            } catch (error) {
                console.error('Fetch profile error:', error);
                const errMsg = error.response?.data?.message || 'Terjadi kesalahan saat memuat data profil.';
                showAlert(errMsg, 'error');
            } finally {
                document.getElementById('loadingSkeleton').classList.add('hidden');
                document.getElementById('profileContent').classList.remove('hidden');
            }
        }

        // Isi form dengan data profil
        function populateProfileForm(data) {
            document.getElementById('user_email').value = data.email || '';
            document.getElementById('nama_lengkap').value = data.nama_lengkap || '';
            document.getElementById('nim').value = data.nim || '';
            document.getElementById('universitas').value = data.universitas || '';
            document.getElementById('fakultas').value = data.fakultas || '';
            document.getElementById('program_studi').value = data.program_studi || '';
            document.getElementById('jenjang').value = data.jenjang || '';
            document.getElementById('semester').value = data.semester || '';
            document.getElementById('domisili').value = data.domisili || '';
            document.getElementById('ketersediaan_waktu').value = data.ketersediaan_waktu || '';
            document.getElementById('bio').value = data.bio || '';

            if (data.nama_lengkap) {
                document.getElementById('navUserName').innerText = data.nama_lengkap;
            }

            // Inisialisasi Skill Tags
            if (data.keahlian) {
                userSkills = data.keahlian.split(',')
                    .map(s => s.trim())
                    .filter(s => s.length > 0);
            } else {
                userSkills = [];
            }
            renderSkillsBadges();
            updateBioCounter();
        }

        // Tampilkan State CV Aktif atau Kosong
        function renderCvSection(data) {
            const activeState = document.getElementById('cvActiveState');
            const emptyState = document.getElementById('cvEmptyState');
            const uploadingState = document.getElementById('cvUploadingState');
            uploadingState.classList.add('hidden');

            if (data.cv_file_path) {
                emptyState.classList.add('hidden');
                activeState.classList.remove('hidden');

                // Set tautan tombol lihat CV
                const btnViewCv = document.getElementById('btnViewCv');
                const cvUrl = data.cv_url || `/storage/${data.cv_file_path}`;
                btnViewCv.href = cvUrl;

                // Tampilkan nama file
                const fileName = data.cv_file_path.split('/').pop();
                document.getElementById('cvFileNameDisplay').innerText = fileName;
            } else {
                activeState.classList.add('hidden');
                emptyState.classList.remove('hidden');
            }
        }

        // ==============================================================
        // 2. F-05: Mengubah Data Profil (PUT /api/mahasiswa/profile)
        // ==============================================================
        async function handleUpdateProfile(event) {
            event.preventDefault();
            clearValidationErrors();

            const btn = document.getElementById('btnSaveProfile');
            const spinner = document.getElementById('saveSpinner');
            const btnText = document.getElementById('saveBtnText');

            // Format keahlian dari tag badges
            const keahlianStr = userSkills.join(', ');

            const payload = {
                nama_lengkap: document.getElementById('nama_lengkap').value.trim(),
                nim: document.getElementById('nim').value.trim() || null,
                universitas: document.getElementById('universitas').value.trim() || null,
                fakultas: document.getElementById('fakultas').value.trim() || null,
                program_studi: document.getElementById('program_studi').value.trim() || null,
                jenjang: document.getElementById('jenjang').value || null,
                semester: document.getElementById('semester').value ? parseInt(document.getElementById('semester').value) : null,
                domisili: document.getElementById('domisili').value.trim() || null,
                ketersediaan_waktu: document.getElementById('ketersediaan_waktu').value.trim() || null,
                keahlian: keahlianStr || null,
                bio: document.getElementById('bio').value.trim() || null
            };

            // Loading state
            btn.disabled = true;
            spinner.classList.remove('hidden');
            btnText.innerText = 'Menyimpan...';

            try {
                const api = getApiClient();
                const response = await api.put(API_PROFILE, payload);

                if (response.data && response.data.success) {
                    showAlert('Profil mahasiswa berhasil diperbarui!', 'success');
                    currentProfileData = response.data.data;
                    if (payload.nama_lengkap) {
                        document.getElementById('navUserName').innerText = payload.nama_lengkap;
                    }
                } else {
                    showAlert('Gagal memperbarui profil.', 'error');
                }
            } catch (error) {
                console.error('Update profile error:', error);
                if (error.response && error.response.status === 422) {
                    const errors = error.response.data.errors;
                    displayValidationErrors(errors);
                    showAlert('Mohon periksa kembali kolom input formulir Anda.', 'error');
                } else {
                    const msg = error.response?.data?.message || 'Terjadi kesalahan saat menyimpan profil.';
                    showAlert(msg, 'error');
                }
            } finally {
                btn.disabled = false;
                spinner.classList.add('hidden');
                btnText.innerText = 'Simpan Perubahan Profil';
            }
        }

        // ==============================================================
        // 3. F-06: Mengunggah Berkas CV (POST /api/mahasiswa/profile/cv)
        // ==============================================================
        function triggerCvUpload() {
            document.getElementById('cvFileInput').click();
        }

        async function handleCvFileSelected(event) {
            const fileInput = event.target;
            if (!fileInput.files || fileInput.files.length === 0) return;

            const file = fileInput.files[0];

            // Validasi Sisi Klien 1: Format File Wajib PDF
            if (file.type !== 'application/pdf' && !file.name.toLowerCase().endsWith('.pdf')) {
                showAlert('Format file tidak valid. CV wajib berformat PDF (.pdf).', 'error');
                fileInput.value = '';
                return;
            }

            // Validasi Sisi Klien 2: Ukuran Maksimal 2 MB (2048 KB)
            const maxBytes = 2 * 1024 * 1024;
            if (file.size > maxBytes) {
                showAlert('Ukuran file melebihi batas 2 MB. Silakan unggah file PDF yang lebih kecil.', 'error');
                fileInput.value = '';
                return;
            }

            // Siapkan FormData
            const formData = new FormData();
            formData.append('cv', file);

            // Tampilkan status uploading
            document.getElementById('cvEmptyState').classList.add('hidden');
            document.getElementById('cvActiveState').classList.add('hidden');
            document.getElementById('cvUploadingState').classList.remove('hidden');

            try {
                const api = getApiClient();
                const response = await api.post(API_CV, formData, {
                    headers: { 'Content-Type': 'multipart/form-data' }
                });

                if (response.data && response.data.success) {
                    showAlert('File CV berhasil diunggah!', 'success');
                    if (currentProfileData) {
                        currentProfileData.cv_file_path = response.data.data.cv_file_path;
                        currentProfileData.cv_url = response.data.data.cv_url;
                    }
                    renderCvSection(response.data.data);
                } else {
                    showAlert('Gagal mengunggah file CV.', 'error');
                    renderCvSection(currentProfileData || {});
                }
            } catch (error) {
                console.error('Upload CV error:', error);
                const errMsg = error.response?.data?.message || 'Terjadi kesalahan saat mengunggah CV.';
                showAlert(errMsg, 'error');
                renderCvSection(currentProfileData || {});
            } finally {
                fileInput.value = '';
            }
        }

        // ==============================================================
        // 4. F-06: Menghapus Berkas CV (DELETE /api/mahasiswa/profile/cv)
        // ==============================================================
        function openDeleteCvModal() {
            document.getElementById('deleteCvModal').classList.remove('hidden');
        }

        function closeDeleteCvModal() {
            document.getElementById('deleteCvModal').classList.add('hidden');
        }

        async function handleDeleteCvConfirm() {
            const btn = document.getElementById('btnConfirmDeleteCv');
            const spinner = document.getElementById('deleteCvSpinner');
            const btnText = document.getElementById('deleteCvBtnText');

            btn.disabled = true;
            spinner.classList.remove('hidden');
            btnText.innerText = 'Menghapus...';

            try {
                const api = getApiClient();
                const response = await api.delete(API_CV);

                if (response.data && response.data.success) {
                    showAlert('File CV berhasil dihapus dari sistem.', 'success');
                    if (currentProfileData) {
                        currentProfileData.cv_file_path = null;
                        currentProfileData.cv_url = null;
                    }
                    renderCvSection({});
                    closeDeleteCvModal();
                } else {
                    showAlert('Gagal menghapus file CV.', 'error');
                }
            } catch (error) {
                console.error('Delete CV error:', error);
                const errMsg = error.response?.data?.message || 'Terjadi kesalahan saat menghapus CV.';
                showAlert(errMsg, 'error');
            } finally {
                btn.disabled = false;
                spinner.classList.add('hidden');
                btnText.innerText = 'Ya, Hapus';
            }
        }

        // ==============================================================
        // Helper: Skill Tags & Bio Counter
        // ==============================================================
        function renderSkillsBadges() {
            const container = document.getElementById('skillsBadgeContainer');
            container.innerHTML = '';

            userSkills.forEach((skill, index) => {
                const badge = document.createElement('span');
                badge.className = 'inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-200';
                badge.innerHTML = `
                    <span>${skill}</span>
                    <button type="button" onclick="removeSkill(${index})" class="text-blue-400 hover:text-blue-800 font-bold leading-none text-sm">&times;</button>
                `;
                container.appendChild(badge);
            });
        }

        function addSkillFromInput() {
            const input = document.getElementById('skillInput');
            const rawVal = input.value.trim();
            if (!rawVal) return;

            const splitSkills = rawVal.split(',').map(s => s.trim()).filter(s => s.length > 0);
            splitSkills.forEach(s => {
                if (!userSkills.includes(s)) {
                    userSkills.push(s);
                }
            });

            input.value = '';
            renderSkillsBadges();
        }

        function handleSkillKeydown(event) {
            if (event.key === 'Enter' || event.key === ',') {
                event.preventDefault();
                addSkillFromInput();
            }
        }

        function removeSkill(index) {
            userSkills.splice(index, 1);
            renderSkillsBadges();
        }

        function updateBioCounter() {
            const bioText = document.getElementById('bio').value || '';
            const counter = document.getElementById('bioCounter');
            counter.innerText = `${bioText.length} / 2000 karakter`;
            if (bioText.length > 2000) {
                counter.classList.add('text-red-500');
            } else {
                counter.classList.remove('text-red-500');
            }
        }

        // ==============================================================
        // Helper: Alerts & Validasi Error Messages
        // ==============================================================
        function showAlert(message, type = 'success') {
            const container = document.getElementById('alertContainer');
            const msgEl = document.getElementById('alertMessage');

            container.classList.remove('hidden', 'bg-green-50', 'border-green-200', 'text-green-800', 'bg-red-50', 'border-red-200', 'text-red-800');

            if (type === 'success') {
                container.classList.add('bg-green-50', 'border-green-200', 'text-green-800');
                msgEl.innerHTML = `
                    <svg class="w-5 h-5 text-green-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <span>${message}</span>
                `;
            } else {
                container.classList.add('bg-red-50', 'border-red-200', 'text-red-800');
                msgEl.innerHTML = `
                    <svg class="w-5 h-5 text-red-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>${message}</span>
                `;
            }

            container.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        }

        function closeAlert() {
            document.getElementById('alertContainer').classList.add('hidden');
        }

        function clearValidationErrors() {
            document.querySelectorAll('[id^="err_"]').forEach(el => {
                el.innerText = '';
                el.classList.add('hidden');
            });
        }

        function displayValidationErrors(errors) {
            for (const [key, messages] of Object.entries(errors)) {
                const el = document.getElementById(`err_${key}`);
                if (el) {
                    el.innerText = messages[0];
                    el.classList.remove('hidden');
                }
            }
        }

        // Jalankan fetch saat DOM siap
        document.addEventListener('DOMContentLoaded', fetchProfileData);
    </script>
</body>
</html>
