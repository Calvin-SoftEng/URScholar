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
        $user = Auth::user(); // Get the authenticated user

        // Retrieve notifications with read status for the current user
        $notifications = Notification::with([
            'users' => function ($query) use ($user) {
                $query->where('user_id', $user->id);
            }
        ])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($notification) {
                return [
                    'id' => $notification->id,
                    'title' => $notification->title,
                    'message' => $notification->message,
                    'type' => $notification->type,
                    'read' => optional($notification->users->first())->pivot->read ?? false,
                    'created_at' => $notification->created_at,
                ];
            });

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
        $user = Auth::user();

        // Find the notification and check if it exists
        $notification = Notification::findOrFail($id);

        // Check if the authenticated user is associated with the notification
        if (!$notification->users()->where('user_id', $user->id)->exists()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Update the read status in the pivot table
        $notification->users()->updateExistingPivot($user->id, ['read' => true]);

        return response()->json(['message' => 'Notification marked as read', 'notification' => $notification]);
    }


    /**
     * Mark all notifications as read.
     */
    public function markAllAsRead(Request $request)
    {
        $user = Auth::user();
        $notificationIds = $request->input('notification_ids'); // Get the array of notification IDs

        // Check if there are IDs to update
        if (empty($notificationIds)) {
            return response()->json(['message' => 'No notifications to mark as read'], 400);
        }

        // Update notifications directly using the Notification model
        Notification::whereIn('id', $notificationIds)
            ->whereHas('users', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->get()
            ->each(function ($notification) use ($user) {
                $notification->users()->updateExistingPivot($user->id, ['read' => true]);
            });

        return response()->json(['message' => 'Selected notifications marked as read']);
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