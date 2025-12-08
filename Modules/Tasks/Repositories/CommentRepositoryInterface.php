<?php

namespace Modules\Tasks\Repositories;

interface CommentRepositoryInterface
{
    public function getByTask($taskId);
    public function find($id);
    public function create(array $data);
    public function delete($id);
}
