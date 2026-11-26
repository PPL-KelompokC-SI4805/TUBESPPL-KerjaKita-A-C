
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Lowongan | KerjaKita</title>

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
            padding: 17px 7%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 3px 15px rgba(41,61,50,0.08);
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            color: white;
            font-size: 21px;
            font-weight: 700;
            letter-spacing: -0.5px;
        }

        .logo {
            background: #91A58A;
            color: #293D32;
            padding: 10px 12px;
            border-radius: 11px;
            font-size: 16px;
        }

        .nav-label {
            color: #E5E8DF;
            font-size: 13px;
        }

        .container {
            max-width: 900px;
            margin: 45px auto 70px;
            padding: 0 25px;
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: #687B65;
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 28px;
            transition: 0.2s;
        }

        .back-link:hover {
            color: #B96F50;
        }

        .heading {
            margin-bottom: 28px;
        }

        .eyebrow {
            color: #B96F50;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 2.5px;
            margin-bottom: 11px;
        }

        h1 {
            font-size: 30px;
            letter-spacing: -0.8px;
            margin-bottom: 9px;
        }

        .subtitle {
            color: #818B7E;
            font-size: 14px;
            line-height: 1.6;
        }

        .form-card {
            background: #FFFFFF;
            border: 1px solid #EAE8DF;
            border-radius: 18px;
            overflow: hidden;
            box-shadow: 0 10px 35px rgba(41,61,50,0.055);
        }

        .card-header {
            padding: 25px 30px;
            border-bottom: 1px solid #EEEDE7;
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .header-icon {
            width: 43px;
            height: 43px;
            border-radius: 12px;
            background: #EDF1E9;
            color: #526B50;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
        }

        .card-header h2 {
            font-size: 16px;
            margin-bottom: 4px;
        }

        .card-header p {
            color: #92998D;
            font-size: 12px;
        }

        .form-body {
            padding: 30px;
        }

        .section-title {
            font-size: 13px;
            font-weight: 700;
            color: #526451;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 9px;
        }

        .section-title::before {
            content: "";
            width: 4px;
            height: 17px;
            background: #91A58A;
            border-radius: 5px;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 21px 22px;
        }

        .form-group {
            min-width: 0;
        }

        .form-group.full {
            grid-column: 1 / -1;
        }

        label {
            display: block;
            font-size: 12px;
            font-weight: 650;
            color: #465747;
            margin-bottom: 9px;
        }

        .required {
            color: #B96F50;
        }

        input, select, textarea {
            width: 100%;
            padding: 13px 14px;
            border: 1px solid #E2E5DC;
            border-radius: 9px;
            background: #FDFDFC;
            color: #293D32;
            font-size: 13px;
            outline: none;
            transition: 0.2s ease;
        }

        input:focus, select:focus, textarea:focus {
            border-color: #91A58A;
            box-shadow: 0 0 0 3px rgba(145,165,138,0.15);
            background: #FFFFFF;
        }

        input::placeholder, textarea::placeholder {
            color: #B0B5AA;
        }

        select {
            cursor: pointer;
        }

        textarea {
            min-height: 120px;
            resize: vertical;
            line-height: 1.7;
        }

        .helper {
            color: #9A9F95;
            font-size: 11px;
            margin-top: 7px;
        }

        .error {
            color: #B94B40;
            font-size: 11px;
            margin-top: 7px;
        }

        .divider {
            height: 1px;
            background: #EEEDE7;
            margin: 30px 0;
        }

        .form-footer {
            background: #FCFCF9;
            border-top: 1px solid #EEEDE7;
            padding: 21px 30px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
        }

        .footer-note {
            color: #92998D;
            font-size: 11px;
        }

        .actions {
            display: flex;
            gap: 11px;
        }

        .btn {
            display: inline-flex;
            justify-content: center;
            align-items: center;
            padding: 12px 19px;
            border-radius: 9px;
            font-size: 12px;
            font-weight: 650;
            text-decoration: none;
            border: none;
            cursor: pointer;
            transition: 0.2s ease;
        }

        .btn-cancel {
            background: #EFF1EA;
            color: #586B55;
        }

        .btn-cancel:hover {
            background: #E4E9DF;
        }

        .btn-save {
            background: #293D32;
            color: white;
        }

        .btn-save:hover {
            background: #3D5845;
            transform: translateY(-1px);
        }

        @media (max-width: 650px) {
            .container {
                margin-top: 30px;
                padding: 0 16px;
            }

            .form-grid {
                grid-template-columns: 1fr;
            }

            .form-group.full {
                grid-column: auto;
            }

            .form-body {
                padding: 22px 19px;
            }

            .card-header {
                padding: 21px 19px;
            }

            .form-footer {
                padding: 20px;
                flex-direction: column;
                align-items: stretch;
            }

            .actions {
                width: 100%;
            }

            .actions .btn {
                flex: 1;
            }

            h1 {
                font-size: 25px;
            }

            .nav-label {
                font-size: 11px;
            }
        }
    </style>
</head>

<body>

<header class="navbar">
    <div class="brand">
        <span class="logo">KK</span>
        KerjaKita
    </div>
    <span class="nav-label">Portal Penyedia</span>
</header>

<main class="container">

    <a href="{{ route('penyedia.lowongan.show', $lowongan->id) }}"
       class="back-link">
        ← Kembali ke Detail Lowongan
    </a>

    <div class="heading">
        <div class="eyebrow">MANAJEMEN LOWONGAN</div>
        <h1>Edit Lowongan</h1>
        <p class="subtitle">
            Perbarui informasi lowongan pekerjaan agar tetap sesuai dengan kebutuhan perusahaan.
        </p>
    </div>

    <section class="form-card">

        <div class="card-header">
            <div class="header-icon">✎</div>
            <div>
                <h2>Informasi Lowongan</h2>
                <p>Ubah detail informasi lowongan pekerjaan kamu.</p>
            </div>
        </div>

        <form action="{{ route('penyedia.lowongan.update', $lowongan->id) }}"
              method="POST">

            @csrf
            @method('PUT')

            <div class="form-body">

                <div class="section-title">Informasi Dasar</div>

                <div class="form-grid">

                    <div class="form-group full">
                        <label>Judul Posisi <span class="required">*</span></label>
                        <input type="text" name="judul_posisi"
                            value="{{ old('judul_posisi', $lowongan->judul_posisi) }}"
                            placeholder="Contoh: UI/UX Designer Intern" required>
                        @error('judul_posisi')
                            <p class="error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Kategori <span class="required">*</span></label>
                        <select name="kategori_id" required>
                            @foreach ($kategori as $item)
                                <option value="{{ $item->id }}"
                                    {{ old('kategori_id', $lowongan->kategori_id) == $item->id ? 'selected' : '' }}>
                                    {{ $item->nama_kategori }}
                                </option>
                            @endforeach
                        </select>
                        @error('kategori_id')
                            <p class="error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Jenis Pekerjaan <span class="required">*</span></label>
                        <select name="jenis_pekerjaan" required>
                            <option value="magang" {{ old('jenis_pekerjaan', $lowongan->jenis_pekerjaan) == 'magang' ? 'selected' : '' }}>Magang</option>
                            <option value="freelance" {{ old('jenis_pekerjaan', $lowongan->jenis_pekerjaan) == 'freelance' ? 'selected' : '' }}>Freelance</option>
                            <option value="paruh_waktu" {{ old('jenis_pekerjaan', $lowongan->jenis_pekerjaan) == 'paruh_waktu' ? 'selected' : '' }}>Paruh Waktu</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Tipe Lokasi <span class="required">*</span></label>
                        <select name="tipe_lokasi" required>
                            <option value="onsite" {{ old('tipe_lokasi', $lowongan->tipe_lokasi) == 'onsite' ? 'selected' : '' }}>Onsite</option>
                            <option value="remote" {{ old('tipe_lokasi', $lowongan->tipe_lokasi) == 'remote' ? 'selected' : '' }}>Remote</option>
                            <option value="hybrid" {{ old('tipe_lokasi', $lowongan->tipe_lokasi) == 'hybrid' ? 'selected' : '' }}>Hybrid</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Lokasi Kota</label>
                        <input type="text" name="lokasi_kota"
                            value="{{ old('lokasi_kota', $lowongan->lokasi_kota) }}"
                            placeholder="Contoh: Bandung">
                    </div>

                    <div class="form-group full">
                        <label>Waktu Kerja</label>
                        <input type="text" name="waktu_kerja"
                            value="{{ old('waktu_kerja', $lowongan->waktu_kerja) }}"
                            placeholder="Contoh: Senin - Jumat, 08.00 - 17.00 WIB">
                    </div>

                </div>

                <div class="divider"></div>

                <div class="section-title">Deskripsi dan Persyaratan</div>

                <div class="form-grid">

                    <div class="form-group full">
                        <label>Deskripsi Pekerjaan <span class="required">*</span></label>
                        <textarea name="deskripsi" placeholder="Jelaskan tanggung jawab dan gambaran pekerjaan..." required>{{ old('deskripsi', $lowongan->deskripsi) }}</textarea>
                        @error('deskripsi')
                            <p class="error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group full">
                        <label>Persyaratan</label>
                        <textarea name="persyaratan" placeholder="Tuliskan kualifikasi atau persyaratan yang dibutuhkan...">{{ old('persyaratan', $lowongan->persyaratan) }}</textarea>
                    </div>

                </div>

                <div class="divider"></div>

                <div class="section-title">Kompensasi</div>

                <div class="form-grid">
                    <div class="form-group full">
                        <label>Kompensasi / Benefit</label>
                        <input type="text" name="kompensasi"
                            value="{{ old('kompensasi', $lowongan->kompensasi) }}"
                            placeholder="Contoh: Rp1.000.000 / bulan">
                        <p class="helper">Isi gaji, tunjangan, atau benefit yang ditawarkan.</p>
                    </div>
                </div>

            </div>

            <div class="form-footer">
                <span class="footer-note">
                    <span class="required">*</span> Wajib diisi
                </span>

                <div class="actions">
                    <a href="{{ route('penyedia.lowongan.show', $lowongan->id) }}"
                       class="btn btn-cancel">
                        Batal
                    </a>

                    <button type="submit" class="btn btn-save">
                        Simpan Perubahan →
                    </button>
                </div>
            </div>

        </form>

    </section>

</main>

</body>
</html>
