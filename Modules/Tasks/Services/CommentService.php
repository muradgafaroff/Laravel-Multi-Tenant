<?php

namespace Modules\Tasks\Services;

use Modules\Tasks\Repositories\CommentRepositoryInterface;
use Modules\Tasks\Repositories\TaskRepositoryInterface;
use Illuminate\Support\Facades\Auth;

class CommentService
{
    protected $repo;
    protected $taskRepo;

    public function __construct(CommentRepositoryInterface $repo, TaskRepositoryInterface $taskRepo)
    {
        $this->repo = $repo;
        $this->taskRepo = $taskRepo;
    }

    public function getByTask($taskId)
    {
       
        $this->taskRepo->find($taskId);

        return $this->repo->getByTask($taskId);
    }

    public function store($taskId, $content)
    {
        $task = $this->taskRepo->find($taskId);
        $user = Auth::user();


        return $this->repo->create([
            'task_id' => $taskId,
            'user_id' => $user->id,
            'content' => $content
        ]);
    }

    public function delete($id)
    {
        $user = Auth::user();

        if (!$user->hasRole('admin')) {
            abort(403, "Yalnız admin şərh silə bilər.");
        }

        return $this->repo->delete($id);
    }
}
