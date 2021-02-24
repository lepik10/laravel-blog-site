<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreComment;
use App\Mail\CommentPosted;
use App\Models\Post;
use App\Http\Resources\Comment as CommentResource;

class PostCommentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->only(['store']);
    }

    public function index(Post $post)
    {
        return CommentResource::collection($post->comments()->with('user')->get());
        return $post->comments()->with('user')->get();
    }

    public function store(Post $post, StoreComment $request)
    {
        $comment = $post->comments()->create([
            'content' => $request->input('content'),
            'user_id' => $request->user()->id
        ]);

//        Mail::to($post->user)->send(
//            new CommentPosted($comment)
//        );

        //$when = now()->addMinutes(1);

        event(new CommentPosted($comment));



//        Mail::to($post->user)->later(
//            $when,
//            new CommentPosted($comment)
//        );

        return redirect()->back()->withStatus('Comment was created!');
    }
}
