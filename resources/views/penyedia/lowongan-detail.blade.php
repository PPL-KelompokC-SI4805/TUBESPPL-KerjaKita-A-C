<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Lowongan - KerjaKita</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 min-h-screen flex flex-col text-slate-800">
    <!-- Navbar -->
    <nav class="bg-white border-b border-slate-200 px-8 py-4 flex justify-between items-center shadow-sm">
        <div class="flex items-center gap-8">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5">
                <div class="w-9 h-9 rounded-xl bg-blue-600 text-white font-bold flex items-center justify-center shadow-md shadow-blue-500/20">KK</div>
                <span class="font-bold text-xl text-slate-800">KerjaKita</span>
            </a>

            <div class="hidden md:flex items-center gap-2">
                <a href="{{ route('dashboard') }}" class="px-3 py-1.5 text-xs font-bold text-slate-600 hover:text-blue-600 transition">
                    Beranda
                </a>
                <a href="{{ route('penyedia.lowongan.index') }}" class="px-3 py-1.5 text-xs font-bold text-blue-600 border-b-2 border-blue-600 transition">
                    Kelola Lowongan
                </a>
                <a href="{{ route('penyedia.profil') }}" class="px-3 py-1.5 text-xs font-bold text-slate-600 hover:text-blue-600 transition">
                    Profil Organisasi
                </a>
            </div>
        </div>

        <div class="flex items-center gap-4">
            <div class="text-right">
                <p class="text-sm font-bold text-slate-800">{{ Auth::user()->name }}</p>
                <p class="text-[11px] font-semibold text-blue-600 uppercase">{{ Auth::user()->role }}</p>
            </div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-xs font-bold rounded-xl transition">
                    Logout
                </button>
            </form>
        </div>
    </nav>

    <!-- Konten Utama -->
    <main class="max-w-4xl w-full mx-auto p-8 flex-1">
        <a href="{{ route('penyedia.lowongan.index') }}"
           class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-600 hover:text-blue-600 mb-6 transition">
            &larr; Kembali ke Daftar Lowongan
        </a>

        @if (session('success'))
            <div class="mb-6 p-4 rounded-xl bg-green-50 border border-green-200 text-sm text-green-700 font-medium">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-8">
            <p class="text-[11px] font-bold tracking-widest text-blue-600 uppercase mb-1.5">DETAIL LOWONGAN</p>
            <h1 class="text-2xl font-bold text-slate-800 mb-4">{{ $lowongan->judul_posisi }}</h1>

            <div class="flex flex-wrap items-center gap-2 mb-6">
                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-100">
                    {{ $lowongan->kategori->nama_kategori ?? '-' }}
                </span>
                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-700">
                    {{ ucfirst(str_replace('_', ' ', $lowongan->jenis_pekerjaan)) }}
                </span>

                @if ($lowongan->status_aktif)
                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                        Aktif
                    </span>
                @else
                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-600 border border-slate-200">
                        Ditutup
                    </span>
                @endif
            </div>

            <hr class="border-slate-100 my-6">

            <!-- Informasi Pekerjaan -->
            <div class="mb-6">
                <h3 class="text-sm font-bold text-slate-800 mb-4">Informasi Pekerjaan</h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="bg-slate-50 border border-slate-100 p-4 rounded-xl">
                        <span class="block text-[11px] font-semibold text-slate-500 mb-1">Tipe Lokasi</span>
                        <strong class="text-sm font-bold text-slate-800">{{ ucfirst($lowongan->tipe_lokasi) }}</strong>
                    </div>

                    <div class="bg-slate-50 border border-slate-100 p-4 rounded-xl">
                        <span class="block text-[11px] font-semibold text-slate-500 mb-1">Lokasi Kota</span>
                        <strong class="text-sm font-bold text-slate-800">{{ $lowongan->lokasi_kota ?: 'Belum ditentukan' }}</strong>
                    </div>

                    <div class="bg-slate-50 border border-slate-100 p-4 rounded-xl">
                        <span class="block text-[11px] font-semibold text-slate-500 mb-1">Waktu Kerja</span>
                        <strong class="text-sm font-bold text-slate-800">{{ $lowongan->waktu_kerja ?: 'Belum ditentukan' }}</strong>
                    </div>

                    <div class="bg-slate-50 border border-slate-100 p-4 rounded-xl">
                        <span class="block text-[11px] font-semibold text-slate-500 mb-1">Kompensasi / Benefit</span>
                        <strong class="text-sm font-bold text-slate-800">{{ $lowongan->kompensasi ?: 'Tidak dicantumkan' }}</strong>
                    </div>
                </div>
            </div>

            <hr class="border-slate-100 my-6">

            <!-- Deskripsi -->
            <div class="mb-6">
                <h3 class="text-sm font-bold text-slate-800 mb-2">Deskripsi Pekerjaan</h3>
                <p class="text-xs text-slate-600 leading-relaxed whitespace-pre-line">{{ $lowongan->deskripsi }}</p>
            </div>

            <!-- Persyaratan -->
            <div class="mb-8">
                <h3 class="text-sm font-bold text-slate-800 mb-2">Persyaratan</h3>
                <p class="text-xs text-slate-600 leading-relaxed whitespace-pre-line">{{ $lowongan->persyaratan ?: 'Tidak ada persyaratan khusus yang dicantumkan.' }}</p>
            </div>

            <div class="border-t border-slate-100 pt-6 flex flex-wrap justify-end items-center gap-3">
                <a href="{{ route('penyedia.lowongan.edit', $lowongan->id) }}"
                   class="px-5 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-900 text-white text-xs font-bold transition">
                    ✎ Edit Lowongan
                </a>
                <a href="{{ route('penyedia.lowongan.index') }}"
                   class="px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold shadow-sm shadow-blue-500/20 transition">
                    Kembali ke Daftar
                </a>
            </div>
        </div>
    </main>
</body>
</html>