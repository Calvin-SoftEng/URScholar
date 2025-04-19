<?php

namespace App\Http\Controllers;
use Inertia\Inertia;
use App\Http\Controllers\Controller;
use App\Models\Scholarship;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    public function calendar()
    {
        $scholarships = Scholarship::with('requirements')->get();
        return Inertia::render('Staff/Calendar/Calendar', [
            'scholarships' => $scholarships
        ]);
    }
}
