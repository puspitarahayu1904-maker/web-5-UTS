<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Akun Admin
        User::create([
            'name' => 'Admin Utama',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        // Akun Staff
        User::create([
            'name' => 'Staff Penjualan',
            'email' => 'staff@gmail.com',
            'password' => Hash::make('password123'),
            'role' => 'staff',
        ]);

        // Contoh data produk agar aplikasi langsung bisa didemokan
        $products = [
            ['name' => 'Buku Tulis A5', 'stock' => 40, 'price' => 6000],
            ['name' => 'Pulpen Gel Hitam', 'stock' => 25, 'price' => 4500],
            ['name' => 'Kopi Bubuk 200g', 'stock' => 12, 'price' => 22000],
            ['name' => 'Air Mineral 600ml', 'stock' => 4, 'price' => 3000],
            ['name' => 'Gula Pasir 1kg', 'stock' => 0, 'price' => 15000],
        ];

        foreach ($products as $index => $item) {
            Product::create([
                'name' => $item['name'],
                'code' => 'PRD-' . str_pad((string) ($index + 1), 4, '0', STR_PAD_LEFT),
                'stock' => $item['stock'],
                'price' => $item['price'],
            ]);
        }
    }
}
