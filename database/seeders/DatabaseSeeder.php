<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Classes;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Permission;
use App\Models\Role;
use App\Models\School;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->seedRoles();
        $this->seedPermissions();
        $this->seedUsers();
        $this->seedSettings();
        $this->seedSchools();
        $this->seedLessons();
        $this->seedCourses();
    }

    /**
     * Seed the roles table.
     */
    public function seedRoles(): void
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
            Role::factory()->create($role);
        }
    }

    /**
     * Seed the permissions table.
     */
    public function seedPermissions(): void
    {
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
                'name' => 'manager.dashboard',
                'description' => 'Require to access any manager page',
            ],
            [
                'name' => 'user.default',
                'description' => 'Require to access any user page',
            ],
            [
                'name' => 'teacher.default',
                'description' => 'Require to access any teacher page',
            ],
            [
                'name' => 'student.default',
                'description' => 'Require to access any student page',
            ],
        ];
        foreach ($permissions as $permission) {
            Permission::factory()->create($permission);
        }
        // attach permissions to roles
        $role = Role::where('name', 'super')->first();
        $role->permissions()->attach(Permission::all());
        $role = Role::where('name', 'admin')->first();
        $role->permissions()->attach(Permission::where('name', 'like', 'admin.%')->get());
        $role = Role::where('name', 'user')->first();
        $role->permissions()->attach(Permission::where('name', 'like', 'user.%')->get());
        $role = Role::where('name', 'manager')->first();
        $role->permissions()->attach(Permission::where('name', 'like', 'manager.%')->get());
        $role = Role::where('name', 'teacher')->first();
        $role->permissions()->attach(Permission::where('name', 'like', 'teacher.%')->get());
        $role = Role::where('name', 'student')->first();
        $role->permissions()->attach(Permission::where('name', 'like', 'student.%')->get());
    }

    /**
     * Seed the users table.
     */
    public function seedUsers(): void
    {
        $users = [
            [
                'role_id' => 1, // 'super
                'username' => 'super_admin',
                'email' => 'superadmin@pontas.dev',
            ],
            [
                'role_id' => 2, // 'admin
                'username' => 'admin',
                'email' => 'admin@pontas.dev',
            ],
            [
                'role_id' => 3, // 'user
                'username' => 'user',
                'email' => 'user@pontas.dev',
            ],
        ];
        foreach ($users as $user) {
            User::factory()->create($user);
        };
        User::factory()->count(10)->create([
            'role_id' => 3,
        ]);
    }

    /**
     * Seed the setting table.
     */
    public function seedSettings(): void
    {
        $settings = [
            [
                'key' => 'app_name',
                'value' => 'Let Learn',
            ],
            [
                'key' => 'app_description',
                'value' => 'Let Learn is a platform for learning and teaching online.',
            ],
            [
                'key' => 'app_logo',
                'value' => 'logo.png',
            ],
            [
                'key' => 'app_favicon',
                'value' => 'favicon.png',
            ],
            [
                'key' => 'app_timezone',
                'value' => 'Asia/Ho_Chi_Minh',
            ],
            [
                'key' => 'app_keywords',
                'value' => 'Let Learn,Learning,Teaching,Online',
            ],
            [
                'key' => 'app_head_code',
                'value' => '',
            ]
        ];
        foreach ($settings as $setting) {
            Setting::factory()->create($setting);
        }
    }

    /**
     * Seed the schools table.
     */
    public function seedSchools(): void
    {
        School::factory()->count(1)->create();
        // Seed the school manager
        $school = School::first();
        User::factory()->count(2)->create([
            'role_id' => 4, // 'manager
            'school_id' => $school->id,
        ]);
        // Seed the classes
        Classes::factory()->count(1)->create([
            'school_id' => $school->id,
        ]);
        $class = Classes::first();
        // Seed the teacher
        User::factory()->count(1)->create([
            'role_id' => 5, // 'teacher
            'school_id' => $school->id,
        ]);
        // attach teacher to class
        $teacher = User::where('role_id', 5)->first();
        $teacher->classes()->attach($class);
        // Seed the student
        User::factory()->count(5)->create([
            'role_id' => 6, // 'student
            'school_id' => $school->id,
        ]);
        // attach student to class
        $students = User::where('role_id', 6)->get();
        foreach ($students as $student) {
            $student->classes()->attach($class);
        }
    }

    /**
     * Seed the lessons table.
     */
    public function seedLessons(): void
    {
        $json = File::get("database/data/lesson_detail.json");
        $lesson_detail = json_decode($json, true);
        $chunks = array_chunk($lesson_detail, 30);
        foreach ($chunks as $chunk) {
            Lesson::factory()->create();
            $lesson = Lesson::latest()->first();
            $lesson->details()->createMany($chunk);
        }
    }

    /**
     * Seed the courses table.
     */
    public function seedCourses(): void
    {
        // create courses
        Course::factory()->count(5)->create();
        // get all lessons id
        $lessons = Lesson::all()->pluck('id')->toArray();
        // get all courses id
        $courses = Course::all()->pluck('id')->toArray();
        // attach lessons to courses
        foreach ($courses as $course) {
            $number_of_lessons = rand(1, count($lessons)-1);
            $lessons_keys = array_rand($lessons, $number_of_lessons);
            $lessons_id = [];
            foreach ($lessons_keys as $key) {
                $lessons_id[] = $lessons[$key];
            }
            $course = Course::find($course);
            $course->lessons()->attach($lessons_id);
        }
    }
}
