<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // Daftar mapping kategori (Nama => Kode)
        $categoryMap = [
            'BEER'    => 'BER',
            'ANGGUR'  => 'ANG',
            'VODKA'   => 'VOD',
            'WHISKY'  => 'WHI',
            'SOJU'    => 'SOJ',
            'RUM'     => 'RUM',
            'LIQUOR'  => 'LIQ',
        ];

        $categories = [];
        foreach ($categoryMap as $name => $code) {
            // firstOrCreate memastikan jika kategori null, dia akan dibuatkan otomatis
            $categories[$code] = Category::firstOrCreate(
                ['code' => $code],
                ['name' => $name]
            );
        }

        $products = [
            // --- BEER ---
            ['sku' => 'BER-001', 'name' => 'Bintang Beer 620ml (4.7%)', 'cat' => 'BER', 'price' => 55000],
            ['sku' => 'BER-002', 'name' => 'Bintang Redler Lemon 330ml (2%)', 'cat' => 'BER', 'price' => 25000],
            ['sku' => 'BER-003', 'name' => 'Singaraja Beer 620ml (2%)', 'cat' => 'BER', 'price' => 40000],
            ['sku' => 'BER-004', 'name' => 'Singaraja Jeruk Madu 330ml (4.8%)', 'cat' => 'BER', 'price' => 23000],
            ['sku' => 'BER-005', 'name' => 'Draft Beer 620ml (4.9%)', 'cat' => 'BER', 'price' => 45000],
            ['sku' => 'BER-006', 'name' => 'Black Panther 330ml (4.9%)', 'cat' => 'BER', 'price' => 35000],
            ['sku' => 'BER-007', 'name' => 'Guinness Beer 325ml (7.5%)', 'cat' => 'BER', 'price' => 40000],
            ['sku' => 'BER-008', 'name' => 'Konig Ludwig Dunkle 330ml (4.5%)', 'cat' => 'BER', 'price' => 30000],

            // --- ANGGUR ---
            ['sku' => 'ANG-001', 'name' => 'Anggur Merah (AK) 620ml (19%)', 'cat' => 'ANG', 'price' => 70000],
            ['sku' => 'ANG-002', 'name' => 'Anggur Hijau (AK) 650ml (19%)', 'cat' => 'ANG', 'price' => 75000],
            ['sku' => 'ANG-003', 'name' => 'Anggur Hijau (API) 620ml (19.7%)', 'cat' => 'ANG', 'price' => 87000],
            ['sku' => 'ANG-004', 'name' => 'Anggur Kolesom 620ml (17.5%)', 'cat' => 'ANG', 'price' => 73000],
            ['sku' => 'ANG-005', 'name' => 'Anggur Putih (OT) 620ml (14.7%)', 'cat' => 'ANG', 'price' => 73000],
            ['sku' => 'ANG-006', 'name' => 'Anggur Merah (OT) 620ml (19.7%)', 'cat' => 'ANG', 'price' => 75000],
            ['sku' => 'ANG-007', 'name' => 'Anggur Merah Gold (OT) 620ml (19.7%)', 'cat' => 'ANG', 'price' => 85000],
            ['sku' => 'ANG-008', 'name' => 'Kawa Kawa Hijau 620ml (19.8%)', 'cat' => 'ANG', 'price' => 90000],
            ['sku' => 'ANG-009', 'name' => 'Kawa Kawa Merah 620ml (19.8%)', 'cat' => 'ANG', 'price' => 80000],
            ['sku' => 'ANG-010', 'name' => 'Kawa Kawa Blackcurrant 620ml (19.8%)', 'cat' => 'ANG', 'price' => 80000],
            ['sku' => 'ANG-011', 'name' => 'Arcadia Black Pink 620ml (19.8%)', 'cat' => 'ANG', 'price' => 85000],
            ['sku' => 'ANG-012', 'name' => 'Balega Black Tea 620ml (19.8%)', 'cat' => 'ANG', 'price' => 78000],
            ['sku' => 'ANG-013', 'name' => 'Alexis Blackcurrant 620ml (19.5%)', 'cat' => 'ANG', 'price' => 78000],
            ['sku' => 'ANG-014', 'name' => 'Atlas (All Varian) 620ml (19.5%)', 'cat' => 'ANG', 'price' => 80000],
            ['sku' => 'ANG-015', 'name' => 'Menjangan Anggur Hijau 600ml (19.8%)', 'cat' => 'ANG', 'price' => 75000],

            // --- VODKA ---
            ['sku' => 'VOD-001', 'name' => 'Iceland Original Kecil 500ml (40%)', 'cat' => 'VOD', 'price' => 150000],
            ['sku' => 'VOD-002', 'name' => 'Iceland Original Besar 700ml (40%)', 'cat' => 'VOD', 'price' => 200000],
            ['sku' => 'VOD-003', 'name' => 'Alpin Swift 700ml (40%)', 'cat' => 'VOD', 'price' => 295000],
            ['sku' => 'VOD-004', 'name' => 'Topi Miring Jenever Besar 1L (14.7%)', 'cat' => 'VOD', 'price' => 130000],
            ['sku' => 'VOD-005', 'name' => 'Topi Miring Vodka Besar 1L (19.2%)', 'cat' => 'VOD', 'price' => 130000],
            ['sku' => 'VOD-006', 'name' => 'Black White Vodka 500ml (45%)', 'cat' => 'VOD', 'price' => 145000],
            ['sku' => 'VOD-007', 'name' => 'Dome Vodka 330ml (40%)', 'cat' => 'VOD', 'price' => 100000],
            ['sku' => 'VOD-008', 'name' => 'Mansion House 350ml (40%)', 'cat' => 'VOD', 'price' => 115000],
            ['sku' => 'VOD-009', 'name' => 'Sicario Blackcurrant 750ml (40%)', 'cat' => 'VOD', 'price' => 335000],
            ['sku' => 'VOD-010', 'name' => 'Friendship B. Tea Vodka 650ml', 'cat' => 'VOD', 'price' => 85000],

            // --- WHISKY ---
            ['sku' => 'WHI-001', 'name' => 'Glenkenny 700ml (40%)', 'cat' => 'WHI', 'price' => 350000],
            ['sku' => 'WHI-002', 'name' => 'Manta Gold 700ml (40%)', 'cat' => 'WHI', 'price' => 330000],
            ['sku' => 'WHI-003', 'name' => 'Drum Whisky 350ml (40%)', 'cat' => 'WHI', 'price' => 115000],
            ['sku' => 'WHI-004', 'name' => 'Dome Whisky 700ml (40%)', 'cat' => 'WHI', 'price' => 220000],
            ['sku' => 'WHI-005', 'name' => 'Mansion House Whisky 350ml (40%)', 'cat' => 'WHI', 'price' => 120000],

            // --- SOJU ---
            ['sku' => 'SOJ-001', 'name' => 'Bae Soju Original 360ml (17.8%)', 'cat' => 'SOJ', 'price' => 75000],
            ['sku' => 'SOJ-002', 'name' => 'Bae Soju Lychee 360ml (13%)', 'cat' => 'SOJ', 'price' => 80000],
            ['sku' => 'SOJ-003', 'name' => 'Cheosnun All Varian 360ml (20%)', 'cat' => 'SOJ', 'price' => 70000],
            ['sku' => 'SOJ-004', 'name' => 'Somaek (All Varian) 320ml (4.8%)', 'cat' => 'SOJ', 'price' => 25000],

            // --- RUM ---
            ['sku' => 'RUM-001', 'name' => 'Captain Morgan Rum 750ml (35%)', 'cat' => 'RUM', 'price' => 335000],
            ['sku' => 'RUM-002', 'name' => 'Black & White Rum 700ml (37.5%)', 'cat' => 'RUM', 'price' => 350000],
            ['sku' => 'RUM-003', 'name' => 'Uncle Hook 700ml (40%)', 'cat' => 'RUM', 'price' => 300000],
            ['sku' => 'RUM-004', 'name' => 'Bacardi Rum Besar 750ml (37.5%)', 'cat' => 'RUM', 'price' => 320000],
            ['sku' => 'RUM-005', 'name' => 'Bacardi Rum Kecil 180ml (37.5%)', 'cat' => 'RUM', 'price' => 90000],

            // --- LIQUOR ---
            ['sku' => 'LIQ-001', 'name' => 'Vibe Black Tea 700ml (40%)', 'cat' => 'LIQ', 'price' => 345000],
            ['sku' => 'LIQ-002', 'name' => 'Vibe Exoticn Lychee 700ml (40%)', 'cat' => 'LIQ', 'price' => 345000],
            ['sku' => 'LIQ-003', 'name' => 'Rockstar Lychee 700ml (40%)', 'cat' => 'LIQ', 'price' => 295000],
        ];

        foreach ($products as $item) {
    Product::updateOrCreate(
        ['sku' => $item['sku']], // Kunci pengecekan unik
        [
            'name'        => $item['name'],
            'category_id' => $categories[$item['cat']]->id,
            'price'       => $item['price'],
            'unit'        => 'botol',
            'description' => 'Produk kategori ' . $item['cat'],
            'min_stock'   => 5,
        ]
    );
}
    }
}