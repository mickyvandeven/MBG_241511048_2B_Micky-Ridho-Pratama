<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - MBG</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="#">
                MBG Admin Dashboard
            </a>
            <div class="navbar-nav ms-auto">
                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-outline-light">
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="row">
            <div class="col-12">
                <h2 class="mb-4">
                    Dashboard Admin Gudang
                </h2>
                <p class="lead">Selamat datang di sistem manajemen bahan baku MBG</p>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-6 col-lg-6 mb-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <h1 style="font-size: 4rem; color: #198754;">+</h1>
                        </div>
                        <h4 class="card-title">Input Bahan Baku</h4>
                        <p class="card-text">Tambahkan bahan baku baru dengan status otomatis "Tersedia"</p>
                        <a href="{{ route('admin.bahan-baku.create') }}" class="btn btn-success btn-lg">
                            Input Bahan Baku Baru
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-6 mb-4">
                <div class="card h-100 shadow-sm border-info">
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <h1 style="font-size: 4rem; color: #0dcaf0;">i</h1>
                        </div>
                        <h4 class="card-title">Informasi System</h4>
                        <p class="card-text">
                            Sistem otomatis menyimpan bahan baku dengan status <strong>"Tersedia"</strong> 
                            dan validasi tanggal kadaluarsa
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-12">
                <div class="alert alert-info">
                    <h5>Fitur Bahan Baku</h5>
                    <ul class="mb-0">
                        <li>Input bahan baku baru dengan atribut lengkap (nama, kategori, jumlah, satuan, tanggal masuk, tanggal kadaluarsa)</li>
                        <li>Status otomatis diset sebagai "Tersedia" saat input baru</li>
                        <li>Validasi tanggal kadaluarsa harus setelah tanggal masuk</li>
                        <li>Kelola dan update status bahan baku (Tersedia, Habis, Kadaluarsa)</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>