<?php

namespace Tests\Unit\Http\Requests;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class TaskUpdateRequestTest extends TestCase
{
    use RefreshDatabase;

    /** @test **/
    public function the_name_is_required(): void
    {
        Sanctum::actingAs(factory(User::class)->create());

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
        Sanctum::actingAs(factory(User::class)->create());

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
        Sanctum::actingAs(factory(User::class)->create());

        $task = factory(Task::class)->create();

        $this->patchJson("api/tasks/{$task->uuid}", [
            'name' => str_repeat('a', 101),
        ])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors('name');
    }

    /** @test **/
    public function the_description_is_a_string(): void
    {
        Sanctum::actingAs(factory(User::class)->create());

        $task = factory(Task::class)->create();

        $this->patchJson("api/tasks/{$task->uuid}", [
            'name' => 'Test',
            'description' => 1,
        ])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors('description');
    }

    /** @test **/
    public function the_description_does_not_exceed_15000_chars(): void
    {
        Sanctum::actingAs(factory(User::class)->create());

        $task = factory(Task::class)->create();

        $this->patchJson("api/tasks/{$task->uuid}", [
            'name' => 'Test',
            'description' => str_repeat('a', 15001),
        ])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors('description');
    }
}
