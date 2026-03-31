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
                'code' => 'MX-01',
                'name' => 'Meksiko Dander',
                'address' => 'Jl. Dander',
                'city' => 'Dander',
                'phone' => '021-1234567',
                'type' => 'ruko',
            ],
            [
                'code' => 'MX-02',
                'name' => 'Meksiko Kalitidu',
                'address' => 'Jl. Kalitidu',
                'city' => 'Kalitidu',
                'phone' => '021-1234567',
                'type' => 'ruko',
            ],
            [
                'code' => 'MX-03',
                'name' => 'Meksiko Kalitidu 2',
                'address' => 'Jl. Kalitidu 2',
                'city' => 'Kalitidu',
                'phone' => '021-1234567',
                'type' => 'ruko',
            ],
            [
                'code' => 'MX-04',
                'name' => 'Meksiko Malo',
                'address' => 'Jl. Malo',
                'city' => 'Malo',
                'phone' => '021-1234567',
                'type' => 'ruko',
            ],
            [
                'code' => 'MX-05',
                'name' => 'Meksiko Purwosari',
                'address' => 'Jl. Purwosari',
                'city' => 'Purwosari',
                'phone' => '021-1234567',
                'type' => 'ruko',
            ],
            [
                'code' => 'MX-06',
                'name' => 'Meksiko Kunci',
                'address' => 'Jl. Kunci',
                'city' => 'Kunci',
                'phone' => '021-1234567',
                'type' => 'ruko',
            ],
        ];

        foreach ($outlets as $outlet) {
            Outlet::create($outlet);
        }
    }
}