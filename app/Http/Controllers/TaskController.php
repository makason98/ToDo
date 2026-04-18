<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\InAppNotification;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $filter = $request->input('filter', 'active');
        $labelId = $request->input('label');
        $priority = $request->input('priority');
        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');

        $baseQuery = $request->user()->tasks()->with(['comments', 'labels', 'subtasks'])->orderBy('position')->latest();

        if ($search) {
            $baseQuery->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($labelId) {
            $baseQuery->whereHas('labels', fn($q) => $q->where('labels.id', $labelId));
        }

        if ($priority) {
            $baseQuery->where('priority', $priority);
        }

        if ($dateFrom) {
            $baseQuery->where('due_date', '>=', $dateFrom);
        }

        if ($dateTo) {
            $baseQuery->where('due_date', '<=', $dateTo);
        }

        if ($filter === 'completed') {
            $tasks = $baseQuery->where('completed', true)->get();
        } else {
            $tasks = $baseQuery->where('completed', false)->get();
        }

        $hasFilters = $priority || $dateFrom || $dateTo || $labelId;

        $activeBadge = $request->user()->tasks()->where('completed', false)->count();
        $completedBadge = $request->user()->tasks()->where('completed', true)->count();
        $labels = $request->user()->labels()->orderBy('name')->get();

        return view('tasks.index', compact('tasks', 'filter', 'activeBadge', 'completedBadge', 'labels', 'hasFilters'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'due_date' => ['nullable', 'date'],
            'priority' => ['nullable', 'in:low,medium,high'],
            'recurrence' => ['nullable', 'in:none,daily,weekly,monthly,custom'],
            'recurrence_interval' => ['nullable', 'integer', 'min:2'],
            'attachments.*' => ['nullable', 'file', 'max:20480'],
            'label_ids' => ['nullable', 'array'],
            'label_ids.*' => ['exists:labels,id'],
        ]);

        $task = $request->user()->tasks()->create($request->only('title', 'description', 'due_date', 'priority', 'recurrence', 'recurrence_interval'));

        if ($request->input('label_ids')) {
            $task->labels()->sync($request->input('label_ids'));
        }

        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('attachments', 'public');
                $task->attachments()->create([
                    'filename' => $file->getClientOriginalName(),
                    'path' => $path,
                    'mime_type' => $file->getMimeType(),
                    'size' => $file->getSize(),
                ]);
            }
        }

        ActivityLog::log('created', 'Task', $task->id, "Created task: {$task->title}");

        return redirect()->route('tasks.index');
    }

    public function show(Task $task)
    {
        abort_unless($task->user_id === auth()->id(), 403);

        $task->load(['comments.user', 'comments.attachments', 'attachments', 'labels', 'subtasks']);
        $labels = auth()->user()->labels()->orderBy('name')->get();

        return view('tasks.show', compact('task', 'labels'));
    }

    public function update(Request $request, Task $task)
    {
        abort_unless($task->user_id === auth()->id(), 403);

        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'due_date' => ['nullable', 'date'],
            'priority' => ['nullable', 'in:low,medium,high'],
            'recurrence' => ['nullable', 'in:none,daily,weekly,monthly,custom'],
            'recurrence_interval' => ['nullable', 'integer', 'min:2'],
            'label_ids' => ['nullable', 'array'],
            'label_ids.*' => ['exists:labels,id'],
        ]);

        $task->update($request->only('title', 'description', 'due_date', 'priority', 'recurrence', 'recurrence_interval'));
        $task->labels()->sync($request->input('label_ids', []));

        ActivityLog::log('updated', 'Task', $task->id, "Updated task: {$task->title}");

        return redirect()->route('tasks.show', $task);
    }

    public function calendar(Request $request)
    {
        $user = $request->user();
        $month = $request->input('month', now()->month);
        $year = $request->input('year', now()->year);

        $date = \Carbon\Carbon::createFromDate($year, $month, 1);
        $startOfMonth = $date->copy()->startOfMonth();
        $endOfMonth = $date->copy()->endOfMonth();

        $tasks = $user->tasks()
            ->whereNotNull('due_date')
            ->whereBetween('due_date', [$startOfMonth, $endOfMonth])
            ->with('labels')
            ->orderBy('due_date')
            ->get()
            ->groupBy(fn($task) => $task->due_date->format('Y-m-d'));

        $labels = $user->labels()->orderBy('name')->get();
        $activeBadge = $user->tasks()->where('completed', false)->count();
        $completedBadge = $user->tasks()->where('completed', true)->count();

        return view('tasks.calendar', compact('tasks', 'date', 'labels', 'activeBadge', 'completedBadge'));
    }

    public function kanban(Request $request)
    {
        $user = $request->user();
        $todoTasks = $user->tasks()->where('status', 'todo')->with('labels')->latest()->get();
        $inProgressTasks = $user->tasks()->where('status', 'in_progress')->with('labels')->latest()->get();
        $doneTasks = $user->tasks()->where('status', 'done')->with('labels')->latest()->get();
        $labels = $user->labels()->orderBy('name')->get();
        $activeBadge = $user->tasks()->where('completed', false)->count();
        $completedBadge = $user->tasks()->where('completed', true)->count();

        return view('tasks.kanban', compact('todoTasks', 'inProgressTasks', 'doneTasks', 'labels', 'activeBadge', 'completedBadge'));
    }

    public function updateStatus(Request $request, Task $task)
    {
        abort_unless($task->user_id === auth()->id(), 403);

        $request->validate(['status' => 'required|in:todo,in_progress,done']);

        $oldStatus = $task->status;
        $task->update([
            'status' => $request->status,
            'completed' => $request->status === 'done',
            'completed_at' => $request->status === 'done' ? now() : null,
        ]);

        ActivityLog::log('status_changed', 'Task', $task->id, "Moved \"{$task->title}\" from {$oldStatus} to {$request->status}");

        return back();
    }

    public function toggle(Task $task)
    {
        abort_unless($task->user_id === auth()->id(), 403);

        // If recurring and currently not completed → shift due date instead of marking done
        if ($task->isRecurring() && !$task->completed) {
            $nextDate = $task->getNextDueDate();
            $task->update([
                'due_date' => $nextDate,
                'status' => 'todo',
            ]);

            ActivityLog::log('completed', 'Task', $task->id, "Completed recurring task: {$task->title} (next: {$nextDate->format('M d, Y')})");

            return back();
        }

        $completed = !$task->completed;
        $task->update([
            'completed' => $completed,
            'completed_at' => $completed ? now() : null,
            'status' => $completed ? 'done' : 'todo',
        ]);

        $action = $completed ? 'completed' : 'uncompleted';
        ActivityLog::log($action, 'Task', $task->id, ($completed ? 'Completed' : 'Reopened') . " task: {$task->title}");

        return back();
    }

    public function reorder(Request $request)
    {
        $request->validate([
            'ids' => ['required', 'array'],
            'ids.*' => ['integer'],
        ]);

        foreach ($request->ids as $position => $id) {
            Task::where('id', $id)->where('user_id', auth()->id())->update(['position' => $position]);
        }

        return response()->json(['ok' => true]);
    }

    public function destroy(Task $task)
    {
        abort_unless($task->user_id === auth()->id(), 403);

        ActivityLog::log('deleted', 'Task', $task->id, "Deleted task: {$task->title}");

        $task->delete();

        return redirect()->route('tasks.index');
    }
}
