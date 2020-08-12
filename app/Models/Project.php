<?php

namespace App\Models;

use App\Models\Concerns\GeneratesUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    use GeneratesUuid;

    /** {@override} */
    protected $fillable = ['name'];

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }
}
