<?php

namespace App\Http\Controllers;

use App\Events\NewNotification;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Get all notifications for the authenticated user.
     */
    public function index()
    {
        $notifications = Notification::orderBy('created_at', 'desc')
            ->get();

        return response()->json($notifications);
    }

    // public function index()
    // {
    //     $currentUserId = Auth::id();

    //     $notifications = Notification::where('user_id', $currentUserId)
    //         ->where(function ($query) use ($currentUserId) {
    //             // Only include notifications where the creator is not the current user
    //             // or where creator_id is null (system notifications)
    //             $query->where('creator_id', '!=', $currentUserId)
    //                 ->orWhereNull('creator_id');
    //         })
    //         ->orderBy('created_at', 'desc')
    //         ->get();

    //     return response()->json($notifications);
    // }

    /**
     * Create a new notification.
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'type' => 'nullable|string|in:info,success,warning,error',
        ]);

        $notification = Notification::create([
            'user_id' => $request->user_id,
            'title' => $request->title,
            'message' => $request->message,
            'type' => $request->type ?? 'info',
        ]);

        // Broadcast the notification
        event(new NewNotification($notification));

        return response()->json($notification, 201);
    }

    /**
     * Mark a notification as read.
     */
    public function markAsRead($id)
    {
        $notification = Notification::findOrFail($id);

        // Make sure the notification belongs to the authenticated user
        if ($notification->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $notification->read = true;
        $notification->save();

        return response()->json($notification);
    }

    /**
     * Mark all notifications as read.
     */
    public function markAllAsRead()
    {
        Notification::where('user_id', Auth::id())
            ->where('read', false)
            ->update(['read' => true]);

        return response()->json(['message' => 'All notifications marked as read']);
    }

    /**
     * Delete a notification.
     */
    public function destroy($id)
    {
        $notification = Notification::findOrFail($id);

        // Make sure the notification belongs to the authenticated user
        if ($notification->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $notification->delete();

        return response()->json(['message' => 'Notification deleted']);
    }

    public function createTestNotification()
    {
        $user = Auth::user();

        $notification = Notification::create([
            'user_id' => $user->id,
            'title' => 'Test Notification',
            'message' => 'This is a test notification sent at ' . now()->format('H:i:s'),
            'type' => 'info',
        ]);

        event(new NewNotification($notification));
    }
}