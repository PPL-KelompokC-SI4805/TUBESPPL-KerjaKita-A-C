<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KerjaKita - Lupa Kata Sandi</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-100 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-md w-full bg-white rounded-2xl shadow-lg p-8 border border-slate-200">
        <div class="text-center mb-6">
            <div class="w-12 h-12 rounded-xl bg-amber-500 text-white font-bold text-xl flex items-center justify-center mx-auto mb-3">?</div>
            <h1 class="text-2xl font-bold text-slate-800">Atur Ulang Kata Sandi</h1>
            <p class="text-xs text-slate-500 mt-1">Masukkan email akun Anda untuk membuat kata sandi baru</p>
        </div>

        @if ($errors->any())
            <div class="mb-4 p-3 rounded-lg bg-red-50 border border-red-200 text-xs text-red-600">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('password.reset.custom') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Email Terdaftar</label>
                <input type="email" name="email" value="{{ old('email') }}" required placeholder="nama@student.telkomuniversity.ac.id"
                    class="w-full px-3.5 py-2.5 text-sm border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Kata Sandi Baru (Min. 8 Karakter)</label>
                <input type="password" name="new_password" required placeholder="••••••••"
                    class="w-full px-3.5 py-2.5 text-sm border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Konfirmasi Kata Sandi Baru</label>
                <input type="password" name="new_password_confirmation" required placeholder="••••••••"
                    class="w-full px-3.5 py-2.5 text-sm border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none">
            </div>

            <button type="submit" class="w-full py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm rounded-xl transition shadow">
                Simpan Kata Sandi Baru
            </button>
        </form>

        <p class="text-center text-xs text-slate-600 mt-6">
            Ingat kata sandi Anda? <a href="{{ route('login') }}" class="text-blue-600 font-bold hover:underline">Kembali ke Login</a>
        </p>
    </div>
</body>
</html>