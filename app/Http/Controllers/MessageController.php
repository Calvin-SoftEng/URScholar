<?php

namespace App\Http\Controllers;

use App\Events\NewNotification;
use App\Models\Batch;
use App\Models\Message;
use App\Models\StaffGroup;
use App\Models\User;
use App\Models\Conversation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Events\MessageSent;
use App\Models\Notification;
use App\Models\ScholarshipGroup;
use StaffGroupUser;

class MessageController extends Controller
{
    public function index()
    {
        // Get the authenticated user
        $currentUser = Auth::user();

        // Get staff groups that the current user belongs to
        $staffGroups = StaffGroup::with([
            'latestMessage.user',
            'users' => function ($query) {
                $query->select('users.id', 'users.name', 'users.first_name', 'users.usertype', 'users.picture');
            }
        ])
            ->whereHas('users', function ($query) use ($currentUser) {
                $query->where('users.id', $currentUser->id);
            })
            ->withCount('users')
            ->get();

        // Get scholarship batches with relationships that the current user has
        $scholarshipGroups = ScholarshipGroup::with([
            'latestMessage.user',
            'users' => function ($query) {
                $query->select('users.id', 'users.name', 'users.first_name', 'users.usertype', 'users.picture');
            }
        ])
            ->whereHas('users', function ($query) use ($currentUser) {
                $query->where('users.id', $currentUser->id);
            })
            ->withCount('users')
            ->with('campus')
            ->get();


        if ($currentUser->usertype === 'sponsor') {
            // Get all users for direct messages (excluding current user)
            $users = User::select('id', 'name', 'first_name', 'last_name', 'usertype', 'picture')
                ->where('id', '!=', $currentUser->id)
                ->where('usertype', 'super_admin')
                ->get();
        } else {

            // Get all users for direct messages (excluding current user)
            $users = User::select('id', 'name', 'first_name', 'last_name', 'usertype', 'picture')
                ->where('id', '!=', $currentUser->id)
                ->where('usertype', 'sponsor')
                ->get();
        }

        // Get conversations for the current user
        $conversations = $this->getUserConversations($currentUser->id);

        // Return the chat page using Inertia
        $viewPath = $this->getViewPathByUserType($currentUser);

        return Inertia::render($viewPath, [
            'messages' => [],
            'currentUser' => $currentUser,
            'staffGroups' => $staffGroups,
            'scholarshipGroups' => $scholarshipGroups,
            'users' => $users,
            'conversations' => $conversations,
            'selectedGroup' => null,
            'groupType' => null,
        ]);
    }

    // Show direct message conversation
    public function showConversation($userId)
    {
        // Get the authenticated user
        $currentUser = Auth::user();

        // Find or create conversation between current user and selected user
        $conversation = $this->findOrCreateConversation($currentUser->id, $userId);

        // Get other user's details
        $otherUser = User::find($userId);

        // Get messages for this conversation
        $messages = Message::with(['user'])
            ->where('conversation_id', $conversation->id)
            ->orderBy('created_at', 'desc')
            ->get();

        // Get combined data for sidebar
        $data = $this->getCombinedGroupsData($currentUser);

        // Get conversations for the sidebar
        $conversations = $this->getUserConversations($currentUser->id);

        // Get all users for potential new conversations
        if ($currentUser->usertype === 'sponsor') {
            $users = User::select('id', 'name', 'first_name', 'last_name', 'usertype', 'picture')
                ->where('id', '!=', $currentUser->id)
                ->where('usertype', 'super_admin')
                ->get();
        } else {
            $users = User::select('id', 'name', 'first_name', 'last_name', 'usertype', 'picture')
                ->where('id', '!=', $currentUser->id)
                ->where('usertype', 'sponsor')
                ->get();
        }

        // Return the chat page using Inertia
        $viewPath = $this->getViewPathByUserType($currentUser);

        return Inertia::render($viewPath, [
            'messages' => $messages,
            'currentUser' => $currentUser,
            'staffGroups' => $data['staffGroups'],
            'scholarshipGroups' => $data['scholarshipGroups'],
            'users' => $users,
            'conversations' => $conversations,
            'selectedUser' => $otherUser,
            'selectedConversation' => $conversation,
            'selectedGroup' => $conversation, // This is important for compatibility with existing code
            'groupType' => 'conversation',
        ]);
    }

    public function showScholarshipGroup(ScholarshipGroup $scholarshipGroup)
    {
        // Get the authenticated user
        $currentUser = Auth::user();

        // Get all messages for this batch
        $messages = Message::with(['user', 'scholarshipGroup'])
            ->where('scholarship_group_id', $scholarshipGroup->id)
            ->orderBy('created_at', 'desc')
            ->get();
            
        // Get both staff groups and scholarship batches for the sidebar listing
        $data = $this->getCombinedGroupsData($currentUser);

        // Get users and conversations for direct messages
        $users = User::select('id', 'name', 'first_name', 'last_name', 'usertype', 'picture')
            ->where('id', '!=', $currentUser->id)
            ->where('usertype', 'sponsor')
            ->get();

        $conversations = $this->getUserConversations($currentUser->id);

        // Return the chat page using Inertia
        $viewPath = $this->getViewPathByUserType($currentUser);

        return Inertia::render($viewPath, [
            'messages' => $messages,
            'currentUser' => $currentUser,
            'staffGroups' => $data['staffGroups'],
            'scholarshipGroups' => $data['scholarshipGroups'],
            'users' => $users,
            'conversations' => $conversations,
            'selectedGroup' => $scholarshipGroup,
            'groupType' => 'scholarship',
        ]);
    }

    public function showStaffGroup(StaffGroup $staffGroup)
    {
        // Get the authenticated user
        $currentUser = Auth::user();

        // Get all messages for this staff group
        $messages = Message::with(['user', 'staffGroup'])
            ->where('staff_group_id', $staffGroup->id)
            ->orderBy('created_at', 'desc')
            ->get();

        $members = StaffGroup::where('id', $staffGroup->id)
            ->with('users')
            ->get();

        // Get both staff groups and scholarship batches for the sidebar listing
        $data = $this->getCombinedGroupsData($currentUser);

        // Get users and conversations for direct messages
        $users = User::select('id', 'name', 'first_name', 'last_name', 'usertype', 'picture')
            ->where('id', '!=', $currentUser->id)
            ->where('usertype', 'sponsor')
            ->get();


        $conversations = $this->getUserConversations($currentUser->id);

        // Return the chat page using Inertia
        $viewPath = $this->getViewPathByUserType($currentUser);

        return Inertia::render($viewPath, [
            'messages' => $messages,
            'currentUser' => $currentUser,
            'staffGroups' => $data['staffGroups'],
            'scholarshipGroups' => $data['scholarshipGroups'],
            'users' => $users,
            'conversations' => $conversations,
            'selectedGroup' => $staffGroup,
            'groupType' => 'staff',
            'members' => $members,
        ]);
    }

    public function storeMessage(Request $request)
    {
        $request->validate([
            'content' => 'required|string',
            'group_id' => 'required',
            'group_type' => 'required|in:scholarship,staff,conversation',
        ]);

        $user = Auth::user()->id;

        $messageData = [
            'user_id' => $user,
            'content' => $request->content,
        ];

        // Set appropriate group ID field based on type
        if ($request->group_type === 'scholarship') {
            $messageData['scholarship_group_id'] = $request->group_id;
            $group = ScholarshipGroup::find($request->group_id);
            $channelName = "batch.{$request->group_id}";
            $notificationMessage = "You have a new message in the scholarship: {$group->name}";
        } elseif ($request->group_type === 'staff') {
            $messageData['staff_group_id'] = $request->group_id;
            $group = StaffGroup::find($request->group_id);
            $channelName = "staff.{$request->group_id}";
            $notificationMessage = "You have a new message in the staff group: {$group->name}";
        } elseif ($request->group_type === 'conversation') {
            $messageData['conversation_id'] = $request->group_id;

            // Find the conversation - handle case when it might not exist
            $conversation = Conversation::find($request->group_id);

            // If conversation doesn't exist, create it
            if (!$conversation) {
                // Try to determine receiver_id from the request or context
                // For now we'll use a placeholder approach
                // You may need to adjust this based on your actual request structure
                $receiverId = $request->receiver_id; // Assuming this is passed

                if (!$receiverId) {
                    return response()->json(['error' => 'Invalid conversation'], 400);
                }

                $conversation = Conversation::create([
                    'sender_id' => $user,
                    'receiver_id' => $receiverId
                ]);

                // Update the message data with the new conversation ID
                $messageData['conversation_id'] = $conversation->id;
            }

            $channelName = "conversation.{$conversation->id}";

            // Determine the recipient (the user who is not the sender)
            $recipientId = ($conversation->sender_id == $user) ? $conversation->receiver_id : $conversation->sender_id;
            $recipient = User::find($recipientId);
            $notificationMessage = "You have a new message from " . Auth::user()->first_name;
        }

        $message = Message::create($messageData);

        // Load user relationship for broadcasting
        $message->load('user');

        // Broadcast message to the appropriate channel
        broadcast(new MessageSent($message))->toOthers();

        // Create notification
        $notification = Notification::create([
            'title' => 'New Message',
            'message' => $notificationMessage,
            'type' => $request->group_type . '_chat',
        ]);

        // Get users who should receive the notification
        if ($request->group_type === 'scholarship') {
            $users = User::whereHas('scholarshipGroups', function ($query) use ($request) {
                $query->where('scholarship_group_id', $request->group_id);
            })->where('id', '!=', Auth::user()->id)->get();
            $group = ScholarshipGroup::with('latestMessage.user')->find($request->group_id);
        } elseif ($request->group_type === 'staff') {
            $users = User::whereHas('staffGroups', function ($query) use ($request) {
                $query->where('staff_group_id', $request->group_id);
            })->where('id', '!=', Auth::user()->id)->get();
            $group = StaffGroup::with('latestMessage.user')->find($request->group_id);
        } elseif ($request->group_type === 'conversation') {
            // For direct messages, only notify the recipient
            $conversation = Conversation::find($request->group_id);
            if ($conversation) {
                $recipientId = ($conversation->sender_id == $user) ? $conversation->receiver_id : $conversation->sender_id;
                $users = User::where('id', $recipientId)->get();
                $conversation = Conversation::with('latestMessage.user')->find($request->group_id);
            } else {
                $users = collect();
                $conversation = null;
            }
        }


        // Attach users to the notification
        $notification->users()->attach($users->pluck('id'));

        // Notify users
        event(new NewNotification($notification));

        if ($request->group_type === 'conversation') {
            return back()->with(['updatedGroup' => $conversation]);
        } else {
            return back()->with(['updatedGroup' => $group]);
        }
    }

    // Helper method to find or create a conversation between two users
    private function findOrCreateConversation($userId1, $userId2)
    {
        // First check if a conversation already exists
        $conversation = Conversation::where(function ($query) use ($userId1, $userId2) {
            $query->where('sender_id', $userId1)->where('receiver_id', $userId2);
        })->orWhere(function ($query) use ($userId1, $userId2) {
            $query->where('sender_id', $userId2)->where('receiver_id', $userId1);
        })->first();

        // If no conversation exists, create one
        if (!$conversation) {
            $conversation = Conversation::create([
                'sender_id' => $userId1,
                'receiver_id' => $userId2
            ]);
        }

        return $conversation;
    }

    // Helper method to get all conversations for a user with latest message
    private function getUserConversations($userId)
    {
        // Get all conversations where the user is either sender or receiver
        $conversations = Conversation::where('sender_id', $userId)
            ->orWhere('receiver_id', $userId)
            ->with(['latestMessage.user'])
            ->get();

        // Add the "other user" to each conversation
        foreach ($conversations as $conversation) {
            $otherUserId = ($conversation->sender_id == $userId) ? $conversation->receiver_id : $conversation->sender_id;
            $conversation->other_user = User::select('id', 'name', 'first_name', 'last_name', 'usertype', 'picture')
                ->where('id', $otherUserId)
                ->first();
        }

        return $conversations;
    }

    private function getCombinedGroupsData($currentUser)
    {
        // Get staff groups
        $staffGroups = StaffGroup::with([
            'latestMessage.user',
            'users' => function ($query) {
                $query->select('users.id', 'users.name', 'users.first_name', 'users.usertype', 'users.picture');
            }
        ])
            ->whereHas('users', function ($query) use ($currentUser) {
                $query->where('users.id', $currentUser->id);
            })
            ->withCount('users')
            ->get();

        // Get batches
        $scholarshipGroups = ScholarshipGroup::with([
            'latestMessage.user',
            'users' => function ($query) {
                $query->select('users.id', 'users.name', 'users.first_name', 'users.usertype', 'users.picture');
            }
        ])
            ->whereHas('users', function ($query) use ($currentUser) {
                $query->where('users.id', $currentUser->id);
            })
            ->withCount('users')
            ->with('campus')
            ->get();

        return [
            'staffGroups' => $staffGroups,
            'scholarshipGroups' => $scholarshipGroups
        ];
    }

    private function getViewPathByUserType($user)
    {
        if ($user->usertype == 'super_admin' || $user->usertype == 'coordinator') {
            return 'Staff/Communication/Messaging';
        } elseif ($user->usertype == 'sponsor') {
            return 'Sponsor/Communication/Messaging';
        } elseif ($user->usertype == 'cashier') {
            return 'Cashier/Communication/Messaging';
        } elseif ($user->usertype == 'student') {
            return 'Student/Communication/Messaging';
        }

        // Default fallback
        return 'Staff/Communication/Messaging';
    }
}