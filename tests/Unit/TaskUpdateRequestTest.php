<?php

namespace Tests\Unit;

use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class TaskUpdateRequestTest extends TestCase
{
    use RefreshDatabase;

    /** @test **/
    public function the_name_is_required(): void
    {
        $task = factory(Task::class)->create();

        $this->patchJson("api/tasks/{$task->uuid}", [
            'name' => '',
        ])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors('name');
    }

    /** @test **/
    public function the_name_is_a_string(): void
    {
        $task = factory(Task::class)->create();

        $this->patchJson("api/tasks/{$task->uuid}", [
            'name' => 1,
        ])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors('name');
    }

    /** @test **/
    public function the_name_does_not_exceed_100_chars(): void
    {
        $task = factory(Task::class)->create();

        $this->patchJson("api/tasks/{$task->uuid}", [
            'name' => str_repeat('a', 101),
        ])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors('name');
    }
}
