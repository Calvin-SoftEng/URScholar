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
use App\Models\Campus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;


class FeedController extends Controller
{
    public function index()
    {
        $scholarships = Scholarship::all();
        $batches = Batch::all();
        $campuses = Campus::all();

        return Inertia::render('Student/Communication/Feed', [
            'scholarships' => $scholarships,
            'batches' => $batches,
            'campuses' => $campuses,
        ]);
    }
}
