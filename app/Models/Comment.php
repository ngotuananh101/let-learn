<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'post_id',
        'comment',
        'status',
        'votes',
    ];

    // get user
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // get class
    public function class(): BelongsTo
    {
        return $this->belongsTo(Classes::class);
    }

    // get post
    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

}
