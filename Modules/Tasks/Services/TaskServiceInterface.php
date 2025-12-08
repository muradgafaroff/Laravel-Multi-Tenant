<?php

namespace Modules\Tasks\Services;

interface TaskServiceInterface
{
    public function getAll();

    public function find($id);

    public function create(array $data);

    public function update($id, array $data);

    public function delete($id);

    public function assign($id, $userId);

    public function updateStatus($id, $status);
}
