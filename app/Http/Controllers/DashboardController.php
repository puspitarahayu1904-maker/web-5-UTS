<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;

class DashboardController extends Controller
{
    public function index()
    {
        $totalProducts = Product::count();
        $totalSales = Sale::count();
        $totalRevenue = Sale::sum('total_amount');
        $lowStockProducts = Product::where('stock', '<=', Product::LOW_STOCK_THRESHOLD)
            ->orderBy('stock')
            ->get();
        $recentSales = Sale::with('user')->latest()->take(5)->get();

        return view('dashboard', compact(
            'totalProducts',
            'totalSales',
            'totalRevenue',
            'lowStockProducts',
            'recentSales'
        ));
    }
}
