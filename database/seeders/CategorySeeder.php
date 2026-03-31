<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Beer', 'code' => 'BER', 'description' => 'Kategori untuk minuman Beer.'],
            ['name' => 'Anggur', 'code' => 'ANG', 'description' => 'Kategori untuk minuman Anggur.'],
            ['name' => 'Vodka', 'code' => 'VOD', 'description' => 'Kategori untuk minuman Vodka.'],
            ['name' => 'Whisky', 'code' => 'WHI', 'description' => 'Kategori untuk minuman Whisky umum.'],
            ['name' => 'Soju', 'code' => 'SOJ', 'description' => 'Kategori untuk minuman Soju.'],
            ['name' => 'Rum', 'code' => 'RUM', 'description' => 'Kategori untuk minuman Rum.'],
            ['name' => 'Liqueur', 'code' => 'LIQ', 'description' => 'Kategori untuk minuman Liqueur.'],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(
                ['code' => $category['code']],
                $category
            );
        }
    }
}