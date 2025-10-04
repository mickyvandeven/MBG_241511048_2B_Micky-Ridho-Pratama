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
            <a class="navbar-brand" href="{{ route('admin.dashboard') }}">
                Sistem Informasi Manajemen Bahan Gudang (MBG)
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
                    Dashboard Gudang
                </h2>
                <p class="lead">Selamat datang di sistem manajemen bahan baku MBG</p>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <h1 style="font-size: 3rem; color: #198754;">+</h1>
                        </div>
                        <h5 class="card-title">Input Bahan Baku</h5>
                        <p class="card-text">Tambahkan bahan baku baru</p>
                        <a href="{{ route('admin.bahan-baku.create') }}" class="btn btn-success">
                            Input Bahan Baku
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <h1 style="font-size: 3rem; color: #0d6efd;">📋</h1>
                        </div>
                        <h5 class="card-title">Daftar Bahan Baku</h5>
                        <p class="card-text">Lihat daftar dan status otomatis</p>
                        <a href="{{ route('admin.bahan-baku.index') }}" class="btn btn-primary">
                            Lihat Daftar
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <h1 style="font-size: 3rem; color: #fd7e14;">📝</h1>
                        </div>
                        <h5 class="card-title">Status Permintaan</h5>
                        <p class="card-text">Lihat semua permintaan & statusnya</p>
                        <a href="{{ route('admin.permintaan.index') }}" class="btn btn-warning">
                            Lihat Permintaan
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm border-info">
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <h1 style="font-size: 3rem; color: #0dcaf0;">i</h1>
                        </div>
                        <h5 class="card-title">Status Otomatis</h5>
                        <p class="card-text">
                            Sistem menghitung status berdasarkan stok dan tanggal kadaluarsa
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-12">
                <div class="alert alert-info">
                    <h5>Aturan Status Otomatis Bahan Baku</h5>
                    <ul class="mb-0">
                        <li><strong>Habis:</strong> jika jumlah = 0</li>
                        <li><strong>Kadaluarsa:</strong> jika hari_ini ≥ tanggal_kadaluarsa</li>
                        <li><strong>Segera Kadaluarsa:</strong> jika tanggal_kadaluarsa - hari_ini ≤ 3 hari dan stok > 0</li>
                        <li><strong>Tersedia:</strong> jika stok > 0 dan tidak masuk kondisi di atas</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>