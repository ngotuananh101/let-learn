<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserLogPost extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'post_id',
        'accessed_at'
    ];

    /**
     * Get the user that owns the user log.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the lesson that the user accessed.
     */
    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }
}