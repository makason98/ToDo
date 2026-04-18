<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Attachment;
use App\Models\Comment;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CommentController extends Controller
{
    public function store(Request $request, Task $task)
    {
        abort_unless($task->user_id === auth()->id(), 403);

        $request->validate([
            'body' => ['nullable', 'string'],
            'attachments.*' => ['nullable', 'file', 'max:20480'],
        ]);

        if (! $request->body && ! $request->hasFile('attachments')) {
            return back()->withErrors(['body' => 'Please enter a comment or attach a file.']);
        }

        $comment = $task->comments()->create([
            'user_id' => auth()->id(),
            'body' => $request->body,
        ]);

        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('attachments', 'public');

                $comment->attachments()->create([
                    'filename' => $file->getClientOriginalName(),
                    'path' => $path,
                    'mime_type' => $file->getMimeType(),
                    'size' => $file->getSize(),
                ]);
            }
        }

        ActivityLog::log('commented', 'Task', $task->id, "Commented on task: {$task->title}");

        return back();
    }

    public function destroy(Comment $comment)
    {
        abort_unless($comment->user_id === auth()->id(), 403);

        foreach ($comment->attachments as $attachment) {
            Storage::disk('public')->delete($attachment->path);
        }

        ActivityLog::log('deleted', 'Comment', $comment->id, "Deleted a comment on task: {$comment->task->title}");

        $comment->delete();

        return back();
    }
}
