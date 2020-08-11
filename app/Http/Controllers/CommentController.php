<?php

namespace App\Http\Controllers;

use App\Http\Resources\CommentResource;
use App\Models\Comment;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class CommentController extends Controller
{
    /**
     * Display a listing of the specified task's comments.
     *
     * @param \App\Models\Task $task
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Task $task): AnonymousResourceCollection
    {
        return CommentResource::collection($task->comments);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Task $task
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Task $task): Response
    {
        $task->comments()->create([
            'description' => $request->description,
        ]);

        return response('', 201);
    }

    /**
     * Display the specified comment.
     *
     * @param \App\Models\Comment $comment
     * @return \App\Http\Resources\CommentResource
     */
    public function show(Comment $comment): CommentResource
    {
        return new CommentResource($comment);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Comment $comment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Comment $comment): Response
    {
        $comment->description = $request->description;
        $comment->save();

        return response('', 204);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Comment $comment
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Comment $comment): Response
    {
        $comment->delete();

        return response('', 204);
    }
}
