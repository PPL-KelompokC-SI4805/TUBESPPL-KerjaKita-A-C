
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Lowongan - KerjaKita</title>

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
            background: #E8EFE5;
            border-bottom: 1px solid #D5DFD0;
            padding: 15px 7%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 8px rgba(41,61,50,0.04);
        }

        .brand {
            display: flex;
            align-items: center;
        }

        .brand img {
            width: 190px;
            height: 65px;
            object-fit: contain;
        }

        .nav-label {
            color: #536C58;
            font-size: 14px;
            font-weight: 600;
        }

        .container {
            max-width: 1100px;
            margin: 50px auto;
            padding: 0 25px;
        }

        .heading {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            gap: 20px;
        }

        .eyebrow {
            color: #B96F50;
            font-size: 12px;
            font-weight: bold;
            letter-spacing: 2px;
            margin-bottom: 10px;
        }

        h1 {
            font-size: 29px;
            margin-bottom: 9px;
            color: #293D32;
        }

        .subtitle {
            color: #7C867B;
            font-size: 14px;
        }

        .btn-add {
            background: #B96F50;
            color: white;
            text-decoration: none;
            padding: 13px 20px;
            border-radius: 9px;
            font-size: 14px;
            font-weight: 600;
            white-space: nowrap;
            transition: 0.2s ease;
        }

        .btn-add:hover {
            background: #A45D40;
        }

        .content {
            background: white;
            border: 1px solid #EAE8DF;
            border-radius: 18px;
            padding: 28px;
            box-shadow: 0 8px 25px rgba(41,61,50,0.05);
        }

        .success {
            background: #E8EFE5;
            color: #456B49;
            padding: 14px 18px;
            border-radius: 9px;
            margin-bottom: 20px;
            border: 1px solid #D5DFD0;
        }

        .empty {
            text-align: center;
            padding: 65px 20px;
        }

        .empty-icon {
            width: 75px;
            height: 75px;
            background: #E8EFE5;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 22px;
            font-size: 30px;
            color: #536C58;
        }

        .empty h3 {
            font-size: 20px;
            margin-bottom: 10px;
        }

        .empty p {
            color: #879084;
            font-size: 14px;
        }

        .table-wrapper {
            width: 100%;
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 850px;
        }

        th {
            background: #E8EFE5;
            color: #405B47;
            padding: 15px;
            font-size: 13px;
            text-align: left;
        }

        td {
            padding: 17px 15px;
            border-bottom: 1px solid #EEEDE7;
            font-size: 14px;
        }

        .status {
            display: inline-block;
            background: #E8EFE5;
            color: #456B49;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .status.closed {
            background: #F9EDE8;
            color: #A45D40;
        }

        .action-buttons {
            display: flex;
            align-items: center;
            gap: 8px;
            white-space: nowrap;
        }

        .action-buttons form {
            margin: 0;
        }

        .btn-detail,
        .btn-edit {
            display: inline-block;
            padding: 9px 13px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 600;
            text-decoration: none;
            transition: 0.2s ease;
            white-space: nowrap;
        }

        .btn-detail {
            background: #E8EFE5;
            color: #405B47;
            border: 1px solid #D5DFD0;
        }

        .btn-detail:hover {
            background: #D5DFD0;
        }

        .btn-edit {
            background: #293D32;
            color: white;
        }

        .btn-edit:hover {
            background: #405B47;
        }

        .btn-delete,
        .btn-status {
            border: none;
            padding: 9px 13px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            white-space: nowrap;
            transition: 0.2s ease;
        }

        .btn-delete {
            background: #F9EDE8;
            color: #A45D40;
        }

        .btn-delete:hover {
            background: #A45D40;
            color: white;
        }

        .btn-close {
            background: #F9EDE8;
            color: #B96F50;
        }

        .btn-open {
            background: #E8EFE5;
            color: #456B49;
        }

        .btn-status:hover {
            opacity: 0.8;
        }

        @media (max-width: 650px) {
            .heading {
                align-items: flex-start;
                flex-direction: column;
            }

            .content {
                padding: 15px;
            }

            .brand img {
                width: 145px;
            }

            .nav-label {
                font-size: 12px;
            }
        }
    </style>
</head>

<body>

<header class="navbar">
    <div class="brand">
        <img src="{{ asset('images/kerjakita-logo.png') }}"
             alt="Logo KerjaKita">
    </div>

    <span class="nav-label">Portal Penyedia</span>
</header>

<main class="container">

    <div class="heading">
        <div>
            <div class="eyebrow">MANAJEMEN LOWONGAN</div>
            <h1>Daftar Lowongan Saya</h1>
            <p class="subtitle">
                Kelola dan pantau lowongan pekerjaan yang telah kamu buat.
            </p>
        </div>

        <a href="{{ route('penyedia.lowongan.create') }}" class="btn-add">
            + Tambah Lowongan
        </a>
    </div>

    @if (session('success'))
        <div class="success">
            {{ session('success') }}
        </div>
    @endif

    <section class="content">

        @if ($lowongan->isEmpty())

            <div class="empty">
                <div class="empty-icon">▤</div>
                <h3>Belum Ada Lowongan</h3>
                <p>
                    Lowongan pekerjaan yang kamu buat akan ditampilkan di sini.
                </p>
            </div>

        @else

            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Judul Posisi</th>
                            <th>Kategori</th>
                            <th>Jenis Pekerjaan</th>
                            <th>Lokasi</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($lowongan as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->judul_posisi }}</td>
                                <td>{{ $item->kategori->nama_kategori }}</td>
                                <td>{{ $item->jenis_pekerjaan }}</td>
                                <td>{{ $item->lokasi_kota ?? '-' }}</td>

                                <td>
                                    @if ($item->status_aktif)
                                        <span class="status">Aktif</span>
                                    @else
                                        <span class="status closed">Ditutup</span>
                                    @endif
                                </td>

                                <td>
                                    <div class="action-buttons">

                                        <a href="{{ route('penyedia.lowongan.show', $item->id) }}"
                                           class="btn-detail">
                                            Detail
                                        </a>

                                        <a href="{{ route('penyedia.lowongan.edit', $item->id) }}"
                                           class="btn-edit">
                                            ✎ Edit
                                        </a>

                                        <form action="{{ route('penyedia.lowongan.toggle-status', $item->id) }}"
                                              method="POST"
                                              onsubmit="return confirm('{{ $item->status_aktif ? 'Tutup' : 'Aktifkan kembali' }} lowongan ini?');">
                                            @csrf
                                            @method('PATCH')

                                            <button type="submit"
                                                class="btn-status {{ $item->status_aktif ? 'btn-close' : 'btn-open' }}">
                                                {{ $item->status_aktif ? 'Tutup' : 'Aktifkan Kembali' }}
                                            </button>
                                        </form>

                                        <form action="{{ route('penyedia.lowongan.destroy', $item->id) }}"
                                              method="POST"
                                              onsubmit="return confirm('Apakah kamu yakin ingin menghapus lowongan ini? Data yang dihapus tidak dapat dikembalikan.');">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit" class="btn-delete">
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
