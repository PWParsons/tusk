<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskStoreRequest;
use App\Http\Requests\TaskUpdateRequest;
use App\Http\Resources\TaskResource;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class TaskController extends Controller
{
    /**
     * Display a listing of the tasks.
     *
     * @param  \App\Models\Project  $project
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Project $project): AnonymousResourceCollection
    {
        return TaskResource::collection($project->tasks);
    }

    /**
     * Store a newly created task in storage.
     *
     * @param  \App\Http\Requests\TaskStoreRequest  $request
     * @param  \App\Models\Project  $project
     *
     * @return \Illuminate\Http\Response
     */
    public function store(TaskStoreRequest $request, Project $project): Response
    {
        $project->tasks()->create($request->all());

        return response('', 201);
    }

    /**
     * Display the specified task.
     *
     * @param \App\Models\Task $task
     *
     * @return \App\Http\Resources\TaskResource
     */
    public function show(Task $task): TaskResource
    {
        return TaskResource::make($task);
    }

    /**
     * Update the specified task in storage.
     *
     * @param  \App\Http\Requests\TaskUpdateRequest  $request
     * @param  \App\Models\Task  $task
     *
     * @return \Illuminate\Http\Response
     */
    public function update(TaskUpdateRequest $request, Task $task): Response
    {
        $task->name = $request->name;
        $task->save();

        return response('', 204);
    }

    /**
     * Remove the specified task from storage.
     *
     * @param \App\Models\Task $task
     *
     * @return \Illuminate\Http\Response
     *
     * @throws \Exception
     */
    public function destroy(Task $task): Response
    {
        $task->delete();

        return response('', 204);
    }
}
