<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::where('name', 'admin')->first();
        $managerRole = Role::where('name', 'manager')->first();

        // Admin User
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@carservice.com',
            'password' => Hash::make('password'),
            'phone' => '1234567890',
            'role_id' => $adminRole->id,
            'is_active' => true,
        ]);

        // Manager User
        User::create([
            'name' => 'Manager User',
            'email' => 'manager@carservice.com',
            'password' => Hash::make('password'),
            'phone' => '1234567891',
            'role_id' => $managerRole->id,
            'is_active' => true,
        ]);
    }
}
