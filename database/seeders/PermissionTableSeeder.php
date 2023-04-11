<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Permissions
        $permissions = [
            'role-list',
            'role-create',
            'role-edit',
            'role-delete',
            'service-list',
            'service-create',
            'service-edit',
            'service-delete',
            'subService-list',
            'subService-create',
            'subService-edit',
            'subService-delete',
            'worker-list',
            'worker-create',
            'worker-edit',
            'worker-delete',
            'category-list',
            'category-create',
            'category-edit',
            'category-delete',
            'offer-list',
            'offer-create',
            'offer-edit',
            'offer-delete',
            'announcement-list',
            'announcement-create',
            'announcement-edit',
            'announcement-delete',
            'order-list',
            'order-create',
            'order-edit',
            'order-delete',
            'chat-list',
            'company-list',
            'company-Approved',
            'user-list',
            'user-create',
            'user-edit',
            'user-delete',
            'notification-list',
            'notification-send',
            'notification-delete',
            'expense-list',
            'expense-create',
            'expense-edit',
            'expense-delete',
            'payment-list',
            'payment-create',
            'payment-edit',
            'payment-delete',
            'type-list',
            'type-create',
            'type-edit',
            'type-delete',

        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission,
            ]);
        }
    }
}
