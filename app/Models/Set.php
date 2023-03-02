<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Set extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    // This is basic information of a set
    protected $fillable = [
        'name',
        'description',
        'user_id',
        'status',
        'is_public',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_public' => 'boolean',
    ];

    /**
     * Get the user that owns the set.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get set details
     */
    public function setDetails(): HasMany
    {
        return $this->hasMany(SetDetail::class);
    }

    /**
     * Get the folder that owns the set.
     */
    public function folder(): BelongsToMany
    {
        return $this->belongsToMany(Folder::class, 'folder_set', 'set_id', 'folder_id');
    }

    /**
     * Get the class that owns the set.
     */
    public function classes(): BelongsToMany
    {
        return $this->belongsToMany(Classes::class, 'class_set', 'set_id', 'class_id');
    }
}
