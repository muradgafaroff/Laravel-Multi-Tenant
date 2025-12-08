<?php

namespace Modules\Tasks\Services;

use Modules\Tasks\Repositories\TaskRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class TaskService implements TaskServiceInterface
{
    protected $repo;

    public function __construct(TaskRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }

    public function getAll()
    {
        $user = Auth::user();

        if ($user->hasRole('employee')) {
            return $this->repo->getAll()
                ->where('assigned_to', $user->id);
        }

        return $this->repo->getAll();
    }


    public function find($id)
    {
        $task = $this->repo->find($id);
        $user = Auth::user();

        if ($user->hasRole('employee') && $task->assigned_to != $user->id) {
            abort(403, "Bu task üçün icazən yoxdur.");
        }

        return $task;
    }


    public function create($data)
    {
        $user = Auth::user();

        if ($user->hasRole('employee')) {
            abort(403, "Employee task yarada bilməz.");
        }

        return $this->repo->create($data);
    }


    public function update($id, $data)
    {
        $task = $this->repo->find($id);
        $user = Auth::user();

        // ADMIN hər şeyi update edə bilər
        if ($user->hasRole('admin')) {
            return $this->repo->update($id, $data);
        }

        // MANAGER adminə aid taskı update edə bilməz
        if ($user->hasRole('manager')) {

            $assignedUser = User::find($task->assigned_to);

            if ($assignedUser && $assignedUser->hasRole('admin')) {
                abort(403, "Manager adminin taskını dəyişə bilməz.");
            }

            return $this->repo->update($id, $data);
        }

        // EMPLOYEE yalnız öz taskını update edə bilər
        if ($user->hasRole('employee')) {

            if ($task->assigned_to != $user->id) {
                abort(403, "Employee yalnız öz taskını dəyişə bilər.");
            }

            return $this->repo->update($id, $data);
        }

        abort(403);
    }


    public function updateStatus($id, $newStatus)
    {
        $task = $this->repo->find($id);
        $user = Auth::user();

        // Employee yalnız öz taskının statusunu dəyişə bilər
        if ($user->hasRole('employee') && $task->assigned_to !== $user->id) {
            abort(403, "Bu task sizə aid deyil.");
        }

        $oldStatus = $task->status;

        if ($oldStatus === 'completed' && $newStatus !== 'completed') {
            abort(422, "Completed task geri qaytarıla bilməz.");
        }

        if ($oldStatus === 'pending' && $newStatus === 'completed') {
            abort(422, "Pending → Completed keçidi qadağandır.");
        }

        return $this->repo->update($id, [
            'status' => $newStatus
        ]);
    }


    public function assign($id, $assignedTo)
    {
        $task = $this->repo->find($id);
        $user = Auth::user();
        $targetUser = User::find($assignedTo);

        if (!$targetUser) {
            abort(404, "User tapılmadı.");
        }

        // Employee heç kimi assign edə bilməz
        if ($user->hasRole('employee')) {
            abort(403, "Employee assign edə bilməz.");
        }

        // Manager adminə task assign edə bilməz
        if ($user->hasRole('manager') && $targetUser->hasRole('admin')) {
            abort(403, "Manager taskı adminə assign edə bilməz.");
        }

        return $this->repo->update($id, [
            'assigned_to' => $assignedTo
        ]);
    }


    public function delete($id)
    {
        $user = Auth::user();

        // Yalnız admin silə bilər
        if (!$user->hasRole('admin')) {
            abort(403, "Yalnız admin task silə bilər.");
        }

        return $this->repo->delete($id);
    }
}
