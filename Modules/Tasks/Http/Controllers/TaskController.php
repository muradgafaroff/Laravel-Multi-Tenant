<?php

namespace Modules\Tasks\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Models\Task;

class TaskController extends Controller
{
    // 1. Task siyahısı
    public function index()
    {
        return Task::with('comments')->get();
    }

    // 2. Task yaratmaq
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'assigned_to' => 'required|exists:users,id',
            'status'      => 'required|string',
        ]);

        $task = Task::create($validated);

        return response()->json([
            'message' => 'Task yaradıldı',
            'task'    => $task
        ], 201);
    }

    // 3. Task detayı
    public function show($id)
    {
        return Task::with('comments')->findOrFail($id);
    }

    // 4. Task yeniləmək
    public function update(Request $request, $id)
    {
        $task = Task::findOrFail($id);

        $validated = $request->validate([
            'title'       => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'assigned_to' => 'nullable|exists:users,id',
            'status'      => 'nullable|string',
        ]);

        $task->update($validated);

        return response()->json([
            'message' => 'Task yeniləndi',
            'task'    => $task
        ]);
    }

    // 5. Task silmək
    public function destroy($id)
    {
        Task::findOrFail($id)->delete();

        return response()->json(['message' => 'Task silindi']);
    }
}
