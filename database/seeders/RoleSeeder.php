<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['name' => 'fleet_admin', 'description' => 'Admin armada yang dapat menugaskan kendaraan.'],
            ['name' => 'dispatch_admin', 'description' => 'Admin dispatching yang memproses perjalanan dinas.'],
            ['name' => 'viewer', 'description' => 'Pengguna yang hanya dapat melihat data.'],
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate(['name' => $role['name']], $role);
        }
    }
}
