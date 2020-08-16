<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectStoreRequest;
use App\Http\Requests\ProjectUpdateRequest;
use App\Http\Resources\ProjectResource;
use App\Models\Project;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ProjectController extends Controller
{
    /**
     * Display a listing of the projects.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(): AnonymousResourceCollection
    {
        $projects = Project::all();

        return ProjectResource::collection($projects);
    }

    /**
     * Store a newly created project in storage.
     *
     * @param  \App\Http\Requests\ProjectStoreRequest  $request
     *
     * @return \App\Http\Resources\ProjectResource
     */
    public function store(ProjectStoreRequest $request): ProjectResource
    {
        $project = Project::create($request->all());

        return ProjectResource::make($project);
    }

    /**
     * Display the specified project.
     *
     * @param  \App\Models\Project  $project
     *
     * @return \App\Http\Resources\ProjectResource
     */
    public function show(Project $project): ProjectResource
    {
        return ProjectResource::make($project->loadMissing('tasks'));
    }

    /**
     * Update the specified project in storage.
     *
     * @param  \App\Http\Requests\ProjectUpdateRequest  $request
     * @param  \App\Models\Project  $project
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function update(ProjectUpdateRequest $request, Project $project)
    {
        $project->update($request->all());

        return response('', 204);
    }

    /**
     * Remove the specified project from storage.
     *
     * @param  \App\Models\Project  $project
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     *
     * @throws \Exception
     */
    public function destroy(Project $project)
    {
        $project->delete();

        return response('', 204);
    }
}
