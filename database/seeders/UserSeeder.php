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
        // Dihapus atau pastikan role ini terdaftar di RoleSeeder jika ingin digunakan

        $warehouse = Outlet::where('type', 'warehouse')->first();
        $ruko1 = Outlet::where('code', 'MX-01')->first();
        $ruko2 = Outlet::where('code', 'MX-02')->first();
        $ruko3 = Outlet::where('code', 'MX-03')->first();
        $ruko4 = Outlet::where('code', 'MX-04')->first();
        $ruko5 = Outlet::where('code', 'MX-05')->first();
        $ruko6 = Outlet::where('code', 'MX-06')->first();

        $users = [
            [
                'search' => ['email' => 'admin@meksiko.com'],
                'data' => ['name' => 'Admin Pusat', 'password' => Hash::make('password'), 'role_id' => $adminRole->id, 'outlet_id' => null]
            ],
            [
                'search' => ['email' => 'dander@meksiko.com'],
                'data' => ['name' => 'Kudanil Dander 1', 'password' => Hash::make('password'), 'role_id' => $kepalaRole->id, 'outlet_id' => $ruko1?->id]
            ],
            [
                'search' => ['email' => 'Kalitidu@meksiko.com'],
                'data' => ['name' => 'Dodik kalitidu', 'password' => Hash::make('password'), 'role_id' => $kepalaRole->id, 'outlet_id' => $ruko2?->id]
            ],
            [
                'search' => ['email' => 'Kalitidu2@meksiko.com'],
                'data' => ['name' => 'Trian kalitidu 2', 'password' => Hash::make('password'), 'role_id' => $kepalaRole->id, 'outlet_id' => $ruko3?->id]
            ],
            [
                'search' => ['email' => 'malo@meksiko.com'],
                'data' => ['name' => 'Ucil Malo', 'password' => Hash::make('password'), 'role_id' => $kepalaRole->id, 'outlet_id' => $ruko4?->id]
            ],
            [
                'search' => ['email' => 'purwosari@meksiko.com'],
                'data' => ['name' => 'Sekrit Purwosari', 'password' => Hash::make('password'), 'role_id' => $kepalaRole->id, 'outlet_id' => $ruko5?->id]
            ],
            [
                'search' => ['email' => 'kunci@meksiko.com'],
                'data' => ['name' => 'Reza Kunci', 'password' => Hash::make('password'), 'role_id' => $kepalaRole->id, 'outlet_id' => $ruko6?->id]
            ],
        ];

        foreach ($users as $user) {
            User::updateOrCreate($user['search'], $user['data']);
        }

        $meksikoCleanRole = Role::where('name', 'meksiko_clean')->first();
        if ($meksikoCleanRole) {
            User::updateOrCreate(
                ['email' => 'meksikoclean@meksiko.com'],
                [
                    'name' => 'Admin Meksiko Clean',
                    'password' => Hash::make('password'),
                    'role_id' => $meksikoCleanRole->id,
                    'outlet_id' => null,
                ]
            );
        }
    }
}