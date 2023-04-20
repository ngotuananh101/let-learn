<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    use HasFactory;
    protected $fillable = [
        'class_id',
        'name',
        'description',
        'status',
        'score_reporting',
        'start_time',
        'end_time',
    ];

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    public function class()
    {
        return $this->belongsTo(Classes::class);
    }

    // get answer of user
    public function answers()
    {
        return $this->hasMany(Answer::class);
    }

}
