<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use App\Models\User;

class ClearInactiveUsersCache extends Command
{
    protected $signature = 'users:clear-inactive-cache';
    protected $description = 'Clear cache of users inactive for more than 30 days';

    public function handle()
    {
        $inactiveUsers = User::table('users')
            ->where('last_activity', '<', now()->subDays(30))
            ->pluck('id');

        foreach ($inactiveUsers as $id) {
            Cache::forget("user:{$id}:tasks");
        }

        $this->info("Inactive users cache cleared.");
    }
}
