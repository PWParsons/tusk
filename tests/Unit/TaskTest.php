<?php

namespace Tests\Unit;

use App\Models\Comment;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    /** @test **/
    public function it_has_many_comments(): void
    {
        $task = factory(Task::class)->create();
        $comment = factory(Comment::class)->create(['task_id' => $task->id]);

        self::assertTrue($task->comments->contains($comment));
    }
}
