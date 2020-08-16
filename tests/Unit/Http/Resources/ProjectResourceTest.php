<?php

namespace Tests\Unit\Http\Resources;

use App\Http\Resources\ProjectResource;
use App\Http\Resources\TaskResource;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectResourceTest extends TestCase
{
    use RefreshDatabase;

    /** @test **/
    public function it_has_the_correct_format(): void
    {
        $project = factory(Project::class)->create();

        $resource = (ProjectResource::make($project))->response()->getData(true);

        self::assertSame([
            'data' => [
                'id' => $project->uuid->toString(),
                'name' => $project->name,
                'created_at' => $project->created_at->toJson(),
                'updated_at' => $project->updated_at->toJson(),
            ],
        ], $resource);
    }

    /** @test **/
    public function it_includes_the_tasks_when_loaded(): void
    {
        $project = factory(Project::class)->create();

        factory(Task::class)->create([
            'project_id' => $project->id,
        ]);

        $resource = ProjectResource::make($project->loadMissing('tasks'))->jsonSerialize();

        self::assertInstanceOf(TaskResource::class, $resource['tasks'][0]);
    }
}
