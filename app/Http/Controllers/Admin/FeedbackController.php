<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\FeedbackRequest;
use App\Models\InAppNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class FeedbackController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->input('search', ''));
        $userSearch = trim((string) $request->input('user', ''));
        $status = $request->input('status', 'unresolved');
        $type = $request->input('type', 'all');

        $query = FeedbackRequest::with(['user', 'resolver']);

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('body', 'like', "%{$search}%");
            });
        }

        if ($userSearch !== '') {
            $query->whereHas('user', function ($q) use ($userSearch) {
                $q->where('name', 'like', "%{$userSearch}%")
                    ->orWhere('email', 'like', "%{$userSearch}%");
            });
        }

        if ($status === 'unresolved') {
            $query->whereIn('status', FeedbackRequest::UNRESOLVED_STATUSES);
        } elseif ($status !== 'all' && array_key_exists($status, FeedbackRequest::STATUSES)) {
            $query->where('status', $status);
        }

        if ($type !== 'all' && array_key_exists($type, FeedbackRequest::TYPES)) {
            $query->where('type', $type);
        }

        $feedback = $query->latest()->paginate(15)->withQueryString();

        $counts = [
            'open' => FeedbackRequest::where('status', 'open')->count(),
            'in_progress' => FeedbackRequest::where('status', 'in_progress')->count(),
            'done' => FeedbackRequest::where('status', 'done')->count(),
            'rejected' => FeedbackRequest::where('status', 'rejected')->count(),
        ];

        return view('admin.feedback.index', compact(
            'feedback', 'counts', 'search', 'userSearch', 'status', 'type'
        ));
    }

    public function show(FeedbackRequest $feedback): View
    {
        $feedback->load(['user', 'resolver']);

        return view('admin.feedback.show', compact('feedback'));
    }

    public function update(Request $request, FeedbackRequest $feedback): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', Rule::in(array_keys(FeedbackRequest::STATUSES))],
            'resolution_note' => ['nullable', 'string', 'max:5000'],
        ]);

        $statusChanged = $feedback->status !== $validated['status'];
        $feedback->status = $validated['status'];
        $feedback->resolution_note = $validated['resolution_note'] ?: null;

        if ($statusChanged) {
            $feedback->resolved_by = $request->user()->id;
            $feedback->resolved_at = in_array($validated['status'], ['done', 'rejected'], true) ? now() : null;
        }

        $feedback->save();

        if ($statusChanged) {
            InAppNotification::send(
                $feedback->user_id,
                'feedback_update',
                'Your feedback is now: ' . $feedback->statusLabel(),
                mb_substr($feedback->title, 0, 120),
                route('feedback.show', $feedback)
            );

            ActivityLog::log(
                'admin.feedback.status_change',
                FeedbackRequest::class,
                $feedback->id,
                "Changed feedback '{$feedback->title}' status to {$feedback->statusLabel()}"
            );
        }

        return back()->with('status', 'Feedback updated.');
    }
}
