<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') · Sistem Inventaris & Penjualan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        :root {
            --brand: #2563eb;
            --brand-dark: #1e3a8a;
            --surface: #ffffff;
            --page-bg: #f1f5f9;
            --muted: #64748b;
        }
        body { background: var(--page-bg); font-family: system-ui, -apple-system, "Segoe UI", Roboto, sans-serif; color: #1e293b; }
        .app-navbar { background: linear-gradient(90deg, var(--brand-dark), var(--brand)); }
        .app-navbar .nav-link { color: rgba(255,255,255,.85); border-radius: .5rem; padding: .5rem .85rem; }
        .app-navbar .nav-link:hover, .app-navbar .nav-link.active { color: #fff; background: rgba(255,255,255,.15); }
        .brand-mark { width: 34px; height: 34px; border-radius: .6rem; background: rgba(255,255,255,.2); display: inline-flex; align-items: center; justify-content: center; }
        .page-header { margin-bottom: 1.5rem; }
        .page-header h1 { font-size: 1.6rem; font-weight: 700; margin-bottom: .2rem; }
        .page-header p { color: var(--muted); margin: 0; }
        .card { border: 1px solid #e2e8f0; border-radius: .9rem; box-shadow: 0 1px 2px rgba(15,23,42,.04); }
        .card-title-bar { font-weight: 600; display: flex; align-items: center; gap: .5rem; }
        .stat-card { border: none; color: #fff; border-radius: 1rem; padding: 1.25rem; height: 100%; }
        .stat-card .stat-icon { width: 46px; height: 46px; border-radius: .8rem; background: rgba(255,255,255,.2); display: flex; align-items: center; justify-content: center; font-size: 1.35rem; }
        .stat-card .stat-label { font-size: .85rem; opacity: .9; }
        .stat-card .stat-value { font-size: 1.6rem; font-weight: 700; line-height: 1.1; }
        .stat-blue { background: linear-gradient(135deg, #3b82f6, #2563eb); }
        .stat-green { background: linear-gradient(135deg, #22c55e, #15803d); }
        .stat-amber { background: linear-gradient(135deg, #f59e0b, #d97706); }
        .stat-rose { background: linear-gradient(135deg, #f43f5e, #be123c); }
        .table thead th { font-size: .8rem; text-transform: uppercase; letter-spacing: .04em; color: var(--muted); background: #f8fafc; border-bottom-width: 1px; white-space: nowrap; }
        .table td { vertical-align: middle; }
        .code-chip { font-family: ui-monospace, SFMono-Regular, Menlo, monospace; font-size: .8rem; background: #f1f5f9; border-radius: .4rem; padding: .15rem .45rem; color: #334155; }
        .empty-state { text-align: center; color: var(--muted); padding: 2rem 1rem; }
        .empty-state i { font-size: 2.2rem; display: block; margin-bottom: .5rem; opacity: .5; }
        .btn-brand { background: var(--brand); border-color: var(--brand); color: #fff; }
        .btn-brand:hover { background: var(--brand-dark); border-color: var(--brand-dark); color: #fff; }
        .form-control, .form-select { border-radius: .6rem; }
        .btn { border-radius: .6rem; }
        .flash-stack { position: fixed; top: 74px; right: 16px; z-index: 1080; width: min(380px, calc(100% - 32px)); }
    </style>
    @stack('styles')
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark app-navbar shadow-sm sticky-top">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center gap-2 fw-bold" href="{{ route('dashboard') }}">
            <span class="brand-mark"><i class="bi bi-box-seam"></i></span>
            Inventaris &amp; Penjualan
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav" aria-controls="mainNav" aria-expanded="false" aria-label="Buka menu">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNav">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 gap-lg-1 mt-2 mt-lg-0">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                        <i class="bi bi-speedometer2 me-1"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}" href="{{ route('products.index') }}">
                        <i class="bi bi-boxes me-1"></i> Data Produk
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('transactions.*') ? 'active' : '' }}" href="{{ route('transactions.index') }}">
                        <i class="bi bi-cart-check me-1"></i> Transaksi
                    </a>
                </li>
                @if(auth()->user()->isAdmin())
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}" href="{{ route('users.index') }}">
                            <i class="bi bi-people me-1"></i> Kelola Staf
                        </a>
                    </li>
                @endif
            </ul>

            <div class="d-flex align-items-center gap-3 text-white">
                <div class="text-end lh-sm">
                    <div class="fw-semibold">{{ auth()->user()->name }}</div>
                    <span class="badge {{ auth()->user()->isAdmin() ? 'bg-danger' : 'bg-light text-dark' }}">
                        {{ auth()->user()->isAdmin() ? 'Admin' : 'Staff' }}
                    </span>
                </div>
                <form action="{{ route('logout') }}" method="POST" class="m-0">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-light">
                        <i class="bi bi-box-arrow-right me-1"></i> Keluar
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>

<div class="flash-stack">
    @foreach (['success' => 'success', 'error' => 'danger'] as $key => $color)
        @if (session($key))
            <div class="alert alert-{{ $color }} alert-dismissible fade show shadow-sm" role="alert">
                <i class="bi {{ $key === 'success' ? 'bi-check-circle' : 'bi-exclamation-triangle' }} me-1"></i>
                {{ session($key) }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
            </div>
        @endif
    @endforeach
</div>

<main class="container py-4">
    @yield('content')
</main>

<footer class="container pb-4 text-center small text-muted">
    Sistem Inventaris &amp; Penjualan · Tugas UTS
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Konfirmasi sebelum aksi berbahaya (form dengan atribut data-confirm)
    document.addEventListener('submit', function (e) {
        var message = e.target.dataset.confirm;
        if (message && !window.confirm(message)) {
            e.preventDefault();
        }
    });

    // Tutup notifikasi otomatis setelah 4 detik
    document.querySelectorAll('.flash-stack .alert').forEach(function (el) {
        setTimeout(function () {
            bootstrap.Alert.getOrCreateInstance(el).close();
        }, 4000);
    });
</script>
@stack('scripts')
</body>
</html>
