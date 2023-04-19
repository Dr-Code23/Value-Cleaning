<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    public function run()
    {
        Model::unguard();


        $adminRole = Role::create(['name' => 'admin', 'guard_name' => 'api']);
        $userRole = Role::create(['name' => 'user', 'guard_name' => 'api']);
        $Role = Role::create(['name' => 'company', 'guard_name' => 'api']);

        $permissions = Permission::pluck('id', 'id')->all();

        $adminRole->syncPermissions($permissions);

    }

}
