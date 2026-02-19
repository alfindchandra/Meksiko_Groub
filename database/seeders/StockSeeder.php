<?php

namespace Database\Seeders;

use App\Models\Stock;
use App\Models\Product;
use App\Models\Outlet;
use Illuminate\Database\Seeder;

class StockSeeder extends Seeder
{
    public function run(): void
    {
        $products = Product::all();
        $outlets = Outlet::all();

        foreach ($outlets as $outlet) {
            foreach ($products as $product) {
                Stock::create([
                    'product_id' => $product->id,
                    'outlet_id' => $outlet->id,
                    'quantity' => $outlet->type === 'warehouse' ? rand(50, 100) : rand(10, 20),
                    'reserved' => 0,
                ]);
            }
        }
    }
}