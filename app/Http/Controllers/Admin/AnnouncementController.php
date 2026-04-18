<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Announcement;
use App\Models\InAppNotification;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AnnouncementController extends Controller
{
    public function index(): View
    {
        $announcements = Announcement::with('sender')->latest()->paginate(15);

        return view('admin.announcements.index', compact('announcements'));
    }

    public function create(): View
    {
        $previewCounts = [
            'all' => User::count(),
            'verified' => User::whereNotNull('email_verified_at')->count(),
            'unverified' => User::whereNull('email_verified_at')->count(),
            'admins' => User::whereNotNull('admin_role')->count(),
            'regular' => User::whereNull('admin_role')->count(),
        ];

        return view('admin.announcements.create', compact('previewCounts'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string', 'max:5000'],
            'audience' => ['required', Rule::in(array_keys(Announcement::AUDIENCES))],
        ]);

        $recipientIds = $this->recipientIdsFor($validated['audience']);

        $announcement = Announcement::create([
            'sender_id' => $request->user()->id,
            'title' => $validated['title'],
            'body' => $validated['body'],
            'audience' => $validated['audience'],
            'recipients_count' => count($recipientIds),
        ]);

        $now = now();
        $rows = array_map(fn ($uid) => [
            'user_id' => $uid,
            'type' => 'announcement',
            'title' => $announcement->title,
            'body' => $announcement->body,
            'link' => null,
            'read_at' => null,
            'created_at' => $now,
            'updated_at' => $now,
        ], $recipientIds);

        foreach (array_chunk($rows, 500) as $chunk) {
            InAppNotification::insert($chunk);
        }

        ActivityLog::log(
            'admin.announcement.send',
            Announcement::class,
            $announcement->id,
            "Broadcast '{$announcement->title}' to {$announcement->recipients_count} user(s) ({$announcement->audienceLabel()})"
        );

        return redirect()
            ->route('admin.announcements.index')
            ->with('status', "Announcement sent to {$announcement->recipients_count} user(s).");
    }

    /**
     * @return array<int, int>
     */
    private function recipientIdsFor(string $audience): array
    {
        $query = User::query();

        match ($audience) {
            'verified' => $query->whereNotNull('email_verified_at'),
            'unverified' => $query->whereNull('email_verified_at'),
            'admins' => $query->whereNotNull('admin_role'),
            'regular' => $query->whereNull('admin_role'),
            default => null,
        };

        return $query->pluck('id')->all();
    }
}
