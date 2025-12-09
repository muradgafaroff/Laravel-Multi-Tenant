<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Task;

class ArchiveOldTasks extends Command
{
    protected $signature = 'tasks:archive-old';
    protected $description = 'Archive tasks older than 60 days';

    public function handle()
    {
        Task::where('created_at', '<', now()->subDays(60))
            ->update(['archived' => true]);

        $this->info("Old tasks archived.");
    }
}
