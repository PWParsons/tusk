<?php

namespace Tests\Unit\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class ProjectStoreRequestTest extends TestCase
{
    use RefreshDatabase;

    /** @test **/
    public function the_name_is_required(): void
    {
        Sanctum::actingAs(factory(User::class)->create());

        $this->postJson('api/projects', [
            'name' => '',
        ])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors('name');
    }

    /** @test **/
    public function the_name_is_a_string(): void
    {
        Sanctum::actingAs(factory(User::class)->create());

        $this->postJson('api/projects', [
            'name' => 1,
        ])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors('name');
    }

    /** @test **/
    public function the_name_does_not_exceed_100_chars(): void
    {
        Sanctum::actingAs(factory(User::class)->create());

        $this->postJson('api/projects', [
            'name' => str_repeat('a', 101),
        ])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors('name');
    }
}
