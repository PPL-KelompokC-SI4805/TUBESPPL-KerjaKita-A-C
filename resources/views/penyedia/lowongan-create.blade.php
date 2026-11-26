<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Lowongan - KerjaKita</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Arial, sans-serif;
        }

        body {
            background: #F7F5EF;
            color: #293D32;
        }

        .navbar {
            background: #293D32;
            padding: 18px 7%;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            color: white;
            font-size: 21px;
            font-weight: 700;
        }

        .logo {
            background: #91A58A;
            color: #293D32;
            width: 40px;
            height: 40px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }

        .nav-label {
            color: #E5E8DF;
            font-size: 14px;
        }

        .container {
            max-width: 850px;
            margin: 45px auto;
            padding: 0 22px 50px;
        }

        .back {
            display: inline-block;
            color: #647C67;
            text-decoration: none;
            font-size: 14px;
            margin-bottom: 25px;
            font-weight: 600;
        }

        .heading {
            margin-bottom: 25px;
        }

        .eyebrow {
            color: #B76E50;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 2px;
            margin-bottom: 10px;
        }

        h1 {
            font-size: 30px;
            margin-bottom: 9px;
            color: #293D32;
        }

        .subtitle {
            color: #7C867B;
            font-size: 14px;
            line-height: 1.6;
        }

        .form-card {
            background: white;
            border: 1px solid #EAE8DF;
            border-radius: 18px;
            padding: 35px;
            box-shadow: 0 8px 25px rgba(41,61,50,0.06);
        }

        .section-title {
            font-size: 17px;
            font-weight: 700;
            margin-bottom: 7px;
        }

        .section-desc {
            color: #8A9389;
            font-size: 13px;
            margin-bottom: 25px;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 21px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 9px;
        }

        .full {
            grid-column: 1 / -1;
        }

        label {
            font-size: 13px;
            font-weight: 600;
            color: #405445;
        }

        input, select, textarea {
            width: 100%;
            padding: 13px 14px;
            border: 1px solid #DDE2D8;
            border-radius: 9px;
            outline: none;
            font-size: 14px;
            color: #344638;
            background: #FEFEFC;
            transition: 0.2s;
        }

        input:focus, select:focus, textarea:focus {
            border-color: #81977D;
            box-shadow: 0 0 0 3px rgba(129,151,125,0.13);
        }

        textarea {
            min-height: 115px;
            resize: vertical;
        }

        .error-box {
            background: #FFF0EC;
            border: 1px solid #E9B9A9;
            color: #A74F39;
            border-radius: 10px;
            padding: 15px 20px;
            margin-bottom: 22px;
            font-size: 13px;
        }

        .error-box ul {
            padding-left: 20px;
            margin-top: 8px;
        }

        .divider {
            border: none;
            border-top: 1px solid #EEEDE7;
            margin: 28px 0;
        }

        .actions {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            gap: 12px;
            margin-top: 30px;
        }

        .btn {
            padding: 13px 23px;
            border-radius: 9px;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            border: none;
        }

        .btn-cancel {
            color: #637366;
            background: #F0F1EB;
        }

        .btn-save {
            color: white;
            background: #B96F50;
        }

        .btn-save:hover {
            background: #A45D40;
        }

        @media (max-width: 600px) {
            .form-card {
                padding: 23px 18px;
            }

            .form-grid {
                grid-template-columns: 1fr;
            }

            .full {
                grid-column: auto;
            }

            h1 {
                font-size: 25px;
            }

            .actions {
                flex-direction: column-reverse;
            }

            .actions .btn {
                width: 100%;
                text-align: center;
            }
        }
        .day-options 
        {
            display: flex;
            flex-wrap: wrap;
            gap: 9px;
            margin: 12px 0 20px;
        }

        .day-option 
        {
            cursor: pointer;
        }

        .day-option input 
        {
            display: none;
        }

        .day-option span 
        {
            display: inline-block;
            padding: 10px 15px;
            border: 1px solid #DFE3D9;
            border-radius: 8px;
            background: #FDFDFC;
            color: #526451;
            font-size: 12px;
        }

        .day-option input:checked + span 
        {
            background: #293D32;
            color: white;
            border-color: #293D32;
        }

        .time-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }

        .helper {
            color: #92998D;
            font-size: 11px;
            margin-top: 10px;
        }
        .input-rupiah {
            display: flex;
            align-items: center;
            border: 1px solid #DFE3D9;
            border-radius: 9px;
            background: #FDFDFC;
            overflow: hidden;
        }

        .input-rupiah:focus-within {
            border-color: #91A58A;
            box-shadow: 0 0 0 3px rgba(145,165,138,0.15);
        }

        .input-rupiah span {
            padding-left: 15px;
            color: #526451;
            font-size: 14px;
            font-weight: 600;
        }

        .input-rupiah input {
            border: none;
            box-shadow: none;
            background: transparent;
        }

        .input-rupiah input:focus {
            box-shadow: none;
        }
        .input-rupiah {
            display: flex;
            align-items: center;
            border: 1px solid #DFE3D9;
            border-radius: 9px;
            background: #FDFDFC;
            overflow: hidden;
        }

        .input-rupiah:focus-within {
            border-color: #91A58A;
            box-shadow: 0 0 0 3px rgba(145,165,138,0.15);
        }

        .input-rupiah span {
            padding-left: 15px;
            color: #526451;
            font-size: 14px;
            font-weight: 600;
        }

        .input-rupiah input {
            border: none;
            box-shadow: none;
            background: transparent;
            flex: 1;
            min-width: 0;
        }

        .input-rupiah .period {
            padding: 0 15px;
            color: #92998D;
            font-weight: 400;
            white-space: nowrap;
        }
    </style>
</head>

<body>

<header class="navbar">
    <div class="brand">
        <div class="logo">KK</div>
        KerjaKita
    </div>
    <span class="nav-label">Portal Penyedia</span>
</header>

<main class="container">

    <a href="{{ route('penyedia.lowongan.index') }}" class="back">
        ← Kembali ke Daftar Lowongan
    </a>

    <div class="heading">
        <div class="eyebrow">MANAJEMEN LOWONGAN</div>
        <h1>Tambah Lowongan</h1>
        <p class="subtitle">
            Temukan kandidat potensial dengan membuat informasi lowongan pekerjaan yang jelas dan menarik.
        </p>
    </div>

    <div class="form-card">

        @if ($errors->any())
            <div class="error-box">
                <strong>Periksa kembali data yang kamu masukkan.</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="section-title">Informasi Lowongan</div>
        <p class="section-desc">Lengkapi detail pekerjaan yang ingin ditawarkan.</p>

        <form action="{{ route('penyedia.lowongan.store') }}" method="POST">
            @csrf

            <div class="form-grid">

                <div class="form-group full">
                    <label for="judul_posisi">Judul Posisi *</label>
                    <input type="text" id="judul_posisi" name="judul_posisi"
                        placeholder="Contoh: UI/UX Designer Intern"
                        value="{{ old('judul_posisi') }}" required>
                </div>

                <div class="form-group">
                    <label for="kategori_id">Kategori Lowongan *</label>
                    <select id="kategori_id" name="kategori_id" required>
                        <option value="">Pilih kategori</option>
                        @foreach ($kategori as $item)
                            <option value="{{ $item->id }}"
                                {{ old('kategori_id') == $item->id ? 'selected' : '' }}>
                                {{ $item->nama_kategori }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="jenis_pekerjaan">Jenis Pekerjaan *</label>
                    <select id="jenis_pekerjaan" name="jenis_pekerjaan" required>
                        <option value="">Pilih jenis pekerjaan</option>
                        <option value="magang" {{ old('jenis_pekerjaan') == 'magang' ? 'selected' : '' }}>Magang</option>
                        <option value="freelance" {{ old('jenis_pekerjaan') == 'freelance' ? 'selected' : '' }}>Freelance</option>
                        <option value="paruh_waktu" {{ old('jenis_pekerjaan') == 'paruh_waktu' ? 'selected' : '' }}>Paruh Waktu</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="tipe_lokasi">Tipe Lokasi *</label>
                    <select id="tipe_lokasi" name="tipe_lokasi" required>
                        <option value="">Pilih tipe lokasi</option>
                        <option value="onsite" {{ old('tipe_lokasi') == 'onsite' ? 'selected' : '' }}>Onsite</option>
                        <option value="remote" {{ old('tipe_lokasi') == 'remote' ? 'selected' : '' }}>Remote</option>
                        <option value="hybrid" {{ old('tipe_lokasi') == 'hybrid' ? 'selected' : '' }}>Hybrid</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="lokasi_kota">Lokasi Kota</label>
                    <input type="text" id="lokasi_kota" name="lokasi_kota"
                        placeholder="Contoh: Bandung"
                        value="{{ old('lokasi_kota') }}">
                </div>

                <div class="form-group full">
                    <label>Waktu Kerja</label>

                    <div class="day-options">
                        @foreach (['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'] as $day)
                            <label class="day-option">
                                <input type="checkbox" name="hari_kerja[]"
                                    value="{{ $day }}">
                                <span>{{ $day }}</span>
                            </label>
                        @endforeach
                    </div>

                    <div class="time-grid">
                        <div>
                            <label for="jam_mulai">Jam Mulai</label>
                            <input type="time" id="jam_mulai" value="08:00">
                        </div>

                        <div>
                            <label for="jam_selesai">Jam Selesai</label>
                            <input type="time" id="jam_selesai" value="17:00">
                        </div>
                    </div>

                    <input type="hidden" name="waktu_kerja" id="waktu_kerja"
                        value="{{ old('waktu_kerja') }}">

                    <p class="helper">Pilih hari dan jam kerja yang berlaku.</p>
                </div>

                <div class="form-group full">
                    <label for="deskripsi">Deskripsi Pekerjaan *</label>
                    <textarea id="deskripsi" name="deskripsi"
                        placeholder="Jelaskan tanggung jawab dan gambaran pekerjaan..."
                        required>{{ old('deskripsi') }}</textarea>
                </div>

                <div class="form-group full">
                    <label for="persyaratan">Persyaratan</label>
                    <textarea id="persyaratan" name="persyaratan"
                        placeholder="Tuliskan kualifikasi atau kemampuan yang dibutuhkan...">{{ old('persyaratan') }}</textarea>
                </div>

                    <div class="form-group full">
                        <label for="kompensasi">Kompensasi</label>

                        <div class="input-rupiah">
                            <span>Rp</span>

                            <input type="number"
                                id="kompensasi"
                                name="kompensasi"
                                placeholder="Masukkan nominal"
                                value="{{ old('kompensasi') }}"
                                min="0">

                            <span class="period">/ bulan</span>
                        </div>
                    </div>

            </div>

            <hr class="divider">

            <div class="actions">
                <a href="{{ route('penyedia.lowongan.index') }}" class="btn btn-cancel">
                    Batal
                </a>
                <button type="submit" class="btn btn-save">
                    Simpan Lowongan
                </button>
            </div>

        </form>
    </div>

</main>
<script>
    const form = document.querySelector('form');
    const waktuKerja = document.getElementById('waktu_kerja');

    form.addEventListener('submit', function () {
        const hari = Array.from(
            document.querySelectorAll('input[name="hari_kerja[]"]:checked')
        ).map(input => input.value);

        const mulai = document.getElementById('jam_mulai').value;
        const selesai = document.getElementById('jam_selesai').value;

        waktuKerja.value = hari.join(', ') + ', ' + mulai + ' - ' + selesai + ' WIB';
    });
</script>
</body>
</html>
