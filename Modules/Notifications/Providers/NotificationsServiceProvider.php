<?php

namespace Modules\Notifications\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Notifications\Services\NotificationService;

class NotificationsServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(NotificationService::class);
    }

    public function boot()
    {
        
    }
}
