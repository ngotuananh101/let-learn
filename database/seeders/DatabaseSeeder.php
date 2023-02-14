<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;

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
        ];
        foreach ($roles as $role) {
            Role::create($role);
        }

        // seed the permissions
        $permissions = [
            // permissions in admin page
            [
                'name' => 'admin.super',
                'description' => 'Give all permissions',
            ],
            [
                'name' => 'admin.access',
                'description' => 'Access to admin panel',
            ],
            [
                'name' => 'admin.users',
                'description' => 'Manage users',
            ],
            [
                'name' => 'admin.roles',
                'description' => 'Manage roles',
            ],
            [
                'name' => 'admin.permissions',
                'description' => 'Manage permissions',
            ],
            [
                'name' => 'admin.settings',
                'description' => 'Manage settings',
            ],
            [
                'name' => 'admin.logs',
                'description' => 'Manage logs',
            ],
            [
                'name' => 'admin.analytics',
                'description' => 'Manage analytics',
            ],
            // permissions about home page
            [
                'name' => 'home.access',
                'description' => 'Access to home page',
            ],
            [
                'name' => 'home.profile',
                'description' => 'Manage profile',
            ],
            [
                'name' => 'home.settings',
                'description' => 'Manage settings',
            ],
            [
                'name' => 'home.logs',
                'description' => 'Manage logs',
            ],
            [
                'name' => 'home.analytics',
                'description' => 'Manage analytics',
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
        $role = Role::where('name', 'user')->first();
        $role->permissions()->attach(Permission::where('name', 'like', 'home.%')->get());

        // seed the users
        $users = [
            [
                'role_id' => 1, // 'super
                'username' => 'superadmin',
                'email' => 'superadmin@pontas.dev',
                'date_of_birth' => '1990-01-01',
                'status' => 'active',
                'password' => bcrypt('1234568'),
            ],
            [
                'role_id' => 2, // 'admin
                'username' => 'admin',
                'email' => 'admin@pontas.dev',
                'date_of_birth' => '1990-01-01',
                'status' => 'active',
                'password' => bcrypt('1234568'),
            ],
            [
                'role_id' => 3, // 'user
                'username' => 'user',
                'email' => 'user@pontas.dev',
                'date_of_birth' => '1990-01-01',
                'status' => 'active',
                'password' => bcrypt('1234568'),
            ],
        ];
        foreach ($users as $user) {
            $user = User::create($user);
        }
    }
}
