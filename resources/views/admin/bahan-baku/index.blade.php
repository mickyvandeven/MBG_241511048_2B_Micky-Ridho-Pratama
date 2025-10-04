<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Bahan Baku - MBG</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="{{ route('admin.dashboard') }}">
                <i class="bi bi-building"></i> MBG Admin
            </a>
            <div class="navbar-nav ms-auto">
                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-outline-light">
                        <i class="bi bi-box-arrow-right"></i> Logout
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2><i class="bi bi-box-seam"></i> Kelola Bahan Baku</h2>
                    <a href="{{ route('admin.bahan-baku.create') }}" class="btn btn-success">
                        <i class="bi bi-plus-circle"></i> Tambah Bahan Baku
                    </a>
                </div>

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle"></i> {{ session('success') }}
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
                                            <th>Status</th>
                                            <th>Aksi</th>
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
                                                    @if($item->status === 'Tersedia')
                                                        <span class="badge bg-success">{{ $item->status }}</span>
                                                    @elseif($item->status === 'Habis')
                                                        <span class="badge bg-warning">{{ $item->status }}</span>
                                                    @else
                                                        <span class="badge bg-danger">{{ $item->status }}</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <a href="{{ route('admin.bahan-baku.show', $item) }}" 
                                                           class="btn btn-sm btn-info" title="Lihat Detail">
                                                            <i class="bi bi-eye"></i>
                                                        </a>
                                                        <a href="{{ route('admin.bahan-baku.edit', $item) }}" 
                                                           class="btn btn-sm btn-warning" title="Edit">
                                                            <i class="bi bi-pencil"></i>
                                                        </a>
                                                        <form action="{{ route('admin.bahan-baku.destroy', $item) }}" 
                                                              method="POST" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-danger" 
                                                                    onclick="return confirm('Yakin ingin menghapus bahan baku ini?')"
                                                                    title="Hapus">
                                                                <i class="bi bi-trash"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="bi bi-box-seam" style="font-size: 3rem; color: #ccc;"></i>
                                <h4 class="mt-3 text-muted">Belum ada data bahan baku</h4>
                                <p class="text-muted">Klik tombol "Tambah Bahan Baku" untuk mulai menginput data</p>
                                <a href="{{ route('admin.bahan-baku.create') }}" class="btn btn-success">
                                    <i class="bi bi-plus-circle"></i> Tambah Bahan Baku Pertama
                                </a>
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