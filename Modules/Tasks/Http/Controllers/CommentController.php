<?php

namespace Modules\Tasks\Http\Controllers;

use Illuminate\Routing\Controller;
use Modules\Tasks\Http\Requests\CommentStoreRequest;
use Modules\Tasks\Services\CommentService;

class CommentController extends Controller
{
    protected $service;

    public function __construct(CommentService $service)
    {
        $this->service = $service;
    }

    public function index($taskId)
    {
        return response()->json(
            $this->service->getByTask($taskId)
        );
    }

    public function store(CommentStoreRequest $request, $taskId)
    {
        $comment = $this->service->store($taskId, $request->content);

        return response()->json([
            'message' => 'Şərh əlavə edildi',
            'comment' => $comment
        ], 201);
    }

    public function destroy($id)
    {
        $this->service->delete($id);

        return response()->json([
            'message' => 'Şərh silindi'
        ], 200);
    }
}
