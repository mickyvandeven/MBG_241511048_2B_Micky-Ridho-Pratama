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

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
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
                                                <td>
                                                    @if($item->status_otomatis === 'Kadaluarsa')
                                                        <form action="{{ route('admin.bahan-baku.destroy', $item) }}" 
                                                              method="POST" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="button" class="btn btn-sm btn-danger" 
                                                                    onclick="confirmDelete(this, '{{ $item->nama }}', '{{ $item->kategori }}', '{{ $item->tanggal_kadaluarsa->format('d/m/Y') }}')"
                                                                    title="Hapus Bahan Baku">
                                                                Hapus
                                                            </button>
                                                        </form>
                                                    @else
                                                        <button class="btn btn-sm btn-secondary" 
                                                                onclick="showCannotDeleteAlert('{{ $item->nama }}', '{{ $item->status_otomatis }}')"
                                                                title="Tidak dapat dihapus">
                                                            Hapus
                                                        </button>
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

    <!-- Modal Konfirmasi Hapus -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">Konfirmasi Hapus Bahan Baku</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <strong>Peringatan!</strong> Tindakan ini tidak dapat dibatalkan.
                    </div>
                    <p>Apakah Anda yakin ingin menghapus bahan baku berikut?</p>
                    <table class="table table-borderless">
                        <tr>
                            <td><strong>Nama Bahan</strong></td>
                            <td>: <span id="deleteName"></span></td>
                        </tr>
                        <tr>
                            <td><strong>Kategori</strong></td>
                            <td>: <span id="deleteCategory"></span></td>
                        </tr>
                        <tr>
                            <td><strong>Tanggal Kadaluarsa</strong></td>
                            <td>: <span id="deleteExpiredDate"></span></td>
                        </tr>
                        <tr>
                            <td><strong>Status</strong></td>
                            <td>: <span class="badge bg-danger">Kadaluarsa</span></td>
                        </tr>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Ya, Hapus Bahan Baku</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Tidak Dapat Dihapus -->
    <div class="modal fade" id="cannotDeleteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title">Tidak Dapat Dihapus</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info">
                        <strong>Informasi:</strong> Hanya bahan baku yang sudah kadaluarsa yang dapat dihapus.
                    </div>
                    <p>Bahan baku <strong id="cannotDeleteName"></strong> dengan status <strong id="cannotDeleteStatus"></strong> tidak dapat dihapus.</p>
                    <p><strong>Alasan:</strong> Sistem hanya mengizinkan penghapusan bahan baku yang sudah kadaluarsa untuk menjaga integritas data.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Mengerti</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let currentForm = null;

        function confirmDelete(button, nama, kategori, tanggalKadaluarsa) {
            currentForm = button.closest('form');
            
            // Set data ke modal
            document.getElementById('deleteName').textContent = nama;
            document.getElementById('deleteCategory').textContent = kategori;
            document.getElementById('deleteExpiredDate').textContent = tanggalKadaluarsa;
            
            // Tampilkan modal
            const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
            modal.show();
        }

        function showCannotDeleteAlert(nama, status) {
            // Set data ke modal
            document.getElementById('cannotDeleteName').textContent = nama;
            document.getElementById('cannotDeleteStatus').textContent = status;
            
            // Tampilkan modal
            const modal = new bootstrap.Modal(document.getElementById('cannotDeleteModal'));
            modal.show();
        }

        // Event listener untuk tombol konfirmasi hapus
        document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
            if (currentForm) {
                currentForm.submit();
            }
        });
    </script>
</body>
</html>