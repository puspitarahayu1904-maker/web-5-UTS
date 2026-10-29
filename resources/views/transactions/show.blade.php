@extends('layouts.app')

@section('title', 'Detail Transaksi')

@section('content')
<div class="page-header d-flex flex-wrap justify-content-between align-items-end gap-2">
    <div>
        <h1>Detail Transaksi</h1>
        <p>Nomor invoice <span class="code-chip">{{ $sale->invoice_number }}</span></p>
    </div>
    <div class="d-flex gap-2">
        <button type="button" class="btn btn-outline-secondary" onclick="window.print()">
            <i class="bi bi-printer me-1"></i> Cetak
        </button>
        <a href="{{ route('transactions.index') }}" class="btn btn-brand">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Riwayat
        </a>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header bg-white py-3">
                <h6 class="card-title-bar mb-0"><i class="bi bi-list-ul text-primary"></i> Rincian Barang</h6>
            </div>
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Produk</th>
                            <th class="text-end">Harga</th>
                            <th class="text-center">Qty</th>
                            <th class="text-end">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sale->details as $detail)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td class="fw-semibold">{{ $detail->product?->name ?? 'Produk telah dihapus' }}</td>
                                <td class="text-end">Rp {{ number_format($detail->price, 0, ',', '.') }}</td>
                                <td class="text-center">{{ $detail->quantity }}</td>
                                <td class="text-end">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="empty-state">Tidak ada rincian barang.</td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr class="table-light">
                            <th colspan="4" class="text-end">Total Pembayaran</th>
                            <th class="text-end fs-5 text-success">Rp {{ number_format($sale->total_amount, 0, ',', '.') }}</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-5 text-muted fw-normal">No. Invoice</dt>
                    <dd class="col-7 fw-semibold">{{ $sale->invoice_number }}</dd>

                    <dt class="col-5 text-muted fw-normal">Tanggal</dt>
                    <dd class="col-7">{{ $sale->created_at->translatedFormat('d F Y, H:i') }}</dd>

                    <dt class="col-5 text-muted fw-normal">Kasir</dt>
                    <dd class="col-7">{{ $sale->user?->name ?? '-' }}</dd>
                </dl>
                <hr>
                <div class="alert alert-success mb-0 small">
                    <i class="bi bi-check-circle me-1"></i>
                    Transaksi berhasil dan stok produk sudah diperbarui otomatis.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
