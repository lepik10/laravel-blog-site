<?php

namespace App\Listeners;

use App\Events\CommentPosted;
use App\Jobs\NotifyUsersPostWasCommented;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class NotifyUsersAboutComment
{
    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(CommentPosted $event)
    {
        dd('asdasdasdasdads');
        Mail::to($event->comment->commentable->user)->queue(
            new \App\Mail\CommentPosted($event->comment)
        );

        NotifyUsersPostWasCommented::dispatch($event->comment);
    }
}
