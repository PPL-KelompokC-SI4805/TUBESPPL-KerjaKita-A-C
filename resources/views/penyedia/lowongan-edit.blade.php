<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Lowongan - KerjaKita</title>
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
        <a href="{{ route('penyedia.lowongan.show', $lowongan->id) }}"
           class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-600 hover:text-blue-600 mb-6 transition">
            &larr; Kembali ke Detail Lowongan
        </a>

        <div class="mb-6">
            <p class="text-[11px] font-bold tracking-widest text-blue-600 uppercase mb-1">MANAJEMEN LOWONGAN</p>
            <h1 class="text-2xl font-bold text-slate-800">Edit Lowongan</h1>
            <p class="text-xs text-slate-500 mt-1">Perbarui informasi lowongan pekerjaan agar tetap sesuai dengan kebutuhan organisasi Anda.</p>
        </div>

        <section class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="px-8 py-5 border-b border-slate-100 flex items-center gap-3.5">
                <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold">
                    ✎
                </div>
                <div>
                    <h2 class="text-sm font-bold text-slate-800">Informasi Lowongan</h2>
                    <p class="text-xs text-slate-500">Ubah detail informasi lowongan pekerjaan yang dipublikasikan.</p>
                </div>
            </div>

            <form action="{{ route('penyedia.lowongan.update', $lowongan->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="p-8 space-y-8">
                    <!-- Informasi Dasar -->
                    <div>
                        <h3 class="text-xs font-bold uppercase tracking-wider text-blue-600 mb-4 flex items-center gap-2">
                            <span class="w-1 h-4 bg-blue-600 rounded-full"></span>
                            Informasi Dasar
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div class="md:col-span-2">
                                <label class="block text-xs font-bold text-slate-700 mb-2">
                                    Judul Posisi <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="judul_posisi"
                                       value="{{ old('judul_posisi', $lowongan->judul_posisi) }}"
                                       placeholder="Contoh: UI/UX Designer Intern" required
                                       class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm text-slate-800 focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-600/15 transition">
                                @error('judul_posisi')
                                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-2">
                                    Kategori <span class="text-red-500">*</span>
                                </label>
                                <select name="kategori_id" required
                                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm text-slate-800 bg-white focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-600/15 transition">
                                    @foreach ($kategori as $item)
                                        <option value="{{ $item->id }}" {{ old('kategori_id', $lowongan->kategori_id) == $item->id ? 'selected' : '' }}>
                                            {{ $item->nama_kategori }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('kategori_id')
                                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-2">
                                    Jenis Pekerjaan <span class="text-red-500">*</span>
                                </label>
                                <select name="jenis_pekerjaan" required
                                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm text-slate-800 bg-white focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-600/15 transition">
                                    <option value="magang" {{ old('jenis_pekerjaan', $lowongan->jenis_pekerjaan) == 'magang' ? 'selected' : '' }}>Magang</option>
                                    <option value="freelance" {{ old('jenis_pekerjaan', $lowongan->jenis_pekerjaan) == 'freelance' ? 'selected' : '' }}>Freelance</option>
                                    <option value="paruh_waktu" {{ old('jenis_pekerjaan', $lowongan->jenis_pekerjaan) == 'paruh_waktu' ? 'selected' : '' }}>Paruh Waktu</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-2">
                                    Tipe Lokasi <span class="text-red-500">*</span>
                                </label>
                                <select name="tipe_lokasi" required
                                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm text-slate-800 bg-white focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-600/15 transition">
                                    <option value="onsite" {{ old('tipe_lokasi', $lowongan->tipe_lokasi) == 'onsite' ? 'selected' : '' }}>Onsite</option>
                                    <option value="remote" {{ old('tipe_lokasi', $lowongan->tipe_lokasi) == 'remote' ? 'selected' : '' }}>Remote</option>
                                    <option value="hybrid" {{ old('tipe_lokasi', $lowongan->tipe_lokasi) == 'hybrid' ? 'selected' : '' }}>Hybrid</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-2">Lokasi Kota</label>
                                <input type="text" name="lokasi_kota"
                                       value="{{ old('lokasi_kota', $lowongan->lokasi_kota) }}"
                                       placeholder="Contoh: Bandung"
                                       class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm text-slate-800 focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-600/15 transition">
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-xs font-bold text-slate-700 mb-2">Waktu Kerja</label>
                                <input type="text" name="waktu_kerja"
                                       value="{{ old('waktu_kerja', $lowongan->waktu_kerja) }}"
                                       placeholder="Contoh: Senin - Jumat, 08.00 - 17.00 WIB"
                                       class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm text-slate-800 focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-600/15 transition">
                            </div>
                        </div>
                    </div>

                    <hr class="border-slate-100">

                    <!-- Deskripsi dan Persyaratan -->
                    <div>
                        <h3 class="text-xs font-bold uppercase tracking-wider text-blue-600 mb-4 flex items-center gap-2">
                            <span class="w-1 h-4 bg-blue-600 rounded-full"></span>
                            Deskripsi dan Persyaratan
                        </h3>

                        <div class="space-y-5">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-2">
                                    Deskripsi Pekerjaan <span class="text-red-500">*</span>
                                </label>
                                <textarea name="deskripsi" rows="4"
                                          placeholder="Jelaskan tanggung jawab dan gambaran pekerjaan..." required
                                          class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm text-slate-800 focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-600/15 transition">{{ old('deskripsi', $lowongan->deskripsi) }}</textarea>
                                @error('deskripsi')
                                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-2">Persyaratan</label>
                                <textarea name="persyaratan" rows="4"
                                          placeholder="Tuliskan kualifikasi atau persyaratan yang dibutuhkan..."
                                          class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm text-slate-800 focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-600/15 transition">{{ old('persyaratan', $lowongan->persyaratan) }}</textarea>
                            </div>
                        </div>
                    </div>

                    <hr class="border-slate-100">

                    <!-- Kompensasi -->
                    <div>
                        <h3 class="text-xs font-bold uppercase tracking-wider text-blue-600 mb-4 flex items-center gap-2">
                            <span class="w-1 h-4 bg-blue-600 rounded-full"></span>
                            Kompensasi
                        </h3>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-2">Kompensasi / Benefit</label>
                            <input type="text" name="kompensasi"
                                   value="{{ old('kompensasi', $lowongan->kompensasi) }}"
                                   placeholder="Contoh: Rp1.000.000 / bulan"
                                   class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm text-slate-800 focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-600/15 transition">
                            <p class="text-[11px] text-slate-400 mt-1.5">Isi gaji, tunjangan, atau benefit yang ditawarkan.</p>
                        </div>
                    </div>
                </div>

                <div class="bg-slate-50 border-t border-slate-100 px-8 py-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <span class="text-[11px] text-slate-500">
                        <span class="text-red-500 font-bold">*</span> Wajib diisi
                    </span>

                    <div class="flex items-center gap-3">
                        <a href="{{ route('penyedia.lowongan.show', $lowongan->id) }}"
                           class="px-5 py-2.5 rounded-xl bg-white border border-slate-200 hover:bg-slate-100 text-slate-700 text-xs font-bold transition">
                            Batal
                        </a>

                        <button type="submit"
                                class="px-6 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold shadow-sm shadow-blue-500/20 transition">
                            Simpan Perubahan &rarr;
                        </button>
                    </div>
                </div>
            </form>
        </section>
    </main>
</body>
</html>