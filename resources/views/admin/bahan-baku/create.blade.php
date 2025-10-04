<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Bahan Baku - MBG</title>
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
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-success text-white">
                        <h4 class="mb-0">
                            Input Bahan Baku Baru
                        </h4>
                    </div>
                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <form action="{{ route('admin.bahan-baku.store') }}" method="POST">
                            @csrf
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="nama" class="form-label">Nama Bahan Baku <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           class="form-control @error('nama') is-invalid @enderror" 
                                           id="nama" 
                                           name="nama" 
                                           value="{{ old('nama') }}" 
                                           placeholder="Contoh: Tepung Terigu"
                                           required>
                                    @error('nama')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="kategori" class="form-label">Kategori <span class="text-danger">*</span></label>
                                    <select class="form-select @error('kategori') is-invalid @enderror" 
                                            id="kategori" 
                                            name="kategori" 
                                            required>
                                        <option value="">Pilih Kategori</option>
                                        <option value="Tepung" {{ old('kategori') == 'Tepung' ? 'selected' : '' }}>Tepung</option>
                                        <option value="Gula" {{ old('kategori') == 'Gula' ? 'selected' : '' }}>Gula</option>
                                        <option value="Mentega" {{ old('kategori') == 'Mentega' ? 'selected' : '' }}>Mentega</option>
                                        <option value="Telur" {{ old('kategori') == 'Telur' ? 'selected' : '' }}>Telur</option>
                                        <option value="Susu" {{ old('kategori') == 'Susu' ? 'selected' : '' }}>Susu</option>
                                        <option value="Bumbu" {{ old('kategori') == 'Bumbu' ? 'selected' : '' }}>Bumbu</option>
                                        <option value="Pewarna" {{ old('kategori') == 'Pewarna' ? 'selected' : '' }}>Pewarna</option>
                                        <option value="Lainnya" {{ old('kategori') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                                    </select>
                                    @error('kategori')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="jumlah" class="form-label">Jumlah <span class="text-danger">*</span></label>
                                    <input type="number" 
                                           class="form-control @error('jumlah') is-invalid @enderror" 
                                           id="jumlah" 
                                           name="jumlah" 
                                           value="{{ old('jumlah') }}" 
                                           min="1"
                                           placeholder="Contoh: 50"
                                           required>
                                    @error('jumlah')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="satuan" class="form-label">Satuan <span class="text-danger">*</span></label>
                                    <select class="form-select @error('satuan') is-invalid @enderror" 
                                            id="satuan" 
                                            name="satuan" 
                                            required>
                                        <option value="">Pilih Satuan</option>
                                        <option value="kg" {{ old('satuan') == 'kg' ? 'selected' : '' }}>Kilogram (kg)</option>
                                        <option value="gram" {{ old('satuan') == 'gram' ? 'selected' : '' }}>Gram</option>
                                        <option value="liter" {{ old('satuan') == 'liter' ? 'selected' : '' }}>Liter</option>
                                        <option value="ml" {{ old('satuan') == 'ml' ? 'selected' : '' }}>Mililiter (ml)</option>
                                        <option value="pcs" {{ old('satuan') == 'pcs' ? 'selected' : '' }}>Pieces (pcs)</option>
                                        <option value="pak" {{ old('satuan') == 'pak' ? 'selected' : '' }}>Paket</option>
                                        <option value="botol" {{ old('satuan') == 'botol' ? 'selected' : '' }}>Botol</option>
                                    </select>
                                    @error('satuan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="tanggal_masuk" class="form-label">Tanggal Masuk <span class="text-danger">*</span></label>
                                    <input type="date" 
                                           class="form-control @error('tanggal_masuk') is-invalid @enderror" 
                                           id="tanggal_masuk" 
                                           name="tanggal_masuk" 
                                           value="{{ old('tanggal_masuk', date('Y-m-d')) }}"
                                           required>
                                    @error('tanggal_masuk')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="tanggal_kadaluarsa" class="form-label">Tanggal Kadaluarsa <span class="text-danger">*</span></label>
                                    <input type="date" 
                                           class="form-control @error('tanggal_kadaluarsa') is-invalid @enderror" 
                                           id="tanggal_kadaluarsa" 
                                           name="tanggal_kadaluarsa" 
                                           value="{{ old('tanggal_kadaluarsa') }}"
                                           required>
                                    @error('tanggal_kadaluarsa')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="alert alert-info">
                                <strong>Informasi:</strong> Bahan baku akan otomatis disimpan dengan status <strong>"Tersedia"</strong>
                            </div>

                            <div class="d-flex justify-content-between">
                                <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                                    Kembali ke Dashboard
                                </a>
                                <button type="submit" class="btn btn-success">
                                    Simpan Bahan Baku
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Set minimum date for tanggal_kadaluarsa berdasarkan tanggal_masuk
        document.getElementById('tanggal_masuk').addEventListener('change', function() {
            document.getElementById('tanggal_kadaluarsa').min = this.value;
        });
    </script>
</body>
</html>