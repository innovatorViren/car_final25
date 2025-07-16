<?php

namespace Database\Seeders;

use App\Models\RoleUser;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Cartalyst\Sentinel\Laravel\Facades\Activation;

class SentinelDatabaseSeeder extends Seeder
{

    public function run(): void
    {
        $adminPermission = [
            'users.list' => true,
            'users.view' => true,
            'users.add' => true,
            'users.edit' => true,
            'users.autologin' => true,
            'users.info' => true,
            'users.superadmin' => true,
            'roles.list' => true,
            'roles.add' => true,
            'roles.edit' => true,
            'roles.delete' => true,
        ];

        $adminRole = Sentinel::getRoleRepository()->create([
            'name' => 'Administrator',
            'slug' => 'administrator',
            'permissions' => $adminPermission
        ]);

        Sentinel::getRoleRepository()->create([
            'name' => 'Customer',
            'slug' => 'customer',
            'permissions' => [
                'customers.view' => true,
                'customers.list' => true,
            ]
        ]);
        Sentinel::getRoleRepository()->create([
            'name' => 'Employee',
            'slug' => 'employee',
            'permissions' => [
                'employee.view' => true,
                'employee.list' => true,
            ]
        ]);

        $admin = Sentinel::getUserRepository()->create([
            'email'    => 'virendrabutani@gmail.com',
            'password' => 'Admin@123',
            'first_name' => 'Virendra',
            'last_name' => 'Butani',
            "emp_type" => "non-employee",
            'mobile' => '7016211477',
            "emp_id" => null,
            "customer_id" => null,
            'roles_id' => $adminRole->id,
            'permissions' => $adminPermission,
            'is_active' => 'Yes',
        ]);

        RoleUser::create([
            'user_id' => $admin->id,
            'role_id' => $adminRole->id,
        ]);

        $code = Activation::create($admin)->code;
        Activation::complete($admin, $code);
    }
}
