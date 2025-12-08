<?php

namespace Modules\Tasks\Repositories;

use App\Models\Comment;

class CommentRepository implements CommentRepositoryInterface
{
    public function getByTask($taskId)
    {
        return Comment::where('task_id', $taskId)
            ->with('user')
            ->orderBy('id', 'desc')
            ->get();
    }

    public function find($id)
    {
        return Comment::findOrFail($id);
    }

    public function create(array $data)
    {
        return Comment::create($data);
    }

    public function delete($id)
    {
        return Comment::destroy($id);
    }
}
