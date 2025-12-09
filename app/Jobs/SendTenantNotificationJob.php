<?php

namespace Modules\Notifications\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Notification;
use Stancl\Tenancy\Tenancy;  // ƏSASDIR

class SendTenantNotificationJob implements ShouldQueue
{
    use Queueable, InteractsWithQueue, SerializesModels;

    public $notifiable;
    public $notification;
    public $tenantId;

    public function __construct($notifiable, $notification)
    {
        $this->notifiable = $notifiable;
        $this->notification = $notification;
        
        // aktiv tenant id-ni yadda saxlayırıq
        $this->tenantId = tenant('id');
    }

    public function handle(Tenancy $tenancy)
    {
        
        $tenancy->initialize($this->tenantId);

        
        Notification::send($this->notifiable, $this->notification);
    }
}
