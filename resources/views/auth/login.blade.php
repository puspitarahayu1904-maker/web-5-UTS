<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Inventaris</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #f8f9fa;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            width: 100%;
            max-width: 400px;
            border: none;
            border-radius: 1rem;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.05);
        }
    </style>
</head>
<body>

    <div class="card login-card p-4 bg-white">
        <div class="card-body">
            <div class="text-center mb-4">
                <div class="bg-primary text-white rounded-3 d-inline-flex p-3 mb-2 shadow-sm">
                    <i class="bi bi-box-seam fs-3"></i>
                </div>
                <h4 class="fw-bold text-dark">Sistem Inventaris</h4>
                <p class="text-muted small">Silakan masuk ke akun Anda</p>
            </div>

            <!-- Pesan Error jika Login Gagal -->
            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show rounded-3 small py-2" role="alert">
                    {{ $errors->first() }}
                    <button type="button" class="btn-close py-2" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- FORM LOGIN (Sudah Dilengkapi @csrf) -->
            <form action="{{ url('/login') }}" method="POST">
                @csrf
                
                <div class="mb-3">
                    <label class="form-label small fw-bold text-secondary">Email</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0 rounded-start-3"><i class="bi bi-envelope text-muted"></i></span>
                        <input type="email" name="email" class="form-control bg-light border-start-0 rounded-end-3" placeholder="nama@email.com" required value="{{ old('email') }}">
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label small fw-bold text-secondary">Password</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0 rounded-start-3"><i class="bi bi-lock text-muted"></i></span>
                        <input type="password" name="password" class="form-control bg-light border-start-0 rounded-end-3" placeholder="••••••••" required>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100 rounded-pill py-2 shadow-sm fw-bold">Masuk Sekarang</button>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>