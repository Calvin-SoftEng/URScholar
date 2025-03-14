<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\Scholarship;
use App\Events\NewMessage;
use App\Events\MessageSent;
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

        // Get all messages with the user who sent them (eager loading)
        $messages = Message::with('user')->latest()->get();

        $scholarships = Scholarship::with([
            'latestMessage.user',
            'users' => function ($query) {
                $query->select('users.id', 'users.name');
            }
        ])
            ->withCount('users')
            ->get();
            

        // Return the chat page using Inertia, passing the messages and user data
        return Inertia::render('Staff/Messaging/Messaging', [
            'messages' => $messages,
            'currentUser' => Auth::user(),
            'scholarships' => $scholarships,
        ]);
    }

    public function store(Request $request, Scholarship $scholarship)
    {
        // Check if user is part of this scholarship
        if (!Auth::user()->scholarships->contains($scholarship->id)) {
            return Redirect::back()->with('error', 'Unauthorized');
        }

        $validated = $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $message = Message::create([
            'user_id' => Auth::id(),
            'scholarship_id' => $scholarship->id,
            'content' => $validated['content'],
        ]);

        // Load the user relation
        $message->load('user');

        // Broadcast the new message to all users in this scholarship
        broadcast(new NewMessage($message))->toOthers();

        return back();
    }

    public function oldstore(Request $request)
    {

        $request->validate([
            'content' => 'required|string',
        ]);

        // dd($request);
        $user = Auth::user()->id;

        $message = Message::create([
            'user_id' => $user,
            'scholarship_id' => 1,
            'content' => $request->content,
        ]);

        // MessageSent::dispatch($message);
        broadcast(new MessageSent($message))->toOthers();

        return back();
    }
}
