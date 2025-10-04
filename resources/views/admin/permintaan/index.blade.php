<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status Permintaan - MBG</title>
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
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Status Permintaan</h2>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-warning">Kembali ke Dashboard</a>
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

    <form method="GET" class="row g-2 mb-3">
        <div class="col-md-3">
            <select name="status" class="form-select" onchange="this.form.submit()">
                <option value="">-- Semua Status --</option>
                @foreach($availableStatuses as $st)
                    <option value="{{ $st }}" {{ $status === $st ? 'selected' : '' }}>{{ $st }}</option>
                @endforeach
            </select>
        </div>
        @if($status)
            <div class="col-md-2">
                <a href="{{ route('admin.permintaan.index') }}" class="btn btn-outline-secondary w-100">Reset</a>
            </div>
        @endif
    </form>

    <div class="card">
        <div class="card-body">
            @if($permintaan->count())
                <div class="table-responsive">
                    <table class="table table-hover table-striped align-middle">
                        <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Nama Peminta</th>
                            <th>Tanggal</th>
                            <th>Bahan yang Diminta</th>
                            <th>Jumlah Total yang Diminta</th>
                            <th>Detail Bahan</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($permintaan as $i => $p)
                            @php
                                $borderColor = '#333333';
                                $borderStyle = 'border-left: 4px solid #333333; background-color: rgba(51, 51, 51, 0.1);';
                                $details = $p->details;
                            @endphp
                            <tr style="{{ $borderStyle }}">
                                <td style="color: {{ $borderColor }}; font-weight: bold;">#{{ $p->id }}</td>
                                <td style="color: {{ $borderColor }};">{{ $p->nama_peminta }}</td>
                                <td>
                                    @php
                                        $dateDisplay = '-';
                                        if(isset($chosenDateColumn) && $chosenDateColumn){
                                            $val = $p->{$chosenDateColumn} ?? null;
                                            if($val){
                                                try {
                                                    $dateDisplay = \Illuminate\Support\Carbon::parse($val)->format('d/m/Y');
                                                } catch (Exception $e) {
                                                    $dateDisplay = $val; // fallback raw
                                                }
                                            }
                                        }
                                    @endphp
                                    {{ $dateDisplay }}
                                </td>
                                <td style="color: {{ $borderColor }};">
                                    @php
                                        // Tampilkan nama bahan saja tanpa kuantitas
                                        $bahanNames = [];
                                        foreach ($p->details as $d) {
                                            if ($d->bahan && $d->bahan->nama) {
                                                $bahanNames[] = $d->bahan->nama;
                                            }
                                        }
                                        $bahanValue = !empty($bahanNames) ? implode(', ', array_unique($bahanNames)) : '-';
                                    @endphp
                                    <span>{{ $bahanValue }}</span>
                                </td>
                                <td style="color: {{ $borderColor }}; font-weight: bold;">{{ number_format($p->total_quantity) }}</td>
                                <td>
                                    <div style="background-color: {{ $borderColor }}; color: white; padding: 4px 12px; border-radius: 6px; border: 2px solid {{ $borderColor }}; display: inline-block; font-size: 0.85em; font-weight: bold; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                                        {{ $p->status }}
                                    </div>
                                    @if($p->status === \App\Models\Permintaan::STATUS_MENUNGGU)
                                        <br><br>
                                        <form action="{{ route('admin.permintaan.approve', $p) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Setujui permintaan #{{ $p->id }}?')">Setujui</button>
                                        </form>
                                        <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal" data-id="{{ $p->id }}">Tolak</button>
                                    @endif
                                    <button type="button" class="btn btn-sm btn-outline-primary ms-1" data-bs-toggle="collapse" data-bs-target="#detail-{{ $p->id }}" aria-expanded="false">Detail</button>
                                    @if($p->status === \App\Models\Permintaan::STATUS_DITOLAK && !empty($p->alasan_penolakan))
                                        <div class="small text-muted mt-1">Alasan: {{ $p->alasan_penolakan }}</div>
                                    @endif
                                </td>
                            </tr>
                            <tr class="collapse" id="detail-{{ $p->id }}" style="background-color: rgba({{ $p->status === 'Menunggu' ? '108, 117, 125' : ($p->status === 'Disetujui' ? '25, 135, 84' : ($p->status === 'Diproses' ? '13, 202, 240' : ($p->status === 'Selesai' ? '13, 110, 253' : '220, 53, 69'))) }}, 0.05); border-left: 4px solid {{ $borderColor }};">
                                <td colspan="6" style="padding: 15px;">
                                    <strong>Detail Permintaan:</strong>
                                    @if($details->count())
                                        <div class="table-responsive mt-2">
                                            <table class="table table-sm table-bordered mb-0" style="border: 1px solid {{ $borderColor }};">
                                                <thead style="background-color: {{ $borderColor }}; color: white;">
                                                    <tr>
                                                        <th style="border-color: {{ $borderColor }};">#</th>
                                                        <th style="border-color: {{ $borderColor }};">Nama Bahan</th>
                                                        <th style="border-color: {{ $borderColor }};">Jumlah Diminta</th>
                                                        <th style="border-color: {{ $borderColor }};">Satuan</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($details as $idx => $d)
                                                        <tr style="border-color: {{ $borderColor }};">
                                                            <td style="border-color: {{ $borderColor }};">{{ $idx + 1 }}</td>
                                                            <td style="border-color: {{ $borderColor }};">{{ $d->bahan->nama ?? '—' }}</td>
                                                            <td style="border-color: {{ $borderColor }};">{{ number_format($d->jumlah) }}</td>
                                                            <td style="border-color: {{ $borderColor }};">{{ $d->bahan->satuan ?? '—' }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <p class="text-muted mb-0">Tidak ada detail item.</p>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <h1 style="font-size:3rem;color:#ccc;">🗂️</h1>
                    <h5 class="text-muted mt-3">Belum ada data permintaan</h5>
                    <p class="text-muted">Permintaan yang dibuat akan tampil di sini.</p>
                </div>
            @endif
        </div>
    </div>

        <div class="alert alert-info mt-4">
        <strong>Catatan:</strong>
        <ul class="mb-0">
                        <li>Status Menunggu: Baru diajukan dan menunggu tindakan gudang.</li>
                        <li>Status Disetujui: Stok berkurang sesuai permintaan.</li>
                        <li>Status Ditolak: Permintaan tidak disetujui.</li>
        </ul>
    </div>
</div>

<!-- Modal Tolak Permintaan -->
<div class="modal fade" id="rejectModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Tolak Permintaan</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" id="rejectForm">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Alasan Penolakan (opsional)</label>
                        <textarea name="alasan" class="form-control" rows="3" maxlength="255" placeholder="Contoh: Stok tidak mencukupi"></textarea>
                        <div class="form-text">Maksimum 255 karakter.</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Tolak Permintaan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const rejectModal = document.getElementById('rejectModal');
    if (rejectModal) {
        rejectModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const id = button.getAttribute('data-id');
            const form = document.getElementById('rejectForm');
            form.action = '{{ url('admin/permintaan') }}/' + id + '/reject';
        });
    }
</script>
</body>
</html>
