<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticate;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticate implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'role_id',
        'username',
        'email',
        'date_of_birth',
        'status',
        'password',
        'email_verified_at'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * 1 user can have 1 roles
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * 1 user can have many sets
     */
    public function lesson(): HasMany
    {
        return $this->hasMany(Lesson::class);
    }

    // get all permissions of user
    public function getAllPermissions()
    {
        return $this->role()->with('permissions')->first()->permissions;
    }

    // check if user has permission
    public function hasPermission($permission)
    {
        return $this->getAllPermissions()->contains('name', $permission);
    }

    // check user is super admin
    public function isSuperAdmin(): bool
    {
        return $this->role->name == 'super';
    }

    // Course belong to user
    public function folders(): HasMany
    {
        return $this->hasMany(Course::class);
    }

    // Get all classes of a student
    public function studentClasses(): BelongsToMany
    {
        return $this->belongsToMany(Classes::class, 'class_student', 'student_id', 'class_id');
    }

    // Get all classes of a teacher
    public function teacherClasses(): BelongsToMany
    {
        return $this->belongsToMany(Classes::class, 'class_teacher', 'teacher_id', 'class_id');
    }

    //check user is in class
    public function isInClass($class_id): bool
    {
        $class= $this->studentClasses()->where('class_id', $class_id)->exists();
        if($class){
            return true;
        }else {
            return $this->teacherClasses()->where('class_id', $class_id)->exists();
        }
    }
    //check user is student in class
    public function isStudentInClass($class_id): bool
    {
        return $this->studentClasses()->where('class_id', $class_id)->exists();
    }

    //check user is teacher in class
    public function isTeacherInClass($class_id): bool
    {
        return $this->teacherClasses()->where('class_id', $class_id)->exists();
    }
}
