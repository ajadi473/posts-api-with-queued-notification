<?php

namespace App\Listeners;

use App\Models\User;
use App\Notifications\NewPostNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class SendNewPostNotification
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        $user_id = Auth::id();
        $users = User::where('id','!=' , $user_id)->get();

        Notification::send($users, new NewPostNotification($event->posts));
    }
}
