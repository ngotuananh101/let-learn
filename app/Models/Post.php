<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    // make model like stackoverflow question
    protected $fillable = [
        'user_id',
        'class_id',
        'name',
        'description',
        'status',
        'score_reporting',
        'tags',
    ];
}
