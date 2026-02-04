<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\Task;

class TaskController extends Controller
{
    // GET /api/tasks
    public function index(Request $request)
    {
        return Task::orderBy('due')
            ->orderBy('id')
            ->get(['id', 'title', 'status', 'assignee', 'due']);
    }

    // PATCH /api/tasks/{id}
    public function updateStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => ['required', Rule::in(['pending', 'done'])],
        ]);

        $task = Task::findOrFail($id);
        $task->status = $validated['status'];
        $task->save();

        return response()->json($task);
    }
}