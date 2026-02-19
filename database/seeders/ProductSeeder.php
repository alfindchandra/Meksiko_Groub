<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // Mapping kategori berdasarkan kode yang dibuat sebelumnya
        $cog = Category::where('code', 'COG')->first(); // Brandy/Cognac [cite: 7]
        $amw = Category::where('code', 'AMW')->first(); // American Whisky [cite: 9]
        $scw = Category::where('code', 'SCW')->first(); // Scotch Whisky [cite: 13]
        $teq = Category::where('code', 'TEQ')->first(); // Tequila [cite: 17]
        $smw = Category::where('code', 'SMW')->first(); // Single Malt [cite: 19]
        $vod = Category::where('code', 'VOD')->first(); // Vodka [cite: 45, 284]
        $ber = Category::where('code', 'BER')->first(); // Beer [cite: 279]
        $ang = Category::where('code', 'ANG')->first(); // Anggur [cite: 281]
        $soj = Category::where('code', 'SOJ')->first(); // Soju [cite: 289]
        $rum = Category::where('code', 'RUM')->first(); // Rum [cite: 291]

        $products = [
            // BRANDY/COGNAC
            [
                'sku' => 'COG-001',
                'name' => 'Hennesy VSOP',
                'description' => 'Premium Cognac Collection',
                'category_id' => $cog->id,
                'unit' => 'botol',
                'price' => 1400000, // [cite: 8]
                'min_stock' => 10,
            ],
            [
                'sku' => 'COG-002',
                'name' => 'Martell Cordon Blue',
                'description' => 'Premium Cognac Collection',
                'category_id' => $cog->id,
                'unit' => 'botol',
                'price' => 3350000, // [cite: 8]
                'min_stock' => 10,
            ],

            // AMERICAN WHISKY
            [
                'sku' => 'AMW-001',
                'name' => 'Jack Daniels',
                'description' => 'American Whisky Collection',
                'category_id' => $amw->id,
                'unit' => 'botol',
                'price' => 700000, // [cite: 10]
                'min_stock' => 10,
            ],

            // SCOTCH WHISKY
            [
                'sku' => 'SCW-001',
                'name' => 'Chivas Regal 12 Y.O',
                'description' => 'Scotch Whisky Collection',
                'category_id' => $scw->id,
                'unit' => 'botol',
                'price' => 850000, // [cite: 14]
                'min_stock' => 10,
            ],

            // SINGLE MALT
            [
                'sku' => 'SMW-001',
                'name' => 'Macallan 12 Y.O Double Cask',
                'description' => 'Single Malt Whisky Collection',
                'category_id' => $smw->id,
                'unit' => 'botol',
                'price' => 2200000, // [cite: 25]
                'min_stock' => 10,
            ],

            // VODKA
            [
                'sku' => 'VOD-001',
                'name' => 'Iceland Original Besar 700ML',
                'description' => 'Vodka 40% Alcohol',
                'category_id' => $vod->id,
                'unit' => 'botol',
                'price' => 200000, // [cite: 285]
                'min_stock' => 10,
            ],

            // BEER
            [
                'sku' => 'BER-001',
                'name' => 'Bintang Beer 620ML',
                'description' => 'Beer 4.7% Alcohol',
                'category_id' => $ber->id,
                'unit' => 'botol',
                'price' => 55000, // 
                'min_stock' => 10,
            ],
            [
                'sku' => 'BER-002',
                'name' => 'Guinness Beer 325ML',
                'description' => 'Beer 7.5% Alcohol',
                'category_id' => $ber->id,
                'unit' => 'botol',
                'price' => 40000, // 
                'min_stock' => 10,
            ],

            // ANGGUR
            [
                'sku' => 'ANG-001',
                'name' => 'Anggur Merah (OT) 620ML',
                'description' => 'Anggur Tradisional 19.7%',
                'category_id' => $ang->id,
                'unit' => 'botol',
                'price' => 75000, // [cite: 282]
                'min_stock' => 10,
            ],
            [
                'sku' => 'ANG-002',
                'name' => 'Kawa Kawa Hijau 620ML',
                'description' => 'Anggur Kawa Kawa 19.8%',
                'category_id' => $ang->id,
                'unit' => 'botol',
                'price' => 90000, // [cite: 282]
                'min_stock' => 10,
            ],

            // SOJU
            [
                'sku' => 'SOJ-001',
                'name' => 'Bae Soju Original 360ml',
                'description' => 'Soju 17.8% Alcohol',
                'category_id' => $soj->id,
                'unit' => 'botol',
                'price' => 75000, // [cite: 290]
                'min_stock' => 10,
            ],

            // RUM
            [
                'sku' => 'RUM-001',
                'name' => 'Captain Morgan Rum 750ML',
                'description' => 'Rum 35% Alcohol',
                'category_id' => $rum->id,
                'unit' => 'botol',
                'price' => 335000, // [cite: 292]
                'min_stock' => 10,
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}