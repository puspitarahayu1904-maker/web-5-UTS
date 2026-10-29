@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="page-header d-flex flex-wrap justify-content-between align-items-end gap-2">
    <div>
        <h1>Dashboard</h1>
        <p>Ringkasan stok dan penjualan hari ini, {{ now()->translatedFormat('d F Y') }}.</p>
    </div>
    <a href="{{ route('transactions.index') }}" class="btn btn-brand">
        <i class="bi bi-plus-lg me-1"></i> Transaksi Baru
    </a>
</div>

<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="stat-card stat-blue shadow-sm">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-label">Total Produk</div>
                    <div class="stat-value">{{ number_format($totalProducts) }}</div>
                </div>
                <div class="stat-icon"><i class="bi bi-boxes"></i></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card stat-green shadow-sm">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-label">Total Transaksi</div>
                    <div class="stat-value">{{ number_format($totalSales) }}</div>
                </div>
                <div class="stat-icon"><i class="bi bi-receipt"></i></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card stat-amber shadow-sm">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-label">Total Pendapatan</div>
                    <div class="stat-value">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
                </div>
                <div class="stat-icon"><i class="bi bi-cash-stack"></i></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card stat-rose shadow-sm">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-label">Stok Menipis</div>
                    <div class="stat-value">{{ $lowStockProducts->count() }}</div>
                </div>
                <div class="stat-icon"><i class="bi bi-exclamation-triangle"></i></div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header bg-white py-3">
                <h6 class="card-title-bar mb-0"><i class="bi bi-exclamation-octagon text-danger"></i> Peringatan Stok Rendah (≤ {{ \App\Models\Product::LOW_STOCK_THRESHOLD }})</h6>
            </div>
            <ul class="list-group list-group-flush">
                @forelse($lowStockProducts as $product)
                    <li class="list-group-item d-flex justify-content-between align-items-center py-3">
                        <div>
                            <div class="fw-semibold">{{ $product->name }}</div>
                            <span class="code-chip">{{ $product->code }}</span>
                        </div>
                        <span class="badge rounded-pill {{ $product->stock === 0 ? 'bg-danger' : 'bg-warning text-dark' }} fs-6">
                            Sisa {{ $product->stock }}
                        </span>
                    </li>
                @empty
                    <li class="list-group-item empty-state">
                        <i class="bi bi-check-circle text-success"></i>
                        Semua stok produk aman.
                    </li>
                @endforelse
            </ul>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h6 class="card-title-bar mb-0"><i class="bi bi-clock-history text-primary"></i> Transaksi Terbaru</h6>
                <a href="{{ route('transactions.index') }}" class="small text-decoration-none">Lihat semua</a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Invoice</th>
                            <th>Kasir</th>
                            <th class="text-end">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentSales as $sale)
                            <tr>
                                <td>
                                    <a href="{{ route('transactions.show', $sale->invoice_number) }}" class="fw-semibold text-decoration-none">{{ $sale->invoice_number }}</a>
                                    <div class="small text-muted">{{ $sale->created_at->format('d M Y, H:i') }}</div>
                                </td>
                                <td>{{ $sale->user?->name ?? '-' }}</td>
                                <td class="text-end fw-semibold">Rp {{ number_format($sale->total_amount, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="empty-state">
                                    <i class="bi bi-receipt"></i>
                                    Belum ada transaksi.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
