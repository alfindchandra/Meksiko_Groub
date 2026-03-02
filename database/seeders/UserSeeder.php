<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use App\Models\Outlet;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::where('name', 'admin_pusat')->first();
        $kepalaRole = Role::where('name', 'kepala_ruko')->first();
        $staffRole = Role::where('name', 'staff_gudang')->first();

        $warehouse = Outlet::where('type', 'warehouse')->first();
        $ruko1 = Outlet::where('code', 'MX-001')->first();
        $ruko2 = Outlet::where('code', 'MX-002')->first();

        User::create([
            'name' => 'Admin Pusat',
            'email' => 'admin@meksiko.com',
            'password' => Hash::make('password'),
            'role_id' => $adminRole->id,
            'outlet_id' => null,
        ]);

        User::create([
            'name' => 'Kepala Ruko dander',
            'email' => 'dander@meksiko.com',
            'password' => Hash::make('password'),
            'role_id' => $kepalaRole->id,
            'outlet_id' => $ruko1->id,
        ]);

        User::create([
            'name' => 'Kepala Ruko bubulan',
            'email' => 'Bubulan@meksiko.com',
            'password' => Hash::make('password'),
            'role_id' => $kepalaRole->id,
            'outlet_id' => $ruko2->id,
        ]);

        $meksikoCleanRole = Role::where('name', 'meksiko_clean')->first();

        User::create([
            'name' => 'Admin Meksiko Clean',
            'email' => 'meksikoclean@meksiko.com',
            'password' => Hash::make('password'),
            'role_id' => $meksikoCleanRole->id,
            'outlet_id' => null,
        ]);
        
    }
}