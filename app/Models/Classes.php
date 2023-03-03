<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Classes extends Model
{
    use HasFactory;

    protected $table = 'classes';

    protected $fillable = [
        'name',
        'description',
        'status',
        'updated_at',
    ];

    // Get all students in a class
    public function students(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'class_student', 'class_id', 'student_id');
    }

    // Get all teachers in a class
    public function teachers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'class_teacher', 'class_id', 'teacher_id');
    }

    // get school own this class
    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class, 'school_id', 'id');
    }

    // get all lesson in a class
    public function lessons(): HasMany
    {
        return $this->hasMany(Lesson::class, 'class_id', 'id');
    }

    // get all assignment in a class
    public function assignments(): HasMany
    {
        return $this->hasMany(Assignment::class, 'class_id', 'id');
    }

    // get all quiz in a class
    public function quizzes(): HasMany
    {
        return $this->hasMany(Quiz::class, 'class_id', 'id');
    }

    // get all exam in a class
    public function exams(): HasMany
    {
        return $this->hasMany(Exam::class, 'class_id', 'id');
    }
}
