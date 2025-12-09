<?php

namespace Modules\Notifications\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TaskUpdated extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public $task)
    {
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Tapşırıq yeniləndi')
            ->line('Tapşırıq: ' . $this->task->title)
            ->line('Yeni status: ' . $this->task->status)
            ->action('Tapşırığa bax', url('/tasks/'.$this->task->id));
    }
}
