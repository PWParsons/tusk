<?php

namespace Tests\Feature\API\Auth;

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    /** @test **/
    public function a_user_can_get_their_token_via_the_api(): void
    {
        $user = factory(User::class)->create();

        $this->postJson('api/login', [
            'email' => $user->email,
            'password' => 'password',
            'device_name' => 'testing',
        ])
            ->assertJsonStructure(['token'])
            ->assertStatus(200);
    }

    /** @test **/
    public function a_user_can_log_in_using_the_web_routes(): void
    {
        $user = factory(User::class)->create();

        $this->post('login', [
            'email' => $user->email,
            'password' => 'password',
        ])
            ->assertRedirect(RouteServiceProvider::HOME);
    }
}
