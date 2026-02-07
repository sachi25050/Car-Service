<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['name' => 'admin', 'display_name' => 'Administrator', 'description' => 'Full system access'],
            ['name' => 'manager', 'display_name' => 'Manager', 'description' => 'Management access'],
            ['name' => 'staff', 'display_name' => 'Staff', 'description' => 'Staff member'],
            ['name' => 'technician', 'display_name' => 'Technician', 'description' => 'Service technician'],
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }
    }
}
