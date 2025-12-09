<?php

namespace Modules\Notifications\Services;

use Illuminate\Support\Facades\Notification;
use Modules\Notifications\Notifications\TaskAssigned;
use Modules\Notifications\Notifications\TaskUpdated;
use Modules\Notifications\Notifications\WeeklyReport;

class NotificationService
{
    public function sendTaskAssigned($user, $task)
    {
        Notification::send($user, new TaskAssigned($task));
    }

    public function sendTaskUpdated($user, $task)
    {
        Notification::send($user, new TaskUpdated($task));
    }

    public function sendWeeklyReport($adminUser, $reportData)
    {
        Notification::send($adminUser, new WeeklyReport($reportData));
    }
}
