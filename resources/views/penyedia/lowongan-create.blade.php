<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Lowongan - KerjaKita</title>
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

        <div class="mb-6">
            <p class="text-[11px] font-bold tracking-widest text-blue-600 uppercase mb-1">MANAJEMEN LOWONGAN</p>
            <h1 class="text-2xl font-bold text-slate-800">Tambah Lowongan</h1>
            <p class="text-xs text-slate-500 mt-1">Temukan kandidat potensial dengan membuat informasi lowongan pekerjaan yang jelas dan menarik.</p>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-8">
            @if ($errors->any())
                <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 text-xs text-red-700">
                    <strong class="block font-bold mb-1">Periksa kembali data yang Anda masukkan:</strong>
                    <ul class="list-disc pl-5 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="border-b border-slate-100 pb-4 mb-6">
                <h2 class="text-base font-bold text-slate-800">Informasi Lowongan</h2>
                <p class="text-xs text-slate-500 mt-0.5">Lengkapi detail pekerjaan yang ingin ditawarkan.</p>
            </div>

            <form id="form-tambah-lowongan" action="{{ route('penyedia.lowongan.store') }}" method="POST">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="md:col-span-2">
                        <label for="judul_posisi" class="block text-xs font-bold text-slate-700 mb-2">
                            Judul Posisi <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="judul_posisi" name="judul_posisi"
                               placeholder="Contoh: UI/UX Designer Intern"
                               value="{{ old('judul_posisi') }}" required
                               class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm text-slate-800 focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-600/15 transition">
                    </div>

                    <div>
                        <label for="kategori_id" class="block text-xs font-bold text-slate-700 mb-2">
                            Kategori Lowongan <span class="text-red-500">*</span>
                        </label>
                        <select id="kategori_id" name="kategori_id" required
                                class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm text-slate-800 bg-white focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-600/15 transition">
                            <option value="">Pilih kategori</option>
                            @foreach ($kategori as $item)
                                <option value="{{ $item->id }}" {{ old('kategori_id') == $item->id ? 'selected' : '' }}>
                                    {{ $item->nama_kategori }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="jenis_pekerjaan" class="block text-xs font-bold text-slate-700 mb-2">
                            Jenis Pekerjaan <span class="text-red-500">*</span>
                        </label>
                        <select id="jenis_pekerjaan" name="jenis_pekerjaan" required
                                class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm text-slate-800 bg-white focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-600/15 transition">
                            <option value="">Pilih jenis pekerjaan</option>
                            <option value="magang" {{ old('jenis_pekerjaan') == 'magang' ? 'selected' : '' }}>Magang</option>
                            <option value="freelance" {{ old('jenis_pekerjaan') == 'freelance' ? 'selected' : '' }}>Freelance</option>
                            <option value="paruh_waktu" {{ old('jenis_pekerjaan') == 'paruh_waktu' ? 'selected' : '' }}>Paruh Waktu</option>
                        </select>
                    </div>

                    <div>
                        <label for="tipe_lokasi" class="block text-xs font-bold text-slate-700 mb-2">
                            Tipe Lokasi <span class="text-red-500">*</span>
                        </label>
                        <select id="tipe_lokasi" name="tipe_lokasi" required
                                class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm text-slate-800 bg-white focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-600/15 transition">
                            <option value="">Pilih tipe lokasi</option>
                            <option value="onsite" {{ old('tipe_lokasi') == 'onsite' ? 'selected' : '' }}>Onsite</option>
                            <option value="remote" {{ old('tipe_lokasi') == 'remote' ? 'selected' : '' }}>Remote</option>
                            <option value="hybrid" {{ old('tipe_lokasi') == 'hybrid' ? 'selected' : '' }}>Hybrid</option>
                        </select>
                    </div>

                    <div>
                        <label for="lokasi_kota" class="block text-xs font-bold text-slate-700 mb-2">
                            Lokasi Kota
                        </label>
                        <input type="text" id="lokasi_kota" name="lokasi_kota"
                               placeholder="Contoh: Bandung"
                               value="{{ old('lokasi_kota') }}"
                               class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm text-slate-800 focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-600/15 transition">
                    </div>

                    <!-- Waktu Kerja -->
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-slate-700 mb-2">Waktu Kerja</label>

                        @php
                            $daftarHari = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
                        @endphp

                        <div class="flex flex-wrap gap-2 mb-4">
                            @foreach ($daftarHari as $day)
                                <label class="cursor-pointer">
                                    <input type="checkbox" name="hari_kerja[]" value="{{ $day }}" class="peer hidden">
                                    <span class="inline-block px-3.5 py-2 rounded-xl border border-slate-200 bg-slate-50 text-xs font-semibold text-slate-600 peer-checked:bg-blue-600 peer-checked:text-white peer-checked:border-blue-600 transition">
                                        {{ $day }}
                                    </span>
                                </label>
                            @endforeach
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="jam_mulai" class="block text-[11px] font-semibold text-slate-500 mb-1.5">Jam Mulai</label>
                                <input type="time" id="jam_mulai" value="08:00"
                                       class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm text-slate-800 focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-600/15 transition">
                            </div>

                            <div>
                                <label for="jam_selesai" class="block text-[11px] font-semibold text-slate-500 mb-1.5">Jam Selesai</label>
                                <input type="time" id="jam_selesai" value="17:00"
                                       class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm text-slate-800 focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-600/15 transition">
                            </div>
                        </div>

                        <input type="hidden" name="waktu_kerja" id="waktu_kerja" value="{{ old('waktu_kerja') }}">
                        <p class="text-[11px] text-slate-400 mt-2">Pilih hari dan jam kerja yang berlaku.</p>
                    </div>

                    <div class="md:col-span-2">
                        <label for="deskripsi" class="block text-xs font-bold text-slate-700 mb-2">
                            Deskripsi Pekerjaan <span class="text-red-500">*</span>
                        </label>
                        <textarea id="deskripsi" name="deskripsi" rows="4"
                                  placeholder="Jelaskan tanggung jawab dan gambaran pekerjaan..." required
                                  class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm text-slate-800 focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-600/15 transition">{{ old('deskripsi') }}</textarea>
                    </div>

                    <div class="md:col-span-2">
                        <label for="persyaratan" class="block text-xs font-bold text-slate-700 mb-2">
                            Persyaratan
                        </label>
                        <textarea id="persyaratan" name="persyaratan" rows="4"
                                  placeholder="Tuliskan kualifikasi atau kemampuan yang dibutuhkan..."
                                  class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm text-slate-800 focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-600/15 transition">{{ old('persyaratan') }}</textarea>
                    </div>

                    <div class="md:col-span-2">
                        <label for="kompensasi" class="block text-xs font-bold text-slate-700 mb-2">
                            Kompensasi
                        </label>
                        <div class="flex items-center rounded-xl border border-slate-200 bg-white overflow-hidden focus-within:border-blue-600 focus-within:ring-2 focus-within:ring-blue-600/15 transition">
                            <span class="pl-4 pr-2 text-xs font-bold text-slate-500">Rp</span>
                            <input type="number" id="kompensasi" name="kompensasi"
                                   placeholder="Masukkan nominal"
                                   value="{{ old('kompensasi') }}" min="0"
                                   class="w-full py-2.5 px-2 text-sm text-slate-800 focus:outline-none">
                            <span class="px-4 text-xs text-slate-400 whitespace-nowrap">/ bulan</span>
                        </div>
                    </div>
                </div>

                <div class="border-t border-slate-100 mt-8 pt-6 flex flex-col-reverse sm:flex-row justify-end items-center gap-3">
                    <a href="{{ route('penyedia.lowongan.index') }}"
                       class="w-full sm:w-auto text-center px-5 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition">
                        Batal
                    </a>
                    <button type="submit"
                            class="w-full sm:w-auto px-6 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold shadow-sm shadow-blue-500/20 transition">
                        Simpan Lowongan
                    </button>
                </div>
            </form>
        </div>
    </main>

    <script>
        const formTambah = document.getElementById('form-tambah-lowongan');
        const waktuKerjaInput = document.getElementById('waktu_kerja');

        formTambah.addEventListener('submit', function () {
            const hari = Array.from(
                document.querySelectorAll('input[name="hari_kerja[]"]:checked')
            ).map(input => input.value);

            const mulai = document.getElementById('jam_mulai').value;
            const selesai = document.getElementById('jam_selesai').value;

            if (hari.length > 0) {
                waktuKerjaInput.value = hari.join(', ') + ', ' + mulai + ' - ' + selesai + ' WIB';
            } else {
                waktuKerjaInput.value = mulai + ' - ' + selesai + ' WIB';
            }
        });
    </script>
</body>
</html>