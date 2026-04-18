<?php

namespace App\Http\Controllers;

use App\Models\FeedbackRequest;
use App\Models\InAppNotification;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class FeedbackController extends Controller
{
    public function index(Request $request): View
    {
        $feedback = FeedbackRequest::where('user_id', $request->user()->id)
            ->latest()
            ->paginate(15);

        return view('feedback.index', compact('feedback'));
    }

    public function create(): View
    {
        return view('feedback.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'type' => ['required', Rule::in(array_keys(FeedbackRequest::TYPES))],
            'title' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string', 'max:5000'],
        ]);

        $feedback = FeedbackRequest::create([
            'user_id' => $request->user()->id,
            'type' => $validated['type'],
            'title' => $validated['title'],
            'body' => $validated['body'],
            'status' => 'open',
        ]);

        $adminIds = User::whereNotNull('admin_role')->pluck('id');
        foreach ($adminIds as $adminId) {
            InAppNotification::send(
                $adminId,
                'feedback_new',
                'New ' . $feedback->typeLabel() . ' from ' . $request->user()->name,
                mb_substr($feedback->title, 0, 120),
                route('admin.feedback.show', $feedback)
            );
        }

        return redirect()
            ->route('feedback.show', $feedback)
            ->with('status', 'Feedback submitted. An admin will review it shortly.');
    }

    public function show(Request $request, FeedbackRequest $feedback): View
    {
        abort_unless($feedback->user_id === $request->user()->id, 403);

        $feedback->load('resolver');

        return view('feedback.show', compact('feedback'));
    }
}
