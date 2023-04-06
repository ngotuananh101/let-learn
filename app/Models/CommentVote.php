<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CommentVote extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'post_id',
        'comment_id',
        'vote_status',
    ];

    // get user
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // get comment
    public function comment(): BelongsTo
    {
        return $this->belongsTo(Comment::class);
    }
}
