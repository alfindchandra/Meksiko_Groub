<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            [
                'name' => 'admin_pusat',
                'display_name' => 'Admin Pusat',
                'description' => 'Akses penuh ke seluruh sistem',
            ],
            [
                'name' => 'rider',
                'display_name' => 'Rider',
                'description' => 'Mengelola satu ruko tertentu',
            ],
            [
                'name' => 'auditor',
                'display_name' => 'Auditor',
                'description' => 'Auditor internal untuk review stok dan transaksi',
            ],
            [
                'name' => 'pegadaian',
                'display_name' => 'Pegadaian',
                'description' => 'Mengelola transaksi pegadaian',
            ],
            [
                'name' => 'meksiko_clean',
                'display_name' => 'Meksiko Clean',
                'description' => 'Manajemen operasional Meksiko Clean',
            ],
        ];

        foreach ($roles as $role) {
            // Menggunakan updateOrCreate agar tidak duplikat berdasarkan kolom 'name'
            Role::updateOrCreate(
                ['name' => $role['name']], // Kunci pencarian data unik
                [
                    'display_name' => $role['display_name'],
                    'description' => $role['description'],
                ]
            );
        }
    }
}