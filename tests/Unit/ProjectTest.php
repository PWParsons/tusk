<?php

namespace Tests\Unit;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectTest extends TestCase
{
    use RefreshDatabase;

    /** @test **/
    public function it_has_many_tasks(): void
    {
        $project = factory(Project::class)->create();
        $task = factory(Task::class)->create(['project_id' => $project->id]);

        self::assertTrue($project->tasks->contains($task));
    }
}
