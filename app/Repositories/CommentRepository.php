<?php

namespace App\Repositories;

use App\Exceptions\GeneralJsonException;
use App\Models\Comment;
use Illuminate\Support\Facades\DB;

class CommentRepository extends BaseRepository
{
    public function create(array $attributes)
    {
        return DB::transaction(function () use ($attributes) {
            $created = Comment::query()->create([
                'body' => data_get($attributes, 'body'),
                'user_id' => data_get($attributes, 'user_id'),
                'post_id' => data_get($attributes, 'post_id')
            ]);

            throw_if(!$created, GeneralJsonException::class, "Failed to add comment");

            return $created;
        });
    }

    public function update($comment, array $attributes)
    {
        return DB::transaction(function () use ($comment, $attributes) {
            $updated = $comment->update([
                'body' => data_get($attributes, 'body', $comment->body),
                'user_id' => data_get($attributes, 'user_id', $comment->user_id),
                'post_id' => data_get($attributes, 'post_id', $comment->post_id)
            ]);

            throw_if(!$updated, GeneralJsonException::class, "Failed to update comment");

            return $comment;
        });
        
    }

    public function delete($comment)
    {
        return DB::transaction(function () use ($comment){
            $deleted = $comment->forceDelete();

            throw_if(!$deleted, GeneralJsonException::class, "Cannot delete  comment");

            return $deleted;
        });

    }
}