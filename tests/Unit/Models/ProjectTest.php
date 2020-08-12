<?php

namespace Tests\Unit\Models;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectTest extends TestCase
{
    use RefreshDatabase;

    /** @test **/
    public function it_genarates_a_uuid_on_creation(): void
    {
        $project = factory(Project::class)->create();

        self::assertNotNull($project->uuid);
    }

    /** @test **/
    public function the_route_key_name_is_the_uuid(): void
    {
        $project = factory(Project::class)->create();

        self::assertEquals('uuid', $project->getRouteKeyName());
    }

    /** @test **/
    public function it_has_many_tasks(): void
    {
        $project = factory(Project::class)->create();
        $task = factory(Task::class)->create(['project_id' => $project->id]);

        self::assertTrue($project->tasks->contains($task));
    }
}
