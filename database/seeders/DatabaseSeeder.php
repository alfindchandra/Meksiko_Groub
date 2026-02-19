<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            OutletSeeder::class,
            UserSeeder::class,
            CategorySeeder::class,
            ProductSeeder::class,
            StockSeeder::class,
        ]);
    }
}