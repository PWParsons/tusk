<?php

namespace Tests\Unit\Models;

use App\Models\Comment;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommentTest extends TestCase
{
    use RefreshDatabase;

    /** @test **/
    public function it_genarates_a_uuid_on_creation(): void
    {
        $comment = factory(Comment::class)->create();

        self::assertNotNull($comment->uuid);
    }

    /** @test **/
    public function the_route_key_name_is_the_uuid(): void
    {
        $comment = factory(Comment::class)->create();

        self::assertEquals('uuid', $comment->getRouteKeyName());
    }

    /** @test **/
    public function it_belongs_to_task(): void
    {
        $comment = factory(Comment::class)->create();

        self::assertInstanceOf(Task::class, $comment->task);
    }
}
