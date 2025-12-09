<?php

namespace Modules\Notifications\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class TaskAssigned extends Notification implements ShouldQueue
{
    use Queueable;

    public $task;
    public $tenantId;

    public function __construct($task)
    {
        $this->task = $task;
        $this->tenantId = tenant('id'); // tələb olunan hissə
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    // QUEUE işə düşməzdən əvvəl tenancy-də DB aktivləşdirilir
    public function beforeSerialization()
    {
        // heç bir şey lazım deyil burada, tenant id onsuz da saxlanılıb
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject("Sizə yeni task təyin edilib")
            ->line("Task: " . $this->task->title)
            ->line("Status: " . $this->task->status);
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => 'Yeni task təyin edildi',
            'task_id' => $this->task->id,
            'task_title' => $this->task->title,
            'tenant_id' => $this->tenantId,
        ];
    }
}
