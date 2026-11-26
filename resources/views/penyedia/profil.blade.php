<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Profil Organisasi & Kelola Logo - KerjaKita</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <style>
        .spinner {
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: #ffffff;
            width: 1rem;
            height: 1rem;
            animation: spin 0.8s linear infinite;
        }
        @keyframes spin { to { transform: rotate(360deg); } }
    </style>
</head>
<body class="bg-slate-50 min-h-screen flex flex-col font-sans text-slate-800">

    <!-- Inject Bearer Token dari Controller Blade -->
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
                <div class="flex items-center gap-4">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5 group">
                        <div class="w-9 h-9 rounded-xl bg-blue-600 text-white font-bold flex items-center justify-center shadow-sm group-hover:bg-blue-700 transition">KK</div>
                        <span class="font-extrabold text-xl tracking-tight text-slate-900">KerjaKita</span>
                    </a>
                    <span class="text-slate-300">|</span>
                    <a href="{{ route('dashboard') }}" class="text-xs font-semibold text-slate-500 hover:text-blue-600 transition flex items-center gap-1">
                        &larr; Kembali ke Dashboard
                    </a>
                </div>

                <div class="flex items-center gap-4">
                    <div class="hidden sm:block text-right">
                        <p class="text-xs font-bold text-slate-800" id="navUserName">{{ Auth::user()->name }}</p>
                        <p class="text-[10px] font-semibold text-blue-600 uppercase tracking-wider">Penyedia</p>
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

        <!-- Page Header & Alert -->
        <div class="mb-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-2">
                <div>
                    <h1 class="text-2xl font-black text-slate-900 tracking-tight">Profil Organisasi & Kelola Logo</h1>
                    <p class="text-xs sm:text-sm text-slate-500 mt-0.5">Kelola informasi organisasi dan logo agar mahasiswa dapat mengenali Anda saat melihat lowongan.</p>
                </div>
                <div class="flex items-center gap-2">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-blue-50 text-blue-700 border border-blue-100">
                        <span class="w-2 h-2 rounded-full bg-blue-600 animate-pulse"></span>
                        Modul M3 (F-07, F-08, F-09)
                    </span>
                </div>
            </div>

            <div id="alertContainer" class="hidden mt-4 p-4 rounded-2xl text-xs sm:text-sm font-medium border flex items-start justify-between shadow-sm transition">
                <div class="flex items-center gap-2" id="alertMessage"></div>
                <button onclick="closeAlert()" class="text-slate-400 hover:text-slate-600 text-base leading-none">&times;</button>
            </div>
        </div>

        <!-- Skeleton Loading -->
        <div id="loadingSkeleton" class="grid grid-cols-1 lg:grid-cols-12 gap-8 animate-pulse">
            <div class="lg:col-span-8 bg-white p-8 rounded-3xl border border-slate-200 h-96"></div>
            <div class="lg:col-span-4 bg-white p-8 rounded-3xl border border-slate-200 h-96"></div>
        </div>

        <!-- Konten Utama -->
        <div id="profileContent" class="hidden grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

            <!-- PANEL KIRI: Informasi Organisasi (F-07 & F-08) -->
            <div class="lg:col-span-8 bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm">

                <div class="flex items-center justify-between pb-5 border-b border-slate-100 mb-6">
                    <div>
                        <h2 class="text-lg font-bold text-slate-900">Informasi Organisasi</h2>
                        <p class="text-xs text-slate-500 mt-0.5">Perbarui data organisasi Anda secara berkala.</p>
                    </div>
                    <span class="text-[11px] font-semibold px-2.5 py-1 rounded-lg bg-slate-100 text-slate-600">F-07 & F-08</span>
                </div>

                <form id="profileForm" onsubmit="handleUpdateProfile(event)" class="space-y-6">

                    <!-- Bagian 1: Identitas -->
                    <div>
                        <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-3">1. Identitas Organisasi</h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Nama Organisasi <span class="text-red-500">*</span></label>
                                <input type="text" id="nama_organisasi" name="nama_organisasi" required
                                    class="w-full px-3.5 py-2.5 text-xs sm:text-sm border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition"
                                    placeholder="Contoh: PT Teknologi Nusantara">
                                <p class="text-[11px] text-red-500 mt-1 hidden" id="err_nama_organisasi"></p>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1 flex items-center justify-between">
                                    <span>Alamat Email</span>
                                    <span class="text-[10px] text-slate-400 font-normal">Dari Akun (Tidak dapat diubah)</span>
                                </label>
                                <input type="email" id="user_email" readonly
                                    class="w-full px-3.5 py-2.5 text-xs sm:text-sm bg-slate-50 border border-slate-200 rounded-xl text-slate-500 cursor-not-allowed outline-none">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Bidang Usaha</label>
                                <input type="text" id="bidang_usaha" name="bidang_usaha"
                                    class="w-full px-3.5 py-2.5 text-xs sm:text-sm border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition"
                                    placeholder="Contoh: Teknologi Informasi">
                                <p class="text-[11px] text-red-500 mt-1 hidden" id="err_bidang_usaha"></p>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Kontak (Telepon / WhatsApp)</label>
                                <input type="text" id="kontak" name="kontak"
                                    class="w-full px-3.5 py-2.5 text-xs sm:text-sm border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition"
                                    placeholder="Contoh: 081234567890">
                                <p class="text-[11px] text-red-500 mt-1 hidden" id="err_kontak"></p>
                            </div>

                            <div class="sm:col-span-2">
                                <label class="block text-xs font-bold text-slate-700 mb-1">Website</label>
                                <input type="url" id="website" name="website"
                                    class="w-full px-3.5 py-2.5 text-xs sm:text-sm border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition"
                                    placeholder="https://perusahaan.com">
                                <p class="text-[11px] text-red-500 mt-1 hidden" id="err_website"></p>
                            </div>
                        </div>
                    </div>

                    <!-- Bagian 2: Lokasi -->
                    <div class="pt-2">
                        <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-3">2. Lokasi</h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Kota</label>
                                <input type="text" id="kota" name="kota"
                                    class="w-full px-3.5 py-2.5 text-xs sm:text-sm border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition"
                                    placeholder="Contoh: Bandung">
                                <p class="text-[11px] text-red-500 mt-1 hidden" id="err_kota"></p>
                            </div>

                            <div class="sm:col-span-2">
                                <label class="block text-xs font-bold text-slate-700 mb-1">Alamat Lengkap</label>
                                <textarea id="alamat" name="alamat" rows="2"
                                    class="w-full px-3.5 py-2.5 text-xs sm:text-sm border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition"
                                    placeholder="Jalan, nomor, kecamatan, kode pos..."></textarea>
                                <p class="text-[11px] text-red-500 mt-1 hidden" id="err_alamat"></p>
                            </div>
                        </div>
                    </div>

                    <!-- Bagian 3: Deskripsi -->
                    <div class="pt-2">
                        <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-3">3. Deskripsi Organisasi</h3>
                        <div class="flex justify-between items-center mb-1">
                            <label class="block text-xs font-bold text-slate-700">Tentang Organisasi</label>
                            <span class="text-[10px] text-slate-400" id="deskripsiCounter">0 / 2000 karakter</span>
                        </div>
                        <textarea id="deskripsi" name="deskripsi" rows="4"
                            class="w-full px-3.5 py-2.5 text-xs sm:text-sm border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition"
                            placeholder="Ceritakan profil singkat, visi, dan budaya kerja organisasi Anda..."
                            oninput="updateDeskripsiCounter()"></textarea>
                        <p class="text-[11px] text-red-500 mt-1 hidden" id="err_deskripsi"></p>
                    </div>

                    <div class="pt-4 flex items-center justify-end gap-3 border-t border-slate-100">
                        <button type="submit" id="btnSaveProfile"
                            class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs sm:text-sm rounded-xl transition shadow-sm hover:shadow flex items-center gap-2">
                            <span id="saveSpinner" class="spinner hidden"></span>
                            <span id="saveBtnText">Simpan Perubahan Profil</span>
                        </button>
                    </div>
                </form>
            </div>

            <!-- PANEL KANAN: Kelola Logo (F-09) -->
            <div class="lg:col-span-4 space-y-6">

                <div class="bg-white rounded-3xl p-6 sm:p-7 border border-slate-200 shadow-sm">
                    <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-5">
                        <div>
                            <h2 class="text-base font-bold text-slate-900">Kelola Logo</h2>
                            <p class="text-[11px] text-slate-500 mt-0.5">Tampil pada profil dan lowongan Anda.</p>
                        </div>
                        <span class="text-[11px] font-semibold px-2 py-0.5 rounded bg-slate-100 text-slate-600">F-09</span>
                    </div>

                    <!-- Input File Tersembunyi -->
                    <input type="file" id="logoFileInput" accept="image/png,image/jpeg" class="hidden" onchange="handleLogoFileSelected(event)">

                    <!-- Status 1: Logo Tersedia -->
                    <div id="logoActiveState" class="hidden space-y-4">
                        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 flex items-center justify-center">
                            <img id="logoPreview" src="" alt="Logo organisasi" class="max-h-40 max-w-full rounded-xl object-contain">
                        </div>

                        <div class="grid grid-cols-1 gap-2 pt-1">
                            <button type="button" onclick="triggerLogoUpload()"
                                class="w-full py-2.5 px-4 bg-blue-50 hover:bg-blue-100 text-blue-700 font-bold text-xs rounded-xl transition text-center border border-blue-200">
                                Ganti Logo
                            </button>

                            <button type="button" onclick="openDeleteLogoModal()"
                                class="w-full py-2 px-4 text-red-600 hover:bg-red-50 font-bold text-xs rounded-xl transition text-center">
                                Hapus Logo
                            </button>
                        </div>
                    </div>

                    <!-- Status 2: Belum Ada Logo (Dropzone) -->
                    <div id="logoEmptyState" class="space-y-4">
                        <div onclick="triggerLogoUpload()"
                            class="border-2 border-dashed border-slate-200 hover:border-blue-400 bg-slate-50 hover:bg-blue-50/50 rounded-2xl p-6 text-center cursor-pointer transition group">
                            <p class="text-xs font-bold text-slate-800">Unggah Logo Organisasi</p>
                            <p class="text-[11px] text-slate-500 mt-1">Klik di sini untuk memilih gambar dari perangkat Anda.</p>
                            <span class="inline-block mt-3 px-3 py-1 bg-white border border-slate-200 rounded-lg text-[10px] font-bold text-blue-600">Pilih File Gambar</span>
                        </div>
                    </div>

                    <!-- Status Uploading -->
                    <div id="logoUploadingState" class="hidden p-6 text-center space-y-3">
                        <div class="inline-block w-8 h-8 border-4 border-blue-600 border-t-transparent rounded-full animate-spin"></div>
                        <p class="text-xs font-bold text-slate-800">Mengunggah logo...</p>
                        <p class="text-[10px] text-slate-500">Mohon tunggu sebentar.</p>
                    </div>

                    <div class="mt-5 pt-4 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-500">
                        <span>Format: <strong>JPG / PNG</strong></span>
                        <span>Maksimal: <strong>2 MB</strong></span>
                    </div>
                </div>

                <div class="bg-blue-50/70 border border-blue-100 rounded-3xl p-5 text-xs text-blue-900">
                    <p class="font-bold mb-1.5 text-blue-800">Tips Profil Organisasi</p>
                    <ul class="list-disc list-inside space-y-1 text-[11px] text-blue-800/90">
                        <li>Gunakan logo berlatar bersih dengan resolusi yang cukup jelas.</li>
                        <li>Lengkapi deskripsi agar mahasiswa memahami budaya kerja Anda.</li>
                        <li>Pastikan kontak dan website aktif.</li>
                    </ul>
                </div>
            </div>
        </div>
    </main>

    <!-- MODAL KONFIRMASI HAPUS LOGO -->
    <div id="deleteLogoModal" class="hidden fixed inset-0 z-50 bg-slate-900/40 flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl p-6 max-w-sm w-full shadow-2xl border border-slate-100">
            <h3 class="text-base font-bold text-slate-900 text-center mb-1">Hapus Logo Organisasi?</h3>
            <p class="text-xs text-slate-500 text-center mb-6">
                Apakah Anda yakin ingin menghapus logo ini? File akan dihapus dari server dan Anda dapat mengunggah logo baru kapan saja.
            </p>
            <div class="flex items-center gap-2">
                <button type="button" onclick="closeDeleteLogoModal()"
                    class="w-1/2 py-2.5 px-4 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl transition">Batal</button>
                <button type="button" id="btnConfirmDeleteLogo" onclick="handleDeleteLogoConfirm()"
                    class="w-1/2 py-2.5 px-4 bg-red-600 hover:bg-red-700 text-white font-bold text-xs rounded-xl transition flex items-center justify-center gap-1.5 shadow-sm">
                    <span id="deleteLogoSpinner" class="spinner hidden"></span>
                    <span id="deleteLogoBtnText">Ya, Hapus</span>
                </button>
            </div>
        </div>
    </div>

    <!-- LOGIKA JAVASCRIPT & INTEGRASI API AXIOS (F-07, F-08, F-09) -->
    <script>
        const API_PROFILE = '/api/penyedia/profile';
        const API_LOGO = '/api/penyedia/profile/logo';

        let currentProfileData = null;

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

        // F-07: Mengambil Data Profil Organisasi
        async function fetchProfileData() {
            try {
                const response = await getApiClient().get(API_PROFILE);

                if (response.data && response.data.success) {
                    currentProfileData = response.data.data;
                    populateProfileForm(currentProfileData);
                    renderLogoSection(currentProfileData);
                } else {
                    showAlert('Gagal memuat profil organisasi.', 'error');
                }
            } catch (error) {
                console.error('Fetch profile error:', error);
                showAlert(error.response?.data?.message || 'Terjadi kesalahan saat memuat data profil.', 'error');
            } finally {
                document.getElementById('loadingSkeleton').classList.add('hidden');
                document.getElementById('profileContent').classList.remove('hidden');
            }
        }

        function populateProfileForm(data) {
            document.getElementById('user_email').value = data.email || '';
            document.getElementById('nama_organisasi').value = data.nama_organisasi || '';
            document.getElementById('bidang_usaha').value = data.bidang_usaha || '';
            document.getElementById('kontak').value = data.kontak || '';
            document.getElementById('website').value = data.website || '';
            document.getElementById('kota').value = data.kota || '';
            document.getElementById('alamat').value = data.alamat || '';
            document.getElementById('deskripsi').value = data.deskripsi || '';

            if (data.nama_organisasi) {
                document.getElementById('navUserName').innerText = data.nama_organisasi;
            }
            updateDeskripsiCounter();
        }

        function renderLogoSection(data) {
            const activeState = document.getElementById('logoActiveState');
            const emptyState = document.getElementById('logoEmptyState');
            document.getElementById('logoUploadingState').classList.add('hidden');

            if (data && data.logo_path) {
                emptyState.classList.add('hidden');
                activeState.classList.remove('hidden');
                const url = data.logo_url || `/storage/${data.logo_path}`;
                // Cache-buster supaya logo yang baru diganti langsung tampil
                document.getElementById('logoPreview').src = `${url}?t=${Date.now()}`;
            } else {
                activeState.classList.add('hidden');
                emptyState.classList.remove('hidden');
            }
        }

        // F-08: Mengubah Profil Organisasi (PUT)
        async function handleUpdateProfile(event) {
            event.preventDefault();
            clearValidationErrors();

            const btn = document.getElementById('btnSaveProfile');
            const spinner = document.getElementById('saveSpinner');
            const btnText = document.getElementById('saveBtnText');
            const val = id => document.getElementById(id).value.trim() || null;

            const payload = {
                nama_organisasi: document.getElementById('nama_organisasi').value.trim(),
                bidang_usaha: val('bidang_usaha'),
                kontak: val('kontak'),
                website: val('website'),
                kota: val('kota'),
                alamat: val('alamat'),
                deskripsi: val('deskripsi')
            };

            btn.disabled = true;
            spinner.classList.remove('hidden');
            btnText.innerText = 'Menyimpan...';

            try {
                const response = await getApiClient().put(API_PROFILE, payload);

                if (response.data && response.data.success) {
                    showAlert('Profil organisasi berhasil diperbarui!', 'success');
                    currentProfileData = response.data.data;
                    document.getElementById('navUserName').innerText = payload.nama_organisasi;
                } else {
                    showAlert('Gagal memperbarui profil.', 'error');
                }
            } catch (error) {
                console.error('Update profile error:', error);
                if (error.response && error.response.status === 422) {
                    displayValidationErrors(error.response.data.errors);
                    showAlert('Mohon periksa kembali kolom input formulir Anda.', 'error');
                } else {
                    showAlert(error.response?.data?.message || 'Terjadi kesalahan saat menyimpan profil.', 'error');
                }
            } finally {
                btn.disabled = false;
                spinner.classList.add('hidden');
                btnText.innerText = 'Simpan Perubahan Profil';
            }
        }

        // F-09: Unggah / Ganti Logo (POST)
        function triggerLogoUpload() {
            document.getElementById('logoFileInput').click();
        }

        async function handleLogoFileSelected(event) {
            const fileInput = event.target;
            if (!fileInput.files || fileInput.files.length === 0) return;

            const file = fileInput.files[0];

            // Validasi sisi klien: format JPG/PNG
            const allowed = ['image/jpeg', 'image/png'];
            if (!allowed.includes(file.type)) {
                showAlert('Format file tidak valid. Logo wajib berformat JPG atau PNG.', 'error');
                fileInput.value = '';
                return;
            }

            // Validasi sisi klien: maksimal 2 MB
            if (file.size > 2 * 1024 * 1024) {
                showAlert('Ukuran file melebihi batas 2 MB. Silakan pilih gambar yang lebih kecil.', 'error');
                fileInput.value = '';
                return;
            }

            const formData = new FormData();
            formData.append('logo', file);

            document.getElementById('logoEmptyState').classList.add('hidden');
            document.getElementById('logoActiveState').classList.add('hidden');
            document.getElementById('logoUploadingState').classList.remove('hidden');

            try {
                const response = await getApiClient().post(API_LOGO, formData, {
                    headers: { 'Content-Type': 'multipart/form-data' }
                });

                if (response.data && response.data.success) {
                    showAlert('Logo organisasi berhasil diunggah!', 'success');
                    currentProfileData = { ...currentProfileData, ...response.data.data };
                    renderLogoSection(currentProfileData);
                } else {
                    showAlert('Gagal mengunggah logo.', 'error');
                    renderLogoSection(currentProfileData);
                }
            } catch (error) {
                console.error('Upload logo error:', error);
                const errors = error.response?.data?.errors;
                const msg = errors?.logo?.[0] || error.response?.data?.message || 'Terjadi kesalahan saat mengunggah logo.';
                showAlert(msg, 'error');
                renderLogoSection(currentProfileData);
            } finally {
                fileInput.value = '';
            }
        }

        // F-09: Hapus Logo (DELETE)
        function openDeleteLogoModal() {
            document.getElementById('deleteLogoModal').classList.remove('hidden');
        }

        function closeDeleteLogoModal() {
            document.getElementById('deleteLogoModal').classList.add('hidden');
        }

        async function handleDeleteLogoConfirm() {
            const btn = document.getElementById('btnConfirmDeleteLogo');
            const spinner = document.getElementById('deleteLogoSpinner');
            const btnText = document.getElementById('deleteLogoBtnText');

            btn.disabled = true;
            spinner.classList.remove('hidden');
            btnText.innerText = 'Menghapus...';

            try {
                const response = await getApiClient().delete(API_LOGO);

                if (response.data && response.data.success) {
                    showAlert('Logo organisasi berhasil dihapus.', 'success');
                    currentProfileData = { ...currentProfileData, logo_path: null, logo_url: null };
                    renderLogoSection(currentProfileData);
                    closeDeleteLogoModal();
                } else {
                    showAlert('Gagal menghapus logo.', 'error');
                }
            } catch (error) {
                console.error('Delete logo error:', error);
                showAlert(error.response?.data?.message || 'Terjadi kesalahan saat menghapus logo.', 'error');
            } finally {
                btn.disabled = false;
                spinner.classList.add('hidden');
                btnText.innerText = 'Ya, Hapus';
            }
        }

        // Helper: Counter, Alerts, Validasi
        function updateDeskripsiCounter() {
            const text = document.getElementById('deskripsi').value || '';
            const counter = document.getElementById('deskripsiCounter');
            counter.innerText = `${text.length} / 2000 karakter`;
            counter.classList.toggle('text-red-500', text.length > 2000);
        }

        function showAlert(message, type = 'success') {
            const container = document.getElementById('alertContainer');
            const msgEl = document.getElementById('alertMessage');

            container.classList.remove('hidden', 'bg-green-50', 'border-green-200', 'text-green-800', 'bg-red-50', 'border-red-200', 'text-red-800');

            if (type === 'success') {
                container.classList.add('bg-green-50', 'border-green-200', 'text-green-800');
            } else {
                container.classList.add('bg-red-50', 'border-red-200', 'text-red-800');
            }
            msgEl.textContent = message;
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

        document.addEventListener('DOMContentLoaded', fetchProfileData);
    </script>
</body>
</html>