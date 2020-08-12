<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Comment;
use App\Models\Task;
use Faker\Generator as Faker;

$factory->define(Comment::class, static function (Faker $faker) {
    return [
        'task_id' => factory(Task::class),
        'description' => $faker->sentence,
    ];
});
