<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard - Sistem MBG</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Dashboard Admin Gudang</h2>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-danger">Logout</button>
                </form>
            </div>
            <div class="card">
                <div class="card-body">
                    <h5>Selamat datang, {{ auth()->user()->name }}!</h5>
                    <p>Anda login sebagai: <strong>{{ auth()->user()->role }}</strong></p>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>