<?php

Route::apiResource('projects', 'ProjectController');

Route::apiResource('projects.tasks', 'TaskController')->shallow();

Route::apiResource('tasks.comments', 'CommentController')->shallow();
