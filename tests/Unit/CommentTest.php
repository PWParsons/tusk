<?php

namespace Tests\Unit;

use App\Models\Comment;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommentTest extends TestCase
{
    use RefreshDatabase;

    /** @test **/
    public function it_belongs_to_task(): void
    {
        $comment = factory(Comment::class)->create();

        self::assertInstanceOf(Task::class, $comment->task);
    }
}
