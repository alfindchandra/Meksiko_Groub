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
        $kepalaRole = Role::where('name', 'rider')->first();
        $staffRole = Role::where('name', 'staff_gudang')->first();

        $warehouse = Outlet::where('type', 'warehouse')->first();
        $ruko1 = Outlet::where('code', 'MX-01')->first();
        $ruko2 = Outlet::where('code', 'MX-02')->first();
        $ruko3 = Outlet::where('code', 'MX-03')->first();
        $ruko4 = Outlet::where('code', 'MX-04')->first();
        $ruko5 = Outlet::where('code', 'MX-05')->first();
        $ruko6 = Outlet::where('code', 'MX-06')->first();

        User::create([
            'name' => 'Admin Pusat',
            'email' => 'admin@meksiko.com',
            'password' => Hash::make('password'),
            'role_id' => $adminRole->id,
            'outlet_id' => null,
        ]);

        User::create([
            'name' => 'Kudanil Dander 1',
            'email' => 'dander@meksiko.com',
            'password' => Hash::make('password'),
            'role_id' => $kepalaRole->id,
            'outlet_id' => $ruko1->id,
        ]);

        User::create([
            'name' => 'Dodik kalitidu',
            'email' => 'Kalitidu@meksiko.com',
            'password' => Hash::make('password'),
            'role_id' => $kepalaRole->id,
            'outlet_id' => $ruko2->id,
        ]);
        User::create([
            'name' => 'Trian kalitidu 2',
            'email' => 'Kalitidu2@meksiko.com',
            'password' => Hash::make('password'),
            'role_id' => $kepalaRole->id,
            'outlet_id' => $ruko3->id,
        ]);
        User::create([
            'name' => 'Ucil Malo',
            'email' => 'malo@meksiko.com',
            'password' => Hash::make('password'),
            'role_id' => $kepalaRole->id,
            'outlet_id' => $ruko4->id,
        ]);
        User::create([
            'name' => 'Sekrit Purwosari',
            'email' => 'purwosari@meksiko.com',
            'password' => Hash::make('password'),
            'role_id' => $kepalaRole->id,
            'outlet_id' => $ruko5->id,
        ]);
        User::create([
            'name' => 'Reza Kunci',
            'email' => 'kunci@meksiko.com',
            'password' => Hash::make('password'),
            'role_id' => $kepalaRole->id,
            'outlet_id' => $ruko6->id,
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