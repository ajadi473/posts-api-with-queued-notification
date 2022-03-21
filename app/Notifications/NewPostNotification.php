<?php

namespace App\Notifications;

use App\Models\posts;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewPostNotification extends Notification implements ShouldQueue
{
    use Queueable;
    private $posts;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($posts)
    {
        $this->post = $posts;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'topic' => $this->post['topic'],
            'post' => $this->post->id, 
        ];
    }
}
