<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    public function run()
    {
        Model::unguard();



      Role::create(['name' => 'admin','guard_name'=>'api']);
      Role::create(['name' => 'user','guard_name'=>'api']);



    }

}
