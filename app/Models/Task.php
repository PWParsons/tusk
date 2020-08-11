<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Task extends Model
{
    /** {@override} */
    protected $fillable = ['name'];

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }
}
