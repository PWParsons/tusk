<?php

namespace Tests\Unit\Http\Requests;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class CommentStoreRequestTest extends TestCase
{
    use RefreshDatabase;

    /** @test **/
    public function the_description_is_required(): void
    {
        Sanctum::actingAs(factory(User::class)->create());

        $task = factory(Task::class)->create();

        $this->postJson("api/tasks/{$task->uuid}/comments", [
            'description' => '',
        ])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors('description');
    }

    /** @test **/
    public function the_description_is_a_string(): void
    {
        Sanctum::actingAs(factory(User::class)->create());

        $task = factory(Task::class)->create();

        $this->postJson("api/tasks/{$task->uuid}/comments", [
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

        $this->postJson("api/tasks/{$task->uuid}/comments", [
            'description' => str_repeat('a', 15001),
        ])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors('description');
    }
}
