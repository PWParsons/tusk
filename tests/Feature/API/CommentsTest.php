<?php

namespace Tests\Feature\API;

use App\Models\Comment;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CommentsTest extends TestCase
{
    use RefreshDatabase;

    /** @test **/
    public function unauthenticated_users_cannot_manage_comments(): void
    {
        $this->getJson('api/tasks/1/comments')->assertUnauthorized();
        $this->postJson('api/tasks/1/comments')->assertUnauthorized();
        $this->getJson('api/comments/1')->assertUnauthorized();
        $this->patchJson('api/comments/1')->assertUnauthorized();
        $this->deleteJson('api/comments/1')->assertUnauthorized();
    }

    /** @test **/
    public function it_can_display_a_listing_of_the_comment(): void
    {
        Sanctum::actingAs(factory(User::class)->create());

        $task = factory(Task::class)->create();

        $comments = factory(Comment::class, 3)->create(['task_id' => $task->id]);

        $this->getJson("api/tasks/{$task->uuid}/comments")
            ->assertOk()
            ->assertJson([
                'data' => [
                    [
                        'id' => $comments->get(0)->uuid,
                        'description' => $comments->get(0)->description,
                        'created_at' => $comments->get(0)->created_at->toJson(),
                        'updated_at' => $comments->get(0)->updated_at->toJson(),
                    ],
                    [
                        'id' => $comments->get(1)->uuid,
                        'description' => $comments->get(1)->description,
                        'created_at' => $comments->get(1)->created_at->toJson(),
                        'updated_at' => $comments->get(1)->updated_at->toJson(),
                    ],
                    [
                        'id' => $comments->get(2)->uuid,
                        'description' => $comments->get(2)->description,
                        'created_at' => $comments->get(2)->created_at->toJson(),
                        'updated_at' => $comments->get(2)->updated_at->toJson(),
                    ],
                ],
            ]);
    }

    /** @test **/
    public function it_only_displays_the_comments_of_the_give_task(): void
    {
        Sanctum::actingAs(factory(User::class)->create());

        $taskA = factory(Task::class)->create();

        $taskB = factory(Task::class)->create();

        factory(Comment::class)->create(['task_id' => $taskB->uuid]);

        $this->getJson("api/tasks/{$taskA->uuid}/comments")
            ->assertOk()
            ->assertJson([
                'data' => [],
            ]);
    }

    /** @test **/
    public function it_returns_a_404_when_trying_to_display_comments_of_a_task_that_doesnt_exist(): void
    {
        Sanctum::actingAs(factory(User::class)->create());

        $this->getJson('api/tasks/does-not-exist/comments')->assertNotFound();
    }

    /** @test **/
    public function it_can_create_a_comment(): void
    {
        Sanctum::actingAs(factory(User::class)->create());

        $task = factory(Task::class)->create();

        $this->postJson("api/tasks/{$task->uuid}/comments", [
            'description' => 'Example comment',
        ])
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'description',
                    'created_at',
                    'updated_at',
                ],
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
        Sanctum::actingAs(factory(User::class)->create());

        $this->postJson('api/tasks/does-not-exist/comments', [
            'description' => 'Example comment',
        ])
            ->assertNotFound();
    }

    /** @test **/
    public function it_can_display_a_comment(): void
    {
        Sanctum::actingAs(factory(User::class)->create());

        $comment = factory(Comment::class)->create();

        $this->getJson("api/comments/{$comment->uuid}")
            ->assertOk()
            ->assertJson([
                'data' => [
                    'id' => $comment->uuid,
                    'description' => $comment->description,
                    'created_at' => $comment->created_at->toJson(),
                    'updated_at' => $comment->updated_at->toJson(),
                ],
            ]);
    }

    /** @test **/
    public function it_returns_a_404_when_trying_to_display_a_comment_that_doesnt_exist(): void
    {
        Sanctum::actingAs(factory(User::class)->create());

        $task = factory(Task::class)->create();

        $this->getJson("api/tasks/{$task->uuid}/comments/does-not-exist")->assertNotFound();
    }

    /** @test **/
    public function it_can_update_a_comment(): void
    {
        Sanctum::actingAs(factory(User::class)->create());

        $comment = factory(Comment::class)->create();

        $this->patchJson("api/comments/{$comment->uuid}", [
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

        $this->patchJson("api/tasks/{$task->uuid}/comments/does-not-exist")->assertNotFound();
    }

    /** @test **/
    public function it_can_delete_a_comment(): void
    {
        Sanctum::actingAs(factory(User::class)->create());

        $comment = factory(Comment::class)->create();

        $this->deleteJson("api/comments/{$comment->uuid}")
            ->assertNoContent();

        $this->assertDatabaseMissing('comments', [
            'id' => $comment->uuid,
        ]);
    }

    /** @test **/
    public function it_returns_a_404_when_trying_to_delete_a_comment_that_doesnt_exist(): void
    {
        $task = factory(Task::class)->create();

        $this->deleteJson("api/tasks/{$task->uuid}/comments/does-not-exist")->assertNotFound();
    }
}
