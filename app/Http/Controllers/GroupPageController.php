<?php

namespace App\Http\Controllers;

use App\Models\Scholarship;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class GroupPageController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        // Get all scholarships the user is part of, with latest message
        $scholarships = Scholarship::with([
            'latestMessage.user',
            'users' => function ($query) {
                $query->select('users.id', 'users.name');
            }
        ])
            ->withCount('users')
            ->get();

        return Inertia::render('Staff/Messaging/Index', [
            'scholarships' => $scholarships
        ]);
    }

    public function show(Scholarship $scholarship)
    {
        // Check if user is part of this scholarship
        if (!Auth::user()->scholarships->contains($scholarship->id)) {
            return redirect()->route('scholarships.index')
                ->with('error', 'You do not have access to this scholarship.');
        }

        // Get the scholarship with its users
        $scholarship = Scholarship::with([
            'users' => function ($query) {
                $query->select('users.id', 'users.name');
            }
        ])->findOrFail($scholarship->id);

        // Get the most recent messages first
        $messages = $scholarship->messages()
            ->with('user')
            ->latest()
            ->paginate(50);

        // Get all scholarships for the sidebar
        $allScholarships = Scholarship::whereHas('users', function ($query) {
            $query->where('users.id', Auth::user()->id);
        })
            ->with('latestMessage.user')
            ->withCount('users')
            ->get();

        return Inertia::render('Staff/Messaging/Show', [
            'scholarship' => $scholarship,
            'messages' => $messages,
            'currentUser' => Auth::user(),
            'allScholarships' => $allScholarships
        ]);
    }
}
