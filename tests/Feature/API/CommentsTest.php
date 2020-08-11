<?php

namespace Tests\Feature\API;

use App\Models\Comment;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommentsTest extends TestCase
{
    use RefreshDatabase;

    /** @test **/
    public function it_can_display_a_listing_of_the_comment(): void
    {
        $task = factory(Task::class)->create();
        $comments = factory(Comment::class, 3)->create(['task_id' => $task->id]);

        $this->getJson("api/tasks/{$task->id}/comments")
            ->assertOk()
            ->assertJson([
                'data' => [
                    [
                        'id' => $comments->get(0)->id,
                        'description' => $comments->get(0)->description,
                        'created_at' => $comments->get(0)->created_at->toJson(),
                        'updated_at' => $comments->get(0)->updated_at->toJson(),
                    ],
                    [
                        'id' => $comments->get(1)->id,
                        'description' => $comments->get(1)->description,
                        'created_at' => $comments->get(1)->created_at->toJson(),
                        'updated_at' => $comments->get(1)->updated_at->toJson(),
                    ],
                    [
                        'id' => $comments->get(2)->id,
                        'description' => $comments->get(2)->description,
                        'created_at' => $comments->get(2)->created_at->toJson(),
                        'updated_at' => $comments->get(2)->updated_at->toJson(),
                    ],
                ],
            ]);
    }

    /** @test **/
    public function it_does_not_display_comments_of_other_tasks(): void
    {
        $taskA = factory(Task::class)->create();
        $taskB = factory(Task::class)->create();
        factory(Comment::class)->create(['task_id' => $taskB->id]);

        $this->getJson("api/tasks/{$taskA->id}/comments")
            ->assertOk()
            ->assertJson([
                'data' => [],
            ]);
    }

    /** @test **/
    public function it_returns_a_404_when_trying_to_display_comments_of_a_task_that_doesnt_exist(): void
    {
        $this->getJson('api/tasks/does-not-exist/comments')->assertNotFound();
    }

    /** @test **/
    public function it_can_create_a_comment(): void
    {
        $task = factory(Task::class)->create();

        $this->postJson("api/tasks/{$task->id}/comments", [
            'description' => 'Example comment',
        ])
            ->assertCreated();

        $this->assertDatabaseHas('comments', [
            'task_id' => $task->id,
            'description' => 'Example comment',
        ]);
    }

    /** @test **/
    public function it_returns_a_404_when_trying_to_create_a_comment_for_a_task_that_doesnt_exist(): void
    {
        $this->postJson('api/tasks/does-not-exist/comments', [
            'description' => 'Example comment',
        ])
            ->assertNotFound();
    }

    /** @test **/
    public function it_can_display_a_comment(): void
    {
        $comment = factory(Comment::class)->create();

        $this->getJson("api/comments/{$comment->id}")
            ->assertOk()
            ->assertJson([
                'data' => [
                    'id' => $comment->id,
                    'description' => $comment->description,
                    'created_at' => $comment->created_at->toJson(),
                    'updated_at' => $comment->updated_at->toJson(),
                ],
            ]);
    }

    /** @test **/
    public function it_returns_a_404_when_trying_to_display_a_comment_that_doesnt_exist(): void
    {
        $task = factory(Task::class)->create();

        $this->getJson("api/tasks/{$task->id}/comments/does-not-exist")->assertNotFound();
    }

    /** @test **/
    public function it_can_update_a_comment(): void
    {
        $comment = factory(Comment::class)->create();

        $this->patchJson("api/comments/{$comment->id}", [
            'description' => 'Example comment',
        ])
            ->assertNoContent();

        $this->assertDatabaseHas('comments', [
            'id' => $comment->id,
            'description' => 'Example comment',
        ]);
    }

    /** @test **/
    public function it_returns_a_404_when_trying_to_update_a_comment_that_doesnt_exist(): void
    {
        $task = factory(Task::class)->create();

        $this->patchJson("api/tasks/{$task->id}/comments/does-not-exist")->assertNotFound();
    }

    /** @test **/
    public function it_can_delete_a_comment(): void
    {
        $comment = factory(Comment::class)->create();

        $this->deleteJson("api/comments/{$comment->id}")
            ->assertNoContent();

        $this->assertDatabaseMissing('comments', [
            'id' => $comment->id,
        ]);
    }

    /** @test **/
    public function it_returns_a_404_when_trying_to_delete_a_comment_that_doesnt_exist(): void
    {
        $task = factory(Task::class)->create();

        $this->deleteJson("api/tasks/{$task->id}/comments/does-not-exist")->assertNotFound();
    }
}
