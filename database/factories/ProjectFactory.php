<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Project;
use Faker\Generator as Faker;

$factory->define(Project::class, static function (Faker $faker) {
    return [
        'name' => $faker->word,
    ];
});
