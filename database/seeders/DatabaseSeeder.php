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
                'name' => 'admin.dashboard',
                'description' => 'Admin Dashboard',
            ],
            [
                'name' => 'admin.settings',
                'description' => 'Admin Settings',
            ],
            [
                'name' => 'admin.settings.update',
                'description' => 'Admin Settings Update',
            ],
            [
                'name' => 'admin.users',
                'description' => 'Admin Users',
            ],
            [
                'name' => 'admin.users.create',
                'description' => 'Admin Users Create',
            ],
            [
                'name' => 'admin.users.edit',
                'description' => 'Admin Users Edit',
            ],
            [
                'name' => 'admin.users.delete',
                'description' => 'Admin Users Delete',
            ],
            [
                'name' => 'admin.roles',
                'description' => 'Admin Roles',
            ],
            [
                'name' => 'admin.roles.create',
                'description' => 'Admin Roles Create',
            ],
            [
                'name' => 'admin.roles.edit',
                'description' => 'Admin Roles Edit',
            ],
            [
                'name' => 'admin.roles.delete',
                'description' => 'Admin Roles Delete',
            ],
            [
                'name' => 'admin.roles.assign',
                'description' => 'Admin Roles Assign',
            ],
            [
                'name' => 'admin.roles.unassign',
                'description' => 'Admin Roles Unassign',
            ],
            [
                'name' => 'admin.schools',
                'description' => 'Admin Schools',
            ],
            [
                'name' => 'admin.schools.create',
                'description' => 'Admin Schools Create',
            ],
            [
                'name' => 'admin.schools.edit',
                'description' => 'Admin Schools Edit',
            ],
            [
                'name' => 'admin.schools.delete',
                'description' => 'Admin Schools Delete',
            ],
            [
                'name' => 'admin.schools.request',
                'description' => 'Admin Schools Request',
            ]
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
