<?php

namespace App\Observers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Support\Facades\Cache;

class CommentObserver
{
    public function creating(Comment $comment)
    {
        if($comment->commentable_type === Post::class) {
            Cache::forget("post-{$comment->commentable_id}");
            Cache::forget("mostCommented");
        }
    }
}
