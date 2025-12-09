<?php

namespace Modules\Tasks\Services;

use Illuminate\Support\Facades\Cache;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class TaskService implements TaskServiceInterface
{
    public function getAll()
    {
        //  Get the active tenant ID
        $tenantId = tenant('id');

        //  Cache key specific to this tenant
        $cacheKey = "tenant:{$tenantId}:tasks";

        /*
             Cache::remember()

            - If the data already exists in Redis → return cached data
            - If not → fetch from DB and store it in Redis
            - Cache duration: 60 seconds
        */

        return Cache::remember($cacheKey, 60, function () {
            return Task::with('user')->get();
        });
    }

    public function create(array $data)
    {
        //  Insert the new task into the database
        $task = Task::create($data);

        // Clear cache so that fresh data is used in the next request
        $this->clearCache();

        return $task;
    }

    public function find($id)
    {
        
        return Task::with('user')->findOrFail($id);
    }

    public function update($id, array $data)
    {
        $task = Task::findOrFail($id);
        $task->update($data);

        // Refresh cache
        $this->clearCache();

        return $task;
    }

    public function updateStatus($id, $status)
    {
        $task = Task::findOrFail($id);
        $task->status = $status;
        $task->save();

        $this->clearCache();
        return $task;
    }

    public function assign($id, $userId)
    {
        $task = Task::findOrFail($id);
        $task->assigned_to = $userId;
        $task->save();

        $this->clearCache();
        return $task;
    }

    public function delete($id)
    {
        Task::findOrFail($id)->delete();

        $this->clearCache();
    }

    public function getStatusCount()
    {
        $tenantId = tenant('id');
        $cacheKey = "tenant:{$tenantId}:task_status_count";

        return Cache::remember($cacheKey, 10, function () {
            return [
                'pending' => Task::where('status', 'pending')->count(),
                'in-progress' => Task::where('status', 'in-progress')->count(),
                'completed' => Task::where('status', 'completed')->count(),
            ];
        });
    }


    // Clear all cache entries related to this tenant 
    private function clearCache()
    {
        $tenantId = tenant('id');

        Cache::forget("tenant:{$tenantId}:tasks");
        Cache::forget("tenant:{$tenantId}:task_status_count");
    }
}
