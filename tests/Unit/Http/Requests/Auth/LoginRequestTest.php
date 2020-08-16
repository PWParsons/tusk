<?php

namespace Tests\Unit\Http\Requests\Auth;

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class LoginRequestTest extends TestCase
{
    use RefreshDatabase;

    /** @test **/
    public function it_requires_the_device_name_when_the_request_wants_json(): void
    {
        $user = factory(User::class)->create();

        $this->postJson('api/login', [
            'email' => $user->email,
            'password' => 'password',
        ])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors('device_name');
    }

    /** @test **/
    public function it_does_not_require_the_device_name_on_web_routes(): void
    {
        $user = factory(User::class)->create();

        $this->post('login', [
            'email' => $user->email,
            'password' => 'password',
        ])
            ->assertRedirect(RouteServiceProvider::HOME);
    }
}
