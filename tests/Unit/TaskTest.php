<?php

namespace Tests\Unit;

use App\Models\Comment;
use App\Models\Project;
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

    /** @test **/
    public function it_belongs_to_a_project(): void
    {
        $task = factory(Task::class)->create();

        self::assertInstanceOf(Project::class, $task->project);
    }
}
