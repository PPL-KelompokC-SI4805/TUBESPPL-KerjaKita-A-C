<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KerjaKita - Beranda Utama</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 min-h-screen flex flex-col">
    <!-- Navbar -->
    <nav class="bg-white border-b border-slate-200 px-8 py-4 flex justify-between items-center shadow-sm">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl bg-blue-600 text-white font-bold flex items-center justify-center">KK</div>
            <span class="font-bold text-xl text-slate-800">KerjaKita</span>
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
    <main class="max-w-5xl w-full mx-auto p-8 flex-1">
        @if (session('success'))
            <div class="mb-6 p-4 rounded-xl bg-green-50 border border-green-200 text-sm text-green-700 font-medium">
                {{ session('success') }}
            </div>
        @endif

        <!-- Hero Banner -->
        <div class="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-3xl p-8 text-white shadow-lg mb-8">
            <h1 class="text-2xl font-bold mb-2">Selamat Datang di KerjaKita, {{ Auth::user()->name }}!</h1>
            <p class="text-sm text-blue-100 max-w-2xl">
                @if(Auth::user()->role === 'mahasiswa')
                    Temukan peluang magang, proyek lepas, dan lowongan kerja terbaik yang sesuai dengan keahlian dan program studi Anda.
                @else
                    Kelola lowongan pekerjaan dan temukan talenta mahasiswa terbaik dari berbagai perguruan tinggi.
                @endif
            </p>
        </div>

        <!-- Ringkasan Menu Utama -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
                <h3 class="font-bold text-slate-800 mb-1">Eksplorasi Lowongan</h3>
                <p class="text-xs text-slate-500 mb-4">Lihat daftar posisi magang dan kerja terbaru yang tersedia.</p>
                <span class="inline-block text-xs font-bold text-blue-600">Segera Hadir &rarr;</span>
            </div>

            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
                <h3 class="font-bold text-slate-800 mb-1">
                    {{ Auth::user()->role === 'mahasiswa' ? 'Status Lamaran Saya' : 'Kelola Pelamar' }}
                </h3>
                <p class="text-xs text-slate-500 mb-4">
                    {{ Auth::user()->role === 'mahasiswa' ? 'Pantau progres lamaran yang sudah Anda kirimkan.' : 'Tinjau daftar kandidat mahasiswa yang melamar.' }}
                </p>
                <span class="inline-block text-xs font-bold text-blue-600">Segera Hadir &rarr;</span>
            </div>

            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex flex-col justify-between">
                <div>
                    <h3 class="font-bold text-slate-800 mb-1">Profil Akun</h3>
                    <p class="text-xs text-slate-500 mb-2">Email: {{ Auth::user()->email }}</p>
                    <span class="inline-block text-xs font-semibold px-2.5 py-1 rounded-lg bg-slate-100 text-slate-700">Akun Terverifikasi</span>
                </div>
                @if(Auth::user()->role === 'mahasiswa')
                    <div class="mt-4 pt-3 border-t border-slate-100">
                        <a href="{{ route('mahasiswa.profil') }}" class="inline-flex items-center text-xs font-bold text-blue-600 hover:text-blue-700 transition">
                            Kelola Profil & CV &rarr;
                        </a>
                    </div>
                     @else
                    <div class="mt-4 pt-3 border-t border-slate-100">
                        <a href="{{ route('penyedia.profil') }}" class="inline-flex items-center text-xs font-bold text-blue-600 hover:text-blue-700 transition">
                            Kelola Profil Organisasi &rarr;
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </main>
</body>
</html>