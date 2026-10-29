<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::orderBy('name')->get();

        return view('products.index', compact('products'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'stock' => ['required', 'integer', 'min:0'],
            'price' => ['required', 'integer', 'min:0'],
        ], [
            'name.required' => 'Nama produk wajib diisi.',
            'stock.required' => 'Stok awal wajib diisi.',
            'price.required' => 'Harga jual wajib diisi.',
            'price.min' => 'Harga jual tidak boleh negatif.',
        ]);

        Product::create([
            'name' => $data['name'],
            'code' => $this->generateCode(),
            'stock' => $data['stock'],
            'price' => $data['price'],
        ]);

        return redirect()->route('products.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    public function destroy(Product $product)
    {
        if ($product->saleDetails()->exists()) {
            return back()->with('error', 'Produk tidak dapat dihapus karena sudah memiliki riwayat transaksi.');
        }

        $product->delete();

        return redirect()->route('products.index')->with('success', 'Produk berhasil dihapus.');
    }

    /** Membuat kode produk unik, misalnya PRD-7KQ2XD. */
    private function generateCode(): string
    {
        do {
            $code = 'PRD-' . strtoupper(Str::random(6));
        } while (Product::where('code', $code)->exists());

        return $code;
    }
}
