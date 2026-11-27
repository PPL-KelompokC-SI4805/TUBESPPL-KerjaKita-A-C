<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Riwayat Lamaran — KerjaKita</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
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
<body class="bg-[#F8FAFC] text-slate-800 min-h-screen flex flex-col">

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

    <!-- Top Navbar -->
    <header class="bg-white border-b border-slate-200/80 sticky top-0 z-30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <div class="flex items-center gap-8">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5">
                    <div class="w-9 h-9 rounded-xl bg-blue-600 text-white font-extrabold text-sm flex items-center justify-center shadow-sm">
                        KK
                    </div>
                    <span class="font-extrabold text-lg text-slate-900 tracking-tight">KerjaKita</span>
                </a>

                <nav class="hidden md:flex items-center gap-6">
                    <a href="{{ route('mahasiswa.lowongan') }}" class="text-sm font-medium text-slate-600 hover:text-blue-600 transition py-5">
                        Cari Lowongan
                    </a>
                    <a href="{{ route('mahasiswa.lowongan_tersimpan') }}" class="text-sm font-medium text-slate-600 hover:text-blue-600 transition py-5">
                        Lowongan Tersimpan
                    </a>
                    <a href="{{ route('mahasiswa.lamaran') }}" class="text-sm font-bold text-blue-600 border-b-2 border-blue-600 py-5">
                        Riwayat Lamaran
                    </a>
                    <a href="{{ route('mahasiswa.profil') }}" class="text-sm font-medium text-slate-600 hover:text-blue-600 transition py-5">
                        Profil Mahasiswa
                    </a>
                </nav>
            </div>

            <div class="flex items-center gap-4">
                <div class="text-right hidden sm:block">
                    <div class="text-sm font-bold text-slate-900">{{ $user->name }}</div>
                    <div class="text-xs font-medium text-blue-600 flex items-center justify-end gap-1">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                        Mahasiswa Aktif
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="px-4 py-2 text-xs font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-xl transition">
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </header>

    <!-- Toast Notification -->
    <div id="toastNotification" class="hidden fixed bottom-6 right-6 z-50 max-w-md bg-white border border-slate-200 shadow-xl rounded-2xl p-4 transition-all duration-300">
        <div class="flex items-start gap-3">
            <div id="toastIcon" class="w-8 h-8 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0 font-bold">
                ✓
            </div>
            <div class="flex-1">
                <p id="toastTitle" class="text-xs font-bold text-slate-900">Notifikasi</p>
                <p id="toastMessage" class="text-xs text-slate-600 mt-0.5"></p>
            </div>
            <button onclick="hideToast()" class="text-slate-400 hover:text-slate-600">&times;</button>
        </div>
    </div>

    <!-- Main Content -->
    <main class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">

        <!-- Hero Header -->
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-50 text-blue-700 text-[11px] font-bold tracking-wide uppercase mb-3">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    AKSES PORTAL KARIR KAMPUS &bull; TAHUN AKADEMIK 2026/2027
                </div>
                <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Riwayat Lamaran</h1>
                <p class="text-sm text-slate-500 mt-1 max-w-2xl">
                    Pantau status berkas lamaran yang telah Anda kirimkan ke mitra perusahaan perguruan tinggi secara terpusat.
                </p>
            </div>

            <div>
                <span class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs font-semibold">
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                    Akun Terverifikasi SIAKAD
                </span>
            </div>
        </div>

        <!-- 4 Summary Metric Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-xs flex items-start justify-between">
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Total Lamaran</p>
                    <div class="mt-2 flex items-baseline gap-2">
                        <span id="stat-total" class="text-3xl font-extrabold text-slate-900">0</span>
                        <span class="text-xs font-medium text-slate-500">berkas aktif</span>
                    </div>
                </div>
                <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                    </svg>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-xs flex items-start justify-between">
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Magang Resmi</p>
                    <div class="mt-2 flex items-baseline gap-2">
                        <span id="stat-magang" class="text-3xl font-extrabold text-slate-900">0</span>
                        <span class="text-xs font-medium text-slate-500">posisi semester aktif</span>
                    </div>
                </div>
                <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                    </svg>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-xs flex items-start justify-between">
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Sedang Diproses</p>
                    <div class="mt-2 flex items-baseline gap-2">
                        <span id="stat-proses" class="text-3xl font-extrabold text-slate-900">0</span>
                        <span class="text-xs font-medium text-slate-500">menunggu seleksi</span>
                    </div>
                </div>
                <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-xs flex items-start justify-between">
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Diterima</p>
                    <div class="mt-2 flex items-baseline gap-2">
                        <span id="stat-diterima" class="text-3xl font-extrabold text-slate-900">0</span>
                        <span class="text-xs font-medium text-slate-500">lolos tahap akhir</span>
                    </div>
                </div>
                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Filter Tabs + Action -->
        <div class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-xs flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div class="flex flex-wrap items-center gap-2" id="statusFilterTabs">
                <button type="button" onclick="filterByStatus('')" data-status="" class="status-tab px-3.5 py-1.5 rounded-xl text-xs font-bold bg-blue-600 text-white transition">
                    Semua Status
                </button>
                <button type="button" onclick="filterByStatus('Terkirim')" data-status="Terkirim" class="status-tab px-3.5 py-1.5 rounded-xl text-xs font-semibold bg-slate-100 text-slate-600 hover:bg-slate-200 transition">
                    Terkirim
                </button>
                <button type="button" onclick="filterByStatus('Ditinjau')" data-status="Ditinjau" class="status-tab px-3.5 py-1.5 rounded-xl text-xs font-semibold bg-slate-100 text-slate-600 hover:bg-slate-200 transition">
                    Ditinjau
                </button>
                <button type="button" onclick="filterByStatus('Diterima')" data-status="Diterima" class="status-tab px-3.5 py-1.5 rounded-xl text-xs font-semibold bg-slate-100 text-slate-600 hover:bg-slate-200 transition">
                    Diterima
                </button>
                <button type="button" onclick="filterByStatus('Ditolak')" data-status="Ditolak" class="status-tab px-3.5 py-1.5 rounded-xl text-xs font-semibold bg-slate-100 text-slate-600 hover:bg-slate-200 transition">
                    Ditolak
                </button>
                <button type="button" onclick="filterByStatus('Dibatalkan')" data-status="Dibatalkan" class="status-tab px-3.5 py-1.5 rounded-xl text-xs font-semibold bg-slate-100 text-slate-600 hover:bg-slate-200 transition">
                    Dibatalkan
                </button>
            </div>

            <a href="{{ route('mahasiswa.lowongan') }}" class="inline-flex items-center justify-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl transition">
                + Cari Lowongan Baru
            </a>
        </div>

        <!-- Daftar Riwayat Lamaran -->
        <div id="lamaranListContainer" class="space-y-4">
            <div class="bg-white rounded-2xl border border-slate-200/80 p-12 text-center">
                <div class="spinner mx-auto mb-3"></div>
                <p class="text-xs text-slate-500 font-medium">Memuat riwayat lamaran Anda...</p>
            </div>
        </div>
    </main>

    <!-- Modal Edit Lamaran (M6: F-18) -->
    <div id="editLamaranModal" class="hidden fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-lg w-full p-6 sm:p-7 shadow-2xl border border-slate-100">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-4">
                <h3 class="text-base font-extrabold text-slate-900">Perbarui Berkas Lamaran</h3>
                <button type="button" onclick="closeEditModal()" class="text-slate-400 hover:text-slate-600 text-lg leading-none">&times;</button>
            </div>

            <p class="text-xs text-slate-500 mb-4">
                Ubah surat lamaran untuk posisi <span id="editModalJobTitle" class="font-bold text-slate-800">-</span>.
            </p>

            <form id="editLamaranForm" onsubmit="handleEditSubmit(event)" class="space-y-4">
                <input type="hidden" id="editLamaranId" value="">
                <div>
                    <label for="editSuratLamaran" class="block text-xs font-bold text-slate-700 mb-1">
                        Surat Lamaran / Catatan untuk Mitra
                    </label>
                    <textarea id="editSuratLamaran" rows="5"
                              placeholder="Tuliskan motivasi dan ringkasan kualifikasi Anda..."
                              class="w-full p-3 bg-slate-50 border border-slate-200 rounded-2xl text-xs sm:text-sm text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 transition"></textarea>
                </div>

                <label class="flex items-center gap-2 text-xs text-slate-600 cursor-pointer">
                    <input type="checkbox" id="editPerbaruiCv" class="rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                    <span>Perbarui lampiran CV menggunakan CV terbaru dari profil saya</span>
                </label>

                <div class="flex items-center justify-end gap-2 pt-2">
                    <button type="button" onclick="closeEditModal()" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition">
                        Batal
                    </button>
                    <button type="submit" id="btnSubmitEdit" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl transition">
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

    <script>
        let allLamaran = [];
        let activeStatusFilter = '';

        document.addEventListener('DOMContentLoaded', () => {
            loadRiwayatLamaran();
        });

        async function loadRiwayatLamaran(status = '') {
            activeStatusFilter = status;
            const container = document.getElementById('lamaranListContainer');
            container.innerHTML = `
                <div class="bg-white rounded-2xl border border-slate-200/80 p-12 text-center">
                    <div class="spinner mx-auto mb-3"></div>
                    <p class="text-xs text-slate-500 font-medium">Memuat riwayat lamaran Anda...</p>
                </div>
            `;

            try {
                const params = status ? { status } : {};
                const response = await axios.get('/api/mahasiswa/lamaran', { params });

                if (response.data && response.data.success) {
                    const list = response.data.data || [];
                    if (!status) {
                        allLamaran = list;
                        updateStats(allLamaran);
                    }
                    renderLamaranList(list);
                }
            } catch (error) {
                console.error('Error loading lamaran:', error);
                container.innerHTML = `
                    <div class="bg-white rounded-2xl border border-red-200 p-8 text-center text-xs text-red-600">
                        Gagal memuat riwayat lamaran. Silakan muat ulang halaman.
                    </div>
                `;
            }
        }

        function updateStats(items) {
            const aktif = items.filter(i => i.status_lamaran !== 'Dibatalkan');
            const magang = aktif.filter(i => i.lowongan?.jenis_pekerjaan === 'magang');
            const proses = aktif.filter(i => ['Terkirim', 'Ditinjau'].includes(i.status_lamaran));
            const diterima = aktif.filter(i => i.status_lamaran === 'Diterima');

            document.getElementById('stat-total').innerText = aktif.length;
            document.getElementById('stat-magang').innerText = magang.length;
            document.getElementById('stat-proses').innerText = proses.length;
            document.getElementById('stat-diterima').innerText = diterima.length;
        }

        function filterByStatus(status) {
            document.querySelectorAll('.status-tab').forEach(btn => {
                if (btn.dataset.status === status) {
                    btn.className = 'status-tab px-3.5 py-1.5 rounded-xl text-xs font-bold bg-blue-600 text-white transition';
                } else {
                    btn.className = 'status-tab px-3.5 py-1.5 rounded-xl text-xs font-semibold bg-slate-100 text-slate-600 hover:bg-slate-200 transition';
                }
            });
            loadRiwayatLamaran(status);
        }

        function renderLamaranList(items) {
            const container = document.getElementById('lamaranListContainer');

            if (items.length === 0) {
                container.innerHTML = `
                    <div class="bg-white rounded-2xl border border-slate-200/80 p-12 text-center">
                        <h3 class="text-base font-bold text-slate-800 mb-1">Belum Ada Riwayat Lamaran</h3>
                        <p class="text-xs text-slate-500 max-w-md mx-auto mb-5">
                            Anda belum mengirimkan lamaran pada kategori status ini. Mulai jelajahi peluang magang dan kerja yang tersedia.
                        </p>
                        <a href="{{ route('mahasiswa.lowongan') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl transition">
                            Cari Lowongan Sekarang &rarr;
                        </a>
                    </div>
                `;
                return;
            }

            container.innerHTML = items.map(item => {
                const lowongan = item.lowongan || {};
                const penyedia = lowongan.penyedia || {};
                const statusBadge = getStatusBadge(item.status_lamaran);
                const tanggal = item.created_at
                    ? new Date(item.created_at).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' })
                    : '-';
                const bisaUbah = ['Terkirim', 'Ditinjau'].includes(item.status_lamaran);

                return `
                    <div class="bg-white rounded-2xl border border-slate-200/80 p-6 shadow-xs hover:border-blue-300 transition">
                        <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
                            <div class="space-y-1.5">
                                <div class="flex flex-wrap items-center gap-2">
                                    <span class="text-xs font-bold text-blue-600">${penyedia.nama_organisasi || 'Mitra KerjaKita'}</span>
                                    <span class="text-slate-300">&bull;</span>
                                    <span class="text-xs text-slate-500">Dikirim ${tanggal}</span>
                                </div>
                                <h3 class="text-lg font-extrabold text-slate-900">${lowongan.judul_posisi || 'Posisi Lowongan'}</h3>
                                <div class="flex flex-wrap items-center gap-2 text-xs text-slate-500 pt-1">
                                    <span class="px-2.5 py-0.5 rounded-lg bg-slate-100 text-slate-700 font-semibold capitalize">
                                        ${(lowongan.jenis_pekerjaan || '-').replace('_', ' ')}
                                    </span>
                                    <span class="px-2.5 py-0.5 rounded-lg bg-slate-100 text-slate-700 font-semibold uppercase">
                                        ${lowongan.tipe_lokasi || '-'}
                                    </span>
                                    <span>${lowongan.lokasi_kota || 'Nasional'}</span>
                                </div>
                            </div>

                            <div class="flex flex-col md:items-end gap-3">
                                ${statusBadge}

                                <div class="flex flex-wrap items-center gap-2">
                                    ${item.cv_snapshot_path ? `
                                        <a href="/storage/${item.cv_snapshot_path}" target="_blank"
                                           class="px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition">
                                            Lihat CV Terlampir
                                        </a>
                                    ` : ''}

                                    ${bisaUbah ? `
                                        <button type="button" onclick="openEditModal(${item.id})"
                                                class="px-3 py-1.5 rounded-xl bg-blue-50 hover:bg-blue-100 text-blue-700 text-xs font-bold transition">
                                            Edit Surat
                                        </button>
                                        <button type="button" onclick="handleCancelLamaran(${item.id})"
                                                class="px-3 py-1.5 rounded-xl bg-red-50 hover:bg-red-600 text-red-600 hover:text-white text-xs font-bold transition">
                                            Batalkan
                                        </button>
                                    ` : ''}
                                </div>
                            </div>
                        </div>

                        ${item.surat_lamaran ? `
                            <div class="mt-4 pt-4 border-t border-slate-100">
                                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-1">Surat Lamaran / Catatan Anda</p>
                                <p class="text-xs text-slate-600 whitespace-pre-line leading-relaxed">${escapeHtml(item.surat_lamaran)}</p>
                            </div>
                        ` : ''}
                    </div>
                `;
            }).join('');
        }

        function getStatusBadge(status) {
            const styles = {
                'Terkirim': 'bg-blue-50 text-blue-700 border-blue-200',
                'Ditinjau': 'bg-amber-50 text-amber-700 border-amber-200',
                'Diterima': 'bg-emerald-50 text-emerald-700 border-emerald-200',
                'Ditolak': 'bg-red-50 text-red-700 border-red-200',
                'Dibatalkan': 'bg-slate-100 text-slate-500 border-slate-200',
            };
            const cls = styles[status] || 'bg-slate-100 text-slate-700 border-slate-200';
            return `<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold border ${cls}">${status}</span>`;
        }

        function escapeHtml(text) {
            const div = document.createElement('div');
            div.innerText = text;
            return div.innerHTML;
        }

        function openEditModal(id) {
            const item = allLamaran.find(l => l.id === id);
            if (!item) return;

            document.getElementById('editLamaranId').value = item.id;
            document.getElementById('editModalJobTitle').innerText = item.lowongan?.judul_posisi || '-';
            document.getElementById('editSuratLamaran').value = item.surat_lamaran || '';
            document.getElementById('editPerbaruiCv').checked = false;

            document.getElementById('editLamaranModal').classList.remove('hidden');
        }

        function closeEditModal() {
            document.getElementById('editLamaranModal').classList.add('hidden');
        }

        async function handleEditSubmit(event) {
            event.preventDefault();
            const id = document.getElementById('editLamaranId').value;
            const surat = document.getElementById('editSuratLamaran').value.trim();
            const perbaruiCv = document.getElementById('editPerbaruiCv').checked;

            try {
                const response = await axios.put(`/api/mahasiswa/lamaran/${id}`, {
                    surat_lamaran: surat || null,
                    perbarui_cv: perbaruiCv,
                });

                if (response.data && response.data.success) {
                    closeEditModal();
                    showToast('Berhasil Diperbarui', response.data.message || 'Berkas lamaran berhasil diperbarui.');
                    loadRiwayatLamaran(activeStatusFilter);
                }
            } catch (error) {
                const msg = error.response?.data?.message || 'Gagal memperbarui berkas lamaran.';
                showToast('Gagal Memperbarui', msg, 'error');
            }
        }

        async function handleCancelLamaran(id) {
            if (!confirm('Apakah Anda yakin ingin membatalkan lamaran ini?')) return;

            try {
                const response = await axios.delete(`/api/mahasiswa/lamaran/${id}`);
                if (response.data && response.data.success) {
                    showToast('Lamaran Dibatalkan', response.data.message || 'Lamaran berhasil dibatalkan.');
                    loadRiwayatLamaran(activeStatusFilter);
                }
            } catch (error) {
                const msg = error.response?.data?.message || 'Gagal membatalkan lamaran.';
                showToast('Gagal', msg, 'error');
            }
        }

        function showToast(title, message, type = 'success') {
            const toast = document.getElementById('toastNotification');
            const icon = document.getElementById('toastIcon');
            document.getElementById('toastTitle').innerText = title;
            document.getElementById('toastMessage').innerText = message;

            if (type === 'error') {
                icon.className = 'w-8 h-8 rounded-xl bg-red-100 text-red-600 flex items-center justify-center shrink-0 font-bold';
                icon.innerText = '✕';
            } else {
                icon.className = 'w-8 h-8 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0 font-bold';
                icon.innerText = '✓';
            }

            toast.classList.remove('hidden');
            setTimeout(() => hideToast(), 4000);
        }

        function hideToast() {
            document.getElementById('toastNotification').classList.add('hidden');
        }
    </script>
</body>
</html>