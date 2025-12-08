<?php

namespace Modules\Tasks\Repositories;

use App\Models\Task;

class TaskRepository implements TaskRepositoryInterface
{
    public function getAll()
    {
        return Task::with(['assignedUser', 'comments'])->get();
    }

    public function find($id)
    {
        return Task::with(['assignedUser', 'comments'])->findOrFail($id);
    }

    public function create(array $data)
    {
        return Task::create($data);
    }

    public function update($id, array $data)
    {
        $task = Task::findOrFail($id);
        $task->update($data);
        return $task;
    }

    public function delete($id)
    {
        $task = Task::findOrFail($id);
        return $task->delete();
    }
}
