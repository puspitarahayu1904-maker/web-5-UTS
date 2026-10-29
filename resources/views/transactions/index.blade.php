@extends('layouts.app')

@section('title', 'Transaksi Penjualan')

@section('content')
<div class="page-header">
    <h1>Transaksi Penjualan</h1>
    <p>Proses penjualan baru dan lihat riwayat transaksi.</p>
</div>

<div class="row g-4">
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header bg-white py-3">
                <h6 class="card-title-bar mb-0"><i class="bi bi-cart-plus text-success"></i> Kasir / Transaksi Baru</h6>
            </div>
            <div class="card-body">
                @if($products->isEmpty())
                    <div class="empty-state p-0">
                        <i class="bi bi-box"></i>
                        Belum ada produk. <a href="{{ route('products.index') }}">Tambah produk</a> terlebih dahulu.
                    </div>
                @else
                    <form action="{{ route('transactions.store') }}" method="POST" id="cashier-form" novalidate>
                        @csrf
                        <div class="mb-3">
                            <label for="product_id" class="form-label fw-semibold">Pilih Produk</label>
                            <select name="product_id" id="product_id" class="form-select @error('product_id') is-invalid @enderror" required>
                                <option value="" data-price="0">-- Pilih produk --</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}"
                                            data-price="{{ (int) $product->price }}"
                                            data-stock="{{ $product->stock }}"
                                            @disabled($product->stock === 0)
                                            @selected(old('product_id') == $product->id)>
                                        {{ $product->name }} — Stok {{ $product->stock }}
                                        @if($product->stock === 0) (habis) @endif
                                    </option>
                                @endforeach
                            </select>
                            @error('product_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="quantity" class="form-label fw-semibold">Jumlah Beli</label>
                            <input type="number" name="quantity" id="quantity" min="1" value="{{ old('quantity', 1) }}"
                                   class="form-control @error('quantity') is-invalid @enderror" required>
                            @error('quantity') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="rounded-3 bg-light p-3 mb-3 d-flex justify-content-between align-items-center">
                            <span class="text-muted">Total Bayar</span>
                            <span class="fs-4 fw-bold text-success" id="total-preview">Rp 0</span>
                        </div>

                        <button type="submit" class="btn btn-success w-100">
                            <i class="bi bi-check2-circle me-1"></i> Proses Transaksi
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h6 class="card-title-bar mb-0"><i class="bi bi-receipt text-primary"></i> Riwayat Transaksi</h6>
                <span class="badge bg-secondary-subtle text-dark">{{ $sales->count() }} transaksi</span>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>No Invoice</th>
                            <th>Tanggal</th>
                            <th>Kasir</th>
                            <th class="text-end">Total</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sales as $sale)
                            <tr>
                                <td><span class="code-chip">{{ $sale->invoice_number }}</span></td>
                                <td>{{ $sale->created_at->format('d M Y, H:i') }}</td>
                                <td>{{ $sale->user?->name ?? '-' }}</td>
                                <td class="text-end fw-semibold">Rp {{ number_format($sale->total_amount, 0, ',', '.') }}</td>
                                <td class="text-center">
                                    <a href="{{ route('transactions.show', $sale->invoice_number) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye"></i> Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="empty-state">
                                    <i class="bi bi-receipt"></i>
                                    Belum ada transaksi penjualan.
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

@push('scripts')
<script>
    (function () {
        const select = document.getElementById('product_id');
        const qty = document.getElementById('quantity');
        const preview = document.getElementById('total-preview');
        if (!select || !qty || !preview) return;

        const rupiah = (n) => 'Rp ' + Number(n).toLocaleString('id-ID');

        function refresh() {
            const option = select.options[select.selectedIndex];
            const price = Number(option.dataset.price || 0);
            const quantity = Math.max(1, parseInt(qty.value, 10) || 0);
            preview.textContent = rupiah(price * quantity);
        }

        select.addEventListener('change', refresh);
        qty.addEventListener('input', refresh);
        refresh();
    })();
</script>
@endpush
