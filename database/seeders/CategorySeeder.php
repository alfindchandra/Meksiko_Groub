<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Brandy/Cognac', 'code' => 'COG', 'description' => 'Kategori untuk minuman Brandy dan Cognac.'],
            ['name' => 'American Whisky', 'code' => 'AMW', 'description' => 'Kategori untuk minuman American Whisky.'],
            ['name' => 'Irish Whisky', 'code' => 'IRW', 'description' => 'Kategori untuk minuman Irish Whisky.'],
            ['name' => 'Scotch Whisky', 'code' => 'SCW', 'description' => 'Kategori untuk minuman Scotch Whisky.'],
            ['name' => 'Bourbon Whisky', 'code' => 'BRW', 'description' => 'Kategori untuk minuman Bourbon Whisky.'],
            ['name' => 'Tequila', 'code' => 'TEQ', 'description' => 'Kategori untuk minuman Tequila.'],
            ['name' => 'Single Malt Whisky', 'code' => 'SMW', 'description' => 'Kategori untuk minuman Single Malt Whisky.'],
            ['name' => 'Japanese Whisky', 'code' => 'JPW', 'description' => 'Kategori untuk minuman Japanese Whisky.'],
            ['name' => 'Vodka', 'code' => 'VOD', 'description' => 'Kategori untuk minuman Vodka.'],
            ['name' => 'Dry Gin', 'code' => 'GIN', 'description' => 'Kategori untuk minuman Dry Gin.'],
            ['name' => 'Liqueur', 'code' => 'LIQ', 'description' => 'Kategori untuk minuman Liqueur.'],
            ['name' => 'Wine', 'code' => 'WNE', 'description' => 'Kategori untuk minuman Wine.'],
            ['name' => 'Beer', 'code' => 'BER', 'description' => 'Kategori untuk minuman Beer.'],
            ['name' => 'Anggur', 'code' => 'ANG', 	'description' => 	'Kategori untuk minuman Anggur.'],
            ['name' => 	'Soju', 	'code'=> 	'SOJ','description'=>'Kategori untuk minuman Soju.'],
            ['name' => 	'Rum','code'=> 	'RUM','description'=>'Kategori untuk minuman Rum.'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}