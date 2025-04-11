<?php

namespace App\Http\Controllers;

use App\Events\NewNotification;
use App\Models\Batch;
use App\Models\Message;
use App\Models\StaffGroup;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Events\MessageSent;
use App\Models\Notification;
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
        $batches = Batch::with([
            'latestMessage.user',
            'users' => function ($query) {
                $query->select('users.id', 'users.name', 'users.first_name', 'users.usertype', 'users.picture');
            },
            'scholarshipGroups' => function ($query) use ($currentUser) {
                $query->where('user_id', $currentUser->id);
            },
            'scholarshipGroups.campus',
            'scholarship'
        ])
            ->whereHas('users', function ($query) use ($currentUser) {
                $query->where('users.id', $currentUser->id);
            })
            ->withCount('users')
            ->get();

        // Return the chat page using Inertia
        $viewPath = $this->getViewPathByUserType($currentUser);

        return Inertia::render($viewPath, [
            'messages' => [],
            'currentUser' => $currentUser,
            'staffGroups' => $staffGroups,
            'batches' => $batches,
            'selectedGroup' => null,
            'groupType' => null,
        ]);
    }

    public function showBatch(Batch $batch)
    {
        // Get the authenticated user
        $currentUser = Auth::user();

        // Get all messages for this batch
        $messages = Message::with(['user', 'batch'])
            ->where('batch_id', $batch->id)
            ->orderBy('created_at', 'desc')
            ->get();

        // Get both staff groups and scholarship batches for the sidebar listing
        $data = $this->getCombinedGroupsData($currentUser);

        // Return the chat page using Inertia
        $viewPath = $this->getViewPathByUserType($currentUser);

        return Inertia::render($viewPath, [
            'messages' => $messages,
            'currentUser' => $currentUser,
            'staffGroups' => $data['staffGroups'],
            'batches' => $data['batches'],
            'selectedGroup' => $batch,
            'groupType' => 'batch',
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

        // $members = StaffGroupUser::where('staff_group_id', $staffGroup->id)->get();

        // $members = StaffGroupUser::all();
        $members = StaffGroup::where('id', $staffGroup->id)
            ->with('users')
            ->get();


        // Get both staff groups and scholarship batches for the sidebar listing
        $data = $this->getCombinedGroupsData($currentUser);

        // Return the chat page using Inertia
        $viewPath = $this->getViewPathByUserType($currentUser);

        return Inertia::render($viewPath, [
            'messages' => $messages,
            'currentUser' => $currentUser,
            'staffGroups' => $data['staffGroups'],
            'batches' => $data['batches'],
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
            'group_type' => 'required|in:batch,staff',
        ]);

        $user = Auth::user()->id;

        $messageData = [
            'user_id' => $user,
            'content' => $request->content,
        ];


        // Set appropriate group ID field based on type
        if ($request->group_type === 'batch') {
            $messageData['batch_id'] = $request->group_id;
            $group = Batch::find($request->group_id);
            $channelName = "batch.{$request->group_id}";
            $notificationMessage = "You have a new message in the batch: {$group->name}";
        } else {

            $messageData['staff_group_id'] = $request->group_id;
            $group = StaffGroup::find($request->group_id);
            $channelName = "staff.{$request->group_id}";
            $notificationMessage = "You have a new message in the staff group: {$group->name}";
        }

        $message = Message::create($messageData);

        // Load user relationship for broadcasting
        $message->load('user');

        // Broadcast message to the appropriate channel
        broadcast(new MessageSent($message, $channelName))->toOthers();

            //broadcast(new MessageSent($message))->toOthers();

        // Create notification
        $notification = Notification::create([
            'title' => 'New Message',
            'message' => $notificationMessage,
            'type' => $request->group_type . '_chat',
        ]);

        // Get users who belong to the specified group (excluding current user)
        if ($request->group_type === 'batch') {
            $users = User::whereHas('scholarshipGroups', function ($query) use ($request) {
                $query->where('batch_id', $request->group_id);
            })->where('id', '!=', Auth::user()->id)->get();
        } else {
            $users = User::whereHas('staffGroups', function ($query) use ($request) {
                $query->where('staff_group_id', $request->group_id);
            })->where('id', '!=', Auth::user()->id)->get();
        }

        // Attach users to the notification
        $notification->users()->attach($users->pluck('id'));

        // Notify users
        event(new NewNotification($notification));

        return back();
    }
    // public function oldstore(Request $request)
    // {

    //     $request->validate([
    //         'content' => 'required|string',
    //         'batch_id' => 'required',
    //     ]);

    //     // dd($request);
    //     $user = Auth::user()->id;

    //     $message = Message::create([
    //         'user_id' => $user,
    //         'batch_id' => $request->batch_id,
    //         'content' => $request->content,
    //     ]);

    //     // MessageSent::dispatch($message);

    //     broadcast(new MessageSent($message))->toOthers();

    //     $batch = Batch::find($request->batch_id);

    //     $notification = Notification::create([
    //         'title' => 'New Group Chat Message!',
    //         'message' => 'You have a new message in the group chat' . $batch->name,
    //         'type' => 'group_chat',
    //     ]);

    //     $batchID = $batch->id;

    //     // Get users who belong to the specified scholarship group
    //     $users = User::whereIn('id', function ($query) use ($batchID) {
    //         $query->select('user_id')
    //             ->from('scholarship_groups')
    //             ->where('batch_id', $batchID);
    //     })
    //         ->where('id', '!=', Auth::user()->id) // Add this line to exclude the current user
    //         ->get();

    //     // Attach users to the notification
    //     $notification->users()->attach($users->pluck('id'));


    //     event(new NewNotification($notification));

    //     return back();
    // }

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
        $batches = Batch::with([
            'latestMessage.user',
            'users' => function ($query) {
                $query->select('users.id', 'users.name', 'users.first_name', 'users.usertype', 'users.picture');
            },
            'scholarshipGroups' => function ($query) use ($currentUser) {
                $query->where('user_id', $currentUser->id);
            },
            'scholarshipGroups.campus',
            'scholarship'
        ])
            ->whereHas('users', function ($query) use ($currentUser) {
                $query->where('users.id', $currentUser->id);
            })
            ->withCount('users')
            ->get();

        return [
            'staffGroups' => $staffGroups,
            'batches' => $batches
        ];
    }

    private function getViewPathByUserType($user)
    {
        if ($user->usertype == 'super_admin' || $user->usertype == 'coordinator') {
            return 'Staff/Communication/Messaging';
        } elseif ($user->usertype == 'cashier') {
            return 'Cashier/Communication/Messaging';
        } elseif ($user->usertype == 'student') {
            return 'Student/Communication/Messaging';
        }

        // Default fallback
        return 'Staff/Communication/Messaging';
    }
}