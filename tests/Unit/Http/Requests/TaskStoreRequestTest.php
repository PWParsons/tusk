<?php

namespace Tests\Unit\Http\Requests;

use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class TaskStoreRequestTest extends TestCase
{
    use RefreshDatabase;

    /** @test **/
    public function the_name_is_required(): void
    {
        $project = factory(Project::class)->create();

        $this->postJson("api/projects/{$project->uuid}/tasks", [
            'name' => '',
        ])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors('name');
    }

    /** @test **/
    public function the_name_is_a_string(): void
    {
        $project = factory(Project::class)->create();

        $this->postJson("api/projects/{$project->uuid}/tasks", [
            'name' => 1,
        ])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors('name');
    }

    /** @test **/
    public function the_name_does_not_exceed_100_chars(): void
    {
        $project = factory(Project::class)->create();

        $this->postJson("api/projects/{$project->uuid}/tasks", [
            'name' => str_repeat('a', 101),
        ])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors('name');
    }

    /** @test **/
    public function the_description_is_a_string(): void
    {
        $project = factory(Project::class)->create();

        $this->postJson("api/projects/{$project->uuid}/tasks", [
            'name' => 'Test',
            'description' => 1,
        ])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors('description');
    }

    /** @test **/
    public function the_description_does_not_exceed_15000_chars(): void
    {
        $project = factory(Project::class)->create();

        $this->postJson("api/projects/{$project->uuid}/tasks", [
            'name' => 'Test',
            'description' => str_repeat('a', 15001),
        ])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors('description');
    }
}
