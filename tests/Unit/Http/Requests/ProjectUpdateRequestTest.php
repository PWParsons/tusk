<?php

namespace Tests\Unit\Http\Requests;

use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class ProjectUpdateRequestTest extends TestCase
{
    use RefreshDatabase;

    /** @test **/
    public function the_name_is_required(): void
    {
        $project = factory(Project::class)->create();

        $this->patchJson("api/projects/{$project->uuid}", [
            'name' => '',
        ])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors('name');
    }

    /** @test **/
    public function the_name_is_a_string(): void
    {
        $project = factory(Project::class)->create();

        $this->patchJson("api/projects/{$project->uuid}", [
            'name' => 1,
        ])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors('name');
    }

    /** @test **/
    public function the_name_does_not_exceed_100_chars(): void
    {
        $project = factory(Project::class)->create();

        $this->patchJson("api/projects/{$project->uuid}", [
            'name' => str_repeat('a', 101),
        ])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors('name');
    }
}
