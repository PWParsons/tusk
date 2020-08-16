<?php

namespace Tests\Unit\Http\Resources;

use App\Http\Resources\CommentResource;
use App\Http\Resources\ProjectResource;
use App\Http\Resources\TaskResource;
use App\Models\Comment;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskResourceTest extends TestCase
{
    use RefreshDatabase;

    /** @test **/
    public function it_has_the_correct_format(): void
    {
        $task = factory(Task::class)->create();

        $resource = (TaskResource::make($task))->response()->getData(true);

        self::assertSame([
            'data' => [
                'id' => $task->uuid->toString(),
                'name' => $task->name,
                'description' => $task->description,
                'created_at' => $task->created_at->toJson(),
                'updated_at' => $task->updated_at->toJson(),
            ],
        ], $resource);
    }

    /** @test **/
    public function it_includes_the_project_when_loaded(): void
    {
        $task = factory(Task::class)->create();

        $resource = TaskResource::make($task->loadMissing('project'))->jsonSerialize();

        self::assertInstanceOf(ProjectResource::class, $resource['project']);
    }

    /** @test **/
    public function it_includes_the_comments_when_loaded(): void
    {
        $task = factory(Task::class)->create();

        factory(Comment::class)->create([
            'task_id' => $task->id,
        ]);

        $resource = TaskResource::make($task->loadMissing('comments'))->jsonSerialize();

        self::assertInstanceOf(CommentResource::class, $resource['comments'][0]);
    }
}
