<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use RuntimeException;

class TransactionController extends Controller
{
    public function index()
    {
        $sales = Sale::with('user')->latest()->get();
        $products = Product::orderBy('name')->get();

        return view('transactions.index', compact('sales', 'products'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:1'],
        ], [
            'product_id.required' => 'Silakan pilih produk terlebih dahulu.',
            'quantity.min' => 'Jumlah beli minimal 1.',
        ]);

        try {
            $sale = DB::transaction(function () use ($data) {
                // Kunci baris produk agar stok tidak bisa diubah transaksi lain secara bersamaan.
                $product = Product::lockForUpdate()->findOrFail($data['product_id']);

                if ($product->stock < $data['quantity']) {
                    throw new RuntimeException(
                        "Stok \"{$product->name}\" tidak mencukupi (sisa: {$product->stock})."
                    );
                }

                $subtotal = $product->price * $data['quantity'];

                $sale = Sale::create([
                    'invoice_number' => $this->generateInvoiceNumber(),
                    'user_id' => Auth::id(),
                    'total_amount' => $subtotal,
                ]);

                SaleDetail::create([
                    'sale_id' => $sale->id,
                    'product_id' => $product->id,
                    'quantity' => $data['quantity'],
                    'price' => $product->price,
                    'subtotal' => $subtotal,
                ]);

                $product->decrement('stock', $data['quantity']);

                return $sale;
            });

            return redirect()->route('transactions.show', $sale->invoice_number)
                ->with('success', 'Transaksi berhasil diproses.');
        } catch (RuntimeException $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function show($invoice_number)
    {
        $sale = Sale::with(['user', 'details.product'])
            ->where('invoice_number', $invoice_number)
            ->firstOrFail();

        return view('transactions.show', compact('sale'));
    }

    /** Format: INV-20261028-AB12C */
    private function generateInvoiceNumber(): string
    {
        do {
            $number = 'INV-' . now()->format('Ymd') . '-' . strtoupper(Str::random(5));
        } while (Sale::where('invoice_number', $number)->exists());

        return $number;
    }
}
