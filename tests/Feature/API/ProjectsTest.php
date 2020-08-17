<?php

namespace Tests\Feature\API;

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ProjectsTest extends TestCase
{
    use RefreshDatabase;

    /** @test **/
    public function unauthenticated_users_cannot_manage_projects(): void
    {
        $this->getJson('api/projects')->assertUnauthorized();
        $this->postJson('api/projects')->assertUnauthorized();
        $this->getJson('api/projects/1')->assertUnauthorized();
        $this->patchJson('api/projects/1')->assertUnauthorized();
        $this->deleteJson('api/projects/1')->assertUnauthorized();
    }

    /** @test **/
    public function it_can_display_a_list_of_projects(): void
    {
        Sanctum::actingAs(factory(User::class)->create());

        $projects = factory(Project::class, 3)->create();

        $this->getJson('api/projects')
            ->assertOk()
            ->assertJson([
                'data' => [
                    [
                        'id' => $projects->get(0)->uuid,
                        'name' => $projects->get(0)->name,
                        'created_at' => $projects->get(0)->created_at->toJson(),
                        'updated_at' => $projects->get(0)->updated_at->toJson(),
                    ],
                    [
                        'id' => $projects->get(1)->uuid,
                        'name' => $projects->get(1)->name,
                        'created_at' => $projects->get(1)->created_at->toJson(),
                        'updated_at' => $projects->get(1)->updated_at->toJson(),
                    ],
                    [
                        'id' => $projects->get(2)->uuid,
                        'name' => $projects->get(2)->name,
                        'created_at' => $projects->get(2)->created_at->toJson(),
                        'updated_at' => $projects->get(2)->updated_at->toJson(),
                    ],
                ],
            ]);
    }

    /** @test **/
    public function it_can_create_a_project(): void
    {
        Sanctum::actingAs(factory(User::class)->create());

        $this->postJson('api/projects', [
            'name' => 'Example Project',
        ])
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'name',
                    'created_at',
                    'updated_at',
                ],
            ])
            ->assertCreated();

        $this->assertDatabaseHas('projects', [
            'name' => 'Example Project',
        ]);
    }

    /** @test **/
    public function it_can_display_a_project(): void
    {
        Sanctum::actingAs(factory(User::class)->create());

        $project = factory(Project::class)->create();

        $this->getJson("api/projects/{$project->uuid}")
            ->assertOk()
            ->assertJson([
                'data' => [
                    'id' => $project->uuid,
                    'name' => $project->name,
                    'created_at' => $project->created_at->toJson(),
                    'updated_at' => $project->updated_at->toJson(),
                ],
            ]);
    }

    /** @test **/
    public function it_returns_a_404_when_trying_to_display_a_project_that_doesnt_exist(): void
    {
        Sanctum::actingAs(factory(User::class)->create());

        $this->getJson('api/projects/does-not-exist')->assertNotFound();
    }

    /** @test **/
    public function it_can_update_a_project(): void
    {
        Sanctum::actingAs(factory(User::class)->create());

        $project = factory(Project::class)->create();

        $this->patchJson("api/projects/{$project->uuid}", [
            'name' => 'Example Project',
        ])
            ->assertNoContent();

        $this->assertDatabaseHas('projects', [
            'name' => 'Example Project',
        ]);
    }

    /** @test **/
    public function it_returns_a_404_when_trying_to_update_a_project_that_doesnt_exist(): void
    {
        Sanctum::actingAs(factory(User::class)->create());

        $this->patchJson('api/projects/does-not-exist')->assertNotFound();
    }

    /** @test **/
    public function it_can_delete_a_project(): void
    {
        Sanctum::actingAs(factory(User::class)->create());

        $project = factory(Project::class)->create();

        $this->deleteJson("api/projects/{$project->uuid}")
            ->assertNoContent();

        $this->assertDatabaseMissing('projects', [
            'id' => $project->uuid,
        ]);
    }
}
