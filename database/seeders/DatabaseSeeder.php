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
                'name' => 'admin.permissions',
                'description' => 'Admin Permissions',
            ],
            [
                'name' => 'admin.permissions.create',
                'description' => 'Admin Permissions Create',
            ],
            [
                'name' => 'admin.permissions.edit',
                'description' => 'Admin Permissions Edit',
            ],
            [
                'name' => 'admin.permissions.delete',
                'description' => 'Admin Permissions Delete',
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
                'name' => 'admin.schools.import',
                'description' => 'Admin Schools Import',
            ],
            [
                'name' => 'admin.schools.export',
                'description' => 'Admin Schools Export',
            ],
            [
                'name' => 'admin.lesson.categories',
                'description' => 'Admin Lesson Categories',
            ],
            [
                'name' => 'admin.lesson.categories.create',
                'description' => 'Admin Lesson Categories Create',
            ],
            [
                'name' => 'admin.lesson.categories.edit',
                'description' => 'Admin Lesson Categories Edit',
            ],
            [
                'name' => 'admin.lesson.categories.delete',
                'description' => 'Admin Lesson Categories Delete',
            ],
            [
                'name' => 'admin.lesson.categories.import',
                'description' => 'Admin Lesson Categories Import',
            ],
            [
                'name' => 'admin.lesson.categories.export',
                'description' => 'Admin Lesson Categories Export',
            ],
            [
                'name' => 'admin.lessons',
                'description' => 'Admin Lessons',
            ],
            [
                'name' => 'admin.lessons.create',
                'description' => 'Admin Lessons Create',
            ],
            [
                'name' => 'admin.lessons.edit',
                'description' => 'Admin Lessons Edit',
            ],
            [
                'name' => 'admin.lessons.delete',
                'description' => 'Admin Lessons Delete',
            ],
            [
                'name' => 'admin.lessons.import',
                'description' => 'Admin Lessons Import',
            ],
            [
                'name' => 'admin.lessons.export',
                'description' => 'Admin Lessons Export',
            ],
            [
                'name' => 'admin.lessons.detail',
                'description' => 'Admin Lessons Questions',
            ],
            [
                'name' => 'admin.lessons.detail.create',
                'description' => 'Admin Lessons Questions Create',
            ],
            [
                'name' => 'admin.lessons.detail.edit',
                'description' => 'Admin Lessons Questions Edit',
            ],
            [
                'name' => 'admin.lessons.detail.delete',
                'description' => 'Admin Lessons Questions Delete',
            ],
            [
                'name' => 'admin.lessons.detail.import',
                'description' => 'Admin Lessons Questions Import',
            ],
            [
                'name' => 'admin.lessons.detail.export',
                'description' => 'Admin Lessons Questions Export',
            ],
            [
                'name' => 'admin.course',
                'description' => 'Admin Course',
            ],
            [
                'name' => 'admin.course.create',
                'description' => 'Admin Course Create',
            ],
            [
                'name' => 'admin.course.edit',
                'description' => 'Admin Course Edit',
            ],
            [
                'name' => 'admin.course.delete',
                'description' => 'Admin Course Delete',
            ],
            [
                'name' => 'admin.course.lessons.add',
                'description' => 'Admin Course Lessons Add',
            ],
            [
                'name' => 'admin.course.lessons.remove',
                'description' => 'Admin Course Lessons Remove',
            ],
            [
                'name' => 'admin.school.class.create',
                'description' => 'Admin School Class Create',
            ],
            [
                'name' => 'admin.school.class.edit',
                'description' => 'Admin School Class Edit',
            ],
            [
                'name' => 'admin.school.class.delete',
                'description' => 'Admin School Class Delete',
            ],
            [
                'name' => 'admin.school.class.students.add',
                'description' => 'Admin School Class Students Add',
            ],
            [
                'name' => 'admin.school.class.students.remove',
                'description' => 'Admin School Class Students Remove',
            ],
            [
                'name' => 'admin.school.class.students.import',
                'description' => 'Admin School Class Students Import',
            ],
            [
                'name' => 'admin.school.class.students.export',
                'description' => 'Admin School Class Students Export',
            ],
            [
                'name' => 'admin.school.class.teacher.add',
                'description' => 'Admin School Class Teacher Add',
            ],
            [
                'name' => 'admin.school.class.teacher.remove',
                'description' => 'Admin School Class Teacher Remove',
            ],
            [
                'name' => 'admin.school.class.teacher.import',
                'description' => 'Admin School Class Teacher Import',
            ],
            [
                'name' => 'admin.school.class.teacher.export',
                'description' => 'Admin School Class Teacher Export',
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
            ],
            [
                'role_id' => 2, // 'admin
                'username' => 'admin',
                'email' => 'admin@pontas.dev',
                'date_of_birth' => '1990-01-01',
                'status' => 'active',
                'password' => Hash::make('1234567890'),
            ],
            [
                'role_id' => 3, // 'user
                'username' => 'user',
                'email' => 'user@pontas.dev',
                'date_of_birth' => '1990-01-01',
                'status' => 'active',
                'password' => Hash::make('1234567890'),
            ],
        ];
        foreach ($users as $user) {
            User::create($user);
        }
    }
}
