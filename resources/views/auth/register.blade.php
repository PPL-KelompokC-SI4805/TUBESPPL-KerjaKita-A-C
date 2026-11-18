<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KerjaKita - Daftar Akun</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-100 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-lg w-full bg-white rounded-2xl shadow-lg p-8 border border-slate-200">
        <div class="text-center mb-6">
            <h1 class="text-2xl font-bold text-slate-800">Daftar Akun KerjaKita</h1>
            <p class="text-xs text-slate-500 mt-1">Pilih peran Anda untuk memulai</p>
        </div>

        @if ($errors->any())
            <div class="mb-4 p-3 rounded-lg bg-red-50 border border-red-200 text-xs text-red-600">
                <ul class="list-disc pl-4 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ url('/register') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Daftar Sebagai *</label>
                <select name="role" id="roleSelect" onchange="toggleRoleFields()" class="w-full px-3.5 py-2.5 text-sm border border-slate-300 rounded-xl bg-white outline-none">
                    <option value="mahasiswa" {{ old('role') == 'mahasiswa' ? 'selected' : '' }}>Mahasiswa</option>
                    <option value="penyedia" {{ old('role') == 'penyedia' ? 'selected' : '' }}>Mitra / Penyedia Lowongan</option>
                </select>
            </div>

            <!-- Form Khusus Mahasiswa -->
            <div id="mahasiswaFields" class="space-y-3">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Nama Lengkap *</label>
                    <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap') }}" placeholder="Nama Lengkap" class="w-full px-3.5 py-2 text-sm border border-slate-300 rounded-xl outline-none">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">NIM</label>
                        <input type="text" name="nim" value="{{ old('nim') }}" placeholder="NIM" class="w-full px-3.5 py-2 text-sm border border-slate-300 rounded-xl outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Program Studi</label>
                        <input type="text" name="program_studi" value="{{ old('program_studi') }}" placeholder="Program Studi" class="w-full px-3.5 py-2 text-sm border border-slate-300 rounded-xl outline-none">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Universitas</label>
                    <input type="text" name="universitas" value="{{ old('universitas') }}" placeholder="Universitas" class="w-full px-3.5 py-2 text-sm border border-slate-300 rounded-xl outline-none">
                </div>
            </div>

            <!-- Form Khusus Penyedia -->
            <div id="penyediaFields" class="space-y-3 hidden">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Nama Perusahaan / Organisasi *</label>
                    <input type="text" name="nama_organisasi" value="{{ old('nama_organisasi') }}" placeholder="PT Teknologi Nusantara" class="w-full px-3.5 py-2 text-sm border border-slate-300 rounded-xl outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Bidang Usaha</label>
                    <input type="text" name="bidang_usaha" value="{{ old('bidang_usaha') }}" placeholder="IT & Software Development" class="w-full px-3.5 py-2 text-sm border border-slate-300 rounded-xl outline-none">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Email *</label>
                <input type="email" name="email" value="{{ old('email') }}" required placeholder="email@domain.com" class="w-full px-3.5 py-2 text-sm border border-slate-300 rounded-xl outline-none">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Kata Sandi (Min. 8) *</label>
                    <input type="password" name="password" required placeholder="••••••••" class="w-full px-3.5 py-2 text-sm border border-slate-300 rounded-xl outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Konfirmasi Sandi *</label>
                    <input type="password" name="password_confirmation" required placeholder="••••••••" class="w-full px-3.5 py-2 text-sm border border-slate-300 rounded-xl outline-none">
                </div>
            </div>

            <button type="submit" class="w-full py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm rounded-xl transition shadow">
                Daftar Sekarang
            </button>
        </form>

        <p class="text-center text-xs text-slate-600 mt-6">
            Sudah punya akun? <a href="{{ route('login') }}" class="text-blue-600 font-bold hover:underline">Masuk di sini</a>
        </p>
    </div>

    <script>
        function toggleRoleFields() {
            const role = document.getElementById('roleSelect').value;
            document.getElementById('mahasiswaFields').classList.toggle('hidden', role !== 'mahasiswa');
            document.getElementById('penyediaFields').classList.toggle('hidden', role !== 'penyedia');
        }
        toggleRoleFields();
    </script>
</body>
</html>