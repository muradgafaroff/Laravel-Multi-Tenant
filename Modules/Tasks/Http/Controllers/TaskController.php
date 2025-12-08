<?php

namespace Modules\Tasks\Http\Controllers;
use Illuminate\Support\Facades\Cache;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Tasks\Http\Requests\TaskStoreRequest;
use Modules\Tasks\Http\Requests\TaskUpdateRequest;
use Modules\Tasks\Http\Requests\TaskUpdateStatusRequest;
use Modules\Tasks\Http\Requests\TaskUpdateAssignRequest;
use Modules\Tasks\Services\TaskServiceInterface;

class TaskController extends Controller
{
    protected $service;

    public function __construct(TaskServiceInterface $service)
    {
        $this->service = $service;
    }

    /** List all tasks */
    public function index()
    {
        return response()->json($this->service->getAll());
    }

    /** Create new task */
    public function store(TaskStoreRequest $request)
    {
        try {
            $task = $this->service->create($request->validated());

            return response()->json([
                'message' => 'Task yaradıldı',
                'data' => $task
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /** Show single task */
    public function show($id)
    {
        try {
            return response()->json([
                'data' => $this->service->find($id)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Task tapılmadı'
            ], 404);
        }
    }

    /** General update (title, description) */
    public function update(TaskUpdateRequest $request, $id)
    {
        try {
            $task = $this->service->update($id, $request->validated());

            return response()->json([
                'message' => 'Task yeniləndi',
                'data' => $task
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], $e->getCode() ?: 400);
        }
    }

    /** UPDATE STATUS */
    public function updateStatus(TaskUpdateStatusRequest $request, $id)
    {
            $task = $this->service->updateStatus($id, $request->status);

            return response()->json([
                'message' => 'Status yeniləndi',
                'data' => $task
            ]);
    }

    /** ASSIGN USER */
    public function assign(TaskUpdateAssignRequest $request, $id)
        {
            $task = $this->service->assign($id, $request->assigned_to);

            return response()->json([
                'message' => 'User assign edildi',
                'data' => $task
            ]);
        }


        public function statusCount()
    {


        $data = $this->service->getStatusCount();

        return response()->json([
            'message' => 'Status count loaded successfully',
            'data' => $data
        ]);
    }


    /** Delete task */
    public function destroy($id)
    {
        try {
            $this->service->delete($id);

            return response()->json([
                'message' => 'Task silindi'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 400);
        }
    }
}
