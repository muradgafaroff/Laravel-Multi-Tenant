<?php

namespace Modules\Tasks\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Models\Comment;

class CommentController extends Controller
{
    // Task-a comment əlavə etmək
    public function store(Request $request)
    {
        $validated = $request->validate([
            'task_id' => 'required|exists:tasks,id',
            'content' => 'required|string',
        ]);

        $comment = Comment::create([
            'task_id' => $validated['task_id'],
            'user_id' => auth()->id(), // Şərhi göndərən istifadəçi
            'content' => $validated['content'],
        ]);

        return response()->json([
            'message' => 'Şərh əlavə edildi',
            'comment' => $comment
        ], 201);
    }
}
