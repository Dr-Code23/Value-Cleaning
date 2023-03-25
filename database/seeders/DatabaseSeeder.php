<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $adminRole = Role::create(['name' => 'admin', 'guard_name' => 'api']);
        $employeeRole = Role::create(['name' => 'user', 'guard_name' => 'api']);
        $Role = Role::create(['name' => 'company', 'guard_name' => 'api']);
    }
}
