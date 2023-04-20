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
        'school_id',
        'name',
        'status',
        'start_date',
        'end_date',
        'created_at',
        'updated_at',
    ];

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class, 'school_id', 'id');
    }

    public function member(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'class_members', 'class_id', 'user_id');
    }

    //get quizzes of a class
    public function quizzes(): HasMany
    {
        return $this->hasMany(Quiz::class, 'class_id', 'id');
    }

    //get post
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class, 'class_id', 'id');
    }
}
