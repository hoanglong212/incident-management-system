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
                'name' => 'USER',
                'description' => 'Reporter who creates incident tickets',
            ],
            [
                'name' => 'ADMIN',
                'description' => 'Coordinator who manages and assigns incidents',
            ],
            [
                'name' => 'TECHNICIAN',
                'description' => 'Staff who handles assigned incidents',
            ],
            [
                'name' => 'MANAGER',
                'description' => 'Manager who views dashboards and reports',
            ],
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate(
                ['name' => $role['name']],
                ['description' => $role['description']]
            );
        }
    }
}