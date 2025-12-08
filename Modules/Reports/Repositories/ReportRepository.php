<?php

namespace Modules\Reports\Repositories;

use App\Models\Task;
use Carbon\Carbon;

class ReportRepository implements ReportRepositoryInterface
{
    public function getWeeklyCompletedTasks()
    {
        $oneWeekAgo = Carbon::now()->subWeek();

        return Task::where('status', 'completed')
            ->where('updated_at', '>=', $oneWeekAgo)
            ->with('user')
            ->get()
            ->map(function ($task) {
                return [
                    'title' => $task->title,
                    'completed_by' => $task->user->name ?? 'User',
                    'completed_at' => $task->updated_at->format('Y-m-d H:i'),
                ];
            });
    }
}
