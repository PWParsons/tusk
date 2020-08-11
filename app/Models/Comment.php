<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comment extends Model
{
    /** {@override} */
    protected $fillable = ['description'];

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }
}
