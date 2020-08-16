<?php

namespace Tests\Feature\API\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    /** @test **/
    public function a_user_can_register(): void
    {
        $this->postJson('api/register', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password',
        ])
            ->assertStatus(201);
    }
}
