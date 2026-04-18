<?php

namespace App\Http\Controllers;

use App\Models\InAppNotification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $notifications = InAppNotification::where('user_id', auth()->id())
            ->latest()
            ->take(50)
            ->get();

        return view('notifications.index', compact('notifications'));
    }

    public function markAsRead(InAppNotification $notification)
    {
        abort_unless($notification->user_id === auth()->id(), 403);

        $notification->update(['read_at' => now()]);

        return back();
    }

    public function markAllAsRead()
    {
        InAppNotification::where('user_id', auth()->id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return back();
    }

    public function destroy(InAppNotification $notification)
    {
        abort_unless($notification->user_id === auth()->id(), 403);

        $notification->delete();

        return back();
    }
}
