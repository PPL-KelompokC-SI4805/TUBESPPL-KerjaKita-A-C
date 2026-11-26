<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Lowongan - KerjaKita</title>

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
            justify-content: space-between;
            align-items: center;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            color: white;
            font-size: 21px;
            font-weight: bold;
        }

        .logo {
            background: #91A58A;
            color: #293D32;
            padding: 10px;
            border-radius: 12px;
        }

        .nav-label {
            color: #E5E8DF;
            font-size: 14px;
        }

        .container {
            max-width: 900px;
            margin: 45px auto;
            padding: 0 22px 50px;
        }

        .back {
            display: inline-block;
            color: #647C67;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 25px;
        }

        .card {
            background: white;
            border: 1px solid #EAE8DF;
            border-radius: 18px;
            padding: 35px;
            box-shadow: 0 8px 25px rgba(41,61,50,0.05);
        }

        .eyebrow {
            color: #B76E50;
            font-size: 12px;
            font-weight: bold;
            letter-spacing: 2px;
            margin-bottom: 12px;
        }

        h1 {
            font-size: 29px;
            margin-bottom: 13px;
        }

        .meta {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 25px;
        }

        .tag {
            background: #F0F2E9;
            color: #526451;
            padding: 7px 12px;
            border-radius: 20px;
            font-size: 12px;
        }

        .status {
            background: #EDF4EB;
            color: #456B49;
            padding: 7px 12px;
            border-radius: 20px;
            font-size: 12px;
        }

        .status.closed {
            background: #F9EDE8;
            color: #A45D40;
        }

        .divider {
            border: none;
            border-top: 1px solid #EEEDE7;
            margin: 25px 0;
        }

        .section {
            margin-bottom: 25px;
        }

        .section h3 {
            font-size: 16px;
            margin-bottom: 11px;
        }

        .section p {
            color: #737F73;
            font-size: 14px;
            line-height: 1.8;
            white-space: pre-line;
        }

        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .info-item {
            background: #F8F8F4;
            padding: 17px;
            border-radius: 10px;
        }

        .info-item span {
            display: block;
            color: #899187;
            font-size: 12px;
            margin-bottom: 8px;
        }

        .info-item strong {
            font-size: 14px;
            font-weight: 600;
        }

        .actions {
            display: flex;
            justify-content: flex-end;
            margin-top: 30px;
        }

        .btn {
            background: #B96F50;
            color: white;
            text-decoration: none;
            padding: 12px 22px;
            border-radius: 9px;
            font-size: 14px;
            font-weight: 600;
        }

        .btn:hover {
            background: #A45D40;
        }
        .btn-edit {
            display: inline-block;
            background: #293D32;
            color: white;
            text-decoration: none;
            padding: 11px 18px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            transition: 0.2s ease;
        }

        .btn-edit:hover {
            background: #3D5845;
            transform: translateY(-1px);
        }
        @media (max-width: 600px) {
            .card {
                padding: 23px 18px;
            }

            .info-grid {
                grid-template-columns: 1fr;
            }

            h1 {
                font-size: 24px;
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

    <a href="{{ route('penyedia.lowongan.index') }}" class="back">
        ← Kembali ke Daftar Lowongan
    </a>

    <div class="card">

        <div class="eyebrow">DETAIL LOWONGAN</div>

        <h1>{{ $lowongan->judul_posisi }}</h1>

        <div class="meta">
            <span class="tag">{{ $lowongan->kategori->nama_kategori }}</span>
            <span class="tag">{{ ucfirst(str_replace('_', ' ', $lowongan->jenis_pekerjaan)) }}</span>

            @if ($lowongan->status_aktif)
                <span class="status">Aktif</span>
            @else
                <span class="status closed">Ditutup</span>
            @endif
        </div>

        <hr class="divider">

        <div class="section">
            <h3>Informasi Pekerjaan</h3>

            <div class="info-grid">
                <div class="info-item">
                    <span>Tipe Lokasi</span>
                    <strong>{{ ucfirst($lowongan->tipe_lokasi) }}</strong>
                </div>

                <div class="info-item">
                    <span>Lokasi Kota</span>
                    <strong>{{ $lowongan->lokasi_kota ?: 'Belum ditentukan' }}</strong>
                </div>

                <div class="info-item">
                    <span>Waktu Kerja</span>
                    <strong>{{ $lowongan->waktu_kerja ?: 'Belum ditentukan' }}</strong>
                </div>

                <div class="info-item">
                    <span>Kompensasi / Benefit</span>
                    <strong>{{ $lowongan->kompensasi ?: 'Tidak dicantumkan' }}</strong>
                </div>
            </div>
        </div>

        <hr class="divider">

        <div class="section">
            <h3>Deskripsi Pekerjaan</h3>
            <p>{{ $lowongan->deskripsi }}</p>
        </div>

        <div class="section">
            <h3>Persyaratan</h3>
            <p>{{ $lowongan->persyaratan ?: 'Tidak ada persyaratan khusus yang dicantumkan.' }}</p>
        </div>

        <div class="actions">
            <a href="{{ route('penyedia.lowongan.index') }}" class="btn">
                Kembali ke Daftar
            </a>
        </div>

    </div>

</main>

</body>
</html>
