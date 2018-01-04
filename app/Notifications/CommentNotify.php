<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CommentNotify extends Notification implements ShouldQueue
{
    private $comment;
    private $user;
    private $isReply;

    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($comment, $user, bool $isReply = false)
    {
        $this->comment = $comment;
        $this->user = $user;
        $this->isReply = $isReply;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toDatabase($notifiable)
    {
        return [
            'html' => $this->getTitle(),
            'text' => strip_tags(str_replace(['<strong>', '</strong>'], '"', $this->getTitle())),
            'slug' => $this->comment->commentable->slug,
            'hash' => '#comment-' . $this->comment->id,
            'type' => snake_case(class_basename($this->comment->commentable_type)),
            'created_at' => (string) $this->comment->created_at,
        ];
    }

    /**
     * Get the broadcastable representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return BroadcastMessage
     */
    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'html' => $this->getTitle(),
            'text' => strip_tags(str_replace(['<strong>', '</strong>'], '"', $this->getTitle())),
            'slug' => $this->comment->commentable->slug,
            'hash' => 'comment-' . $this->comment->id,
            '_type' => snake_case(class_basename($this->comment->commentable_type)),
            'created_at' => (string) $this->comment->created_at,
        ]);
    }

    // Hung comment on your post Post name
    // Hung reply your commnent on Post name
    private function getTitle()
    {
        return $this->isReply
            ? '<b>' . $this->user->name . '</b> đã trả lời bình luận của bạn trong <strong>' . $this->comment->commentable->title . '</strong>'
            : '<b>' . $this->user->name . '</b> đã bình luận trong <strong>' . $this->comment->commentable->title . '</strong>';
    }
}
