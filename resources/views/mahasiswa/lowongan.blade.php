<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Eksplorasi & Pencarian Lowongan - KerjaKita</title>
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
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f5f9;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }
    </style>
</head>
<body class="bg-[#f8fafc] min-h-screen flex flex-col font-sans text-slate-800 antialiased">

    <!-- Inject Bearer Token & User Data -->
    <script>
        window.AUTH_TOKEN = "{{ $apiToken ?? '' }}";
        if (window.AUTH_TOKEN) {
            localStorage.setItem('auth_token', window.AUTH_TOKEN);
        }
        axios.defaults.headers.common['Authorization'] = `Bearer ${window.AUTH_TOKEN}`;
        axios.defaults.headers.common['Accept'] = 'application/json';
        axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    </script>

    <!-- Top Navigation Bar -->
    <header class="bg-white border-b border-slate-200 sticky top-0 z-40 shadow-xs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <div class="flex items-center gap-8">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5 group">
                        <div class="w-9 h-9 rounded-xl bg-blue-600 text-white font-extrabold flex items-center justify-center shadow-md shadow-blue-500/20 group-hover:bg-blue-700 transition">
                            KK
                        </div>
                        <span class="font-extrabold text-xl tracking-tight text-slate-900">KerjaKita</span>
                    </a>

                    <nav class="hidden md:flex items-center gap-1">
                        <a href="{{ route('mahasiswa.lowongan') }}" class="px-3 py-2 text-sm font-bold text-blue-600 border-b-2 border-blue-600 transition flex items-center gap-1.5">
                            Cari Lowongan
                        </a>
                        <a href="{{ route('mahasiswa.lowongan_tersimpan') }}" class="px-3 py-2 text-sm font-semibold text-slate-600 hover:text-blue-600 transition flex items-center gap-1.5">
                            Lowongan Tersimpan
                        </a>
                        <a href="{{ route('mahasiswa.lamaran') }}" class="px-3 py-2 text-sm font-semibold text-slate-600 hover:text-blue-600 transition flex items-center gap-1.5">
                            Riwayat Lamaran
                        </a>
                        <a href="{{ route('mahasiswa.profil') }}" class="px-3 py-2 text-sm font-semibold text-slate-600 hover:text-blue-600 transition">
                            Profil Mahasiswa
                        </a>
                    </nav>
                </div>

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
        <a href="{{ route('mahasiswa.lowongan') }}" class="text-blue-600 font-bold border-b-2 border-blue-600 pb-1">Cari Lowongan</a>
        <a href="{{ route('mahasiswa.lowongan_tersimpan') }}" class="text-slate-600">Tersimpan</a>
        <a href="{{ route('mahasiswa.lamaran') }}" class="text-slate-600">Lamaran</a>
        <a href="{{ route('mahasiswa.profil') }}" class="text-slate-600">Profil</a>
    </div>

    <!-- Main Container -->
    <main class="max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-6 flex-1 flex flex-col">

        <!-- Notification Toast Bar -->
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

        <!-- SECTION 1: SEARCH & FILTER HEADER -->
        <div class="bg-white rounded-3xl border border-slate-200/80 p-5 sm:p-6 shadow-sm mb-6">
            <form id="searchFilterForm" onsubmit="handleSearchSubmit(event)" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-12 gap-3 items-center">
                    <div class="md:col-span-6 relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <input type="text" id="searchInput" placeholder="Cari posisi kerja, kata kunci, atau nama perusahaan..." 
                               class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-2xl text-xs sm:text-sm text-slate-800 placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 transition">
                    </div>

                    <div class="md:col-span-4 relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <input type="text" id="lokasiInput" placeholder="Semua Kota (cth: Jakarta, Bandung)" 
                               class="w-full pl-9 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-2xl text-xs sm:text-sm text-slate-800 placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 transition">
                    </div>

                    <div class="md:col-span-2">
                        <button type="submit" class="w-full py-2.5 px-4 bg-blue-600 hover:bg-blue-700 text-white text-xs sm:text-sm font-bold rounded-2xl shadow-sm hover:shadow transition flex items-center justify-center gap-2">
                            <span>Cari Peluang</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </button>
                    </div>
                </div>

                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Bidang Studi:</span>
                        <button type="button" onclick="resetFilters()" class="text-[11px] font-semibold text-blue-600 hover:text-blue-800">Reset Semua Filter</button>
                    </div>
                    <div id="categoryPillsContainer" class="flex items-center gap-2 overflow-x-auto pb-1 custom-scrollbar">
                        <button type="button" onclick="setCategoryFilter('semua')" data-slug="semua" class="category-pill active shrink-0 px-3.5 py-1.5 rounded-full text-xs font-semibold bg-blue-600 text-white transition shadow-xs">
                            Semua Bidang
                        </button>
                    </div>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 pt-3 border-t border-slate-100 text-xs">
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Tipe Peluang</label>
                        <select id="jenisPekerjaanFilter" onchange="applyFilters()" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-2.5 py-1.5 text-xs text-slate-700 focus:outline-none focus:ring-1 focus:ring-blue-500">
                            <option value="semua">Semua Tipe</option>
                            <option value="magang">Magang (Internship)</option>
                            <option value="freelance">Freelance</option>
                            <option value="paruh_waktu">Paruh Waktu (Part-time)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Mode Kerja</label>
                        <select id="tipeLokasiFilter" onchange="applyFilters()" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-2.5 py-1.5 text-xs text-slate-700 focus:outline-none focus:ring-1 focus:ring-blue-500">
                            <option value="semua">Semua Mode</option>
                            <option value="hybrid">Hybrid</option>
                            <option value="remote">Remote (Online)</option>
                            <option value="onsite">Onsite (Di Kantor)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Komitmen Waktu</label>
                        <input type="text" id="waktuKerjaFilter" placeholder="cth: Fleksibel, 20 jam" oninput="applyFiltersDebounced()" 
                               class="w-full bg-slate-50 border border-slate-200 rounded-xl px-2.5 py-1.5 text-xs text-slate-700 focus:outline-none focus:ring-1 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Aksi Cepat</label>
                        <a href="{{ route('mahasiswa.lowongan_tersimpan') }}" class="inline-flex w-full items-center justify-center gap-1.5 px-3 py-1.5 bg-blue-50 hover:bg-blue-100 text-blue-700 rounded-xl font-bold transition">
                            <svg class="w-3.5 h-3.5 text-blue-600" fill="currentColor" viewBox="0 0 20 20"><path d="M5 4a2 2 0 012-2h6a2 2 0 012 2v14l-5-2.5L5 18V4z"/></svg>
                            <span>Lihat Tersimpan</span>
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <!-- SECTION 2: TWO-PANEL SPLIT VIEW -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start flex-1">
            <!-- PANEL KIRI -->
            <div class="lg:col-span-5 flex flex-col space-y-3">
                <div class="flex items-center justify-between px-1">
                    <div>
                        <h2 class="text-sm font-extrabold text-slate-900 tracking-tight">Peluang Karir Terbuka</h2>
                        <p id="totalCountText" class="text-[11px] text-slate-500 font-medium">Memuat lowongan...</p>
                    </div>
                    <span class="text-[10px] font-semibold text-slate-500 bg-slate-100 px-2 py-0.5 rounded-lg border border-slate-200">
                        Urutan: Terbaru
                    </span>
                </div>

                <div id="jobCardsContainer" class="space-y-3">
                    <div class="animate-pulse space-y-3">
                        <div class="bg-white rounded-2xl p-4 border border-slate-200 h-32"></div>
                        <div class="bg-white rounded-2xl p-4 border border-slate-200 h-32"></div>
                    </div>
                </div>

                <div id="paginationContainer" class="hidden flex items-center justify-between pt-3 border-t border-slate-200 px-1 text-xs">
                    <span id="paginationInfo" class="text-slate-500 text-[11px]">Halaman 1 dari 1</span>
                    <div class="flex items-center gap-1.5">
                        <button id="btnPrevPage" onclick="changePage(-1)" class="px-2.5 py-1 rounded-lg border border-slate-200 bg-white hover:bg-slate-50 disabled:opacity-40 disabled:pointer-events-none font-semibold text-slate-700 transition">
                            &larr; Prev
                        </button>
                        <button id="btnNextPage" onclick="changePage(1)" class="px-2.5 py-1 rounded-lg border border-slate-200 bg-white hover:bg-slate-50 disabled:opacity-40 disabled:pointer-events-none font-semibold text-slate-700 transition">
                            Next &rarr;
                        </button>
                    </div>
                </div>
            </div>

            <!-- PANEL KANAN -->
            <div class="lg:col-span-7 sticky top-24">
                <div id="detailPanelContainer" class="bg-white rounded-3xl border border-slate-200/80 p-6 sm:p-8 shadow-sm">
                    <div id="detailLoading" class="py-20 text-center">
                        <div class="spinner mx-auto mb-3"></div>
                        <p class="text-xs text-slate-500 font-medium">Memuat rincian pekerjaan...</p>
                    </div>

                    <div id="detailContent" class="hidden">
                        <div class="flex flex-wrap items-center justify-between gap-2 pb-4 border-b border-slate-100">
                            <div class="flex items-center gap-3">
                                <div id="detailAvatar" class="w-12 h-12 rounded-2xl bg-blue-600 text-white font-black flex items-center justify-center text-lg shadow-sm">
                                    TM
                                </div>
                                <div>
                                    <div class="flex items-center gap-1.5">
                                        <h3 id="detailCompanyName" class="text-sm font-bold text-slate-900">-</h3>
                                        <svg class="w-4 h-4 text-blue-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                    </div>
                                    <p id="detailCompanyIndustry" class="text-[11px] text-slate-500">-</p>
                                </div>
                            </div>

                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                Pendaftaran Aktif
                            </span>
                        </div>

                        <div class="mt-4">
                            <h2 id="detailJobTitle" class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">-</h2>
                            <p id="detailLocationHeader" class="text-xs text-slate-500 mt-1 flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                <span id="detailCityText">-</span> &bull; <span id="detailModeText">-</span>
                            </p>
                        </div>

                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5 mt-5 p-3.5 bg-slate-50 rounded-2xl border border-slate-100 text-xs">
                            <div class="bg-white p-2.5 rounded-xl border border-slate-200/60">
                                <p class="text-[10px] font-bold text-slate-400 uppercase">Tipe Peluang</p>
                                <p id="gridTipePekerjaan" class="font-bold text-slate-800 capitalize mt-0.5">-</p>
                            </div>
                            <div class="bg-white p-2.5 rounded-xl border border-slate-200/60">
                                <p class="text-[10px] font-bold text-slate-400 uppercase">Jam Kerja</p>
                                <p id="gridJamKerja" class="font-bold text-slate-800 mt-0.5">-</p>
                            </div>
                            <div class="bg-white p-2.5 rounded-xl border border-slate-200/60">
                                <p class="text-[10px] font-bold text-slate-400 uppercase">Kompensasi / Saku</p>
                                <p id="gridKompensasi" class="font-bold text-blue-600 mt-0.5">-</p>
                            </div>
                            <div class="bg-white p-2.5 rounded-xl border border-slate-200/60">
                                <p class="text-[10px] font-bold text-slate-400 uppercase">Kategori Bidang</p>
                                <p id="gridKategori" class="font-bold text-slate-800 mt-0.5 truncate">-</p>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex items-center gap-3 mt-6 pb-6 border-b border-slate-100">
                            <button type="button" id="btnApplyNow" onclick="handleApplyClick()" class="flex-1 py-3 px-5 bg-blue-600 hover:bg-blue-700 text-white text-xs sm:text-sm font-bold rounded-2xl shadow-sm hover:shadow transition flex items-center justify-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                <span id="btnApplyNowText">Ajukan Lamaran Sekarang</span>
                            </button>

                            <button type="button" id="btnToggleSaveDetail" onclick="openSaveModalForCurrent()" class="px-4 py-3 bg-slate-100 hover:bg-blue-50 text-slate-700 hover:text-blue-700 rounded-2xl font-bold text-xs sm:text-sm border border-slate-200 transition flex items-center gap-2">
                                <svg id="detailBookmarkIcon" class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/></svg>
                                <span id="detailBookmarkText">Catatan Pribadi</span>
                            </button>

                            <button type="button" onclick="shareCurrentJob()" class="p-3 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-2xl border border-slate-200 transition" title="Bagikan Lowongan">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/></svg>
                            </button>
                        </div>

                        <div id="savedNoteBanner" class="hidden mt-4 p-4 rounded-2xl bg-blue-50/70 border border-blue-200 text-xs">
                            <div class="flex items-center justify-between font-bold text-blue-900 mb-1">
                                <span class="flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5 text-blue-600" fill="currentColor" viewBox="0 0 20 20"><path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z"/><path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd"/></svg>
                                    CATATAN PRIBADI MAHASISWA
                                </span>
                                <button onclick="openSaveModalForCurrent()" class="text-blue-600 hover:underline text-[11px]">Edit Catatan</button>
                            </div>
                            <p id="savedNoteBannerText" class="text-slate-700 leading-relaxed"></p>
                        </div>

                        <div class="mt-6">
                            <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wider flex items-center gap-1.5 mb-2">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                Deskripsi Program & Tanggung Jawab
                            </h4>
                            <div id="detailDescription" class="text-xs sm:text-sm text-slate-600 leading-relaxed whitespace-pre-line space-y-2">
                            </div>
                        </div>

                        <div class="mt-6 pt-6 border-t border-slate-100">
                            <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wider flex items-center gap-1.5 mb-2">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Persyaratan & Kualifikasi Mahasiswa
                            </h4>
                            <div id="detailRequirements" class="text-xs sm:text-sm text-slate-600 leading-relaxed whitespace-pre-line">
                            </div>
                        </div>

                        <div class="mt-6 pt-6 border-t border-slate-100">
                            <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wider flex items-center gap-1.5 mb-2">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                Informasi Mitra Rekruter
                            </h4>
                            <div class="p-3.5 bg-slate-50 rounded-2xl border border-slate-200/60 text-xs text-slate-600 space-y-1.5">
                                <p><span class="font-bold text-slate-800">Nama Perusahaan:</span> <span id="companyAboutName"></span></p>
                                <p><span class="font-bold text-slate-800">Alamat Kantor:</span> <span id="companyAboutAddress"></span></p>
                                <p><span class="font-bold text-slate-800">Website Resmi:</span> <a id="companyAboutWebsite" href="#" target="_blank" class="text-blue-600 hover:underline"></a></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- MODAL: SIMPAN / UBAH CATATAN PRIBADI (F-15) -->
    <div id="saveNoteModal" class="hidden fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-lg w-full p-6 sm:p-7 shadow-2xl border border-slate-100">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-4">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M5 4a2 2 0 012-2h6a2 2 0 012 2v14l-5-2.5L5 18V4z"/></svg>
                    </div>
                    <h3 class="text-base font-extrabold text-slate-900" id="modalNoteTitle">Simpan Lowongan & Catatan Pribadi</h3>
                </div>
                <button onclick="closeSaveModal()" class="text-slate-400 hover:text-slate-600 text-lg leading-none">&times;</button>
            </div>

            <p class="text-xs text-slate-500 mb-3">
                Simpan posisi <span id="modalJobTitle" class="font-bold text-slate-800"></span> ke arsip karir Anda.
            </p>

            <form id="saveNoteForm" onsubmit="handleSaveNoteSubmit(event)" class="space-y-4">
                <input type="hidden" id="modalJobId" value="">
                <div>
                    <label for="modalCatatanPribadi" class="block text-xs font-bold text-slate-700 mb-1">
                        Catatan Pribadi Mahasiswa (Opsional)
                    </label>
                    <textarea id="modalCatatanPribadi" rows="4" 
                              placeholder="Contoh: Perlu update CV format ATS..." 
                              class="w-full p-3 bg-slate-50 border border-slate-200 rounded-2xl text-xs sm:text-sm text-slate-800 placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 transition"></textarea>
                </div>

                <div class="flex items-center justify-between pt-2">
                    <button type="button" id="btnDeleteSimpananModal" onclick="handleUnsaveFromModal()" class="hidden px-3.5 py-2 text-xs font-bold text-red-600 hover:bg-red-50 rounded-xl transition">
                        Hapus Simpanan
                    </button>
                    <div class="flex items-center gap-2 ml-auto">
                        <button type="button" onclick="closeSaveModal()" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition">
                            Batal
                        </button>
                        <button type="submit" id="btnSubmitSaveModal" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl shadow-xs transition flex items-center gap-1.5">
                            <span id="btnSubmitSaveText">Simpan ke Arsip</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL: AJUKAN LAMARAN (M6: F-16) -->
    <div id="applyJobModal" class="hidden fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-lg w-full p-6 sm:p-7 shadow-2xl border border-slate-100">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-4">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </div>
                    <h3 class="text-base font-extrabold text-slate-900">Ajukan Lamaran Pekerjaan</h3>
                </div>
                <button type="button" onclick="closeApplyModal()" class="text-slate-400 hover:text-slate-600 text-lg leading-none">&times;</button>
            </div>

            <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-200/70 mb-4 text-xs">
                <p class="font-extrabold text-slate-900" id="applyModalJobTitle">-</p>
                <p class="text-slate-500 mt-0.5" id="applyModalCompanyName">-</p>
            </div>

            <form id="applyJobForm" onsubmit="handleApplySubmit(event)" class="space-y-4">
                <input type="hidden" id="applyModalJobId" value="">
                <div>
                    <label for="applySuratLamaran" class="block text-xs font-bold text-slate-700 mb-1">
                        Surat Lamaran / Catatan Singkat untuk Mitra
                    </label>
                    <textarea id="applySuratLamaran" rows="5"
                              placeholder="Perkenalkan diri Anda secara singkat, jelaskan ketertarikan pada posisi ini, dan relevansi keahlian Anda..."
                              class="w-full p-3 bg-slate-50 border border-slate-200 rounded-2xl text-xs sm:text-sm text-slate-800 placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 transition"></textarea>
                    <p class="text-[10px] text-slate-400 mt-1">CV PDF aktif di profil Anda akan otomatis dilampirkan sebagai snapshot saat lamaran dikirim.</p>
                </div>

                <div class="flex items-center justify-end gap-2 pt-2">
                    <button type="button" onclick="closeApplyModal()" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition">
                        Batal
                    </button>
                    <button type="submit" id="btnSubmitApplyModal" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl shadow-xs transition flex items-center gap-1.5">
                        <span id="btnSubmitApplyText">Kirim Lamaran Sekarang</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-200 mt-12 py-6 text-center text-xs text-slate-500">
        <p>&copy; 2026 KerjaKita Indonesia. Platform Terdaftar Resmi Kemendikbudristek & Forum Rektor Indonesia.</p>
    </footer>

    <!-- JAVASCRIPT LOGIC -->
    <script>
        let currentJobs = [];
        let selectedJob = null;
        let selectedCategorySlug = 'semua';
        let currentPage = 1;
        let lastPage = 1;
        let searchTimeout = null;

        document.addEventListener('DOMContentLoaded', () => {
            loadCategories();
            loadJobs(1);
        });

        async function loadCategories() {
            try {
                const response = await axios.get('/api/mahasiswa/lowongan/categories');
                if (response.data && response.data.success) {
                    renderCategoryPills(response.data.data);
                }
            } catch (error) {
                console.error('Gagal memuat kategori:', error);
            }
        }

        function renderCategoryPills(categories) {
            const container = document.getElementById('categoryPillsContainer');
            container.innerHTML = `
                <button type="button" onclick="setCategoryFilter('semua')" data-slug="semua" class="category-pill ${selectedCategorySlug === 'semua' ? 'bg-blue-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'} shrink-0 px-3.5 py-1.5 rounded-full text-xs font-semibold transition shadow-xs">
                    Semua Bidang
                </button>
            `;

            categories.forEach(cat => {
                const isActive = selectedCategorySlug === cat.slug;
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.onclick = () => setCategoryFilter(cat.slug);
                btn.dataset.slug = cat.slug;
                btn.className = `category-pill ${isActive ? 'bg-blue-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'} shrink-0 px-3.5 py-1.5 rounded-full text-xs font-semibold transition shadow-xs flex items-center gap-1.5`;
                btn.innerHTML = `<span>${cat.nama_kategori}</span> <span class="text-[10px] opacity-75 font-normal">(${cat.lowongan_count || 0})</span>`;
                container.appendChild(btn);
            });
        }

        function setCategoryFilter(slug) {
            selectedCategorySlug = slug;
            document.querySelectorAll('.category-pill').forEach(btn => {
                if (btn.dataset.slug === slug) {
                    btn.className = 'category-pill bg-blue-600 text-white shrink-0 px-3.5 py-1.5 rounded-full text-xs font-semibold transition shadow-xs flex items-center gap-1.5';
                } else {
                    btn.className = 'category-pill bg-slate-100 text-slate-600 hover:bg-slate-200 shrink-0 px-3.5 py-1.5 rounded-full text-xs font-semibold transition shadow-xs flex items-center gap-1.5';
                }
            });
            loadJobs(1);
        }

        async function loadJobs(page = 1) {
            currentPage = page;
            const container = document.getElementById('jobCardsContainer');
            container.innerHTML = `
                <div class="py-12 text-center text-slate-400">
                    <div class="spinner mx-auto mb-2"></div>
                    <p class="text-xs">Mencari lowongan yang cocok...</p>
                </div>
            `;

            const keyword = document.getElementById('searchInput').value.trim();
            const lokasi = document.getElementById('lokasiInput').value.trim();
            const jenisPekerjaan = document.getElementById('jenisPekerjaanFilter').value;
            const tipeLokasi = document.getElementById('tipeLokasiFilter').value;
            const waktuKerja = document.getElementById('waktuKerjaFilter').value.trim();

            const params = {
                page: page,
                per_page: 6,
                keyword: keyword || undefined,
                lokasi_kota: lokasi || undefined,
                kategori_slug: selectedCategorySlug !== 'semua' ? selectedCategorySlug : undefined,
                jenis_pekerjaan: jenisPekerjaan !== 'semua' ? jenisPekerjaan : undefined,
                tipe_lokasi: tipeLokasi !== 'semua' ? tipeLokasi : undefined,
                waktu_kerja: waktuKerja || undefined,
            };

            try {
                const response = await axios.get('/api/mahasiswa/lowongan', { params });
                if (response.data && response.data.success) {
                    currentJobs = response.data.data;
                    const meta = response.data.meta;
                    lastPage = meta.last_page;
                    currentPage = meta.current_page;

                    document.getElementById('totalCountText').innerText = `Menampilkan ${meta.total} lowongan aktif untuk mahasiswa`;
                    renderJobList(currentJobs);
                    renderPagination(meta);

                    if (currentJobs.length > 0) {
                        selectJob(currentJobs[0].id);
                    } else {
                        renderEmptyDetail();
                    }
                }
            } catch (error) {
                console.error('Error fetching jobs:', error);
                container.innerHTML = `
                    <div class="p-8 text-center bg-white rounded-2xl border border-red-200 text-xs text-red-600">
                        Gagal memuat lowongan. Silakan muat ulang halaman.
                    </div>
                `;
            }
        }

        function renderJobList(jobs) {
            const container = document.getElementById('jobCardsContainer');
            if (jobs.length === 0) {
                container.innerHTML = `
                    <div class="bg-white rounded-3xl p-8 text-center border border-slate-200 shadow-xs">
                        <h4 class="text-sm font-bold text-slate-800">Tidak ada lowongan yang sesuai</h4>
                        <p class="text-xs text-slate-500 mt-1">Coba sesuaikan kata kunci pencarian atau reset filter untuk melihat opsi lain.</p>
                        <button onclick="resetFilters()" class="mt-4 px-4 py-1.5 bg-blue-50 text-blue-600 hover:bg-blue-100 text-xs font-bold rounded-xl transition">
                            Reset Semua Filter
                        </button>
                    </div>
                `;
                return;
            }

            container.innerHTML = '';
            jobs.forEach(job => {
                const isSelected = selectedJob && selectedJob.id === job.id;
                const card = document.createElement('div');
                card.id = `job-card-${job.id}`;
                card.className = `p-4 sm:p-5 rounded-3xl border transition-all cursor-pointer relative bg-white hover:border-blue-400 hover:shadow-md ${isSelected ? 'border-blue-600 ring-2 ring-blue-500/10 shadow-sm' : 'border-slate-200'}`;
                card.onclick = () => selectJob(job.id);

                const colors = ['bg-blue-600', 'bg-indigo-600', 'bg-emerald-600', 'bg-amber-600', 'bg-purple-600'];
                const avatarColor = colors[job.id % colors.length];
                const initials = getInitials(job.penyedia?.nama_organisasi || 'KK');
                const jenisBadge = formatJenisBadge(job.jenis_pekerjaan);

                card.innerHTML = `
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-2xl ${avatarColor} text-white font-extrabold flex items-center justify-center text-xs shrink-0 shadow-xs">
                                ${initials}
                            </div>
                            <div>
                                <div class="flex items-center gap-1">
                                    <span class="text-xs font-bold text-slate-700">${job.penyedia?.nama_organisasi || 'Mitra KerjaKita'}</span>
                                    <svg class="w-3.5 h-3.5 text-blue-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                </div>
                                <h3 class="text-sm font-black text-slate-900 group-hover:text-blue-600 transition leading-snug">${job.judul_posisi}</h3>
                            </div>
                        </div>

                        <button type="button" onclick="event.stopPropagation(); toggleDirectSave(${job.id})" 
                                class="p-2 rounded-xl text-slate-400 hover:text-blue-600 hover:bg-blue-50 transition" title="${job.is_saved ? 'Tersimpan (Klik untuk opsi)' : 'Simpan lowongan'}">
                            <svg class="w-5 h-5 ${job.is_saved ? 'text-blue-600 fill-blue-600' : 'fill-none'}" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/>
                            </svg>
                        </button>
                    </div>

                    <div class="flex flex-wrap items-center gap-1.5 mt-3">
                        ${jenisBadge}
                        <span class="px-2.5 py-0.5 rounded-lg text-[10px] font-semibold bg-slate-100 text-slate-700 uppercase">
                            ${job.tipe_lokasi}
                        </span>
                        <span class="px-2.5 py-0.5 rounded-lg text-[10px] font-semibold bg-emerald-50 text-emerald-700">
                            Konversi SKS Ready
                        </span>
                    </div>

                    <div class="flex items-center justify-between mt-3 pt-3 border-t border-slate-100 text-xs">
                        <span class="font-extrabold text-blue-600">${job.kompensasi || 'Sesuai Kesepakatan'}</span>
                        <span class="text-[10px] text-slate-400">Aktif &bull; ${job.lokasi_kota || 'Nasional'}</span>
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

        async function selectJob(id) {
            document.querySelectorAll('[id^="job-card-"]').forEach(el => {
                el.classList.remove('border-blue-600', 'ring-2', 'ring-blue-500/10');
                el.classList.add('border-slate-200');
            });
            const activeCard = document.getElementById(`job-card-${id}`);
            if (activeCard) {
                activeCard.classList.remove('border-slate-200');
                activeCard.classList.add('border-blue-600', 'ring-2', 'ring-blue-500/10');
            }

            document.getElementById('detailLoading').classList.remove('hidden');
            document.getElementById('detailContent').classList.add('hidden');

            try {
                const response = await axios.get(`/api/mahasiswa/lowongan/${id}`);
                if (response.data && response.data.success) {
                    selectedJob = response.data.data;
                    renderDetailContent(selectedJob);
                }
            } catch (error) {
                console.error('Error fetching detail:', error);
            } finally {
                document.getElementById('detailLoading').classList.add('hidden');
                document.getElementById('detailContent').classList.remove('hidden');
            }
        }

        function renderDetailContent(job) {
            document.getElementById('detailCompanyName').innerText = job.penyedia?.nama_organisasi || 'Mitra KerjaKita';
            document.getElementById('detailCompanyIndustry').innerText = job.penyedia?.bidang_usaha || 'Perusahaan Mitra';
            document.getElementById('detailJobTitle').innerText = job.judul_posisi;
            document.getElementById('detailCityText').innerText = job.lokasi_kota || 'Indonesia';
            document.getElementById('detailModeText').innerText = (job.tipe_lokasi || 'hybrid').toUpperCase();
            document.getElementById('detailAvatar').innerText = getInitials(job.penyedia?.nama_organisasi || 'KK');

            document.getElementById('gridTipePekerjaan').innerText = job.jenis_pekerjaan?.replace('_', ' ') || '-';
            document.getElementById('gridJamKerja').innerText = job.waktu_kerja || 'Fleksibel';
            document.getElementById('gridKompensasi').innerText = job.kompensasi || 'Tersedia';
            document.getElementById('gridKategori').innerText = job.kategori?.nama_kategori || 'Umum';

            document.getElementById('detailDescription').innerText = job.deskripsi || 'Deskripsi belum tersedia.';
            document.getElementById('detailRequirements').innerText = job.persyaratan || 'Persyaratan terbuka untuk semua mahasiswa aktif.';

            document.getElementById('companyAboutName').innerText = job.penyedia?.nama_organisasi || '-';
            document.getElementById('companyAboutAddress').innerText = job.penyedia?.alamat || `${job.lokasi_kota || 'Indonesia'}`;
            const webLink = document.getElementById('companyAboutWebsite');
            if (job.penyedia?.website) {
                webLink.href = job.penyedia.website;
                webLink.innerText = job.penyedia.website;
                webLink.parentElement.classList.remove('hidden');
            } else {
                webLink.parentElement.classList.add('hidden');
            }

            updateDetailBookmarkState(job.is_saved, job.catatan_pribadi);
        }

        function renderEmptyDetail() {
            document.getElementById('detailLoading').classList.add('hidden');
            document.getElementById('detailContent').classList.remove('hidden');
            document.getElementById('detailContent').innerHTML = `
                <div class="py-16 text-center text-slate-400">
                    <p class="text-sm font-bold text-slate-700">Pilih salah satu lowongan untuk melihat rincian</p>
                </div>
            `;
        }

        function updateDetailBookmarkState(isSaved, note) {
            const btn = document.getElementById('btnToggleSaveDetail');
            const icon = document.getElementById('detailBookmarkIcon');
            const text = document.getElementById('detailBookmarkText');
            const banner = document.getElementById('savedNoteBanner');
            const bannerText = document.getElementById('savedNoteBannerText');

            if (isSaved) {
                icon.classList.add('text-blue-600', 'fill-blue-600');
                text.innerText = 'Tersimpan (Catatan)';
                btn.classList.add('bg-blue-50', 'text-blue-700', 'border-blue-200');

                if (note) {
                    banner.classList.remove('hidden');
                    bannerText.innerText = note;
                } else {
                    banner.classList.add('hidden');
                }
            } else {
                icon.classList.remove('text-blue-600', 'fill-blue-600');
                text.innerText = 'Catatan Pribadi';
                btn.classList.remove('bg-blue-50', 'text-blue-700', 'border-blue-200');
                banner.classList.add('hidden');
            }
        }

        function renderPagination(meta) {
            const container = document.getElementById('paginationContainer');
            if (meta.last_page <= 1) {
                container.classList.add('hidden');
                return;
            }
            container.classList.remove('hidden');
            document.getElementById('paginationInfo').innerText = `Halaman ${meta.current_page} dari ${meta.last_page}`;
            document.getElementById('btnPrevPage').disabled = meta.current_page <= 1;
            document.getElementById('btnNextPage').disabled = meta.current_page >= meta.last_page;
        }

        function changePage(direction) {
            const targetPage = currentPage + direction;
            if (targetPage >= 1 && targetPage <= lastPage) {
                loadJobs(targetPage);
            }
        }

        function handleSearchSubmit(event) {
            event.preventDefault();
            loadJobs(1);
        }

        function applyFilters() {
            loadJobs(1);
        }

        function applyFiltersDebounced() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                loadJobs(1);
            }, 400);
        }

        function resetFilters() {
            document.getElementById('searchInput').value = '';
            document.getElementById('lokasiInput').value = '';
            document.getElementById('jenisPekerjaanFilter').value = 'semua';
            document.getElementById('tipeLokasiFilter').value = 'semua';
            document.getElementById('waktuKerjaFilter').value = '';
            selectedCategorySlug = 'semua';
            setCategoryFilter('semua');
        }

        function toggleDirectSave(jobId) {
            const job = currentJobs.find(j => j.id === jobId);
            if (!job) return;
            if (job.is_saved) {
                openSaveModal(job);
            } else {
                doSaveJob(jobId, null);
            }
        }

        function openSaveModalForCurrent() {
            if (selectedJob) openSaveModal(selectedJob);
        }

        function openSaveModal(job) {
            document.getElementById('modalJobId').value = job.id;
            document.getElementById('modalJobTitle').innerText = job.judul_posisi;
            document.getElementById('modalCatatanPribadi').value = job.catatan_pribadi || '';

            const btnDelete = document.getElementById('btnDeleteSimpananModal');
            const submitText = document.getElementById('btnSubmitSaveText');
            const modalTitle = document.getElementById('modalNoteTitle');

            if (job.is_saved) {
                btnDelete.classList.remove('hidden');
                submitText.innerText = 'Perbarui Catatan';
                modalTitle.innerText = 'Kelola Catatan Pribadi Lowongan';
            } else {
                btnDelete.classList.add('hidden');
                submitText.innerText = 'Simpan ke Arsip';
                modalTitle.innerText = 'Simpan Lowongan & Catatan Pribadi';
            }

            document.getElementById('saveNoteModal').classList.remove('hidden');
        }

        function closeSaveModal() {
            document.getElementById('saveNoteModal').classList.add('hidden');
        }

        async function handleSaveNoteSubmit(event) {
            event.preventDefault();
            const jobId = document.getElementById('modalJobId').value;
            const catatan = document.getElementById('modalCatatanPribadi').value.trim();
            await doSaveJob(jobId, catatan);
            closeSaveModal();
        }

        async function doSaveJob(jobId, catatan) {
            try {
                const response = await axios.post('/api/mahasiswa/lowongan-tersimpan', {
                    lowongan_id: jobId,
                    catatan_pribadi: catatan || null
                });

                if (response.data && response.data.success) {
                    showToast('Berhasil Disimpan', 'Lowongan dan catatan preferensi Anda tersimpan di Arsip Karir.');
                    const job = currentJobs.find(j => j.id == jobId);
                    if (job) {
                        job.is_saved = true;
                        job.catatan_pribadi = catatan;
                    }
                    if (selectedJob && selectedJob.id == jobId) {
                        selectedJob.is_saved = true;
                        selectedJob.catatan_pribadi = catatan;
                        updateDetailBookmarkState(true, catatan);
                    }
                    renderJobList(currentJobs);
                }
            } catch (error) {
                console.error('Error saving job:', error);
                showToast('Galat Simpan', 'Gagal menyimpan lowongan.', 'error');
            }
        }

        async function handleUnsaveFromModal() {
            const jobId = document.getElementById('modalJobId').value;
            if (!confirm('Apakah Anda yakin ingin menghapus lowongan ini dari daftar tersimpan?')) return;

            try {
                const response = await axios.delete(`/api/mahasiswa/lowongan-tersimpan/${jobId}`);
                if (response.data && response.data.success) {
                    showToast('Dihapus dari Simpanan', 'Lowongan berhasil dihapus dari daftar tersimpan.');
                    closeSaveModal();

                    const job = currentJobs.find(j => j.id == jobId);
                    if (job) {
                        job.is_saved = false;
                        job.catatan_pribadi = null;
                    }
                    if (selectedJob && selectedJob.id == jobId) {
                        selectedJob.is_saved = false;
                        selectedJob.catatan_pribadi = null;
                        updateDetailBookmarkState(false, null);
                    }
                    renderJobList(currentJobs);
                }
            } catch (error) {
                console.error('Error deleting saved job:', error);
                showToast('Galat', 'Gagal menghapus lowongan tersimpan.', 'error');
            }
        }

        // ==========================================
        // FITUR AJUKAN LAMARAN (M6: F-16)
        // ==========================================
        function handleApplyClick() {
            if (!selectedJob) return;

            document.getElementById('applyModalJobId').value = selectedJob.id;
            document.getElementById('applyModalJobTitle').innerText = selectedJob.judul_posisi;
            document.getElementById('applyModalCompanyName').innerText = selectedJob.penyedia?.nama_organisasi || 'Mitra KerjaKita';
            document.getElementById('applySuratLamaran').value = '';

            document.getElementById('applyJobModal').classList.remove('hidden');
        }

        function closeApplyModal() {
            document.getElementById('applyJobModal').classList.add('hidden');
        }

        async function handleApplySubmit(event) {
            event.preventDefault();

            const jobId = Number(document.getElementById('applyModalJobId').value);
            const suratLamaran = document.getElementById('applySuratLamaran').value.trim();
            const submitBtn = document.getElementById('btnSubmitApplyModal');
            const submitText = document.getElementById('btnSubmitApplyText');

            submitBtn.disabled = true;
            submitText.innerText = 'Mengirim...';

            try {
                const response = await axios.post('/api/mahasiswa/lamaran', {
                    lowongan_id: jobId,
                    surat_lamaran: suratLamaran || null
                });

                if (response.data && response.data.success) {
                    closeApplyModal();
                    showToast('Lamaran Terkirim', response.data.message || 'Lamaran berhasil dikirim ke mitra.');
                }
            } catch (error) {
                console.error('Error submitting application:', error);
                const errMsg = error.response?.data?.errors
                    ? Object.values(error.response.data.errors).flat()[0]
                    : (error.response?.data?.message || 'Gagal mengirim lamaran.');
                showToast('Gagal Mengajukan Lamaran', errMsg, 'error');
            } finally {
                submitBtn.disabled = false;
                submitText.innerText = 'Kirim Lamaran Sekarang';
            }
        }

        function shareCurrentJob() {
            if (navigator.clipboard) {
                navigator.clipboard.writeText(window.location.href);
                showToast('Tautan Disalin', 'Tautan lowongan telah disalin ke papan klip.');
            }
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