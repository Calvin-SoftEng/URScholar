<?php

namespace App\Http\Controllers;

use App\Events\NewNotification;
use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\Scholarship;
use App\Events\NewMessage;
use App\Events\MessageSent;
use App\Models\Batch;
use App\Models\Notification;
use App\Models\ScholarshipGroup;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;



class MessageController extends Controller
{
    // public function conversation()
    // {
    //     return Inertia::render('Coordinator/Messaging/Messaging');
    // }
    public function index(User $user)
    {
        // Get the authenticated user
        $currentUser = Auth::user();

        // Get scholarships with relationships that the current user has
        $batches = Batch::with([
            'latestMessage.user',
            'users' => function ($query) {
                $query->select('users.id', 'users.name');
            },
            'scholarshipGroups' => function ($query) use ($currentUser) {
                $query->where('user_id', $currentUser->id);
            },
            'scholarshipGroups.campus'
        ])
            ->whereHas('users', function ($query) use ($currentUser) {
                $query->where('users.id', $currentUser->id);
            })
            ->withCount('users')
            ->get();

        if ($currentUser->usertype == 'super_admin' || $currentUser->usertype == 'coordinator') {
            // Return the chat page using Inertia
            return Inertia::render('Staff/Communication/Communication', [
                'messages' => [],
                'currentUser' => $currentUser,
                'batches' => $batches, // Original scholarships data
                'selectedBatch' => [],
            ]);
        } elseif ($currentUser->usertype == 'cashier') {
            // Return the chat page using Inertia
            return Inertia::render('Cashier/Communication/Communication', [
                'messages' => [],
                'currentUser' => $currentUser,
                'batches' => $batches, // Original scholarships data
                'selectedBatch' => [],
            ]);
        } elseif ($currentUser->usertype == 'student') {
            // Return the chat page using Inertia
            return Inertia::render('Student/Communication/Communication', [
                'messages' => [],
                'currentUser' => $currentUser,
                'batches' => $batches, // Original scholarships data
                'selectedBatch' => [],
            ]);
        }

    }

    public function show(Batch $batch)
    {

        // Get all messages with the user who sent them (eager loading)
        $messages = Message::with(['user', 'batch'])
            ->where('batch_id', $batch->id)
            ->latest()
            ->get();

        // Get the authenticated user
        $currentUser = Auth::user();

        // Get scholarships with relationships that the current user has
        $batches = Batch::with([
            'latestMessage.user',
            'users' => function ($query) {
                $query->select('users.id', 'users.name');
            },
            'scholarshipGroups' => function ($query) use ($currentUser) {
                $query->where('user_id', $currentUser->id);
            },
            // 'scholarshipGroups.batch',
            // 'scholarshipGroups.campus'
        ])
            ->whereHas('users', function ($query) use ($currentUser) {
                $query->where('users.id', $currentUser->id);
            })
            ->withCount('users')
            ->get();

        $selectedBatch = $batch;


        if ($currentUser->usertype == 'super_admin' || $currentUser->usertype == 'coordinator') {
            // Return the chat page using Inertia, passing the messages and user data
            return Inertia::render('Staff/Communication/Communication', [
                'messages' => $messages,
                'currentUser' => Auth::user(),
                'batches' => $batches,
                'selectedBatch' => $selectedBatch,
            ]);
        } elseif ($currentUser->usertype == 'cashier') {
            // Return the chat page using Inertia, passing the messages and user data
            return Inertia::render('Cashier/Communication/Communication', [
                'messages' => $messages,
                'currentUser' => Auth::user(),
                'batches' => $batches,
                'selectedBatch' => $selectedBatch,
            ]);
        } elseif ($currentUser->usertype == 'student') {
            // Return the chat page using Inertia, passing the messages and user data
            return Inertia::render('Student/Communication/Messaging', [
                'messages' => $messages,
                'currentUser' => Auth::user(),
                'batches' => $batches,
                'selectedBatch' => $selectedBatch,
            ]);
        }

    }

    public function oldstore(Request $request)
    {

        $request->validate([
            'content' => 'required|string',
            'batch_id' => 'required',
        ]);

        dd($request);
        $user = Auth::user()->id;

        $message = Message::create([
            'user_id' => $user,
            'batch_id' => $request->batch_id,
            'content' => $request->content,
        ]);

        // MessageSent::dispatch($message);
        broadcast(new MessageSent($message))->toOthers();

        //Notifs
        // $user = Auth::user();

        // $notification = Notification::create([
        //     'creator_id' => $user->id,
        //     'title' => 'New Message',
        //     'message' => 'May nag text ngani ' . now()->format('H:i:s'),
        //     'type' => 'info',
        // ]);

        $batch = Batch::find($request->batch_id);

        $notification = Notification::create([
            'title' => 'New Group Chat Message!',
            'message' => 'You have a new message in the group chat' . $batch->name,
            'type' => 'group_chat',
        ]);

        $batchID = $batch->id;

        // Get users who belong to the specified scholarship group
        $users = User::whereIn('id', function ($query) use ($batchID) {
            $query->select('user_id')
                ->from('scholarship_groups')
                ->where('batch_id', $batchID);
        })
            ->where('id', '!=', Auth::user()->id) // Add this line to exclude the current user
            ->get();

        // Attach users to the notification
        $notification->users()->attach($users->pluck('id'));


        event(new NewNotification($notification));

        return back();
    }
}
