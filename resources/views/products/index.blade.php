@extends('layouts.app')

@section('title', 'Data Produk')

@section('content')
<div class="page-header">
    <h1>Data Produk</h1>
    <p>Kelola daftar barang, stok awal, dan harga jual.</p>
</div>

<div class="row g-4">
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header bg-white py-3">
                <h6 class="card-title-bar mb-0"><i class="bi bi-plus-circle text-success"></i> Tambah Produk</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('products.store') }}" method="POST" novalidate>
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label fw-semibold">Nama Produk</label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}"
                               class="form-control @error('name') is-invalid @enderror"
                               placeholder="Contoh: Buku Tulis A5" required>
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-6">
                            <label for="stock" class="form-label fw-semibold">Stok Awal</label>
                            <input type="number" id="stock" name="stock" min="0" value="{{ old('stock') }}"
                                   class="form-control @error('stock') is-invalid @enderror" placeholder="0" required>
                            @error('stock') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-6">
                            <label for="price" class="form-label fw-semibold">Harga Jual (Rp)</label>
                            <input type="number" id="price" name="price" min="0" value="{{ old('price') }}"
                                   class="form-control @error('price') is-invalid @enderror" placeholder="5000" required>
                            @error('price') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    <button type="submit" class="btn btn-brand w-100">
                        <i class="bi bi-save me-1"></i> Simpan Produk
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h6 class="card-title-bar mb-0"><i class="bi bi-table text-primary"></i> Daftar Inventaris</h6>
                <span class="badge bg-secondary-subtle text-dark">{{ $products->count() }} produk</span>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode</th>
                            <th>Nama Produk</th>
                            <th class="text-center">Stok</th>
                            <th class="text-end">Harga Jual</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $product)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td><span class="code-chip">{{ $product->code }}</span></td>
                                <td class="fw-semibold">{{ $product->name }}</td>
                                <td class="text-center">
                                    @if($product->stock === 0)
                                        <span class="badge bg-danger">Habis</span>
                                    @elseif($product->isLowStock())
                                        <span class="badge bg-warning text-dark">{{ $product->stock }}</span>
                                    @else
                                        <span class="badge bg-success">{{ $product->stock }}</span>
                                    @endif
                                </td>
                                <td class="text-end">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                                <td class="text-center">
                                    <form action="{{ route('products.destroy', $product) }}" method="POST"
                                          data-confirm="Hapus produk {{ $product->name }}?" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="empty-state">
                                    <i class="bi bi-box"></i>
                                    Belum ada produk. Tambahkan produk pertama Anda di samping.
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
