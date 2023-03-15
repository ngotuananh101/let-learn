<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // seed the roles
        $roles = [
            [
                'name' => 'super',
                'description' => 'Super Administrator',
            ],
            [
                'name' => 'admin',
                'description' => 'Administrator',
            ],
            [
                'name' => 'user',
                'description' => 'User',
            ],
            [
                'name' => 'manager',
                'description' => 'School Manager',
            ],
            [
                'name' => 'teacher',
                'description' => 'Teacher',
            ],
            [
                'name' => 'student',
                'description' => 'Student',
            ],
        ];
        foreach ($roles as $role) {
            Role::create($role);
        }

        // seed the permissions
        $permissions = [
            // permissions in admin page
            [
                'name' => 'admin.super',
                'description' => 'Give all permissions to user',
            ],
            [
                'name' => 'admin.dashboard',
                'description' => 'Require to access any admin page',
            ],
            [
                'name' => 'admin.analytics',
                'description' => 'Require to access analytics page',
            ],
            [
                'name' => 'admin.setting',
                'description' => 'Require to update setting',
            ],
            // permissions in manager of school
            [
                'name' => 'manager.super',
                'description' => 'Give all permissions of school to user',
            ],
            [
                'name' => 'manager.dashboard',
                'description' => 'Require to access any manager page',
            ],
            [
                'name' => 'manager.info',
                'description' => 'Require to update info of school',
            ],
            [
                'name' => 'manager.user',
                'description' => 'Require to manage users of school',
            ],
            [
                'name' => 'manager.class',
                'description' => 'Require to manage classes of school',
            ],
            [
                'name' => 'manager.student',
                'description' => 'Require to manage students of school',
            ],
            [
                'name' => 'manager.teacher',
                'description' => 'Require to manage teachers of school',
            ],
            [
                'name' => 'manager.mark',
                'description' => 'Require to manage marks of school',
            ],
        ];
        foreach ($permissions as $permission) {
            Permission::create($permission);
        }

        // attach permissions to roles
        $role = Role::where('name', 'super')->first();
        $role->permissions()->attach(Permission::all());
        $role = Role::where('name', 'admin')->first();
        $role->permissions()->attach(Permission::where('name', 'like', 'admin.%')->get());
        // seed the users
        $users = [
            [
                'role_id' => 1, // 'super
                'username' => 'super_admin',
                'email' => 'superadmin@pontas.dev',
                'date_of_birth' => '1990-01-01',
                'status' => 'active',
                'password' => Hash::make('1234567890'),
                'email_verified_at' => '2021-01-01 00:00:00',
            ],
            [
                'role_id' => 2, // 'admin
                'username' => 'admin',
                'email' => 'admin@pontas.dev',
                'date_of_birth' => '1990-01-01',
                'status' => 'active',
                'password' => Hash::make('1234567890'),
                'email_verified_at' => '2021-01-01 00:00:00',
            ],
            [
                'role_id' => 3, // 'user
                'username' => 'user',
                'email' => 'user@pontas.dev',
                'date_of_birth' => '1990-01-01',
                'status' => 'active',
                'password' => Hash::make('1234567890'),
                'email_verified_at' => '2021-01-01 00:00:00',
            ],
        ];
        foreach ($users as $user) {
            User::create($user);
        }
    }
}
