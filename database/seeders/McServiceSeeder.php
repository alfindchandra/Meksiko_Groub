<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\McService;

class McServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $services = [
            // Shoe Cleaning
            ['name' => 'Deep Cleaning', 'category' => 'sepatu', 'price' => 85000, 'description' => 'Metode pembersihan menyeluruh untuk menghilangkan kotoran, noda, dan bakteri'],
            ['name' => 'Deep Cleaning for Suede/Leather', 'category' => 'sepatu', 'price' => 120000, 'description' => 'Metode pembersihan menyeluruh untuk sepatu berbahan suede maupun leather'],
            ['name' => 'Kid Shoes/Hat/Wallet Cleaning', 'category' => 'sepatu', 'price' => 50000, 'description' => 'Metode pembersihan menyeluruh untuk sepatu anak (hanya tersedia untuk size tertentu), topi dan dompet'],
            ['name' => 'Special Care', 'category' => 'sepatu', 'price' => 150000, 'description' => 'Perawatan spesial untuk sepatu kesayangan kamu'],
            ['name' => 'One-Day Service', 'category' => 'sepatu', 'price' => 150000, 'description' => 'Layanan pembersihan sepatu hanya dalam waktu 24 jam'],

            // Shoe Repaint
            ['name' => 'Repaint Canvas', 'category' => 'sepatu', 'price' => 250000, 'description' => 'Mewarnai kembali sepatu dengan bahas canvas'],
            ['name' => 'Repaint Midsole', 'category' => 'sepatu', 'price' => 250000, 'description' => 'Mewarnai kembali midsole sepatu'],
            ['name' => 'Repaint Suede or Leather', 'category' => 'sepatu', 'price' => 275000, 'description' => 'Mewarnai kembali sepatu berbahan suede atau kulit'],
            ['name' => 'Repaint + Leather Filler', 'category' => 'sepatu', 'price' => 325000, 'description' => 'Mewarnai kembali dan menambal kulit yang terkelupas'],
            ['name' => 'Add Colour', 'category' => 'sepatu', 'price' => 25000, 'description' => 'Menambahkan warna pada sepatu dengan warna yang diinginkan'],

            // Shoe Unyellowing
            ['name' => 'Sole Unyellowing', 'category' => 'sepatu', 'price' => 85000, 'description' => ''],
            ['name' => 'Sole Unyellowing + Deep Cleaning', 'category' => 'sepatu', 'price' => 135000, 'description' => ''],
            ['name' => 'Canvas Whitening', 'category' => 'sepatu', 'price' => 135000, 'description' => ''],
            ['name' => 'Boost Whitening', 'category' => 'sepatu', 'price' => 200000, 'description' => ''],

            // Bag Cleaning
            ['name' => 'Deep Cleaning (S)', 'category' => 'tas', 'price' => 200000, 'description' => ''],
            ['name' => 'Deep Cleaning (M)', 'category' => 'tas', 'price' => 250000, 'description' => ''],
            ['name' => 'Deep Cleaning (L)', 'category' => 'tas', 'price' => 300000, 'description' => ''],
            ['name' => 'Suede/Leather Deep Cleaning (S)', 'category' => 'tas', 'price' => 225000, 'description' => ''],
            ['name' => 'Suede/Leather Deep Cleaning (M)', 'category' => 'tas', 'price' => 325000, 'description' => ''],
            ['name' => 'Suede/Leather Deep Cleaning (L)', 'category' => 'tas', 'price' => 425000, 'description' => ''],

            // Bag Repaint
            ['name' => 'Canvas Bag Repaint (S)', 'category' => 'tas', 'price' => 300000, 'description' => ''],
            ['name' => 'Canvas Bag Repaint (M)', 'category' => 'tas', 'price' => 400000, 'description' => ''],
            ['name' => 'Canvas Bag Repaint (L)', 'category' => 'tas', 'price' => 500000, 'description' => ''],
            ['name' => 'Canvas Bag Repaint (XL)', 'category' => 'tas', 'price' => 600000, 'description' => ''],
            ['name' => 'Suede/Leather Bag Repaint (S)', 'category' => 'tas', 'price' => 375000, 'description' => ''],
            ['name' => 'Suede/Leather Bag Repaint (M)', 'category' => 'tas', 'price' => 475000, 'description' => ''],
            ['name' => 'Suede/Leather Bag Repaint (L)', 'category' => 'tas', 'price' => 575000, 'description' => ''],
            ['name' => 'Suede/Leather Bag Repaint (XL)', 'category' => 'tas', 'price' => 675000, 'description' => ''],

            // Shoe Repair
            ['name' => 'Lem', 'category' => 'repair', 'price' => 90000, 'description' => ''],
            ['name' => 'Jahit', 'category' => 'repair', 'price' => 75000, 'description' => ''],
            ['name' => 'Lem + Jahit', 'category' => 'repair', 'price' => 125000, 'description' => ''],
            ['name' => 'Ganti Sol', 'category' => 'repair', 'price' => 300000, 'description' => ''],
            ['name' => 'Ganti Sol (Pantofel)', 'category' => 'repair', 'price' => 375000, 'description' => ''],
            ['name' => 'Ganti Cang (Toplist)', 'category' => 'repair', 'price' => 120000, 'description' => ''],
            ['name' => 'Ganti Sol Fiber', 'category' => 'repair', 'price' => 450000, 'description' => ''],
            ['name' => 'Mata Ayam', 'category' => 'repair', 'price' => 75000, 'description' => ''],
            ['name' => 'Ganti Insole', 'category' => 'repair', 'price' => 90000, 'description' => ''],
            ['name' => 'Ganti Karet', 'category' => 'repair', 'price' => 105000, 'description' => ''],
            ['name' => 'Ganti Antislip', 'category' => 'repair', 'price' => 225000, 'description' => ''],
            ['name' => 'Tambal Canvas', 'category' => 'repair', 'price' => 130000, 'description' => ''],
            ['name' => 'Tambal Leather', 'category' => 'repair', 'price' => 225000, 'description' => ''],
            ['name' => 'Ganti Kulit', 'category' => 'repair', 'price' => 300000, 'description' => ''],

            // Bag Repair
            ['name' => 'Ganti Kuping Sepasang', 'category' => 'repair', 'price' => 225000, 'description' => ''],
            ['name' => 'Ganti Ring D', 'category' => 'repair', 'price' => 60000, 'description' => ''],
            ['name' => 'Ganti Zipper', 'category' => 'repair', 'price' => 90000, 'description' => ''],
            ['name' => 'Ganti Rel', 'category' => 'repair', 'price' => 225000, 'description' => ''],
            ['name' => 'Ganti Perekat', 'category' => 'repair', 'price' => 105000, 'description' => ''],

            // Luggage Repair
            ['name' => 'Ganti Roda (per pcs)', 'category' => 'lainnya', 'price' => 120000, 'description' => ''],
            ['name' => 'Ganti Furing', 'category' => 'lainnya', 'price' => 750000, 'description' => ''],
            ['name' => 'Ganti Troli', 'category' => 'lainnya', 'price' => 300000, 'description' => ''],
            ['name' => 'Ganti Handle', 'category' => 'lainnya', 'price' => 150000, 'description' => ''],
            ['name' => 'Ganti Resleting', 'category' => 'lainnya', 'price' => 450000, 'description' => ''],

            // Other Repair
            ['name' => 'Hat Reshape', 'category' => 'lainnya', 'price' => 85000, 'description' => ''],
        ];

        foreach ($services as $service) {
            McService::updateOrCreate(
                ['name' => $service['name'], 'category' => $service['category']],
                $service
            );
        }
    }
}
