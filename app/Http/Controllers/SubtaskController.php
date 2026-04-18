<?php

namespace App\Http\Controllers;

use App\Models\Subtask;
use App\Models\Task;
use Illuminate\Http\Request;

class SubtaskController extends Controller
{
    public function store(Request $request, Task $task)
    {
        abort_unless($task->user_id === auth()->id(), 403);

        $request->validate([
            'title' => ['required', 'string', 'max:255'],
        ]);

        $maxPosition = $task->subtasks()->max('position') ?? -1;

        $task->subtasks()->create([
            'title' => $request->title,
            'position' => $maxPosition + 1,
        ]);

        return back();
    }

    public function toggle(Subtask $subtask)
    {
        abort_unless($subtask->task->user_id === auth()->id(), 403);

        $subtask->update(['completed' => !$subtask->completed]);

        return back();
    }

    public function update(Request $request, Subtask $subtask)
    {
        abort_unless($subtask->task->user_id === auth()->id(), 403);

        $request->validate([
            'title' => ['required', 'string', 'max:255'],
        ]);

        $subtask->update(['title' => $request->title]);

        return back();
    }

    public function destroy(Subtask $subtask)
    {
        abort_unless($subtask->task->user_id === auth()->id(), 403);

        $subtask->delete();

        return back();
    }
}
