<?php

Route::apiResource('tasks', 'TaskController');

Route::apiResource('tasks.comments', 'CommentController')->shallow();
