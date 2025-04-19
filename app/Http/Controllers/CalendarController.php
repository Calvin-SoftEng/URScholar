<?php

namespace App\Http\Controllers;
use Inertia\Inertia;
use App\Http\Controllers\Controller;
use App\Models\Campus;
use App\Models\Payout;
use App\Models\Scholarship;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    public function calendar()
    {
        $scholarships = Scholarship::with('requirements')->get();

        // Get all payouts with their schedules
        $payouts = Payout::with(['payout_schedule', 'campus', 'scholarship'])
            ->get();

        // Get all campuses
        $campuses = Campus::all();

        return Inertia::render('Staff/Calendar/Calendar', [
            'scholarships' => $scholarships,
            'payouts' => $payouts,
            'campuses' => $campuses
        ]);
    }
}
