<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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

    // Get all sets in a class
    public function sets(): BelongsToMany
    {
        return $this->belongsToMany(Set::class, 'class_set', 'class_id', 'set_id');
    }

    // Get all folder in a class
    public function folders(): BelongsToMany
    {
        return $this->belongsToMany(Folder::class, 'class_folder', 'class_id', 'folder_id');
    }
}
