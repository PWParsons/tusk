<?php

namespace Tests\Unit\Exceptions;

use App\Exceptions\Handler;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class HandlerTest extends TestCase
{
    /** @test **/
    public function the_query_exception_returns_a_resource_not_found_message(): void
    {
        $handler = app(Handler::class);

        $request = Request::create('/test', 'GET');
        $request->headers->set('accept', 'application/json');

        $exception = new QueryException('select ? from ?', ['name', 'nothing'], new Exception);

        $response = $handler->render($request, $exception);

        TestResponse::fromBaseResponse($response)
            ->assertJson(['message' => 'Resource not found'])
            ->assertStatus(404);
    }
}
