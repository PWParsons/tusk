<?php

namespace Tests\Unit\Http\Resources;

use App\Http\Resources\CommentResource;
use App\Http\Resources\TaskResource;
use App\Models\Comment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommentResourceTest extends TestCase
{
    use RefreshDatabase;

    /** @test **/
    public function it_has_the_correct_format(): void
    {
        $comment = factory(Comment::class)->create();

        $resource = (CommentResource::make($comment))->response()->getData(true);

        self::assertSame([
            'data' => [
                'id' => $comment->uuid->toString(),
                'description' => $comment->description,
                'created_at' => $comment->created_at->toJson(),
                'updated_at' => $comment->updated_at->toJson(),
            ],
        ], $resource);
    }

    /** @test **/
    public function it_includes_the_project_when_loaded(): void
    {
        $comment = factory(Comment::class)->create();

        $resource = CommentResource::make($comment->loadMissing('task'))->jsonSerialize();

        self::assertInstanceOf(TaskResource::class, $resource['task']);
    }
}
