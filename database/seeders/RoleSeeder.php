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
                'name' => 'kepala_ruko',
                'display_name' => 'Kepala Ruko',
                'description' => 'Mengelola satu ruko tertentu',
            ],
            [
                'name' => 'staff_gudang',
                'display_name' => 'Staff Gudang',
                'description' => 'Input dan verifikasi pengiriman',
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
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }
    }
}