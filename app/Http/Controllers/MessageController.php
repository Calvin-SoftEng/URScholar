<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Message;
use App\Events\MessageSent;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function conversation()
    {
        return Inertia::render('Coordinator/Messaging/Messaging');
    }
    public function index(User $user)
    {

        // Get all messages with the user who sent them (eager loading)
        $messages = Message::with('user')->latest()->get();

        // Return the chat page using Inertia, passing the messages and user data
        return Inertia::render('Staff/Messaging/Messaging', [
            'messages' => $messages,
            'currentUser' => Auth::user(),
        ]);

        
    }

    public function store(Request $request)
    {

        $request->validate([
            'content' => 'required|string',
        ]);

        // dd($request);
        $user = Auth::user()->id;

        $message = Message::create([
            'user_id' => $user,
            'content' => $request->content,
        ]);

        // MessageSent::dispatch($message);
        broadcast(new MessageSent($message))->toOthers();

        return back();
    }
}
