<?php

namespace App\Models;

use App\Models\Concerns\GeneratesUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comment extends Model
{
    use GeneratesUuid;

    /** {@override} */
    protected $fillable = ['description'];

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }
}
