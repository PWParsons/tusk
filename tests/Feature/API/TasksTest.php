<?php

namespace Tests\Feature\API;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TasksTest extends TestCase
{
    use RefreshDatabase;

    /** @test **/
    public function it_can_display_a_list_of_tasks(): void
    {
        $project = factory(Project::class)->create();

        $tasks = factory(Task::class, 3)->create([
            'project_id' => $project,
        ]);

        $this->getJson("api/projects/{$project->id}/tasks")
            ->assertOk()
            ->assertJson([
                'data' => [
                    [
                        'id' => $tasks->get(0)->id,
                        'name' => $tasks->get(0)->name,
                        'created_at' => $tasks->get(0)->created_at->toJson(),
                        'updated_at' => $tasks->get(0)->updated_at->toJson(),
                    ],
                    [
                        'id' => $tasks->get(1)->id,
                        'name' => $tasks->get(1)->name,
                        'created_at' => $tasks->get(1)->created_at->toJson(),
                        'updated_at' => $tasks->get(1)->updated_at->toJson(),
                    ],
                    [
                        'id' => $tasks->get(2)->id,
                        'name' => $tasks->get(2)->name,
                        'created_at' => $tasks->get(2)->created_at->toJson(),
                        'updated_at' => $tasks->get(2)->updated_at->toJson(),
                    ],
                ],
            ]);
    }

    /** @test **/
    public function it_only_displays_the_given_projects_tasks(): void
    {
        $project = factory(Project::class)->create();

        $tasks = factory(Task::class, 2)->create([
            'project_id' => $project,
        ]);

        $anotherTask = factory(Task::class)->create();

        $this->getJson("api/projects/{$project->id}/tasks")
            ->assertOk()
            ->assertJson([
                'data' => [
                    [
                        'id' => $tasks->get(0)->id,
                        'name' => $tasks->get(0)->name,
                        'created_at' => $tasks->get(0)->created_at->toJson(),
                        'updated_at' => $tasks->get(0)->updated_at->toJson(),
                    ],
                    [
                        'id' => $tasks->get(1)->id,
                        'name' => $tasks->get(1)->name,
                        'created_at' => $tasks->get(1)->created_at->toJson(),
                        'updated_at' => $tasks->get(1)->updated_at->toJson(),
                    ],
                ],
            ])
            ->assertJsonMissing([
                'data' => [
                    [
                        'id' => $anotherTask->id,
                        'name' => $anotherTask->name,
                        'created_at' => $anotherTask->created_at->toJson(),
                        'updated_at' => $anotherTask->updated_at->toJson(),
                    ],
                ],
            ]);
    }

    /** @test **/
    public function it_can_create_a_task(): void
    {
        $project = factory(Project::class)->create();

        $this->postJson("api/projects/{$project->id}/tasks", [
            'name' => 'Example Task',
        ])
            ->assertCreated();

        $this->assertDatabaseHas('tasks', [
            'name' => 'Example Task',
        ]);
    }

    /** @test **/
    public function it_can_display_a_task(): void
    {
        $task = factory(Task::class)->create();

        $this->getJson("api/tasks/{$task->id}")
            ->assertOk()
            ->assertJson([
                'data' => [
                    'id' => $task->id,
                    'name' => $task->name,
                    'created_at' => $task->created_at->toJson(),
                    'updated_at' => $task->updated_at->toJson(),
                ],
            ]);
    }

    /** @test **/
    public function it_returns_a_404_when_trying_to_display_a_task_that_doesnt_exist(): void
    {
        $this->getJson('api/tasks/does-not-exist')->assertNotFound();
    }

    /** @test **/
    public function it_can_update_a_task(): void
    {
        $task = factory(Task::class)->create();

        $this->patchJson("api/tasks/{$task->id}", [
            'name' => 'Example Test',
        ])
            ->assertNoContent();

        $this->assertDatabaseHas('tasks', [
            'name' => 'Example Test',
        ]);
    }

    /** @test **/
    public function it_returns_a_404_when_trying_to_update_a_task_that_doesnt_exist(): void
    {
        $this->patchJson('api/tasks/does-not-exist')->assertNotFound();
    }

    /** @test **/
    public function it_can_delete_a_task(): void
    {
        $task = factory(Task::class)->create();

        $this->deleteJson("api/tasks/{$task->id}")
            ->assertNoContent();

        $this->assertDatabaseMissing('tasks', [
            'id' => $task->id,
        ]);
    }

    /** @test **/
    public function it_returns_a_404_when_trying_to_delete_a_task_that_doesnt_exist(): void
    {
        $this->deleteJson('api/tasks/does-not-exist')->assertNotFound();
    }
}
