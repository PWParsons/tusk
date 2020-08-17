<?php

Route::post('register', 'Auth\RegisterController@register');
Route::post('login', 'Auth\LoginController@login');

Route::middleware('auth:sanctum')->group(static function () {
    Route::apiResource('projects', 'ProjectController');

    Route::apiResource('projects.tasks', 'TaskController')->shallow();

    Route::apiResource('tasks.comments', 'CommentController')->shallow();
});

