<?php

namespace Database\Seeders;

use App\Models\Outlet;
use Illuminate\Database\Seeder;

class OutletSeeder extends Seeder
{
    public function run(): void
    {
        $outlets = [
            [
                'code' => 'GDG-001',
                'name' => 'Gudang Pusat',
                'address' => 'Jl. Industri No. 123',
                'city' => 'Jakarta',
                'phone' => '021-1234567',
                'type' => 'warehouse',
            ],
            [
                'code' => 'MX-001',
                'name' => 'Meksiko Kemang',
                'address' => 'Jl. Kemang Raya No. 45',
                'city' => 'Jakarta Selatan',
                'phone' => '021-7654321',
                'type' => 'ruko',
            ],
            [
                'code' => 'MX-002',
                'name' => 'Meksiko Kelapa Gading',
                'address' => 'Mall Kelapa Gading Lt. 3',
                'city' => 'Jakarta Utara',
                'phone' => '021-9876543',
                'type' => 'ruko',
            ],
        ];

        foreach ($outlets as $outlet) {
            Outlet::create($outlet);
        }
    }
}