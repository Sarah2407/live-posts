<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Resources\PostResource;
use App\Notifications\PostSharedNotification;
use App\Repositories\PostRepository;
use Illuminate\Notifications\Notification as NotificationsNotification;
use Illuminate\Support\Facades\Notification as FacadesNotification;
use Illuminate\Support\Facades\URL;

/**
 * @group Post Management
 * 
 * APIs to manage user resource
 */

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return ResourceCollection
     */
    public function index()
    {
        $posts = Post::query()->paginate(10);

        return PostResource::collection($posts);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \StorePostRequest  $request
     * @return PostResource
     */
    public function store(StorePostRequest $request, PostRepository $repository)
    {
        $created = $repository->create($request->only([
            'title',
            'body',
            'user_ids'
        ]));

        return new PostResource($created);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return PostResource
     */
    public function show(Post $post)
    {
        return new PostResource($post);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatePostRequest  $request
     * @param  \App\Models\Post  $post
     * @return PostResource | JsonResponse
     */
    public function update(Request $request, Post $post, PostRepository $repository)
    {
        $post = $repository->update($post, $request->only([
            'title',
            'body',
            'user_ids'
        ]));

        return new PostResource($post);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Post $post, PostRepository $repository)
    {
        $post = $repository->delete($post);

        return new JsonResponse([
            'data' => 'success'
        ]);
    }

    /**
     * Share a specified post from storage.
     *
     * @response 200 {
     * "data": "signed url..."
     * }
     * 
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\JsonResponse
     */

    public function share(Request $request, Post $post)
    {
        $url = URL::temporarySignedRoute('shared.post', now()->addDays(30), [
            'post' => $post->id,
        ]);

        $users = User::query()->whereIn('id', [$request->user_ids])->get();

        FacadesNotification::send($users, new PostSharedNotification($post, $url));

        $user = User::query()->find(1);
        $user->notify(new PostSharedNotification($post, $url));

        return new JsonResponse([
            'data' => $url,
        ]);
    }
}
