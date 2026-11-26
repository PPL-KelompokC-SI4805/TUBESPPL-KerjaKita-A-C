<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Lowongan Tersimpan - Arsip Minat Karir - KerjaKita</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <style>
        [x-cloak] { display: none !important; }
        .spinner {
            border: 2px solid rgba(59, 130, 246, 0.2);
            border-radius: 50%;
            border-top-color: #2563eb;
            width: 1.5rem;
            height: 1.5rem;
            animation: spin 0.8s linear infinite;
        }
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    </style>
</head>
<body class="bg-[#f8fafc] min-h-screen flex flex-col font-sans text-slate-800 antialiased">

    <!-- Inject Bearer Token -->
    <script>
        window.AUTH_TOKEN = "{{ $apiToken ?? '' }}";
        if (window.AUTH_TOKEN) {
            localStorage.setItem('auth_token', window.AUTH_TOKEN);
        }
        axios.defaults.headers.common['Authorization'] = `Bearer ${window.AUTH_TOKEN}`;
        axios.defaults.headers.common['Accept'] = 'application/json';
        axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    </script>

    <!-- Top Navigation Bar Sesuai Mockup Proposal (Mockup 6, Halaman 42) -->
    <header class="bg-white border-b border-slate-200 sticky top-0 z-40 shadow-xs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <!-- Brand & Nav Links -->
                <div class="flex items-center gap-8">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5 group">
                        <div class="w-9 h-9 rounded-xl bg-blue-600 text-white font-extrabold flex items-center justify-center shadow-md shadow-blue-500/20 group-hover:bg-blue-700 transition">
                            KK
                        </div>
                        <span class="font-extrabold text-xl tracking-tight text-slate-900">KerjaKita</span>
                    </a>

                    <nav class="hidden md:flex items-center gap-1">
                        <a href="{{ route('mahasiswa.lowongan') }}" class="px-3 py-2 text-sm font-semibold text-slate-600 hover:text-blue-600 transition flex items-center gap-1.5">
                            Cari Lowongan
                        </a>
                        <a href="{{ route('mahasiswa.lowongan_tersimpan') }}" class="px-3 py-2 text-sm font-bold text-blue-600 border-b-2 border-blue-600 transition flex items-center gap-1.5">
                            Lowongan Tersimpan
                        </a>
                        <a href="#" onclick="showNotice('Fitur Riwayat Lamaran sedang dalam tahap integrasi modul M6.')" class="px-3 py-2 text-sm font-semibold text-slate-500 hover:text-slate-800 transition">
                            Riwayat Lamaran
                        </a>
                        <a href="{{ route('mahasiswa.profil') }}" class="px-3 py-2 text-sm font-semibold text-slate-600 hover:text-blue-600 transition">
                            Profil Mahasiswa
                        </a>
                    </nav>
                </div>

                <!-- User Info & Action -->
                <div class="flex items-center gap-4">
                    <div class="text-right hidden sm:block">
                        <p class="text-xs font-bold text-slate-800">{{ Auth::user()->name }}</p>
                        <span class="inline-flex items-center gap-1 text-[10px] font-semibold text-blue-600">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                            Mahasiswa Aktif
                        </span>
                    </div>

                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="px-3 py-1.5 bg-slate-100 hover:bg-red-50 hover:text-red-600 text-slate-600 text-xs font-bold rounded-xl border border-slate-200 transition">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <!-- Mobile Subnav -->
    <div class="md:hidden bg-white border-b border-slate-200 px-4 py-2 flex items-center justify-around text-xs font-semibold">
        <a href="{{ route('mahasiswa.lowongan') }}" class="text-slate-600">Cari Lowongan</a>
        <a href="{{ route('mahasiswa.lowongan_tersimpan') }}" class="text-blue-600 font-bold border-b-2 border-blue-600 pb-1">Tersimpan</a>
        <a href="{{ route('mahasiswa.profil') }}" class="text-slate-600">Profil</a>
    </div>

    <!-- Main Container -->
    <main class="max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8 flex-1 flex flex-col">

        <!-- Toast Notification Bar -->
        <div id="toastNotification" class="hidden fixed bottom-6 right-6 z-50 max-w-md bg-white border border-slate-200 shadow-xl rounded-2xl p-4 transition-all duration-300 transform translate-y-4">
            <div class="flex items-start gap-3">
                <div id="toastIcon" class="w-8 h-8 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                </div>
                <div class="flex-1">
                    <p id="toastTitle" class="text-xs font-bold text-slate-900">Notifikasi</p>
                    <p id="toastMessage" class="text-xs text-slate-600 mt-0.5"></p>
                </div>
                <button onclick="hideToast()" class="text-slate-400 hover:text-slate-600">&times;</button>
            </div>
        </div>

        <!-- ============================================================== -->
        <!-- HEADER ARSIP MINAT KARIR (Mockup Halaman 42)                   -->
        <!-- ============================================================== -->
        <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-[10px] font-extrabold uppercase tracking-wider bg-blue-50 text-blue-700 border border-blue-200">
                    <svg class="w-3 h-3 text-blue-600" fill="currentColor" viewBox="0 0 20 20"><path d="M5 4a2 2 0 012-2h6a2 2 0 012 2v14l-5-2.5L5 18V4z"/></svg>
                    ARSIP MINAT KARIR
                </span>
                <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight mt-1">Lowongan Tersimpan</h1>
                <p class="text-xs sm:text-sm text-slate-500 max-w-2xl mt-1 leading-relaxed">
                    Pantau daftar posisi magang, freelance, dan paruh waktu yang Anda simpan beserta catatan preferensi pribadi untuk akselerasi karir akademik.
                </p>
            </div>

            <!-- Metric Box di Kanan (Mockup Halaman 42) -->
            <div class="bg-white rounded-3xl border border-slate-200/80 p-4 shadow-sm flex items-center gap-4 shrink-0">
                <div class="w-12 h-12 rounded-2xl bg-blue-600 text-white font-black text-xl flex items-center justify-center shadow-md shadow-blue-500/20" id="metricCounterNumber">
                    0
                </div>
                <div>
                    <p class="text-xs font-bold text-slate-800" id="metricCounterLabel">Lowongan Tersimpan</p>
                    <p class="text-[11px] text-emerald-600 font-semibold flex items-center gap-1">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                        Tersedia Aktif
                    </p>
                </div>
            </div>
        </div>

        <!-- Tab Filters (Mockup Halaman 42) -->
        <div class="flex items-center gap-2 overflow-x-auto pb-3 mb-6">
            <button type="button" onclick="setTabFilter('semua')" data-tab="semua" class="tab-btn active px-4 py-2 rounded-2xl text-xs font-bold bg-blue-600 text-white transition shadow-xs">
                Semua Tersimpan (<span id="countSemua">0</span>)
            </button>
            <button type="button" onclick="setTabFilter('magang')" data-tab="magang" class="tab-btn px-4 py-2 rounded-2xl text-xs font-bold bg-white text-slate-600 hover:bg-slate-100 border border-slate-200 transition">
                Magang Bersertifikat (<span id="countMagang">0</span>)
            </button>
            <button type="button" onclick="setTabFilter('freelance')" data-tab="freelance" class="tab-btn px-4 py-2 rounded-2xl text-xs font-bold bg-white text-slate-600 hover:bg-slate-100 border border-slate-200 transition">
                Freelance (<span id="countFreelance">0</span>)
            </button>
            <button type="button" onclick="setTabFilter('paruh_waktu')" data-tab="paruh_waktu" class="tab-btn px-4 py-2 rounded-2xl text-xs font-bold bg-white text-slate-600 hover:bg-slate-100 border border-slate-200 transition">
                Part-time (<span id="countPartTime">0</span>)
            </button>
        </div>

        <!-- ============================================================== -->
        <!-- MAIN CONTENT: 2 COLUMNS (Mockup Halaman 42)                    -->
        <!-- ============================================================== -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start flex-1">

            <!-- ========================== -->
            <!-- KOLOM KIRI: DAFTAR KARTU   -->
            <!-- ========================== -->
            <div class="lg:col-span-8 space-y-4">
                <div id="savedListContainer" class="space-y-4">
                    <!-- Loading Skeleton -->
                    <div class="animate-pulse space-y-4">
                        <div class="bg-white rounded-3xl p-6 border border-slate-200 h-44"></div>
                        <div class="bg-white rounded-3xl p-6 border border-slate-200 h-44"></div>
                    </div>
                </div>
            </div>

            <!-- ========================== -->
            <!-- KOLOM KANAN: SIDEBAR TIPS  -->
            <!-- ========================== -->
            <div class="lg:col-span-4 space-y-5">
                <!-- Tips Persiapan Berkas (Mockup Halaman 42) -->
                <div class="bg-white rounded-3xl border border-slate-200/80 p-6 shadow-sm">
                    <div class="flex items-center gap-2 mb-3">
                        <span class="text-blue-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </span>
                        <h3 class="text-sm font-extrabold text-slate-900">Tips Persiapan Berkas</h3>
                    </div>
                    <p class="text-xs text-slate-500 mb-4">Pastikan berkas profil Anda memenuhi standar kurasi industri sebelum mengirimkan berkas lamaran.</p>

                    <div class="space-y-3.5 text-xs">
                        <div class="flex items-start gap-2.5">
                            <svg class="w-4 h-4 text-emerald-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <div>
                                <p class="font-bold text-slate-800">CV Format Standar ATS</p>
                                <p class="text-[11px] text-slate-500 mt-0.5 leading-relaxed">Gunakan format dokumen teks sederhana tanpa kolom ganda atau grafik rumit agar terbaca sistem filter otomatis.</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-2.5">
                            <svg class="w-4 h-4 text-emerald-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <div>
                                <p class="font-bold text-slate-800">Transkrip Terverifikasi</p>
                                <p class="text-[11px] text-slate-500 mt-0.5 leading-relaxed">Lampirkan dokumen nilai resmi bertanda tangan digital atau cap dekanat untuk percepatan konversi SKS kampus merdeka.</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-2.5">
                            <svg class="w-4 h-4 text-emerald-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <div>
                                <p class="font-bold text-slate-800">Tautan Portofolio Terbuka</p>
                                <p class="text-[11px] text-slate-500 mt-0.5 leading-relaxed">Pastikan izin akses Google Drive atau link Behance/GitHub disetel publik agar rekruter tidak mengalami kendala.</p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-5 pt-4 border-t border-slate-100 text-center">
                        <a href="{{ route('mahasiswa.profil') }}" class="inline-flex items-center gap-1 text-xs font-bold text-blue-600 hover:text-blue-800">
                            Perbarui CV di Profil Saya &rarr;
                        </a>
                    </div>
                </div>

                <!-- Mitra Universitas Card (Mockup Halaman 42) -->
                <div class="bg-gradient-to-br from-blue-50 to-indigo-50/50 rounded-3xl border border-blue-200/70 p-6 shadow-sm text-xs">
                    <span class="text-[10px] font-extrabold uppercase tracking-wider text-blue-700 flex items-center gap-1.5 mb-1.5">
                        <svg class="w-3.5 h-3.5 text-blue-600" fill="currentColor" viewBox="0 0 20 20"><path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a10.97 10.97 0 00-.25 2.324v2.5a1 1 0 00.5.866l5 2.887a1 1 0 001 0l5-2.887a1 1 0 00.5-.866v-2.5a10.97 10.97 0 00-.25-2.324l2.644-1.131a1 1 0 000-1.84l-7-3z"/></svg>
                        MITRA UNIVERSITAS
                    </span>
                    <h4 class="font-bold text-slate-900 text-sm">Konversi 20 SKS Resmi</h4>
                    <p class="text-slate-600 mt-1 leading-relaxed text-[11px]">
                        Seluruh lowongan bertanda <span class="font-bold text-blue-700">Konversi SKS Ready</span> telah diselaraskan dengan capaian pembelajaran program studi dan mendapat persetujuan dosen pembimbing lapangan.
                    </p>
                    <div class="mt-4 pt-3 border-t border-blue-200/60 flex items-center justify-between text-[10px] text-blue-700 font-bold">
                        <span>Terverifikasi LLDIKTI</span>
                        <span>Regulasi 2024</span>
                    </div>
                </div>
            </div>

        </div>
    </main>

    <!-- ============================================================== -->
    <!-- MODAL: EDIT CATATAN PRIBADI (F-15)                             -->
    <!-- ============================================================== -->
    <div id="editNoteModal" class="hidden fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-lg w-full p-6 sm:p-7 shadow-2xl border border-slate-100">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-4">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z"/></svg>
                    </div>
                    <h3 class="text-base font-extrabold text-slate-900">Ubah Catatan Pribadi</h3>
                </div>
                <button onclick="closeEditNoteModal()" class="text-slate-400 hover:text-slate-600 text-lg leading-none">&times;</button>
            </div>

            <p class="text-xs text-slate-500 mb-3">
                Posisi: <span id="modalEditJobTitle" class="font-bold text-slate-800"></span>
            </p>

            <form id="editNoteForm" onsubmit="handleEditNoteSubmit(event)" class="space-y-4">
                <input type="hidden" id="modalEditJobId" value="">
                <div>
                    <label for="modalEditCatatanInput" class="block text-xs font-bold text-slate-700 mb-1">
                        Catatan Pribadi Mahasiswa
                    </label>
                    <textarea id="modalEditCatatanInput" rows="4" 
                              placeholder="Tuliskan catatan persiapan, deadline, portofolio, atau jadwal kuliah..." 
                              class="w-full p-3 bg-slate-50 border border-slate-200 rounded-2xl text-xs sm:text-sm text-slate-800 placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 transition"></textarea>
                    <p class="text-[10px] text-slate-400 mt-1">Catatan ini dapat Anda ubah kapan saja dan tidak terlihat oleh rekruter.</p>
                </div>

                <div class="flex items-center justify-end gap-2 pt-2">
                    <button type="button" onclick="closeEditNoteModal()" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl shadow-xs transition">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-200 mt-12 py-6 text-center text-xs text-slate-500">
        <p>&copy; 2026 KerjaKita Indonesia. Platform Terdaftar Resmi Kemendikbudristek & Forum Rektor Indonesia.</p>
    </footer>

    <!-- ============================================================== -->
    <!-- JAVASCRIPT LOGIC (M5: F-15)                                    -->
    <!-- ============================================================== -->
    <script>
        let allSavedItems = [];
        let activeTab = 'semua';

        document.addEventListener('DOMContentLoaded', () => {
            loadSavedJobs();
        });

        async function loadSavedJobs() {
            const container = document.getElementById('savedListContainer');
            container.innerHTML = `
                <div class="py-12 text-center text-slate-400">
                    <div class="spinner mx-auto mb-2"></div>
                    <p class="text-xs">Memuat daftar lowongan tersimpan...</p>
                </div>
            `;

            try {
                const response = await axios.get('/api/mahasiswa/lowongan-tersimpan');
                if (response.data && response.data.success) {
                    allSavedItems = response.data.data;
                    updateCounters();
                    renderSavedList();
                }
            } catch (error) {
                console.error('Error fetching saved jobs:', error);
                container.innerHTML = `
                    <div class="p-8 text-center bg-white rounded-3xl border border-red-200 text-xs text-red-600">
                        Gagal memuat lowongan tersimpan. Silakan muat ulang halaman.
                    </div>
                `;
            }
        }

        function updateCounters() {
            const total = allSavedItems.length;
            const magang = allSavedItems.filter(item => item.lowongan?.jenis_pekerjaan === 'magang').length;
            const freelance = allSavedItems.filter(item => item.lowongan?.jenis_pekerjaan === 'freelance').length;
            const partTime = allSavedItems.filter(item => item.lowongan?.jenis_pekerjaan === 'paruh_waktu').length;

            document.getElementById('metricCounterNumber').innerText = total;
            document.getElementById('countSemua').innerText = total;
            document.getElementById('countMagang').innerText = magang;
            document.getElementById('countFreelance').innerText = freelance;
            document.getElementById('countPartTime').innerText = partTime;
        }

        function setTabFilter(tab) {
            activeTab = tab;
            document.querySelectorAll('.tab-btn').forEach(btn => {
                if (btn.dataset.tab === tab) {
                    btn.className = 'tab-btn active px-4 py-2 rounded-2xl text-xs font-bold bg-blue-600 text-white transition shadow-xs';
                } else {
                    btn.className = 'tab-btn px-4 py-2 rounded-2xl text-xs font-bold bg-white text-slate-600 hover:bg-slate-100 border border-slate-200 transition';
                }
            });
            renderSavedList();
        }

        function renderSavedList() {
            const container = document.getElementById('savedListContainer');

            let filtered = allSavedItems;
            if (activeTab !== 'semua') {
                filtered = allSavedItems.filter(item => item.lowongan?.jenis_pekerjaan === activeTab);
            }

            if (filtered.length === 0) {
                container.innerHTML = `
                    <div class="bg-white rounded-3xl p-10 text-center border border-slate-200 shadow-xs">
                        <div class="w-14 h-14 rounded-3xl bg-blue-50 text-blue-600 mx-auto flex items-center justify-center mb-4">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/></svg>
                        </div>
                        <h4 class="text-base font-extrabold text-slate-800">Belum Ada Lowongan Tersimpan</h4>
                        <p class="text-xs text-slate-500 max-w-md mx-auto mt-1">Anda belum menyimpan lowongan pada kategori ini. Jelajahi berbagai peluang karir menarik dan simpan dengan catatan preferensi Anda.</p>
                        <div class="mt-5">
                            <a href="{{ route('mahasiswa.lowongan') }}" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-2xl transition inline-flex items-center gap-2 shadow-xs">
                                <span>Cari Lowongan Sekarang</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </a>
                        </div>
                    </div>
                `;
                return;
            }

            container.innerHTML = '';
            filtered.forEach(item => {
                const job = item.lowongan;
                if (!job) return;

                const card = document.createElement('div');
                card.id = `saved-item-${item.id}`;
                card.className = 'bg-white rounded-3xl border border-slate-200/90 p-5 sm:p-6 shadow-xs hover:shadow transition';

                const colors = ['bg-blue-600', 'bg-indigo-600', 'bg-emerald-600', 'bg-amber-600', 'bg-purple-600'];
                const avatarColor = colors[job.id % colors.length];
                const initials = getInitials(job.penyedia?.nama_organisasi || 'KK');

                const jenisBadge = formatJenisBadge(job.jenis_pekerjaan);

                card.innerHTML = `
                    <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-4">
                        <!-- Left Info -->
                        <div class="flex items-start gap-3.5">
                            <div class="w-12 h-12 rounded-2xl ${avatarColor} text-white font-black flex items-center justify-center text-sm shrink-0 shadow-xs">
                                ${initials}
                            </div>
                            <div>
                                <div class="flex flex-wrap items-center gap-1.5 mb-1">
                                    ${jenisBadge}
                                    <span class="px-2 py-0.5 rounded-lg text-[10px] font-semibold bg-emerald-50 text-emerald-700">Konversi SKS Ready</span>
                                    <span class="px-2 py-0.5 rounded-lg text-[10px] font-bold text-blue-600 bg-blue-50/80">${job.kompensasi || 'Tersedia'}</span>
                                </div>
                                <h3 class="text-base font-black text-slate-900">${job.judul_posisi}</h3>
                                <p class="text-xs text-slate-500 mt-0.5">
                                    <span class="font-bold text-slate-700">${job.penyedia?.nama_organisasi || 'Mitra KerjaKita'}</span> &bull; 
                                    ${job.lokasi_kota || 'Indonesia'} (${(job.tipe_lokasi || 'hybrid').toUpperCase()})
                                </p>
                                <p class="text-[11px] text-slate-400 mt-1 flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    Tersimpan &bull; Pendaftaran masih dibuka
                                </p>
                            </div>
                        </div>

                        <!-- Right Actions -->
                        <div class="flex items-center gap-2 sm:self-start shrink-0">
                            <button type="button" onclick="handleApplyDirect('${job.judul_posisi}', '${job.penyedia?.nama_organisasi || ''}')" 
                                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl transition flex items-center gap-1.5 shadow-xs">
                                <span>Ajukan Lamaran</span>
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </button>

                            <!-- Edit Note Button -->
                            <button type="button" onclick="openEditNoteModal(${item.id}, ${job.id}, '${escapeHtml(job.judul_posisi)}', '${escapeHtml(item.catatan_pribadi || '')}')" 
                                    class="p-2 bg-slate-100 hover:bg-blue-50 text-slate-600 hover:text-blue-600 rounded-xl transition" title="Ubah Catatan Pribadi">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </button>

                            <!-- Delete / Unsave Button -->
                            <button type="button" onclick="confirmDeleteSaved(${item.id}, '${escapeHtml(job.judul_posisi)}')" 
                                    class="p-2 bg-slate-100 hover:bg-red-50 text-slate-400 hover:text-red-600 rounded-xl transition" title="Hapus dari Simpanan">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </div>
                    </div>

                    <!-- Highlight Box Catatan Pribadi Mahasiswa (Mockup Halaman 42) -->
                    <div class="mt-4 p-3.5 sm:p-4 rounded-2xl bg-blue-50/60 border border-blue-200/80 text-xs">
                        <div class="flex items-center justify-between text-blue-900 font-extrabold text-[11px] mb-1.5">
                            <span class="flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-blue-600" fill="currentColor" viewBox="0 0 20 20"><path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z"/><path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd"/></svg>
                                CATATAN PRIBADI MAHASISWA
                            </span>
                            <button type="button" onclick="openEditNoteModal(${item.id}, ${job.id}, '${escapeHtml(job.judul_posisi)}', '${escapeHtml(item.catatan_pribadi || '')}')" 
                                    class="text-blue-600 hover:text-blue-800 font-bold hover:underline flex items-center gap-1">
                                <span>Edit</span>
                            </button>
                        </div>
                        <p class="text-slate-700 leading-relaxed font-normal">
                            ${item.catatan_pribadi ? escapeHtml(item.catatan_pribadi) : '<span class="italic text-slate-400">Belum ada catatan pribadi. Klik Edit untuk menambahkan preferensi atau pengingat jadwal.</span>'}
                        </p>
                    </div>
                `;

                container.appendChild(card);
            });
        }

        function formatJenisBadge(jenis) {
            if (jenis === 'magang') {
                return `<span class="px-2.5 py-0.5 rounded-lg text-[10px] font-bold bg-blue-50 text-blue-700">Magang Bersertifikat</span>`;
            } else if (jenis === 'freelance') {
                return `<span class="px-2.5 py-0.5 rounded-lg text-[10px] font-bold bg-purple-50 text-purple-700">Freelance Project</span>`;
            } else {
                return `<span class="px-2.5 py-0.5 rounded-lg text-[10px] font-bold bg-amber-50 text-amber-700">Paruh Waktu (Part-time)</span>`;
            }
        }

        function getInitials(name) {
            return name.split(' ').map(w => w[0]).filter(Boolean).slice(0, 2).join('').toUpperCase();
        }

        function escapeHtml(str) {
            if (!str) return '';
            return str
                .replace(/&/g, "&amp;")
                .replace(/</g, "&lt;")
                .replace(/>/g, "&gt;")
                .replace(/"/g, "&quot;")
                .replace(/'/g, "&#039;");
        }

        // ==========================================
        // EDIT CATATAN PRIBADI MODAL (F-15)
        // ==========================================
        function openEditNoteModal(savedId, jobId, jobTitle, currentNote) {
            document.getElementById('modalEditJobId').value = jobId;
            document.getElementById('modalEditJobTitle').innerText = jobTitle;
            document.getElementById('modalEditCatatanInput').value = currentNote;
            document.getElementById('editNoteModal').classList.remove('hidden');
        }

        function closeEditNoteModal() {
            document.getElementById('editNoteModal').classList.add('hidden');
        }

        async function handleEditNoteSubmit(event) {
            event.preventDefault();
            const jobId = document.getElementById('modalEditJobId').value;
            const newNote = document.getElementById('modalEditCatatanInput').value.trim();

            try {
                const response = await axios.put(`/api/mahasiswa/lowongan-tersimpan/${jobId}`, {
                    catatan_pribadi: newNote || null
                });

                if (response.data && response.data.success) {
                    showToast('Catatan Diperbarui', 'Catatan pribadi untuk lowongan ini berhasil diperbarui.');
                    closeEditNoteModal();

                    // Update di memori lokal
                    const item = allSavedItems.find(i => i.lowongan_id == jobId || i.id == jobId);
                    if (item) {
                        item.catatan_pribadi = newNote;
                    }
                    renderSavedList();
                }
            } catch (error) {
                console.error('Error updating note:', error);
                showToast('Galat', 'Gagal memperbarui catatan pribadi.', 'error');
            }
        }

        // ==========================================
        // HAPUS SIMPANAN (F-15)
        // ==========================================
        async function confirmDeleteSaved(savedId, jobTitle) {
            if (!confirm(`Hapus lowongan "${jobTitle}" dari daftar tersimpan Anda?`)) return;

            try {
                const response = await axios.delete(`/api/mahasiswa/lowongan-tersimpan/${savedId}`);
                if (response.data && response.data.success) {
                    showToast('Lowongan Dihapus', `Lowongan "${jobTitle}" berhasil dihapus dari simpanan.`);

                    // Hapus dari memori lokal
                    allSavedItems = allSavedItems.filter(i => i.id !== savedId);
                    updateCounters();
                    renderSavedList();
                }
            } catch (error) {
                console.error('Error deleting saved job:', error);
                showToast('Galat', 'Gagal menghapus lowongan tersimpan.', 'error');
            }
        }

        function handleApplyDirect(jobTitle, company) {
            alert(`Anda memilih untuk melamar posisi "${jobTitle}" di ${company}.\n\nFormulir pengajuan lamaran interaktif berada pada Modul M6 (F-16: Mengajukan Lamaran).`);
        }

        function showNotice(msg) {
            showToast('Informasi Fitur', msg);
        }

        function showToast(title, message, type = 'success') {
            const toast = document.getElementById('toastNotification');
            const icon = document.getElementById('toastIcon');
            document.getElementById('toastTitle').innerText = title;
            document.getElementById('toastMessage').innerText = message;

            if (type === 'error') {
                icon.className = 'w-8 h-8 rounded-xl bg-red-100 text-red-600 flex items-center justify-center shrink-0';
                icon.innerHTML = `<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>`;
            } else {
                icon.className = 'w-8 h-8 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0';
                icon.innerHTML = `<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>`;
            }

            toast.classList.remove('hidden');
            setTimeout(() => {
                toast.classList.remove('translate-y-4');
            }, 10);

            setTimeout(() => {
                hideToast();
            }, 4000);
        }

        function hideToast() {
            const toast = document.getElementById('toastNotification');
            toast.classList.add('translate-y-4');
            setTimeout(() => {
                toast.classList.add('hidden');
            }, 300);
        }
    </script>
</body>
</html>
