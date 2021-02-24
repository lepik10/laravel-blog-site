<?php

namespace App\Observers;

use App\Models\Post;
use Illuminate\Support\Facades\Cache;

class PostObserver
{
    public function updating(Post $post)
    {
        Cache::forget("post-{$post->id}");
    }

    public function deleting(Post $post)
    {
        $post->comments()->delete();
        $post->image()->delete();
        Cache::forget("post-{$post->id}");
    }

    public function restoring(Post $post)
    {
        $post->comments()->restore();
    }
}
