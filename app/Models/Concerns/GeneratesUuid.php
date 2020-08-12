<?php

namespace App\Models\Concerns;

use Illuminate\Support\Str;

trait GeneratesUuid
{
    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    /**
     * Generate a slug when the resource is created
     *
     * @return void
     */
    public static function boot(): void
    {
        parent::boot();

        static::saving(static function ($model) {
            $model->uuid = Str::uuid();
        });
    }
}
