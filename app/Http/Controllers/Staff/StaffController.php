<?php

namespace App\Http\Controllers\Staff;

use App\Models\Sponsor;
use Inertia\Inertia;
use App\Http\Controllers\Controller;
use App\Models\Scholarship;
use Illuminate\Http\Request;

class StaffController extends Controller
{
    public function dashboard()
    {
        $sponsor = Sponsor::all();
        $activeScholarships = Scholarship::where('status', 'active')->get();

        return Inertia::render('Staff/Dashboard', [
            'sponsors' => $sponsor,
            'scholarships' => $activeScholarships
        ]);
    }
}
