<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\CommentResource;
use App\Http\Requests\StoreCommentRequest;
use App\Http\Requests\UpdateCommentRequest;
use App\Repositories\CommentRepository;

/**
 * @group Comment Management
 * 
 * APIs to manage user resource
 */

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return ResoureController
     */
    public function index()
    {
        $comments = Comment::query()->paginate(10);

        return CommentResource::collection($comments);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Illuminate\Http\Request $request
     * @return CommentResource
     */
    public function store(Request $request, CommentRepository $repository)
    {
        $created = $repository->create($request->only([
            'body',
            'user_id',
            'post_id'
        ]));

        return new CommentResource($created);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Comment  $comment
     * @return CommentResource
     */
    public function show(Comment $comment)
    {
        return new CommentResource($comment);
    }

    /**
     * Update the specified resource in storage.
     *
     *@param  Illuminate\Http\Request $request
     * @param  \App\Models\Comment  $comment
     * @return CommentResource | JsonResponse
     */
    public function update(Request $request, Comment $comment, CommentRepository $repository)
    {
        $comment = $repository->update($comment, $request->only([
            'body',
            'user_id',
            'post_id'
        ]));

        return new CommentResource($comment);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Comment $comment, CommentRepository $repository)
    {
        $comment = $repository->delete($comment);

        return new JsonResponse([
            'data' => 'delete successful'
        ]);
    }
}
