<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;

class ScheduleTasks
{
    public function __invoke(Schedule $schedule)
    {
        $schedule->command('tasks:archive-old')->daily();
        $schedule->command('users:clear-inactive-cache')->daily();
    }
}
