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


class FeedController extends Controller
{
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
            return Inertia::render('Student/Communication/Feed', [
                'messages' => [],
                'currentUser' => $currentUser,
                'batches' => $batches, // Original scholarships data
                'selectedBatch' => [],
            ]);
        }

    }
}
