<?php

namespace Modules\Notifications\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WeeklyReport extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public $reportData)
    {
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Həftəlik hesabat hazırdır')
            ->line('Bu həftə tamamlanan tapşırıqlar: ' . $this->reportData['completed'])
            ->line('Davam edənlər: ' . $this->reportData['in_progress'])
            ->line('Qeydə alınanlar: ' . $this->reportData['pending'])
            ->action('Hesabata bax', url('/reports/weekly'));
    }
}
