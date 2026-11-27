<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Lowongan - KerjaKita</title>
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
    <main class="max-w-5xl w-full mx-auto p-8 flex-1">
        <!-- Header Halaman -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
            <div>
                <p class="text-[11px] font-bold tracking-widest text-blue-600 uppercase mb-1">MANAJEMEN LOWONGAN</p>
                <h1 class="text-2xl font-bold text-slate-800">Daftar Lowongan Saya</h1>
                <p class="text-xs text-slate-500 mt-1">Kelola dan pantau seluruh lowongan pekerjaan maupun magang yang telah dipublikasikan.</p>
            </div>

            <a href="{{ route('penyedia.lowongan.create') }}"
               class="inline-flex items-center justify-center px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl shadow-sm shadow-blue-500/20 transition whitespace-nowrap">
                + Tambah Lowongan
            </a>
        </div>

        @if (session('success'))
            <div class="mb-6 p-4 rounded-xl bg-green-50 border border-green-200 text-sm text-green-700 font-medium">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 text-sm text-red-700 font-medium">
                {{ session('error') }}
            </div>
        @endif

        <!-- Card Tabel Lowongan -->
        <section class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            @if ($lowongan->isEmpty())
                <div class="text-center py-16 px-6">
                    <div class="w-16 h-16 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center mx-auto mb-4 text-2xl font-bold">
                        ▤
                    </div>
                    <h3 class="text-base font-bold text-slate-800 mb-1">Belum Ada Lowongan</h3>
                    <p class="text-xs text-slate-500 max-w-md mx-auto mb-6">
                        Lowongan pekerjaan yang Anda buat akan ditampilkan di sini. Mulai buat lowongan pertama Anda untuk menjangkau mahasiswa.
                    </p>
                    <a href="{{ route('penyedia.lowongan.create') }}"
                       class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl transition">
                        + Buat Lowongan Pertama
                    </a>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse min-w-[820px]">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-200 text-[11px] font-bold uppercase tracking-wider text-slate-500">
                                <th class="py-3.5 px-5">No</th>
                                <th class="py-3.5 px-5">Judul Posisi</th>
                                <th class="py-3.5 px-5">Kategori</th>
                                <th class="py-3.5 px-5">Jenis Pekerjaan</th>
                                <th class="py-3.5 px-5">Lokasi</th>
                                <th class="py-3.5 px-5">Status</th>
                                <th class="py-3.5 px-5 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-xs">
                            @foreach ($lowongan as $item)
                                <tr class="hover:bg-slate-50/70 transition">
                                    <td class="py-4 px-5 font-medium text-slate-500">{{ $loop->iteration }}</td>
                                    <td class="py-4 px-5 font-bold text-slate-800">{{ $item->judul_posisi }}</td>
                                    <td class="py-4 px-5 text-slate-600">{{ $item->kategori->nama_kategori ?? '-' }}</td>
                                    <td class="py-4 px-5 text-slate-600">{{ ucfirst(str_replace('_', ' ', $item->jenis_pekerjaan)) }}</td>
                                    <td class="py-4 px-5 text-slate-600">{{ $item->lokasi_kota ?? '-' }}</td>
                                    <td class="py-4 px-5">
                                        @if ($item->status_aktif)
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                Aktif
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-semibold bg-slate-100 text-slate-600 border border-slate-200">
                                                Ditutup
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-4 px-5">
                                        <div class="flex items-center justify-end gap-2 whitespace-nowrap">
                                            <a href="{{ route('penyedia.lowongan.show', $item->id) }}"
                                               class="px-3 py-1.5 rounded-lg bg-blue-50 hover:bg-blue-100 text-blue-700 font-semibold transition">
                                                Detail
                                            </a>

                                            <a href="{{ route('penyedia.lowongan.edit', $item->id) }}"
                                               class="px-3 py-1.5 rounded-lg bg-slate-800 hover:bg-slate-900 text-white font-semibold transition">
                                                Edit
                                            </a>

                                            <form action="{{ route('penyedia.lowongan.toggle-status', $item->id) }}"
                                                  method="POST"
                                                  class="m-0"
                                                  onsubmit="return confirm('{{ $item->status_aktif ? 'Tutup' : 'Aktifkan kembali' }} lowongan ini?');">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit"
                                                        class="px-3 py-1.5 rounded-lg font-semibold transition {{ $item->status_aktif ? 'bg-amber-50 hover:bg-amber-100 text-amber-700 border border-amber-200' : 'bg-emerald-50 hover:bg-emerald-100 text-emerald-700 border border-emerald-200' }}">
                                                    {{ $item->status_aktif ? 'Tutup' : 'Aktifkan' }}
                                                </button>
                                            </form>

                                            <form action="{{ route('penyedia.lowongan.destroy', $item->id) }}"
                                                  method="POST"
                                                  class="m-0"
                                                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus lowongan ini? Data yang dihapus tidak dapat dikembalikan.');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="px-3 py-1.5 rounded-lg bg-red-50 hover:bg-red-600 text-red-600 hover:text-white font-semibold transition">
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </section>
    </main>
</body>
</html>