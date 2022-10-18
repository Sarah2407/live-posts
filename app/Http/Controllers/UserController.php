<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\UserResource;
use App\Repositories\UserRepository;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;

/**
 * @group User Management
 * 
 * APIs to manage user resource
 */

class UserController extends Controller
{
    /**
     * Display a listing of the users.
     * 
     * Gets a list of users
     *
     * @return ResourceCollection
     */
    public function index()
    {
        $users = User::query()->paginate(10);

        return UserResource::collection($users);
    }

    /**
     * Store a newly created user in storage.
     *
     * @bodyParam name string required Name of the user. Example John Mensah
     * @bodyParam email string required Email of the user. Example john@gmail.com 
     * 
     * @param  Illuminate\Http\Request $request
     * @return UserResource
     */
    public function store(Request $request, UserRepository $repository)
    {
        $created = $repository->create($request->only([
            'name',
            'email',
            'password'
        ]));

        return new UserResource($created);
    }

    /**
     * Display the specified user.
     * 
     * @urlParam id int required User ID
     *
     * @param  \App\Models\User  $user
     * @return UserResource
     */
    public function show(User $user)
    {
        return new UserResource($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Illuminate\Http\Request $request
     * @param  \App\Models\User  $user
     * @return UserResource | JsonResponse
     */
    public function update(Request $request, User $user, UserRepository $repository)
    {
        $user = $repository->update($user, $request->only([
            'name',
            'email'
        ]));

        return new UserResource($user);
    }

    /**
     * Remove the specified user from storage.
     * 
     * @response 200 {
     * "data": "delete successful"
     * }
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(User $user, UserRepository $repository)
    {
        $post = $repository->delete($user);

        return new JsonResponse([
            'data' => 'delete successful'
        ]);
    }
}
