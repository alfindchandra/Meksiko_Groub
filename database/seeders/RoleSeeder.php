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
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }
    }
}