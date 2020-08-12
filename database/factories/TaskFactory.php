<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Project;
use App\Models\Task;
use Faker\Generator as Faker;

$factory->define(Task::class, static function (Faker $faker) {
    return [
        'project_id' => factory(Project::class),
        'name' => $faker->word,
        'description' => $faker->sentence,
    ];
});
