<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Bahan Baku - MBG</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="{{ route('admin.dashboard') }}">
                Sistem Informasi Manajemen Bahan Gudang (MBG)
            </a>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2>Daftar Bahan Baku</h2>
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                        Kembali ke Dashboard
                    </a>
                </div>

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <div class="card">
                    <div class="card-body">
                        @if($bahanBaku->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Bahan</th>
                                            <th>Kategori</th>
                                            <th>Jumlah</th>
                                            <th>Satuan</th>
                                            <th>Tanggal Masuk</th>
                                            <th>Tanggal Kadaluarsa</th>
                                            <th>Status (Otomatis)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($bahanBaku as $index => $item)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td><strong>{{ $item->nama }}</strong></td>
                                                <td>{{ $item->kategori }}</td>
                                                <td>{{ number_format($item->jumlah) }}</td>
                                                <td>{{ $item->satuan }}</td>
                                                <td>{{ $item->tanggal_masuk->format('d/m/Y') }}</td>
                                                <td>{{ $item->tanggal_kadaluarsa->format('d/m/Y') }}</td>
                                                <td>
                                                    @if($item->status_otomatis === 'Tersedia')
                                                        <span class="badge bg-success">{{ $item->status_otomatis }}</span>
                                                    @elseif($item->status_otomatis === 'Segera Kadaluarsa')
                                                        <span class="badge bg-warning">{{ $item->status_otomatis }}</span>
                                                    @elseif($item->status_otomatis === 'Kadaluarsa')
                                                        <span class="badge bg-danger">{{ $item->status_otomatis }}</span>
                                                    @else
                                                        <span class="badge bg-secondary">{{ $item->status_otomatis }}</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <h1 style="font-size: 3rem; color: #ccc;">📦</h1>
                                <h4 class="mt-3 text-muted">Belum ada data bahan baku</h4>
                                <p class="text-muted">Silakan input bahan baku melalui dashboard admin</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>